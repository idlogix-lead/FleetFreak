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
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['vehicle_id']);
            $table->dropColumn('vehicle_id');
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles');
        });
        Schema::table('invoice_lines', function (Blueprint $table) {
            $table->dropColumn('product');
        });
        Schema::table('invoice_lines', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable()->constrained('products');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['vehicle_id']);
            $table->dropColumn('vehicle_id');
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('vehicle_id')->constrained('vehicles');
            
        });
        Schema::table('invoice_lines', function (Blueprint $table) {
            $table->string('product');
        });
        Schema::table('invoice_lines', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');

        });
    }
};
