<?php

namespace Tests\Feature;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Creating or editing an employee used to throw "Undefined array key prefix_whatsapp":
 * Partner::store_employee / update_employee are shared with drivers and read ten
 * driver-only fields that the employee form never sends. Employees now save with those
 * fields empty, and an employee edit leaves any stored values alone.
 */
class EmployeeCreateTest extends TestCase
{
    use RefreshDatabase;

    private function employeeForm(array $overrides = []): array
    {
        // The fields resources/views/employee/form.blade.php submits.
        return array_merge([
            'name' => 'Test Employee',
            'email' => 'test.employee@fleetfreak.test',
            'phone_no' => '3001234567',
            'whatsapp_no' => '3001234567',
            'cnic' => '35202-1234567-1',
            'address1' => 'Main Street 1',
            'address2' => null,
            'address3' => null,
            'city' => 'Lahore',
            'country' => 'Pakistan',
            'passport' => null,
            'employee_type' => 'management',
            'create_user' => '0',
        ], $overrides);
    }

    public function test_admin_can_create_and_edit_an_employee(): void
    {
        $admin = User::where('email', 'admin@idl.pk')->first();

        // Create, without a login.
        $this->actingAs($admin)->post('/employees', $this->employeeForm())
            ->assertRedirect(route('employees.index'));
        $employee = Partner::where('email', 'test.employee@fleetfreak.test')->firstOrFail();
        $this->assertSame(7, (int) $employee->actor_id);
        $this->assertSame('management', $employee->employee_type);
        $this->assertSame((int) $admin->active_company_id, (int) $employee->company_id);
        $this->assertNull($employee->prefix_whatsapp);
        $this->assertNull($employee->driver_license);

        // Create, with a login: an office_staff employee's user gets role 7.
        $this->actingAs($admin)->post('/employees', $this->employeeForm([
            'name' => 'Office Login',
            'email' => 'office.login@fleetfreak.test',
            'employee_type' => 'office_staff',
            'create_user' => '1',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]))->assertRedirect(route('employees.index'));
        $login = User::where('email', 'office.login@fleetfreak.test')->firstOrFail();
        $this->assertSame(7, (int) $login->role_id); // office_staff role
        $this->assertSame(7, (int) $login->actor_id);

        // Edit: the change is saved, and a stored driver-only value is not wiped.
        $employee->forceFill(['driver_license' => 'LIC-123'])->save();
        $this->actingAs($admin)->put("/employees/{$employee->id}", $this->employeeForm(['name' => 'Renamed Employee']))
            ->assertRedirect();
        $employee->refresh();
        $this->assertSame('Renamed Employee', $employee->name);
        $this->assertSame('LIC-123', $employee->driver_license);
    }

    public function test_driver_fields_are_still_written_when_sent(): void
    {
        $admin = User::where('email', 'admin@idl.pk')->first();
        $this->actingAs($admin);

        $driver = Partner::create(['name' => 'Test Driver', 'actor_id' => 5, 'company_id' => $admin->active_company_id, 'created_by' => $admin->id]);

        // The same path the driver controllers use: every driver-only field present.
        Partner::update_employee(['partner' => $driver, 'partner_data' => [
            'name' => 'Test Driver', 'email' => 'driver@fleetfreak.test', 'phone_no' => '1', 'whatsapp_no' => '2',
            'cnic' => '3', 'address1' => 'a', 'city' => 'c', 'country' => 'k', 'passport' => null, 'updated_by' => $admin->id,
            'actor_id' => 5, 'prefix_whatsapp' => '+92', 'prefix_emergency_contact1' => '+92', 'prefix_emergency_contact2' => '+92',
            'nic_expiry_date' => '2030-01-01', 'license_country' => 'PK', 'licensee_expiry_date' => '2031-01-01',
            'emergency_contact_no1' => '111', 'emergency_contact_no2' => '222', 'emergency_contact_name' => 'Kin', 'driver_license' => 'DL-9',
        ]]);

        $driver->refresh();
        $this->assertSame('+92', $driver->prefix_whatsapp);
        $this->assertSame('DL-9', $driver->driver_license);
        $this->assertSame('Kin', $driver->emergency_contact_name);
    }
}
