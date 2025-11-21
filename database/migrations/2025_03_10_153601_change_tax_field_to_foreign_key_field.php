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
        Schema::table('invoice_lines', function (Blueprint $table) {
            $table->dropColumn('tax');
        });
        Schema::table('invoice_lines', function (Blueprint $table) {
            $table->foreignId('tax_id')->nullable()->constrained('taxes');
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->date('date')->nullable(true)->change();
        });
        Schema::table('order_lines', function (Blueprint $table) {
            $table->dropColumn('tax');
        });
        Schema::table('order_lines', function (Blueprint $table) {
            $table->foreignId('tax_id')->nullable()->constrained('taxes');
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_lines', function (Blueprint $table) {
            // Rollback: Drop foreign key and add back the double 'tax' column
            $table->dropForeign(['tax_id']);
            $table->dropColumn('tax_id');
        });
        Schema::table('invoice_lines', function (Blueprint $table) {
            $table->double('tax')->nullable();
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->date('date')->nullable(false)->change();
        });
        Schema::table('order_lines', function (Blueprint $table) {
            $table->dropForeign(['tax_id']);
            $table->dropColumn('tax_id');
        });
        Schema::table('order_lines', function (Blueprint $table) {
            $table->double('tax')->nullable();
        });
    }
};
