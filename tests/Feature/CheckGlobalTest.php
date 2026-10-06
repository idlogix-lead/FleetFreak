<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Partner;
use App\Models\Role;
use App\Models\RoleHasModule;
use App\Models\RolePermission;
use App\Models\RolePermissionType;
use App\Models\Route as RouteModel;
use App\Models\User;
use App\Models\UserCompany;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

/**
 * checkGlobal (docs/HANDOVER.md §9.8). It read `->permission` on the role's `global` row without a null check, so a
 * role with no row for the module crashed the page; and TourService (43), DailyRental (42) and RentalVehicle (41)
 * looked up Orders (9) instead of their own module, so they crashed for any role without Orders, and their own
 * `global` tick did nothing. Now each uses its own module, a missing row counts as unticked (own records only, logged),
 * and a missing record on show/edit is a 404.
 */
class CheckGlobalTest extends TestCase
{
    use RefreshDatabase;

    /** path => [module, trip type] */
    private const PAGES = [
        'tour_services' => [43, 'tour_booking'],
        'daily_rentals' => [42, 'daily_booking'],
        'rental_vehicles' => [41, 'monthly_booking'],
    ];

    private User $admin;
    private User $desk;       // a role holding 41, 42 and 43, but not Orders (9)
    private Role $deskRole;
    private array $orders = []; // path => ['desk' => Order, 'admin' => Order]

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::where('email', 'admin@idl.pk')->first();
        $company = $this->admin->active_company_id;

        $this->deskRole = Role::create(['name' => 'Bookings Desk', 'home' => '/dashboard', 'client_id' => $this->admin->client_id, 'actor_id' => 2, 'is_system' => 0]);
        foreach (self::PAGES as [$module]) {
            RoleHasModule::create(['role_id' => $this->deskRole->id, 'role_module_id' => $module]);
            foreach (RolePermissionType::where('role_module_id', $module)->pluck('id') as $type) {
                RolePermission::create(['role_id' => $this->deskRole->id, 'role_module_id' => $module, 'role_permission_type_id' => $type, 'permission' => 1]);
            }
        }
        $this->desk = User::create([
            'name' => 'desk', 'email' => 'desk@fleetfreak.test', 'password' => Hash::make('secret-123'),
            'role_id' => $this->deskRole->id, 'actor_id' => 2, 'client_id' => $this->admin->client_id,
            'active_company_id' => $company, 'flag' => 0,
        ]);
        UserCompany::create(['user_id' => $this->desk->id, 'company_id' => $company]);

        $customer = Partner::create(['name' => 'Desk Customer', 'actor_id' => 6, 'company_id' => $company, 'created_by' => $this->admin->id]);
        foreach (self::PAGES as $path => [, $tripType]) {
            foreach (['desk' => $this->desk, 'admin' => $this->admin] as $who => $creator) {
                $this->orders[$path][$who] = Order::create([
                    'order_no' => strtoupper("CG-{$tripType}-{$who}"), 'trip_type' => $tripType, 'overall_status' => 'cancelled',
                    'customer_partner_id' => $customer->id, 'final_amount' => '100', 'company_id' => $company, 'created_by' => $creator->id,
                ]);
            }
        }
    }

    public function test_each_page_uses_its_own_modules_global_tick(): void
    {
        $this->actingAs($this->desk);

        foreach (self::PAGES as $path => $_) {
            $this->assertEqualsCanonicalizing($this->ids($path, ['desk', 'admin']), $this->listed($path), "{$path}: global ticked, everything listed");
            $this->get("/{$path}/{$this->orders[$path]['admin']->id}")->assertOk();
            $this->get("/{$path}/{$this->orders[$path]['admin']->id}/edit")->assertOk();
        }
    }

    public function test_global_unticked_shows_only_own_orders(): void
    {
        $this->setGlobal(43, 0);
        $this->actingAs($this->desk);

        $this->assertSame($this->ids('tour_services', ['desk']), $this->listed('tour_services'));
        $this->get("/tour_services/{$this->orders['tour_services']['admin']->id}")->assertNotFound();
        $this->get("/tour_services/{$this->orders['tour_services']['desk']->id}")->assertOk();
    }

    public function test_a_missing_global_row_counts_as_unticked_and_is_logged(): void
    {
        $this->setGlobal(43, null);
        Log::spy();
        $this->actingAs($this->desk);

        $this->assertSame($this->ids('tour_services', ['desk']), $this->listed('tour_services'));
        Log::shouldHaveReceived('warning')->with('checkGlobal: no global permission row', [
            'user_id' => $this->desk->id, 'role_id' => $this->deskRole->id, 'role_module_id' => 43,
        ]);
    }

    public function test_a_missing_record_is_a_404(): void
    {
        $this->actingAs($this->desk);

        foreach (self::PAGES as $path => $_) {
            $this->get("/{$path}/999999")->assertNotFound();
            $this->get("/{$path}/999999/edit")->assertNotFound();
        }
    }

    public function test_admin_still_sees_everything(): void
    {
        $this->actingAs($this->admin);

        foreach (self::PAGES as $path => $_) {
            $this->assertEqualsCanonicalizing($this->ids($path, ['desk', 'admin']), $this->listed($path), $path);
            $this->get("/{$path}/{$this->orders[$path]['desk']->id}")->assertOk();
        }
    }

    public function test_tables_without_created_by_return_nothing_instead_of_failing(): void
    {
        // The desk role holds neither Routes (7) nor Vehicles (5): no global row for either.
        $this->actingAs($this->desk);
        RouteModel::create(['name' => 'CG Route', 'distance' => 5]);

        $this->assertCount(0, RouteModel::checkGlobal(7)->get(), 'routes has no created_by: nothing, not a SQL error');
        $this->assertStringContainsString('"vehicles"."created_by" = ?', Vehicle::checkGlobal(5)->toSql());
    }

    /** @return int[] ids of the orders listed on the page's index */
    private function listed(string $path): array
    {
        return collect($this->get("/{$path}")->assertOk()->viewData('orders')->items())->pluck('id')->sort()->values()->all();
    }

    /** @return int[] */
    private function ids(string $path, array $who): array
    {
        return collect($who)->map(fn ($w) => $this->orders[$path][$w]->id)->sort()->values()->all();
    }

    /** 1 or 0: tick or untick the desk role's `global` permission for $module; null: delete the row. */
    private function setGlobal(int $module, ?int $value): void
    {
        $type = RolePermissionType::where('role_module_id', $module)->where('action', 'global')->value('id');
        $row = RolePermission::where('role_id', $this->deskRole->id)->where('role_permission_type_id', $type);
        $value === null ? $row->delete() : $row->update(['permission' => $value]);
    }
}
