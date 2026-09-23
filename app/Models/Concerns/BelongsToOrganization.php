<?php

namespace App\Models\Concerns;

use App\Support\OrganizationContext;
use Illuminate\Database\Eloquent\Builder;

/**
 * Organization-level data isolation for models carrying a company_id column.
 *
 * - Global scope: every query is filtered to the current organization.
 *   Neutral on the console (no auth user) and for super admins; fail-closed
 *   (OrganizationContextMissing) for authenticated users without an active
 *   organization — never an unfiltered read.
 * - creating hook: defaults company_id server-side from the context, so a
 *   forged request payload cannot place a record in another organization.
 *
 * Only apply this trait to models whose table HAS a company_id column.
 * Escape hatch (grep for it in reviews): scopeWithoutGlobalOrganizationalScope().
 */
trait BelongsToOrganization
{
    public static function bootBelongsToOrganization(): void
    {
        static::addGlobalScope('organization', function (Builder $builder) {
            $companyId = OrganizationContext::scopeCompanyId();

            if ($companyId !== null) {
                $builder->where($builder->getModel()->getTable() . '.company_id', $companyId);
            }
        });

        static::creating(function ($model) {
            $companyId = OrganizationContext::id();

            if ($companyId !== null) {
                // The context wins over any payload value: a request can never
                // place a record into another organization.
                $model->company_id = $companyId;
            }
        });
    }

    public function scopeWithoutGlobalOrganizationalScope(Builder $query): Builder
    {
        return $query->withoutGlobalScope('organization');
    }
}
