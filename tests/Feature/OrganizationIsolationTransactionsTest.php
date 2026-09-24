<?php

namespace Tests\Feature;

use App\Exports\CustomerExport;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Partner;
use App\Models\PaymentHeader;
use App\Models\User;
use App\Models\UserCompany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OrganizationIsolationTransactionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_transaction_tables_ledger_and_exports_are_isolated(): void
    {
        $this->seed();

        $adminA = User::where('email', 'admin@idl.pk')->first();

        $companyB = Company::create([
            'name' => 'Org B Tx',
            'client_id' => $adminA->client_id,
        ]);

        $userB = User::create([
            'name' => 'userb2',
            'email' => 'userb2@fleetfreak.test',
            'password' => Hash::make('secret123'),
            'role_id' => $adminA->role_id,
            'actor_id' => $adminA->actor_id,
            'active_company_id' => $companyB->id,
            'client_id' => $adminA->client_id,
        ]);

        UserCompany::create([
            'user_id' => $userB->id,
            'company_id' => $companyB->id,
        ]);

        $agentA = Partner::create([
            'name' => 'Agent Org A',
            'actor_id' => 4,
            'company_id' => $adminA->active_company_id,
            'created_by' => $adminA->id,
        ]);

        $agentB = Partner::create([
            'name' => 'Agent Org B',
            'actor_id' => 4,
            'company_id' => $companyB->id,
            'created_by' => $userB->id,
        ]);

        $customerA = Partner::create([
            'name' => 'Customer Org A',
            'actor_id' => 6,
            'company_id' => $adminA->active_company_id,
            'created_by' => $adminA->id,
        ]);

        $customerB = Partner::create([
            'name' => 'Customer Org B',
            'actor_id' => 6,
            'company_id' => $companyB->id,
            'created_by' => $userB->id,
        ]);

        // Orders carry a customer: the ledger UNION inner-joins customers, so
        // customer-less orders never reach it (pre-existing behaviour, open
        // client question in docs/HANDOVER.md section 5).
        $orderA = Order::create([
            'order_no' => 'ORD-TX-A',
            'business_partner_id' => $agentA->id,
            'customer_partner_id' => $customerA->id,
            'overall_status' => 'approved',
            'company_id' => $adminA->active_company_id,
            'client_id' => $adminA->client_id,
            'created_by' => $adminA->id,
        ]);

        $lineA = OrderDetail::create([
            'order_id' => $orderA->id,
            'company_id' => $adminA->active_company_id,
            'client_id' => $adminA->client_id,
            'status' => 'completed',
            'rate' => 500,
            'date' => '2026-09-23',
        ]);

        $orderB = Order::create([
            'order_no' => 'ORD-TX-B',
            'business_partner_id' => $agentB->id,
            'customer_partner_id' => $customerB->id,
            'overall_status' => 'approved',
            'company_id' => $companyB->id,
            'created_by' => $userB->id,
        ]);

        OrderDetail::create([
            'order_id' => $orderB->id,
            'company_id' => $companyB->id,
            'status' => 'completed',
            'rate' => 700,
            'date' => '2026-09-23',
        ]);

        $invoiceA = Invoice::create([
            'document_no' => 'INV-TX-A',
            'document_type_id' => 1,
            'document_status' => 'completed',
            'total_amount' => 100,
            'grand_total_amount' => 100,
            'business_partner_id' => $agentA->id,
            'company_id' => $adminA->active_company_id,
            'client_id' => $adminA->client_id,
            'created_by' => $adminA->id,
        ]);

        $headerA = PaymentHeader::create([
            'payment_no' => 'PAY-TX-A',
            'type' => 'receipt',
            'status' => 'paid',
            'agent_id' => $agentA->id,
            'date' => '2026-09-23',
            'total_amount' => 100,
            'company_id' => $adminA->active_company_id,
            'created_by' => $adminA->id,
        ]);

        $accountA = Account::where('company_id', $adminA->active_company_id)->first();

        $txA = AccountTransaction::create([
            'transaction_date' => '2026-09-23',
            'company_id' => $adminA->active_company_id,
            'account_id' => $accountA->id,
            'currency_id' => 1,
            'table_id' => 21,
            'record_id' => $orderA->id,
            'debit' => 500,
            'credit' => 0,
            'created_by' => $adminA->id,
        ]);

        // Organization B user: none of organization A's transaction rows are visible.
        $this->actingAs($userB);

        $this->assertNull(Order::find($orderA->id));
        $this->assertNull(OrderDetail::find($lineA->id));
        $this->assertNull(Invoice::find($invoiceA->id));
        $this->assertNull(PaymentHeader::find($headerA->id));
        $this->assertNull(AccountTransaction::find($txA->id));
        $this->assertTrue(Order::where('order_no', 'ORD-TX-B')->exists());

        // Organization B user's ledger shows their agent, never organization A's.
        // (SUM over varchar rate columns was a pre-existing PostgreSQL bug -
        // fixed with explicit numeric casts in both ledger controllers.)
        $response = $this->get('/ledgers?from_date=2020-01-01&to_date=2030-01-01');
        $response->assertStatus(200);
        $response->assertSee('Agent Org B');
        $response->assertDontSee('Agent Org A');

        // Organization A admin: mirror image, plus the customer export stays scoped.
        // (flushSession: AuthenticateSession logs out a user whose password hash
        // differs from the one the previous request stored.)
        $this->flushSession();
        $this->actingAs($adminA);

        $response = $this->get('/ledgers?from_date=2020-01-01&to_date=2030-01-01');
        $response->assertStatus(200);
        $response->assertSee('Agent Org A');
        $response->assertDontSee('Agent Org B');

        $exportNames = (new CustomerExport())->collection()->pluck('name')->all();
        $this->assertNotContains('Customer Org B', $exportNames);
        $this->assertNotContains('Agent Org B', $exportNames);
    }
}
