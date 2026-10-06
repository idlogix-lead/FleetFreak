<?php

namespace Tests\Feature;

use App\Models\LoadType;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Partner;
use App\Models\Role;
use App\Models\UnitMeasure;
use App\Models\User;
use App\Models\UserCompany;
use App\Models\VehicleClass;
use App\Models\VehicleModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Agents own the orders whose business_partner_id is their partner (docs/HANDOVER.md §9.3). They used to reach
 * any order in their company by changing the id: show, edit, update, delete and line delete on the web, and show,
 * edit, update and the status endpoint in the mobile API. They could also save an order in another agent's name
 * or with any status. Now another agent's order is a 404, and an agent's saves are always theirs, draft or pending.
 */
class AgentOrderOwnershipTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $agentA;
    private User $agentB;
    private Partner $customer;
    private Order $orderA;
    private Order $orderB;
    private Order $orderForA;
    private OrderDetail $lineA;
    private OrderDetail $lineB;
    private array $refs;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->admin = User::where('email', 'admin@idl.pk')->first();
        $company = $this->admin->active_company_id;
        $this->agentA = $this->user('agent.a', 'agent', 4);
        $this->agentB = $this->user('agent.b', 'agent', 4);
        $this->customer = Partner::create(['name' => 'Ownership Customer', 'actor_id' => 6, 'company_id' => $company, 'created_by' => $this->admin->id]);

        $class = VehicleClass::create(['name' => 'Ownership Class', 'company_id' => $company, 'created_by' => $this->admin->id]);
        $this->refs = [
            'vehicle_class_id' => $class->id,
            'vehicle_model_id' => VehicleModel::create(['name' => 'Ownership Model', 'vehicle_class_id' => $class->id, 'company_id' => $company, 'created_by' => $this->admin->id])->id,
            'unit' => UnitMeasure::create(['name' => 'kg', 'company_id' => $company])->id,
            'type_of_load' => LoadType::create(['name' => 'Boxes', 'company_id' => $company])->id,
        ];

        // The list hides pending and approved orders, so these are drafts and a cancelled order.
        [$this->orderA, $this->lineA] = $this->order('OWN-A', $this->agentA, $this->agentA);
        [$this->orderB, $this->lineB] = $this->order('OWN-B', $this->agentB, $this->agentB);
        [$this->orderForA] = $this->order('OWN-ADMIN-FOR-A', $this->agentA, $this->admin, 'cancelled');
    }

    public function test_agent_cannot_open_change_or_delete_another_agents_order(): void
    {
        $this->actingAs($this->agentA);

        $this->get("/agentorders/{$this->orderB->id}")->assertNotFound();
        $this->get("/agentorders/{$this->orderB->id}/edit")->assertNotFound();
        $this->put("/agentorders/{$this->orderB->id}", $this->form(['overall_status' => 'draft']))->assertNotFound();
        $this->delete("/agentorders/{$this->orderB->id}")->assertNotFound();
        $this->deleteJson("/delete-route-row/{$this->lineB->id}")->assertNotFound();

        $this->assertOrderUntouched($this->orderB, $this->lineB);
    }

    public function test_agent_can_still_work_on_their_own_orders(): void
    {
        $this->actingAs($this->agentA);

        $this->get("/agentorders/{$this->orderA->id}")->assertOk()->assertSee('OWN-A');
        $this->get("/agentorders/{$this->orderA->id}/edit")->assertOk();

        $this->deleteJson("/delete-route-row/{$this->lineA->id}")->assertOk()->assertJson(['success' => true]);
        $this->assertNull(OrderDetail::find($this->lineA->id));

        $this->put("/agentorders/{$this->orderA->id}", $this->form(['overall_status' => 'draft', 'final_amount' => '750']))
            ->assertRedirect(route('agentorders.index'));
        $this->assertSame('750', (string) $this->orderA->fresh()->final_amount);

        // An order with no lines: order_lines.order_id is a NO ACTION foreign key, so an order that still has
        // lines can't be deleted at all (a separate, pre-existing defect; see the report).
        $empty = Order::create(['order_no' => 'OWN-A-EMPTY', 'business_partner_id' => $this->agentA->partner_id, 'overall_status' => 'draft', 'company_id' => $this->admin->active_company_id, 'created_by' => $this->agentA->id]);
        $this->delete("/agentorders/{$empty->id}")->assertRedirect(route('agentorders.index'));
        $this->assertNull(Order::find($empty->id));
    }

    public function test_agent_saves_are_always_their_own_and_draft_or_pending(): void
    {
        $this->actingAs($this->agentA);
        $forged = ['business_partner_id' => $this->agentB->partner_id, 'overall_status' => 'approved'];

        $this->post('/agentorders', $this->form($forged + ['order_no' => 'OWN-NEW']))->assertRedirect(route('agentorders.index'));
        $created = Order::where('order_no', 'OWN-NEW')->firstOrFail();
        $this->assertSame($this->agentA->partner_id, (int) $created->business_partner_id);
        $this->assertSame('pending', $created->overall_status);

        $this->put("/agentorders/{$this->orderA->id}", $this->form($forged))->assertRedirect(route('agentorders.index'));
        $this->orderA->refresh();
        $this->assertSame($this->agentA->partner_id, (int) $this->orderA->business_partner_id);
        $this->assertSame('pending', $this->orderA->overall_status);
    }

    public function test_agent_list_shows_their_orders_including_ones_an_admin_created_for_them(): void
    {
        $orders = collect($this->actingAs($this->agentA)->get('/agentorders')->assertOk()->viewData('orders')->items());

        $this->assertEqualsCanonicalizing([$this->orderA->id, $this->orderForA->id], $orders->pluck('id')->all());
    }

    public function test_mobile_agent_cannot_reach_another_agents_order(): void
    {
        Sanctum::actingAs($this->agentA);

        $this->getJson("/api/show-order/{$this->orderB->id}")->assertNotFound();
        $this->getJson("/api/edit-order/{$this->orderB->id}")->assertNotFound();
        $this->postJson("/api/update-order/{$this->orderB->id}", $this->form(['overall_status' => 'draft']))->assertNotFound();
        $this->postJson("/api/order/updatestatus/{$this->orderB->id}", ['overall_status' => 'cancelled'])->assertNotFound();

        $this->assertOrderUntouched($this->orderB, $this->lineB);
    }

    public function test_mobile_agent_works_on_their_own_orders_within_the_status_rules(): void
    {
        Sanctum::actingAs($this->agentA);

        $this->getJson("/api/show-order/{$this->orderA->id}")->assertOk()->assertJsonPath('order.id', $this->orderA->id);
        $this->getJson("/api/edit-order/{$this->orderA->id}")->assertOk()->assertJsonPath('order.id', $this->orderA->id);

        $ids = collect($this->getJson('/api/fetch-orders')->assertOk()->json('orders'))->pluck('id')->all();
        $this->assertEqualsCanonicalizing([$this->orderA->id, $this->orderForA->id], $ids);

        // Forged owner and status on update and create: saved as agent A's, pending.
        $forged = ['business_partner_id' => $this->agentB->partner_id, 'overall_status' => 'approved'];
        $this->postJson("/api/update-order/{$this->orderA->id}", $this->form($forged))->assertOk();
        $this->orderA->refresh();
        $this->assertSame($this->agentA->partner_id, (int) $this->orderA->business_partner_id);
        $this->assertSame('pending', $this->orderA->overall_status);

        $this->postJson('/api/create-order', $this->form($forged + ['order_no' => 'OWN-API-NEW']))->assertOk();
        $created = Order::where('order_no', 'OWN-API-NEW')->firstOrFail();
        $this->assertSame($this->agentA->partner_id, (int) $created->business_partner_id);
        $this->assertSame('pending', $created->overall_status);

        // Status endpoint: draft, pending or cancelled only.
        $this->postJson("/api/order/updatestatus/{$this->orderA->id}", ['overall_status' => 'cancelled'])->assertOk();
        $this->assertSame('cancelled', $this->orderA->fresh()->overall_status);
        $this->postJson("/api/order/updatestatus/{$this->orderA->id}", ['overall_status' => 'approved'])->assertStatus(422);
        $this->assertSame('cancelled', $this->orderA->fresh()->overall_status);
    }

    public function test_status_endpoint_is_for_admins_and_agents_only(): void
    {
        Sanctum::actingAs($this->user('driver.status', 'driver', 5));
        $this->postJson("/api/order/updatestatus/{$this->orderB->id}", ['overall_status' => 'approved'])->assertForbidden();
        $this->assertSame('draft', $this->orderB->fresh()->overall_status);

        Sanctum::actingAs($this->admin);
        $this->postJson("/api/order/updatestatus/{$this->orderB->id}", ['overall_status' => 'approved'])->assertOk();
        $this->assertSame('approved', $this->orderB->fresh()->overall_status);
    }

    public function test_admins_can_still_delete_lines_on_any_order(): void
    {
        $this->actingAs($this->admin)->deleteJson("/delete-route-row/{$this->lineB->id}")->assertOk()->assertJson(['success' => true]);

        $this->assertNull(OrderDetail::find($this->lineB->id));
    }

    private function user(string $name, string $role, int $actorId): User
    {
        $partner = Partner::create(['name' => $name, 'actor_id' => $actorId, 'company_id' => $this->admin->active_company_id, 'created_by' => $this->admin->id]);
        // flag 0: AfterAuthentication forces a password change on flagged users.
        $user = User::create([
            'name' => $name, 'email' => "{$name}@fleetfreak.test", 'password' => Hash::make('secret123'),
            'role_id' => Role::where('name', $role)->value('id'), 'actor_id' => $actorId, 'partner_id' => $partner->id,
            'active_company_id' => $this->admin->active_company_id, 'client_id' => $this->admin->client_id, 'flag' => 0,
        ]);
        UserCompany::create(['user_id' => $user->id, 'company_id' => $this->admin->active_company_id]);

        return $user;
    }

    /** @return array{Order, OrderDetail} */
    private function order(string $orderNo, User $agent, User $createdBy, string $status = 'draft'): array
    {
        $company = $this->admin->active_company_id;
        $order = Order::create([
            'order_no' => $orderNo, 'business_partner_id' => $agent->partner_id, 'customer_partner_id' => $this->customer->id,
            'overall_status' => $status, 'trip_type' => 'cargo_trip', 'final_amount' => '500', 'company_id' => $company, 'created_by' => $createdBy->id,
        ]);
        $line = OrderDetail::create(['order_id' => $order->id, 'company_id' => $company, 'status' => 'draft', 'rate' => '500', 'date' => now()->toDateString()]);

        return [$order, $line];
    }

    /** A cargo order as the agent form and the agent app post it (cargo: no rate-list lookup, no WhatsApp call). */
    private function form(array $overrides = []): array
    {
        return array_merge([
            'order_no' => $this->orderA->order_no,
            'customer_partner_id' => $this->customer->id,
            'business_partner_id' => $this->agentA->partner_id,
            'vehicle_model_id' => $this->refs['vehicle_model_id'],
            'vehicle_class_id' => $this->refs['vehicle_class_id'],
            'overall_status' => 'draft',
            'booking_amount' => '100',
            'final_amount' => '500',
            'trip_type' => 'cargo_trip',
            'direction' => 'oneway',
            'from' => ['Lahore'], 'to' => ['Karachi'], 'rate' => ['500'], 'status' => ['draft'],
            'weight' => ['10'], 'unit' => [$this->refs['unit']], 'type_of_load' => [$this->refs['type_of_load']],
            'date' => [now()->addDay()->toDateString()], 'pickup_time' => ['10:00'], 'is_ac' => ['1'],
        ], $overrides);
    }

    private function assertOrderUntouched(Order $order, OrderDetail $line): void
    {
        $fresh = Order::find($order->id);
        $this->assertNotNull($fresh, 'the order still exists');
        $this->assertSame('draft', $fresh->overall_status);
        $this->assertSame((int) $order->business_partner_id, (int) $fresh->business_partner_id);
        $this->assertSame('500', (string) $fresh->final_amount);
        $this->assertNotNull(OrderDetail::find($line->id), 'its line still exists');
    }
}
