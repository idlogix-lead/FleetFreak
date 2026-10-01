<?php

namespace Tests\Unit;

use App\Models\User;
use App\Support\OrganizationContext;
use App\Support\OrganizationContextMissing;
use Tests\TestCase;

class OrganizationContextTest extends TestCase
{
    protected function tearDown(): void
    {
        OrganizationContext::reset();
        auth()->logout();

        parent::tearDown();
    }

    public function test_id_is_null_when_no_user_is_authenticated(): void
    {
        $this->assertNull(OrganizationContext::id());
    }

    public function test_id_is_the_active_company_of_the_authenticated_user(): void
    {
        $this->actingAs(new User(['active_company_id' => 7]));

        $this->assertSame(7, OrganizationContext::id());
    }

    public function test_id_is_null_when_the_user_has_no_active_company(): void
    {
        $this->actingAs(new User(['active_company_id' => null]));

        $this->assertNull(OrganizationContext::id());
    }

    public function test_id_or_throw_fails_closed_for_a_user_without_an_active_company(): void
    {
        $this->actingAs(new User(['active_company_id' => null, 'is_super_admin' => 0]));

        $this->expectException(OrganizationContextMissing::class);

        OrganizationContext::idOrThrow();
    }

    public function test_super_admin_is_neutral_in_the_global_scope_platform_operator_mode(): void
    {
        $this->actingAs(new User(['active_company_id' => null, 'is_super_admin' => 1]));

        $this->assertNull(OrganizationContext::scopeCompanyId());
    }

    public function test_scope_company_id_returns_the_active_company_for_normal_users(): void
    {
        $this->actingAs(new User(['active_company_id' => 3]));

        $this->assertSame(3, OrganizationContext::scopeCompanyId());
    }

    public function test_explicit_override_wins(): void
    {
        $this->actingAs(new User(['active_company_id' => 3]));
        OrganizationContext::set(9);

        $this->assertSame(9, OrganizationContext::id());
    }

    public function test_bypass_makes_the_context_unresolvable(): void
    {
        $this->actingAs(new User(['active_company_id' => 3]));
        OrganizationContext::bypass();

        $this->assertNull(OrganizationContext::id());
    }

    public function test_reset_clears_override_and_bypass(): void
    {
        OrganizationContext::set(9);
        OrganizationContext::reset();

        $this->assertNull(OrganizationContext::id());
    }
}
