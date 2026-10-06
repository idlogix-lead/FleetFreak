<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * Row-level "global" permission: `Model::checkGlobal($role_module_id)` (docs/HANDOVER.md §9.8).
 *
 * - The user's role has `global` ticked for the module: no filter.
 * - Unticked: only the records the user created (`created_by`).
 * - No `global` row at all for the module: treated like unticked, and logged as a warning so the misconfigured role
 *   shows up. This used to crash every page that called it ("Attempt to read property permission on null").
 * - Tables without a `created_by` column (actors, routes, rate_lists) can't be limited to the user's own records, so
 *   they return nothing instead of failing with a SQL error.
 *
 * Callers pass their own controller's module (`self::$role_module_id`), not another module's.
 */
trait ChecksGlobalPermission
{
    public function scopeCheckGlobal($query, $role_module_id)
    {
        $user = auth()->user();
        $global = $user->role_module_permission_via_action($role_module_id, 'global');

        if ($global === null) {
            Log::warning('checkGlobal: no global permission row', [
                'user_id' => $user->id, 'role_id' => $user->role_id, 'role_module_id' => $role_module_id,
            ]);
        } elseif ($global->permission) {
            return $query;
        }

        return $this->tableHasCreatedBy()
            ? $query->where($this->getTable() . '.created_by', $user->id)
            : $query->whereRaw('1 = 0');
    }

    private function tableHasCreatedBy(): bool
    {
        static $cache = [];

        return $cache[$this->getTable()] ??= Schema::hasColumn($this->getTable(), 'created_by');
    }
}
