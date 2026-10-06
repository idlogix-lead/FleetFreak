<?php

namespace Tests\Feature;

use App\Exports\CustomerExport;
use App\Models\Company;
use App\Models\Partner;
use App\Models\RolePermissionType;
use App\Models\User;
use App\Models\UserCompany;
use App\Models\VehicleClass;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

/**
 * Web actions that RolePermissions used to redirect to /unauthorized for every
 * user (no role_permission_type_functions row): the 11 module-controller Excel
 * exports, getVehicleClassDetails and delete_row.
 */
class RbacWebActionsTest extends TestCase
{
    use RefreshDatabase;

    private const EXPORTS = [
        '/export_agent' => 'agents.xlsx',
        '/export_customer' => 'customers.xlsx',
        '/export_driver' => 'drivers.xlsx',
        '/export_agent_ledger?from_date=2020-01-01&to_date=2030-12-31' => 'agent_ledger.xlsx',
        '/export_driver_ledger?from_date=2020-01-01&to_date=2030-12-31' => 'driver_ledgers.xlsx',
        '/export_location' => 'locations.xlsx',
        '/export_ratelist' => 'ratelists.xlsx',
        '/export_route' => 'routes.xlsx',
        '/export_company' => 'vehicle_companies.xlsx',
        '/export_vehicle' => 'vehicles.xlsx',
        '/export_models' => 'models.xlsx',
    ];

    public function test_admin_can_download_every_module_export(): void
    {
        Excel::fake();
        $admin = User::where('email', 'admin@idl.pk')->first();

        foreach (self::EXPORTS as $uri => $file) {
            $response = $this->actingAs($admin)->get($uri);
            $this->assertSame(200, $response->getStatusCode(), "{$uri}: " . $response->headers->get('Location'));
            Excel::assertDownloaded($file);
        }
    }

    public function test_customer_export_is_organization_and_agent_scoped(): void
    {
        Excel::fake();
        $admin = User::where('email', 'admin@idl.pk')->first();
        $companyA = $admin->active_company_id;

        $companyB = Company::create(['name' => 'Org B Export', 'client_id' => $admin->client_id]);

        $agentA = $this->partner('Export Agent A', 4, $companyA, null, $admin);
        $otherAgentA = $this->partner('Export Agent A2', 4, $companyA, null, $admin);
        $this->partner('Customer Of Agent A', 6, $companyA, $agentA->id, $admin);
        $this->partner('Customer Of Agent A2', 6, $companyA, $otherAgentA->id, $admin);
        $this->partner('Customer Org B', 6, $companyB->id, null, $admin);

        // Admin of organization A: all of A's customers, none of B's.
        $this->actingAs($admin)->get('/export_customer')->assertOk();
        Excel::assertDownloaded('customers.xlsx', function (CustomerExport $export) {
            $names = $export->collection()->pluck('name');

            return $names->contains('Customer Of Agent A')
                && $names->contains('Customer Of Agent A2')
                && !$names->contains('Customer Org B');
        });

        // Agent of organization A: only their own customers (as on the list page).
        $agentUser = User::create([
            'name' => 'exportagent',
            'email' => 'exportagent@rbac.test',
            'password' => Hash::make('secret123'),
            'role_id' => DB::table('roles')->where('name', 'agent')->value('id'),
            'actor_id' => 4,
            'partner_id' => $agentA->id,
            'active_company_id' => $companyA,
            'client_id' => $admin->client_id,
            'flag' => 0,
        ]);
        UserCompany::create(['user_id' => $agentUser->id, 'company_id' => $companyA]);

        $this->flushSession();
        $this->actingAs($agentUser)->get('/export_customer')->assertOk();
        Excel::assertDownloaded('customers.xlsx', function (CustomerExport $export) {
            return $export->collection()->pluck('name')->all() === ['Customer Of Agent A'];
        });
    }

    public function test_vehicle_class_details_and_delete_row_are_reachable(): void
    {
        $admin = User::where('email', 'admin@idl.pk')->first();

        $class = VehicleClass::create([
            'name' => 'RBAC Class',
            'seats_allow' => 4,
            'bags_allow' => 2,
            'company_id' => $admin->active_company_id,
            'created_by' => $admin->id,
        ]);

        $this->actingAs($admin)->post('/get-vehicle-class-details', ['vehicle_class_id' => $class->id])
            ->assertOk()
            ->assertJson(['seats_allow' => 4, 'bags_allow' => 2]);

        // delete_row: Super Admin removes an unused permission-type row.
        $superAdmin = User::where('email', 'super_admin@idl.pk')->first();
        $type = RolePermissionType::create([
            'role_module_id' => 3,
            'action' => 'rbac_test_unused',
            'is_read' => 0,
            'denial_msg' => 'test',
        ]);

        $this->flushSession();
        $this->actingAs($superAdmin)->delete("/delete-row/{$type->id}")->assertOk();
        $this->assertNull(RolePermissionType::find($type->id));
    }

    public function test_migration_registers_the_actions_on_an_already_seeded_database(): void
    {
        $admin = User::where('email', 'admin@idl.pk')->first();

        // Simulate a database seeded before the registration existed.
        DB::table('role_permission_type_functions')
            ->whereIn('method', ['export', 'driver_export', 'getVehicleClassDetails', 'delete_row'])
            ->delete();
        $this->actingAs($admin)->get('/export_customer')->assertRedirect(route('unauthorized'));

        (require database_path('migrations/2026_09_24_000001_register_rbac_web_actions.php'))->up();

        Excel::fake();
        $this->flushSession();
        $this->actingAs($admin)->get('/export_customer')->assertOk();
        Excel::assertDownloaded('customers.xlsx');

        // Idempotent: a second run adds nothing.
        $count = DB::table('role_permission_type_functions')->where('method', 'export')->count();
        (require database_path('migrations/2026_09_24_000001_register_rbac_web_actions.php'))->up();
        $this->assertSame($count, DB::table('role_permission_type_functions')->where('method', 'export')->count());
    }

    private function partner(string $name, int $actorId, int $companyId, ?int $businessPartnerId, User $admin): Partner
    {
        return Partner::create([
            'name' => $name,
            'actor_id' => $actorId,
            'business_partner_id' => $businessPartnerId,
            'company_id' => $companyId,
            'created_by' => $admin->id,
        ]);
    }
}
