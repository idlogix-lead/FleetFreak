<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use App\Models\UserCompany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanySwitchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A user may only switch their active organization (company) to one they hold
     * a user_companies membership for — this is the backbone of organization-level
     * access, so it gets an explicit smoke test.
     */
    public function test_user_can_switch_active_company_within_their_membership(): void
    {
        $this->seed();

        $user = User::where('email', 'admin@idl.pk')->first();
        $this->assertNotNull($user);

        $secondCompany = Company::create([
            'name' => 'Second Test Company',
            'client_id' => $user->client_id,
        ]);
        UserCompany::create([
            'user_id' => $user->id,
            'company_id' => $secondCompany->id,
        ]);

        $response = $this->actingAs($user)->postJson('/companies/change_active', [
            'company_id' => $secondCompany->id,
        ]);

        $response->assertStatus(200);
        $this->assertEquals(
            $secondCompany->id,
            $user->fresh()->active_company_id,
            'Active company must be switched to the member company.'
        );
    }

    /**
     * Security property: switching to a company the user does NOT belong to must
     * never result in that company becoming active.
     *
     * Note: change_active_company() currently falls through on validation failure
     * and nulls active_company_id (known defect, queued for Phase 1) — either way
     * the foreign company must never become active.
     */
    public function test_user_cannot_switch_to_a_company_they_do_not_belong_to(): void
    {
        $this->seed();

        $user = User::where('email', 'admin@idl.pk')->first();
        $originalActiveCompanyId = $user->active_company_id;

        $foreignCompany = Company::create([
            'name' => 'Foreign Company',
        ]);

        $this->actingAs($user)->postJson('/companies/change_active', [
            'company_id' => $foreignCompany->id,
        ]);

        $this->assertNotEquals(
            $foreignCompany->id,
            $user->fresh()->active_company_id,
            'A non-member company must never become the active company.'
        );
        $this->assertEquals(
            $originalActiveCompanyId,
            $user->fresh()->active_company_id,
            'A failed switch must leave the active company unchanged (current code nulls it — defect tracked for Phase 1).'
        );
    }
}
