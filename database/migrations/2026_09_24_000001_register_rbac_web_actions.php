<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Registers web actions that RolePermissions could never authorize: with no
 * role_permission_type_functions row for (module, method), every user -
 * admins included - was redirected to /unauthorized.
 *
 * - `export` on every non-report module's `export` permission type (the type
 *   was seeded, and granted to the module's roles, but with no methods);
 * - `driver_export` on the Ledgers (17) export type;
 * - `getVehicleClassDetails` on Vehicle (5) read, `delete_row` on
 *   RoleModules (3) delete.
 *
 * Mirrors the RolePermissionSeeder change for databases seeded before it. On a
 * fresh database the RBAC tables are still empty when migrations run, so this
 * is a no-op there and the seeder registers the same rows.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach ($this->registrations() as [$typeId, $method, $returnType]) {
            $exists = DB::table('role_permission_type_functions')
                ->where('role_permission_type_id', $typeId)
                ->where('method', $method)
                ->exists();

            if (!$exists) {
                DB::table('role_permission_type_functions')->insert([
                    'role_permission_type_id' => $typeId,
                    'method' => $method,
                    'return_type' => $returnType,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        foreach ($this->registrations() as [$typeId, $method]) {
            DB::table('role_permission_type_functions')
                ->where('role_permission_type_id', $typeId)
                ->where('method', $method)
                ->delete();
        }
    }

    /**
     * @return array<int, array{0: int, 1: string, 2: string}> [permission type id, method, return type]
     */
    private function registrations(): array
    {
        $rows = [];

        $exportTypeIds = DB::table('role_permission_types as t')
            ->join('role_modules as m', 'm.id', '=', 't.role_module_id')
            ->where('t.action', 'export')
            ->where('m.is_report', 0)
            ->pluck('t.id');

        foreach ($exportTypeIds as $typeId) {
            $rows[] = [$typeId, 'export', 'view'];
        }

        $specific = [
            // [module id, permission type action, method, return type]
            [17, 'export', 'driver_export', 'view'],
            [5, 'read', 'getVehicleClassDetails', 'json'],
            [3, 'delete', 'delete_row', 'json'],
        ];

        foreach ($specific as [$moduleId, $action, $method, $returnType]) {
            $typeId = DB::table('role_permission_types')
                ->where('role_module_id', $moduleId)
                ->where('action', $action)
                ->value('id');

            if ($typeId) {
                $rows[] = [$typeId, $method, $returnType];
            }
        }

        return $rows;
    }
};
