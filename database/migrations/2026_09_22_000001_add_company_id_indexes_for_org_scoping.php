<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 1: the organization global scope now filters every core table by
 * company_id — give the filter indexes to keep it cheap.
 */
return new class extends Migration
{
    private const TABLES = [
        'orders', 'order_lines', 'partners', 'vehicles', 'invoices',
        'account_transactions', 'payment_headers', 'payment_lines',
        'accounts', 'gl_journals',
    ];

    public function up(): void
    {
        foreach (self::TABLES as $tableName) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }

            $alreadyIndexed = collect(Schema::getIndexes($tableName))
                ->contains(function ($index) {
                    return ($index['columns'] ?? []) === ['company_id'];
                });

            if ($alreadyIndexed) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) {
                $table->index('company_id');
            });
        }
    }

    public function down(): void
    {
        foreach (self::TABLES as $tableName) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }

            try {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropIndex(['company_id']);
                });
            } catch (\Throwable $e) {
                // index already gone / named differently — nothing to reverse
            }
        }
    }
};
