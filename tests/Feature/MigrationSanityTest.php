<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MigrationSanityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * RefreshDatabase runs migrate:fresh --seed against fleet_freak_testing once
     * per run, before the first database test (not necessarily this one) — the
     * fact that we got here at all proves the full migration chain and the
     * DatabaseSeeder run cleanly on a fresh database (including the guarded
     * duplicate-column breaker in 2025_02_18_105450_add_fields_in_invoices.php).
     */
    public function test_the_full_migration_chain_runs_and_core_tables_exist(): void
    {
        $tables = [
            // Tenancy
            'clients', 'companies', 'user_companies', 'users',
            // RBAC
            'actors', 'roles', 'role_permissions', 'sidebar_groups', 'sidebar_items',
            // Vehicles
            'vehicles', 'vehicle_companies', 'vehicle_classes', 'vehicle_models', 'vehicle_managers',
            // People
            'partners', 'partner_locations',
            // Trips
            'orders', 'order_lines', 'order_detail_route_histories',
            // Documents / expenses
            'invoices', 'invoice_lines', 'invoice_document_types',
            // Payments
            'payment_headers', 'payment_lines',
            // Accounting
            'account_types', 'accounts', 'account_transactions',
            'gl_journals', 'gl_journal_lines', 'tables',
            // ERP core
            'products', 'ware_houses', 'material_inouts', 'material_inout_lines', 'stock_storages',
        ];

        foreach ($tables as $table) {
            $this->assertTrue(Schema::hasTable($table), "Table [{$table}] should exist after migrations.");
        }

        // Regression guard: invoice_lines.material_inout_line_id used to be added
        // by two migrations, which broke migrate:fresh on fresh installs.
        $this->assertTrue(
            Schema::hasColumn('invoice_lines', 'material_inout_line_id'),
            'invoice_lines.material_inout_line_id must exist exactly once via the guarded migration.'
        );
    }
}
