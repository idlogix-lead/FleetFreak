<?php

namespace Tests\Feature;

use App\Models\AccountTransaction;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Partner;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleClass;
use App\Models\VehicleModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Maintenance business partner (docs/HANDOVER.md §11, X-01). The drop-down listed only agents, drivers, customers and
 * walk-ins, so a vendor could never be chosen and Accounts Payable was credited against an agent. The server took any
 * id, another organization's partner included. Now vendors come first in the drop-down (maintenance and approval
 * forms) and the server accepts only a partner of the active organization of a type the drop-down offers.
 */
class MaintenanceVendorPartnerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Partner $vendor;
    private Partner $agent;
    private Partner $employee;
    private Partner $otherOrgVendor;
    private Vehicle $vehicle;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::where('email', 'admin@idl.pk')->first();
        $company = $this->admin->active_company_id;

        $this->vendor = Partner::create(['name' => 'Test Workshop', 'actor_id' => 10, 'company_id' => $company, 'created_by' => $this->admin->id]);
        $this->agent = Partner::create(['name' => 'Maintenance Agent', 'actor_id' => 4, 'company_id' => $company, 'created_by' => $this->admin->id]);
        $this->employee = Partner::create(['name' => 'Maintenance Employee', 'actor_id' => 7, 'employee_type' => 'management', 'company_id' => $company, 'created_by' => $this->admin->id]);

        $otherCompany = Company::create(['name' => 'Maintenance Org B', 'client_id' => $this->admin->client_id]);
        $this->otherOrgVendor = Partner::create(['name' => 'Other Org Workshop', 'actor_id' => 10, 'company_id' => $otherCompany->id, 'created_by' => $this->admin->id]);

        $class = VehicleClass::create(['name' => 'Maintenance Class', 'company_id' => $company, 'created_by' => $this->admin->id]);
        $model = VehicleModel::create(['name' => 'Maintenance Model', 'vehicle_class_id' => $class->id, 'company_id' => $company, 'created_by' => $this->admin->id]);
        $this->vehicle = Vehicle::create([
            'vehicle_identification_number' => 'MNT-VENDOR-1', 'registration_no' => 'MNT-001', 'vehicle_model_id' => $model->id,
            'year' => 2024, 'ownership' => 'owned', 'is_ac' => 1, 'is_status' => 'active', 'fuel_type' => 'petrol',
            'transmission_type' => 'manual', 'car_condition' => 'new', 'company_id' => $company, 'created_by' => $this->admin->id,
        ]);
    }

    public function test_create_form_lists_the_organizations_vendors_first(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get('/maintenances/create')->assertOk();
        $response->assertSeeInOrder(['<optgroup label="Vendors">', 'Test Workshop', '<optgroup label="Other partners">', 'Maintenance Agent'], false);
        $response->assertDontSee('Other Org Workshop');
    }

    public function test_approval_form_shows_a_vendor_partner(): void
    {
        $invoice = $this->maintenance('pending', $this->vendor);
        $this->actingAs($this->admin);

        $this->get("/maintenance_approvals/{$invoice->id}/edit")
            ->assertOk()
            ->assertSee('<option value="' . $this->vendor->id . '"', false)
            ->assertSee('Test Workshop');
    }

    public function test_maintenance_with_a_vendor_is_saved_and_posted_against_the_vendor(): void
    {
        $this->actingAs($this->admin);

        $this->from('/maintenances/create')->post('/maintenances', $this->form($this->vendor->id))->assertRedirect('/maintenances/create');
        $invoice = Invoice::where('document_type_id', 1)->where('business_partner_id', $this->vendor->id)->firstOrFail();
        $this->assertSame('pending', $invoice->document_status);

        $this->patch("/maintenance_approvals/{$invoice->id}", ['document_status' => 'completed'])->assertRedirect();

        $postings = AccountTransaction::where('table_id', 51)->where('record_id', $invoice->id)->get();
        $this->assertCount(2, $postings);
        $this->assertSame([$this->vendor->id, $this->vendor->id], $postings->pluck('b_partner_id')->map(fn ($id) => (int) $id)->all());
        $this->assertEquals(400, $postings->sum('debit'));
    }

    public function test_another_organizations_partner_is_rejected(): void
    {
        $this->actingAs($this->admin);

        $this->from('/maintenances/create')->post('/maintenances', $this->form($this->otherOrgVendor->id))->assertRedirect('/maintenances/create');

        $this->assertPartnerRejected($this->otherOrgVendor);
    }

    public function test_a_partner_type_the_form_does_not_offer_is_rejected(): void
    {
        $this->actingAs($this->admin);

        $this->from('/maintenances/create')->post('/maintenances', $this->form($this->employee->id))->assertRedirect('/maintenances/create');

        $this->assertPartnerRejected($this->employee);
    }

    public function test_a_draft_can_be_changed_to_a_vendor(): void
    {
        $invoice = $this->maintenance('draft', $this->agent);
        $line = InvoiceLine::where('invoice_id', $invoice->id)->firstOrFail();
        $this->actingAs($this->admin);

        $form = $this->form($this->vendor->id, 'draft');
        $form['row'][0]['row_id'] = $line->id;
        $this->from("/maintenances/{$invoice->id}/edit")->put("/maintenances/{$invoice->id}", $form)->assertRedirect("/maintenances/{$invoice->id}/edit");

        $this->assertSame($this->vendor->id, (int) $invoice->fresh()->business_partner_id);
    }

    private function form(int $partnerId, string $status = 'pending'): array
    {
        return [
            'vehicle_id' => $this->vehicle->id,
            'business_partner_id' => $partnerId,
            'date' => '2026-10-08',
            'start_time' => '09:00',
            'end_time' => '10:00',
            'description' => '',
            'total_amount' => 400,
            'grand_total_amount' => 400,
            'document_status' => $status,
            'row' => [[
                'row_id' => null, 'is_service_charge' => 1, 'description' => 'Service Charges',
                'quantity' => 1, 'rate' => 400, 'line_total' => 400,
            ]],
        ];
    }

    private function maintenance(string $status, Partner $partner): Invoice
    {
        $invoice = Invoice::create([
            'document_no' => 'MNT-T' . $partner->id . $status, 'document_type_id' => 1, 'document_status' => $status,
            'vehicle_id' => $this->vehicle->id, 'business_partner_id' => $partner->id, 'date' => '2026-10-08',
            'start_time' => '09:00', 'end_time' => '10:00', 'total_amount' => 400, 'grand_total_amount' => 400,
            'company_id' => $this->admin->active_company_id, 'client_id' => $this->admin->client_id, 'created_by' => $this->admin->id,
        ]);
        InvoiceLine::create([
            'invoice_id' => $invoice->id, 'is_service_charge' => 1, 'description' => 'Service Charges',
            'quantity' => 1, 'rate' => 400, 'line_amount' => 400,
        ]);

        return $invoice;
    }

    private function assertPartnerRejected(Partner $partner): void
    {
        $this->assertTrue(session('errors')?->has('business_partner_id') ?? false, 'Expected a business_partner_id validation error.');
        $this->assertFalse(Invoice::withoutGlobalOrganizationalScope()->where('business_partner_id', $partner->id)->exists());
    }
}
