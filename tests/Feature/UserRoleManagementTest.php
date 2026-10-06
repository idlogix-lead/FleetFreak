<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Company;
use App\Models\Role;
use App\Models\RoleHasModule;
use App\Models\RolePermission;
use App\Models\RolePermissionType;
use App\Models\User;
use App\Models\UserCompany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Users and roles (docs/HANDOVER.md §9.12). Editing a user used to load any user in any organization (route-model
 * binding), and create/edit accepted any role id, so an admin could give anyone, themselves included, the super
 * admin role. Editing a role used to write any permission row id the form posted, whatever role it belonged to.
 * Now users load through User::manageableBy, roles must be Role::assignableBy, and a role edit only writes that
 * role's own rows.
 */
class UserRoleManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;      // the seeded admin: the client's owner, organization A
    private User $adminA;     // a second admin in organization A
    private User $staffA;     // organization A
    private User $sibling;    // the same client, a sibling organization
    private User $staffB;     // organization B, another client
    private User $superAdmin;
    private int $superAdminRole;
    private int $staffRole;
    private Role $otherClientRole;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->owner = User::where('email', 'admin@idl.pk')->first();
        $this->superAdmin = User::where('is_super_admin', 1)->firstOrFail();
        $this->superAdminRole = Role::where('actor_id', 1)->value('id');
        $this->staffRole = Role::where('name', 'office_staff')->value('id');
        $client = $this->owner->client_id;
        $siblingOrg = Company::create(['name' => 'Sibling Org', 'client_id' => $client])->id;
        $otherClient = Client::create(['name' => 'Other Client', 'is_active' => 1])->id;
        $orgB = Company::create(['name' => 'Org B', 'client_id' => $otherClient])->id;
        $this->otherClientRole = Role::create(['name' => 'Other Client Staff', 'home' => '/dashboard', 'client_id' => $otherClient, 'actor_id' => 7, 'is_system' => 0]);

        $this->adminA = $this->member('admin.a', $client, [$this->owner->active_company_id], 'admin', 2);
        $this->staffA = $this->member('staff.a', $client, [$this->owner->active_company_id]);
        $this->sibling = $this->member('sibling', $client, [$siblingOrg]);
        $this->staffB = $this->member('staff.b', $otherClient, [$orgB]);
    }

    public function test_admin_cannot_view_or_edit_a_user_outside_their_organization_or_the_super_admin(): void
    {
        $this->actingAs($this->owner);

        foreach (['staffB' => 'another client', 'sibling' => 'a sibling organization', 'superAdmin' => 'the super admin'] as $target => $why) {
            $user = $this->{$target};
            $before = $user->fresh()->only(['name', 'email', 'role_id']);

            $this->get("/users/{$user->id}")->assertNotFound();
            $this->get("/users/{$user->id}/edit")->assertNotFound();
            $this->put("/users/{$user->id}", $this->editForm($user, ['name' => 'Taken Over', 'email' => 'taken.over@evil.test']))->assertNotFound();

            $this->assertSame($before, $user->fresh()->only(['name', 'email', 'role_id']), "unchanged: {$why}");
        }
    }

    public function test_admin_can_still_view_and_edit_a_user_in_their_organization(): void
    {
        $this->actingAs($this->owner);
        $manager = Role::where('name', 'management')->value('id');

        $this->get("/users/{$this->staffA->id}")->assertOk();
        $this->get("/users/{$this->staffA->id}/edit")->assertOk();
        $this->get('/users/create')->assertOk(); // both forms render their role drop-down from the controller's $roles
        $this->put("/users/{$this->staffA->id}", $this->editForm($this->staffA, ['name' => 'Renamed Staff', 'role_id' => $manager, 'role_id_hidden' => $manager]))
            ->assertRedirect()->assertSessionHas('success');

        $this->assertSame('Renamed Staff', $this->staffA->fresh()->name);
        $this->assertSame($manager, (int) $this->staffA->fresh()->role_id);
    }

    // One user per test: AuthenticateSession (web group) logs a session out when the user behind it changes.
    public function test_admin_cannot_make_themselves_super_admin(): void
    {
        // A second admin: their own account is manageable, so the role rule is what stops them.
        $super = ['role_id' => $this->superAdminRole, 'role_id_hidden' => $this->superAdminRole];
        $this->actingAs($this->adminA)->put("/users/{$this->adminA->id}", $this->editForm($this->adminA, $super))
            ->assertRedirect()->assertSessionHas('errors');

        $this->assertSame(Role::where('name', 'admin')->value('id'), (int) $this->adminA->fresh()->role_id);
        $this->assertSame(2, (int) $this->adminA->fresh()->actor_id);
    }

    public function test_client_owner_cannot_make_themselves_super_admin(): void
    {
        // The owner can't edit their own account here at all (as before, the user list hides it).
        $super = ['role_id' => $this->superAdminRole, 'role_id_hidden' => $this->superAdminRole];
        $this->actingAs($this->owner)->put("/users/{$this->owner->id}", $this->editForm($this->owner, $super))->assertNotFound();

        $this->assertSame(Role::where('name', 'admin')->value('id'), (int) $this->owner->fresh()->role_id);
    }

    public function test_only_the_clients_own_roles_can_be_given_and_never_the_super_admin_role(): void
    {
        $this->actingAs($this->owner);

        foreach ([$this->superAdminRole => 'the super admin role', $this->otherClientRole->id => "another client's role"] as $roleId => $why) {
            $this->put("/users/{$this->staffA->id}", $this->editForm($this->staffA, ['role_id' => $roleId, 'role_id_hidden' => $roleId]))
                ->assertRedirect()->assertSessionHas('errors');
            $this->put("/users/{$this->staffA->id}", $this->editForm($this->staffA, ['role_id' => null, 'role_id_hidden' => $roleId]))
                ->assertRedirect()->assertSessionHas('errors');
            $this->assertSame($this->staffRole, (int) $this->staffA->fresh()->role_id, "edit refused: {$why}");

            $email = "created.{$roleId}@fleetfreak.test";
            $this->post('/users', ['name' => 'New User', 'email' => $email, 'password' => 'secret-123', 'password_confirmation' => 'secret-123', 'role_id' => $roleId])
                ->assertRedirect()->assertSessionHas('errors');
            $this->assertFalse(User::where('email', $email)->exists(), "create refused: {$why}");
        }
    }

    public function test_role_edit_writes_only_that_roles_rows_and_only_for_the_callers_client(): void
    {
        $this->actingAs($this->owner);
        $module = 12; // Customers
        [$read, $create] = RolePermissionType::where('role_module_id', $module)->orderBy('id')->take(2)->get()->all();

        $custom = Role::create(['name' => 'Custom Dispatcher', 'home' => '/dashboard', 'client_id' => $this->owner->client_id, 'is_system' => 0]);
        RoleHasModule::create(['role_id' => $custom->id, 'role_module_id' => $module]);
        $ownRow = RolePermission::create(['role_id' => $custom->id, 'role_module_id' => $module, 'role_permission_type_id' => $read->id, 'permission' => 0]);
        $adminRow = RolePermission::where('role_id', Role::where('name', 'admin')->value('id'))->where('permission', 1)->firstOrFail();
        $superRow = RolePermission::where('role_id', $this->superAdminRole)->where('permission', 1)->firstOrFail();

        $this->patch("/roles/{$custom->id}", ['name' => 'Custom Dispatcher', 'home' => '/dashboard', 'permission' => [$module => [
            $read->id => ['permission_id' => $ownRow->id, 'permission' => 1],
            $create->id => ['permission_id' => $adminRow->id, 'permission' => 0],
            999999 => ['permission_id' => $superRow->id, 'permission' => 0],
        ]]])->assertRedirect()->assertSessionHas('success');

        $this->assertSame(1, (int) $ownRow->fresh()->permission, "the role's own row is saved");
        $this->assertSame(1, (int) $adminRow->fresh()->permission, "the admin role's row is untouched");
        $this->assertSame(1, (int) $superRow->fresh()->permission, "the super admin role's row is untouched");

        $this->patch("/roles/{$this->otherClientRole->id}", ['name' => 'Renamed By Another Client', 'home' => '/x', 'permission' => [$module => [$read->id => ['permission_id' => '', 'permission' => 1]]]])
            ->assertNotFound();
        $this->assertSame('Other Client Staff', $this->otherClientRole->fresh()->name);
    }

    /** The fields resources/views/user/edituserform.blade.php submits, for $user. */
    private function editForm(User $user, array $overrides = []): array
    {
        return array_merge([
            'name' => $user->name, 'email' => $user->email, 'phone_no1' => null, 'phone_no2' => null, 'description' => null,
            'role_id' => $user->role_id, 'role_id_hidden' => $user->role_id,
        ], $overrides);
    }

    private function member(string $name, int $clientId, array $companies, string $role = 'office_staff', int $actorId = 7): User
    {
        $user = User::create([
            'name' => $name, 'email' => "{$name}@fleetfreak.test", 'password' => Hash::make('secret-123'),
            'role_id' => Role::where('name', $role)->value('id'), 'actor_id' => $actorId, 'client_id' => $clientId,
            'active_company_id' => $companies[0], 'flag' => 0,
        ]);
        foreach ($companies as $companyId) {
            UserCompany::create(['user_id' => $user->id, 'company_id' => $companyId]);
        }

        return $user;
    }
}
