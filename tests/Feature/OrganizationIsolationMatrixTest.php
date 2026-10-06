<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Partner;
use App\Models\User;
use App\Models\UserCompany;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OrganizationIsolationMatrixTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_only_see_their_own_organizations_data(): void
    {
        $adminA = User::where('email', 'admin@idl.pk')->first();

        $companyB = Company::create([
            'name' => 'Org B',
            'client_id' => $adminA->client_id,
        ]);

        $userB = User::create([
            'name' => 'userb',
            'email' => 'userb@fleetfreak.test',
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

        $vehicleFixture = [
            'model' => 'Test Model',
            'year' => 2022,
            'color' => 'White',
            'ownership' => 'owned',
            'is_ac' => 0,
            'fuel_type' => 'petrol',
            'engine_type' => '1600cc',
            'transmission_type' => 'automatic',
            'weight' => 1000,
            'milage' => '0',
            'car_condition' => 'used',
            'is_status' => 'active',
        ];

        $vehicleA = Vehicle::create(array_merge($vehicleFixture, [
            'vehicle_identification_number' => 'MATRIX-A',
            'registration_no' => 'REG-A',
            'created_by' => $adminA->id,
            'company_id' => $adminA->active_company_id,
            'vehicle_no' => 'AAA-001',
        ]));

        $vehicleB = Vehicle::create(array_merge($vehicleFixture, [
            'vehicle_identification_number' => 'MATRIX-B',
            'registration_no' => 'REG-B',
            'created_by' => $userB->id,
            'company_id' => $companyB->id,
            'vehicle_no' => 'BBB-001',
        ]));

        Partner::create([
            'name' => 'Partner Org A',
            'actor_id' => 6,
            'company_id' => $adminA->active_company_id,
        ]);

        Partner::create([
            'name' => 'Partner Org B',
            'actor_id' => 6,
            'company_id' => $companyB->id,
        ]);

        $this->actingAs($adminA);

        $this->assertTrue(Vehicle::where('id', $vehicleA->id)->exists());
        $this->assertFalse(Vehicle::where('id', $vehicleB->id)->exists());
        $this->assertNull(Vehicle::find($vehicleB->id));
        $this->assertTrue(Partner::where('name', 'Partner Org A')->exists());
        $this->assertFalse(Partner::where('name', 'Partner Org B')->exists());

        $stampedA = Partner::create([
            'name' => 'Auto Stamp A',
            'actor_id' => 6,
        ]);
        $this->assertSame((int) $adminA->active_company_id, (int) $stampedA->company_id);

        $this->actingAs($userB);

        $this->assertTrue(Vehicle::where('id', $vehicleB->id)->exists());
        $this->assertFalse(Vehicle::where('id', $vehicleA->id)->exists());
        $this->assertNull(Vehicle::find($vehicleA->id));
        $this->assertTrue(Partner::where('name', 'Partner Org B')->exists());
        $this->assertFalse(Partner::where('name', 'Partner Org A')->exists());

        $stampedB = Partner::create([
            'name' => 'Auto Stamp B',
            'actor_id' => 6,
        ]);
        $this->assertSame((int) $companyB->id, (int) $stampedB->company_id);
    }
}
