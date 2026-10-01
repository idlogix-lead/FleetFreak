<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderIndexShowsApprovedTest extends TestCase
{
    use RefreshDatabase;

    /**
     * "Save & Submit" on /orders/create stores overall_status = 'approved'.
     * The Orders list must show such orders (read-only, with a view link);
     * pending orders stay on their own Pending Rides screen.
     */
    public function test_submitted_orders_are_listed_and_viewable(): void
    {
        $this->seed();

        $admin = User::where('email', 'admin@idl.pk')->first();

        $agent = Partner::create([
            'name' => 'Agent Index',
            'actor_id' => 4,
            'company_id' => $admin->active_company_id,
            'created_by' => $admin->id,
        ]);

        $approved = Order::create([
            'order_no' => 'ORD-APPROVED-1',
            'trip_type' => 'passenger_trip',
            'business_partner_id' => $agent->id,
            'overall_status' => 'approved',
            'company_id' => $admin->active_company_id,
            'created_by' => $admin->id,
        ]);

        OrderDetail::create([
            'order_id' => $approved->id,
            'company_id' => $admin->active_company_id,
            'rate' => 1500,
            'status' => 'incomplete',
            'date' => '2026-09-25',
            'from_loc' => 'Line From Loc',
            'to_loc' => 'Line To Loc',
        ]);

        Order::create([
            'order_no' => 'ORD-PENDING-1',
            'trip_type' => 'passenger_trip',
            'business_partner_id' => $agent->id,
            'overall_status' => 'pending',
            'company_id' => $admin->active_company_id,
            'created_by' => $admin->id,
        ]);

        $this->actingAs($admin)->get(route('orders.index'))
            ->assertStatus(200)
            ->assertSee('ORD-APPROVED-1')
            ->assertSee(route('orders.show', $approved->id))
            ->assertDontSee(route('orders.edit', $approved->id))
            ->assertDontSee('ORD-PENDING-1');

        $this->actingAs($admin)->get(route('orders.show', $approved->id))
            ->assertStatus(200)
            ->assertSee('ORD-APPROVED-1')
            ->assertSee('Line From Loc')
            ->assertSee('Line To Loc');
    }
}
