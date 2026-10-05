<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use App\Models\UserCompany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

/**
 * Passwords (docs/HANDOVER.md §9.12). An admin's reset (PUT /change-password/{id}) used to reach any user in any
 * organization, the super admin included; now only users the admin may manage, and it signs the user's API tokens
 * out. Every user changes their own password on the profile page, after confirming the current one. The first-login
 * page is only for agents who still have to set their own, and the forgot-password form posts to the reset route.
 */
class PasswordManagementTest extends TestCase
{
    use RefreshDatabase;

    private const OLD = 'old-secret-1';
    private const NEW = 'new-secret-2';

    private User $owner;      // the seeded admin: the client's owner, organization A
    private User $adminA;     // a second admin in organization A
    private User $staffA;     // organization A
    private User $sibling;    // the same client, a sibling organization
    private User $shared;     // organization A and the sibling organization
    private User $staffB;     // organization B, another client
    private User $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->owner = User::where('email', 'admin@idl.pk')->first();
        $this->superAdmin = User::where('is_super_admin', 1)->firstOrFail();
        $client = $this->owner->client_id;
        $orgA = $this->owner->active_company_id;
        $siblingOrg = Company::create(['name' => 'Sibling Org', 'client_id' => $client])->id;
        $otherClient = Client::create(['name' => 'Other Client', 'is_active' => 1])->id;
        $orgB = Company::create(['name' => 'Org B', 'client_id' => $otherClient])->id;

        $this->adminA = $this->member('admin.a', $client, [$orgA], 'admin', 2);
        $this->staffA = $this->member('staff.a', $client, [$orgA]);
        $this->sibling = $this->member('sibling', $client, [$siblingOrg]);
        $this->shared = $this->member('shared', $client, [$orgA, $siblingOrg]);
        $this->staffB = $this->member('staff.b', $otherClient, [$orgB]);
    }

    public function test_admin_resets_a_user_in_their_organization_and_signs_out_their_api_tokens(): void
    {
        $this->staffA->createToken('mobile');
        Log::spy();

        $this->actingAs($this->owner)->put("/change-password/{$this->staffA->id}", $this->newPassword())
            ->assertRedirect()->assertSessionHas('success');

        $this->assertTrue(Hash::check(self::NEW, $this->staffA->fresh()->password));
        $this->assertSame(0, $this->staffA->tokens()->count(), 'the old API tokens are revoked');
        Log::shouldHaveReceived('notice')->with('Password reset by an admin', ['actor_id' => $this->owner->id, 'user_id' => $this->staffA->id]);
    }

    public function test_admin_cannot_reset_anyone_outside_their_organization_or_a_super_admin(): void
    {
        $this->actingAs($this->owner);

        foreach (['staffB' => 'another client', 'sibling' => 'a sibling organization', 'shared' => 'also in an organization the admin is not in', 'superAdmin' => 'the super admin'] as $target => $why) {
            $user = $this->{$target};
            $before = $user->fresh()->password;

            $this->put("/change-password/{$user->id}", $this->newPassword())->assertNotFound();

            $this->assertSame($before, $user->fresh()->password, "unchanged: {$why}");
        }
    }

    public function test_admin_cannot_reset_the_client_owner(): void
    {
        $before = $this->owner->password;

        $this->actingAs($this->adminA)->put("/change-password/{$this->owner->id}", $this->newPassword())->assertNotFound();

        $this->assertSame($before, $this->owner->fresh()->password);
    }

    public function test_admin_changes_their_own_password_on_the_profile_page_not_through_a_reset(): void
    {
        $this->actingAs($this->adminA)->put("/change-password/{$this->adminA->id}", $this->newPassword())
            ->assertRedirect()->assertSessionHas('error');

        $this->assertTrue(Hash::check(self::OLD, $this->adminA->fresh()->password));
    }

    public function test_every_user_can_change_their_own_password_after_confirming_the_current_one(): void
    {
        $agent = $this->member('agent.self', $this->owner->client_id, [$this->owner->active_company_id], 'agent', 4);
        $this->actingAs($agent);

        $this->put('/user-profile/password', ['current_password' => 'wrong-password'] + $this->newPassword())->assertSessionHasErrors('current_password');
        $this->put('/user-profile/password', ['current_password' => self::OLD, 'password' => self::OLD, 'password_confirmation' => self::OLD])->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check(self::OLD, $agent->fresh()->password));

        $this->put('/user-profile/password', ['current_password' => self::OLD] + $this->newPassword())
            ->assertRedirect()->assertSessionHasNoErrors()->assertSessionHas('success');
        $this->assertTrue(Hash::check(self::NEW, $agent->fresh()->password));

        // Still signed in: AuthenticateSession stored the new password hash with that request.
        $this->get('/user-profile')->assertOk();
    }

    // One user per test: AuthenticateSession (web group) logs a session out when the user behind it changes.
    public function test_first_login_page_turns_away_users_who_dont_need_it(): void
    {
        $this->actingAs($this->staffA)->get('/password/change')->assertRedirect(route('user-profile.create'));
        $this->post('/password/change', $this->newPassword())->assertRedirect(route('user-profile.create'));

        $this->assertTrue(Hash::check(self::OLD, $this->staffA->fresh()->password));
    }

    public function test_first_login_page_still_works_for_agents_who_must_set_their_own_password(): void
    {
        $flagged = $this->member('agent.new', $this->owner->client_id, [$this->owner->active_company_id], 'agent', 4, flag: 1);

        $this->actingAs($flagged)->get('/password/change')->assertOk();
        $this->post('/password/change', $this->newPassword())->assertRedirect(route('login'));

        $this->assertTrue(Hash::check(self::NEW, $flagged->fresh()->password));
        $this->assertSame(0, (int) $flagged->fresh()->flag);
    }

    public function test_forgot_password_form_posts_to_the_reset_route_again(): void
    {
        $this->assertSame(url('/password/reset'), route('password.update'));
        $this->assertSame(url('/password/change'), route('password.change.update'));

        $this->get('/password/reset/some-token')->assertOk()->assertSee('action="' . url('/password/reset') . '"', false);
    }

    private function member(string $name, int $clientId, array $companies, string $role = 'office_staff', int $actorId = 7, int $flag = 0): User
    {
        // flag 0: AfterAuthentication sends flagged agents to the first-login page.
        $user = User::create([
            'name' => $name, 'email' => "{$name}@fleetfreak.test", 'password' => Hash::make(self::OLD),
            'role_id' => Role::where('name', $role)->value('id'), 'actor_id' => $actorId, 'client_id' => $clientId,
            'active_company_id' => $companies[0], 'flag' => $flag,
        ]);
        foreach ($companies as $companyId) {
            UserCompany::create(['user_id' => $user->id, 'company_id' => $companyId]);
        }

        return $user;
    }

    private function newPassword(): array
    {
        return ['password' => self::NEW, 'password_confirmation' => self::NEW];
    }
}
