<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Guarded with hasColumn checks: material_inout_line_id was also added by
        // 2025_01_20_122339, which previously broke `migrate:fresh` on fresh installs.
        Schema::table('invoice_lines', function (Blueprint $table) {
            if (!Schema::hasColumn('invoice_lines', 'seq_no')) {
                $table->integer('seq_no')->nullable();
            }
            if (!Schema::hasColumn('invoice_lines', 'total_line_amount')) {
                $table->double('total_line_amount')->nullable();
            }
            if (!Schema::hasColumn('invoice_lines', 'material_inout_line_id')) {
                $table->foreignId('material_inout_line_id')->nullable()->constrained('material_inout_lines');
            }
        });
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'material_inout_id')) {
                $table->foreignId('material_inout_id')->nullable()->constrained('material_inouts');
            }
            if (Schema::hasColumn('invoices', 'price_list')) {
                $table->dropColumn('price_list');
            }
        });
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'price_list_id')) {
                $table->foreignId('price_list_id')->nullable()->constrained('price_lists');
            }
        });
        Schema::table('material_inout_lines', function (Blueprint $table) {
            if (!Schema::hasColumn('material_inout_lines', 'status')) {
                $table->string('status')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_lines', function (Blueprint $table) {
            if (Schema::hasColumn('invoice_lines', 'material_inout_line_id')) {
                $table->dropForeign(['material_inout_line_id']);
                $table->dropColumn('material_inout_line_id');
            }
            if (Schema::hasColumn('invoice_lines', 'seq_no')) {
                $table->dropColumn('seq_no');
            }
            if (Schema::hasColumn('invoice_lines', 'total_line_amount')) {
                $table->dropColumn('total_line_amount');
            }
        });
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'material_inout_id')) {
                $table->dropForeign(['material_inout_id']);
                $table->dropColumn('material_inout_id');
            }
            if (!Schema::hasColumn('invoices', 'price_list')) {
                $table->string('price_list');
            }
        });
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'price_list_id')) {
                $table->dropForeign(['price_list_id']);
                $table->dropColumn('price_list_id');
            }
        });
        Schema::table('material_inout_lines', function (Blueprint $table) {
            if (Schema::hasColumn('material_inout_lines', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
