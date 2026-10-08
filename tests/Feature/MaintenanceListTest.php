<?php

namespace Tests\Feature;

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
 * Maintenance list (docs/HANDOVER.md §11, MNT-06/07/08). The list showed only pending documents while only drafts can
 * be edited or deleted, so a saved draft could not be found again. Every row had Delete, but only drafts are deleted
 * and the message always said "deleted successfully". Editing a draft dropped its start and end time.
 */
class MaintenanceListTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Partner $partner;
    private Vehicle $vehicle;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::where('email', 'admin@idl.pk')->first();
        $company = $this->admin->active_company_id;

        $this->partner = Partner::create(['name' => 'List Workshop', 'actor_id' => 10, 'company_id' => $company, 'created_by' => $this->admin->id]);
        $class = VehicleClass::create(['name' => 'List Class', 'company_id' => $company, 'created_by' => $this->admin->id]);
        $model = VehicleModel::create(['name' => 'List Model', 'vehicle_class_id' => $class->id, 'company_id' => $company, 'created_by' => $this->admin->id]);
        $this->vehicle = Vehicle::create([
            'vehicle_identification_number' => 'MNT-LIST-1', 'registration_no' => 'LIST-001', 'vehicle_model_id' => $model->id,
            'year' => 2024, 'ownership' => 'owned', 'is_ac' => 1, 'is_status' => 'active', 'fuel_type' => 'petrol',
            'transmission_type' => 'manual', 'car_condition' => 'new', 'company_id' => $company, 'created_by' => $this->admin->id,
        ]);
    }

    public function test_list_shows_drafts_and_pending_but_not_completed(): void
    {
        $this->maintenance('MNT-LIST-DRAFT', 'draft');
        $this->maintenance('MNT-LIST-PENDING', 'pending');
        $this->maintenance('MNT-LIST-DONE', 'completed');
        $this->actingAs($this->admin);

        $this->get('/maintenances')->assertOk()
            ->assertSee('MNT-LIST-DRAFT')
            ->assertSee('MNT-LIST-PENDING')
            ->assertDontSee('MNT-LIST-DONE');
    }

    public function test_delete_is_offered_on_drafts_only(): void
    {
        $draft = $this->maintenance('MNT-LIST-DRAFT', 'draft');
        $pending = $this->maintenance('MNT-LIST-PENDING', 'pending');
        $this->actingAs($this->admin);

        $page = $this->get('/maintenances')->assertOk();
        $page->assertSee('action="' . route('maintenances.destroy', $draft->id) . '"', false);
        $page->assertDontSee('action="' . route('maintenances.destroy', $pending->id) . '"', false);
    }

    public function test_deleting_a_pending_document_is_refused_and_a_draft_is_deleted(): void
    {
        $draft = $this->maintenance('MNT-LIST-DRAFT', 'draft');
        $pending = $this->maintenance('MNT-LIST-PENDING', 'pending');
        $this->actingAs($this->admin);

        $this->from('/maintenances')->delete("/maintenances/{$pending->id}")
            ->assertRedirect('/maintenances')
            ->assertSessionHas('error')
            ->assertSessionMissing('success');
        $this->assertNotNull(Invoice::find($pending->id));

        $this->from('/maintenances')->delete("/maintenances/{$draft->id}")
            ->assertRedirect('/maintenances')
            ->assertSessionHas('success');
        $this->assertNull(Invoice::find($draft->id));
        $this->assertSoftDeleted('invoices', ['id' => $draft->id]);
    }

    public function test_editing_a_draft_saves_its_times(): void
    {
        $draft = $this->maintenance('MNT-LIST-DRAFT', 'draft');
        $line = InvoiceLine::where('invoice_id', $draft->id)->firstOrFail();
        $this->actingAs($this->admin);

        $this->from("/maintenances/{$draft->id}/edit")->put("/maintenances/{$draft->id}", [
            'vehicle_id' => $this->vehicle->id,
            'business_partner_id' => $this->partner->id,
            'date' => '2026-10-08',
            'start_time' => '14:30',
            'end_time' => '16:45',
            'description' => '',
            'total_amount' => 400,
            'grand_total_amount' => 400,
            'document_status' => 'draft',
            'row' => [[
                'row_id' => $line->id, 'is_service_charge' => 1, 'description' => 'Service Charges',
                'quantity' => 1, 'rate' => 400, 'line_total' => 400,
            ]],
        ])->assertRedirect("/maintenances/{$draft->id}/edit");

        $draft->refresh();
        $this->assertStringStartsWith('14:30', (string) $draft->start_time);
        $this->assertStringStartsWith('16:45', (string) $draft->end_time);
    }

    private function maintenance(string $number, string $status): Invoice
    {
        $invoice = Invoice::create([
            'document_no' => $number, 'document_type_id' => 1, 'document_status' => $status,
            'vehicle_id' => $this->vehicle->id, 'business_partner_id' => $this->partner->id, 'date' => '2026-10-08',
            'start_time' => '09:00', 'end_time' => '10:00', 'total_amount' => 400, 'grand_total_amount' => 400,
            'company_id' => $this->admin->active_company_id, 'client_id' => $this->admin->client_id, 'created_by' => $this->admin->id,
        ]);
        InvoiceLine::create([
            'invoice_id' => $invoice->id, 'is_service_charge' => 1, 'description' => 'Service Charges',
            'quantity' => 1, 'rate' => 400, 'line_amount' => 400,
        ]);

        return $invoice;
    }
}
