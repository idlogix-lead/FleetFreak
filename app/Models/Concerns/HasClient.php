<?php

namespace App\Models\Concerns;

/**
 * Data-quality companion to BelongsToOrganization: defaults client_id on
 * create from the authenticated user, for tables that carry the column
 * (the 2025+ ERP wave and all new tables).
 *
 * Deliberately NO global scope: tenant isolation is already guaranteed
 * transitively — company_id scoping pins queries to specific company rows,
 * and users can only hold memberships in companies of their own client
 * (registration + change_active_company validate membership). A client_id
 * query filter would add SQL surface without closing any hole, and would
 * break legitimate cross-organization (same client) platform screens.
 */
trait HasClient
{
    public static function bootHasClient(): void
    {
        static::creating(function ($model) {
            if (empty($model->client_id) && auth()->check()) {
                $clientId = auth()->user()->client_id;

                if (!empty($clientId)) {
                    $model->client_id = $clientId;
                }
            }
        });
    }
}
