<?php

namespace Tests\Feature;

use App\Exports\DriverLedgerExport;
use App\Http\Controllers\LedgerController;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Partner;
use App\Models\PaymentHeader;
use App\Models\PaymentLine;
use App\Models\Role;
use App\Models\User;
use App\Models\UserCompany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

/**
 * The web driver ledger used to 500 for every non-admin
 * (compact(): Undefined variable $ledgerEntries). Drivers now see only their
 * own ledger; other non-admin roles get an empty page.
 */
class DriverLedgerAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_driver_ledger_access_per_role(): void
    {
        $admin = User::where('email', 'admin@idl.pk')->first();
        $companyId = $admin->active_company_id;

        $customer = Partner::create([
            'name' => 'Ledger Customer',
            'actor_id' => 6,
            'company_id' => $companyId,
            'created_by' => $admin->id,
        ]);

        [, $driverOneUser] = $this->driverWithRide($admin, $customer, 'ONE', '300', '50');
        [$driverTwo] = $this->driverWithRide($admin, $customer, 'TWO', '700', '70');

        $agentUser = $this->makeUser($admin, 'agent', 4, null);
        $staffUser = $this->makeUser($admin, 'management', 7, null);

        $dates = 'from_date=2020-01-01&to_date=2030-12-31';

        // Driver: own ledger only, even when asking for another driver.
        $response = $this->actingAs($driverOneUser)->get("/ledger/driver_ledger?{$dates}&agent={$driverTwo->id}");
        $response->assertOk();
        $rows = collect($response->viewData('ledgerEntries'));
        $this->assertEquals([300.0], $rows->where('trtype', 'inv')->pluck('debit')->map(fn ($v) => (float) $v)->values()->all());
        $this->assertEquals([50.0], $rows->where('trtype', 'pay')->pluck('credit')->map(fn ($v) => (float) $v)->values()->all());
        $this->assertTrue($rows->every(fn ($row) => in_array($row->driver, ['', 'Driver ONE'], true)));

        // Other non-admin roles: empty page instead of a 500.
        // (flushSession: AuthenticateSession logs out a user whose password
        // hash differs from the one the previous request stored.)
        foreach ([$agentUser, $staffUser] as $user) {
            $this->flushSession();
            $response = $this->actingAs($user)->get("/ledger/driver_ledger?{$dates}");
            $this->assertSame(200, $response->status(), "{$user->name}: redirected to " . $response->headers->get('Location'));
            $this->assertCount(0, $response->viewData('ledgerEntries'));
        }

        // Admin: unchanged, can pick any driver.
        $this->flushSession();
        $response = $this->actingAs($admin)->get("/ledger/driver_ledger?{$dates}&agent={$driverTwo->id}");
        $response->assertOk();
        $this->assertEquals([700.0], collect($response->viewData('ledgerEntries'))->where('trtype', 'inv')->pluck('debit')->map(fn ($v) => (float) $v)->values()->all());

        // Export (called directly: RBAC maps no function for driver_export).
        Excel::fake();
        $request = Request::create('/export_driver_ledger', 'GET', ['from_date' => '2020-01-01', 'to_date' => '2030-12-31', 'agent' => $driverTwo->id]);

        $this->actingAs($driverOneUser);
        app(LedgerController::class)->driver_export($request);
        Excel::assertDownloaded('driver_ledgers.xlsx', function (DriverLedgerExport $export) {
            $debits = $export->collection()->where('trtype', 'inv')->pluck('debit')->map(fn ($v) => (float) $v)->values()->all();

            return $debits === [300.0];
        });

        $this->actingAs($agentUser);
        app(LedgerController::class)->driver_export($request);
        Excel::assertDownloaded('driver_ledgers.xlsx', fn (DriverLedgerExport $export) => $export->collection()->isEmpty());
    }

    /**
     * A driver partner + user, with one completed ride (the web driver ledger
     * counts rides whose business partner is the driver) and one payment.
     */
    private function driverWithRide(User $admin, Partner $customer, string $tag, string $rate, string $paid): array
    {
        $companyId = $admin->active_company_id;

        $driver = Partner::create([
            'name' => "Driver {$tag}",
            'actor_id' => 5,
            'company_id' => $companyId,
            'created_by' => $admin->id,
        ]);

        $user = $this->makeUser($admin, 'driver', 5, $driver->id, strtolower($tag));

        $order = Order::create([
            'order_no' => "ORD-DRV-{$tag}",
            'business_partner_id' => $driver->id,
            'customer_partner_id' => $customer->id,
            'overall_status' => 'approved',
            'company_id' => $companyId,
            'created_by' => $admin->id,
        ]);

        $line = OrderDetail::create([
            'order_id' => $order->id,
            'company_id' => $companyId,
            'driver_id' => $driver->id,
            'status' => 'completed',
            'rate' => $rate,
            'date' => now()->toDateString(),
        ]);

        $header = PaymentHeader::create([
            'payment_no' => "PAY-DRV-{$tag}",
            'type' => 'payment',
            'status' => 'paid',
            'driver_id' => $driver->id,
            'customer_id' => $customer->id,
            'date' => now()->toDateString(),
            'total_amount' => $paid,
            'company_id' => $companyId,
            'created_by' => $admin->id,
        ]);

        PaymentLine::create([
            'payment_header_id' => $header->id,
            'order_id' => $order->id,
            'order_detail_id' => $line->id,
            'total_amount' => $paid,
            'transaction_date' => now()->toDateString(),
            'company_id' => $companyId,
        ]);

        return [$driver, $user];
    }

    private function makeUser(User $admin, string $roleName, int $actorId, ?int $partnerId, string $suffix = ''): User
    {
        $user = User::create([
            'name' => "{$roleName}{$suffix}",
            'email' => "{$roleName}{$suffix}@ledger.test",
            'password' => Hash::make('secret123'),
            'role_id' => Role::where('name', $roleName)->value('id'),
            'actor_id' => $actorId,
            'partner_id' => $partnerId,
            'active_company_id' => $admin->active_company_id,
            'client_id' => $admin->client_id,
            'flag' => 0,
        ]);

        UserCompany::create([
            'user_id' => $user->id,
            'company_id' => $admin->active_company_id,
        ]);

        return $user;
    }
}
