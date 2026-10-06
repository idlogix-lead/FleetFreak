<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmokeLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_loads(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /**
     * The RolePermissionSeeder creates admin@idl.pk (password 00000000) with an
     * active company, so the afterauth middleware (forced company registration /
     * agent password change) must let it straight through to the dashboard.
     */
    public function test_seeded_admin_can_log_in_and_reach_the_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@idl.pk',
            'password' => '00000000',
        ]);

        $this->assertAuthenticated();

        $user = User::where('email', 'admin@idl.pk')->first();
        $this->assertNotNull($user, 'Seeded admin user must exist.');
        $this->assertNotNull($user->active_company_id, 'Seeded admin must have an active company so afterauth passes.');

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
    }
}
