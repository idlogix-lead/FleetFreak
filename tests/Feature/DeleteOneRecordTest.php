<?php

namespace Tests\Feature;

use App\Models\Partner;
use App\Models\RateList;
use App\Models\Route as RouteModel;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleClass;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Deleting one record used to run `Model::find($id)->where('company_id', ...)->delete()`.
 * where() on a loaded model starts a new query without the id, so the delete hit every row of
 * that table in the company: it wiped them all, or failed on a foreign key when any row was
 * referenced. Each delete now removes exactly the requested row, and an unknown id is a 404.
 */
class DeleteOneRecordTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('email', 'admin@idl.pk')->first();
    }

    public function test_deleting_an_employee_deletes_only_that_employee(): void
    {
        $this->actingAs($this->admin);
        $target = $this->employee('Delete Me');
        $sibling = $this->employee('Keep Me');
        $before = Partner::count();

        $this->delete("/employees/{$target->id}")->assertRedirect(route('employees.index'));

        $this->assertNull(Partner::find($target->id));
        $this->assertNotNull(Partner::find($sibling->id));
        $this->assertSame($before - 1, Partner::count());
    }

    public function test_api_vehicle_delete_deletes_only_that_vehicle(): void
    {
        Sanctum::actingAs($this->admin);
        $target = $this->vehicle('VIN-DELETE');
        $sibling = $this->vehicle('VIN-KEEP');
        $before = Vehicle::count();

        $this->deleteJson("/api/delete-vehicle/{$target->id}")->assertOk();

        $this->assertNull(Vehicle::find($target->id));
        $this->assertNotNull(Vehicle::find($sibling->id));
        $this->assertSame($before - 1, Vehicle::count());
        $this->deleteJson('/api/delete-vehicle/999999')->assertNotFound();
    }

    public function test_api_route_delete_deletes_only_that_route(): void
    {
        Sanctum::actingAs($this->admin);
        $target = $this->route('Route Delete');
        $sibling = $this->route('Route Keep');
        $before = RouteModel::count();

        $this->deleteJson("/api/route/delete/{$target->id}")->assertOk();

        $this->assertNull(RouteModel::find($target->id));
        $this->assertNotNull(RouteModel::find($sibling->id));
        $this->assertSame($before - 1, RouteModel::count());
        $this->deleteJson('/api/route/delete/999999')->assertNotFound();
    }

    public function test_api_rate_list_delete_deletes_only_that_rate_list(): void
    {
        Sanctum::actingAs($this->admin);
        $route = $this->route('Rate List Route');
        $target = $this->rateList('Rate Delete', $route);
        $sibling = $this->rateList('Rate Keep', $route);
        $before = RateList::count();

        $this->deleteJson("/api/ratelist/delete/{$target->id}")->assertOk();

        $this->assertNull(RateList::find($target->id));
        $this->assertNotNull(RateList::find($sibling->id));
        $this->assertSame($before - 1, RateList::count());
        $this->deleteJson('/api/ratelist/delete/999999')->assertNotFound();
    }

    private function employee(string $name): Partner
    {
        return Partner::create(['name' => $name, 'actor_id' => 7, 'employee_type' => 'management', 'created_by' => $this->admin->id]);
    }

    private function vehicle(string $vin): Vehicle
    {
        return Vehicle::create([
            'vehicle_identification_number' => $vin, 'year' => 2024, 'ownership' => 'owned', 'is_ac' => 1,
            'is_status' => 'active', 'fuel_type' => 'petrol', 'transmission_type' => 'manual',
            'car_condition' => 'new', 'created_by' => $this->admin->id,
        ]);
    }

    private function route(string $name): RouteModel
    {
        return RouteModel::create(['name' => $name, 'distance' => 10]);
    }

    private function rateList(string $name, RouteModel $route): RateList
    {
        $class = VehicleClass::create(['name' => "{$name} class", 'created_by' => $this->admin->id]);

        return RateList::create(['name' => $name, 'price' => 100, 'route_id' => $route->id, 'vehicle_class_id' => $class->id]);
    }
}
