<?php

namespace App\Support;

/**
 * Helpers for query surfaces the BelongsToOrganization global scope cannot
 * reach: DB::table() builders, UNION legs, and report/aggregate queries.
 */
class OrganizationAccess
{
    /**
     * Fail-closed company id for manual query building. Replaces the fail-open
     * `->when($companyId, fn ($q) => $q->where('company_id', $companyId))`
     * idiom, which silently returned every tenant's rows when the active
     * company was null.
     */
    public static function companyId(): int
    {
        return OrganizationContext::idOrThrow();
    }

    /**
     * Apply the organization filter to any query builder (Eloquent or query
     * builder). Returns the builder for chaining.
     */
    public static function scopeCompany($query, string $column = 'company_id')
    {
        return $query->where($column, self::companyId());
    }
}
