<?php

namespace App\Support;

use App\Models\User;

/**
 * Resolves the organization (the `companies` table) a request operates in.
 *
 * Contract:
 * - id() never throws: returns the active company id, an explicit override,
 *   or null when unresolvable (console without set(), super admin, guest).
 * - idOrThrow() is the fail-closed accessor for controllers/services: it
 *   throws OrganizationContextMissing instead of letting a null company id
 *   turn a query into a cross-tenant read.
 * - scopeCompanyId() is used by the BelongsToOrganization global scope:
 *   neutral on the console (no auth user — commands scope themselves via
 *   set()/bypass()), neutral for super admins (platform operators), and
 *   fail-closed for every other authenticated user.
 *
 * There is deliberately no "first company" fallback — that pattern is what
 * produced the AccountController cross-tenant defect.
 */
class OrganizationContext
{
    protected static ?int $override = null;

    protected static bool $bypassed = false;

    public static function id(): ?int
    {
        if (static::$bypassed) {
            return null;
        }

        if (static::$override !== null) {
            return static::$override;
        }

        $user = auth()->user();

        if (!$user instanceof User) {
            return null;
        }

        return $user->active_company_id ?: null;
    }

    public static function idOrThrow(): int
    {
        $id = static::id();

        if ($id === null) {
            throw new OrganizationContextMissing('No active organization for the current user.');
        }

        return $id;
    }

    public static function scopeCompanyId(): ?int
    {
        $user = auth()->user();

        if (!$user instanceof User) {
            return static::id();
        }

        if ($user->is_super_admin) {
            return null;
        }

        return static::idOrThrow();
    }

    /** Console commands: pin the context explicitly. */
    public static function set(?int $companyId): void
    {
        static::$override = $companyId;
    }

    /** Platform CLI/seeding paths only. */
    public static function bypass(bool $bypassed = true): void
    {
        static::$bypassed = $bypassed;
    }

    public static function isBypassed(): bool
    {
        return static::$bypassed;
    }

    /** Long-running workers/tests: drop override and bypass between runs. */
    public static function reset(): void
    {
        static::$override = null;
        static::$bypassed = false;
    }
}
