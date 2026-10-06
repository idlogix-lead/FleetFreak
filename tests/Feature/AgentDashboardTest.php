<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Partner;
use App\Models\PaymentHeader;
use App\Models\PaymentLine;
use App\Models\User;
use App\Models\UserCompany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * The agent dashboard (actor_id 4) runs a ledger-style UNION over the
 * organization-scoped Order / PaymentHeader models. It used to fail on
 * PostgreSQL (SUM over varchar money columns) and, once scoped, lost the
 * first leg's company_id binding (toSql() + mergeBindings(getQuery())).
 */
class AgentDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_agent_dashboard_ledger_loads_and_stays_in_the_organization(): void
    {
        $admin = User::where('email', 'admin@idl.pk')->first();
        $companyA = $admin->active_company_id;

        $companyB = Company::create([
            'name' => 'Org B Dashboard',
            'client_id' => $admin->client_id,
        ]);

        $agent = Partner::create([
            'name' => 'Dashboard Agent',
            'actor_id' => 4,
            'company_id' => $companyA,
            'created_by' => $admin->id,
        ]);

        $customer = Partner::create([
            'name' => 'Dashboard Customer',
            'actor_id' => 6,
            'business_partner_id' => $agent->id,
            'company_id' => $companyA,
            'created_by' => $admin->id,
        ]);

        // flag 0: AfterAuthentication forces a password change on flagged agents.
        $agentUser = User::create([
            'name' => 'dashboardagent',
            'email' => 'dashboardagent@fleetfreak.test',
            'password' => Hash::make('secret123'),
            'role_id' => $admin->role_id,
            'actor_id' => 4,
            'partner_id' => $agent->id,
            'active_company_id' => $companyA,
            'client_id' => $admin->client_id,
            'flag' => 0,
        ]);

        UserCompany::create([
            'user_id' => $agentUser->id,
            'company_id' => $companyA,
        ]);

        $today = now()->toDateString();
        $beforeWindow = now()->subDays(30)->toDateString();

        // Organization A: one line inside the 7-day window, one before it (opening row).
        $order = Order::create([
            'order_no' => 'ORD-DASH-A',
            'business_partner_id' => $agent->id,
            'customer_partner_id' => $customer->id,
            'overall_status' => 'approved',
            'company_id' => $companyA,
            'created_by' => $admin->id,
        ]);

        $lineInWindow = OrderDetail::create([
            'order_id' => $order->id,
            'company_id' => $companyA,
            'status' => 'completed',
            'rate' => '500',
            'date' => $today,
        ]);

        OrderDetail::create([
            'order_id' => $order->id,
            'company_id' => $companyA,
            'status' => 'completed',
            'rate' => '200',
            'date' => $beforeWindow,
        ]);

        $receipt = PaymentHeader::create([
            'payment_no' => 'PAY-DASH-A',
            'type' => 'receipt',
            'status' => 'paid',
            'agent_id' => $agent->id,
            'customer_id' => $customer->id,
            'date' => $today,
            'total_amount' => '100',
            'company_id' => $companyA,
            'created_by' => $admin->id,
        ]);

        PaymentLine::create([
            'payment_header_id' => $receipt->id,
            'order_id' => $order->id,
            'order_detail_id' => $lineInWindow->id,
            'total_amount' => '100',
            'transaction_date' => $today,
            'company_id' => $companyA,
        ]);

        // Organization B rows forged onto the same agent: must never appear,
        // including in the opening (first-leg) summary.
        $orderB = Order::create([
            'order_no' => 'ORD-DASH-B',
            'business_partner_id' => $agent->id,
            'customer_partner_id' => $customer->id,
            'overall_status' => 'approved',
            'company_id' => $companyB->id,
            'created_by' => $admin->id,
        ]);

        $lineB = OrderDetail::create([
            'order_id' => $orderB->id,
            'company_id' => $companyB->id,
            'status' => 'completed',
            'rate' => '999',
            'date' => $today,
        ]);

        OrderDetail::create([
            'order_id' => $orderB->id,
            'company_id' => $companyB->id,
            'status' => 'completed',
            'rate' => '50',
            'date' => $beforeWindow,
        ]);

        $receiptB = PaymentHeader::create([
            'payment_no' => 'PAY-DASH-B',
            'type' => 'receipt',
            'status' => 'paid',
            'agent_id' => $agent->id,
            'customer_id' => $customer->id,
            'date' => $today,
            'total_amount' => '777',
            'company_id' => $companyB->id,
            'created_by' => $admin->id,
        ]);

        PaymentLine::create([
            'payment_header_id' => $receiptB->id,
            'order_id' => $orderB->id,
            'order_detail_id' => $lineB->id,
            'total_amount' => '777',
            'transaction_date' => $today,
            'company_id' => $companyB->id,
        ]);

        $response = $this->actingAs($agentUser)->get('/');

        $response->assertOk();

        $entries = collect($response->viewData('ledgerEntries'));

        $opening = $entries->where('trtype', 'OPN');
        $invoices = $entries->where('trtype', 'inv');
        $payments = $entries->where('trtype', 'pay');

        $this->assertCount(1, $opening);
        $this->assertEquals(200, (float) $opening->first()->debit);

        $this->assertCount(1, $invoices);
        $this->assertEquals(500, (float) $invoices->first()->debit);
        $this->assertStringStartsWith('ORD-DASH-A', $invoices->first()->tr_no);

        $this->assertCount(1, $payments);
        $this->assertEquals(100, (float) $payments->first()->credit);
        $this->assertSame('PAY-DASH-A', $payments->first()->tr_no);
    }
}
