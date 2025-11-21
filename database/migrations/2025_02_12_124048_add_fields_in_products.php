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
        Schema::table('products', function (Blueprint $table) {
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('is_default')->default(0);
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->string('sku')->nullable(true)->change();
            $table->double('cost_price')->nullable(true)->change();
            $table->double('sale_price')->nullable(true)->change();

        });
        Schema::table('material_inouts', function (Blueprint $table) {
            $table->date('accounting_date')->nullable(true)->change();
           

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_active');
            $table->dropColumn('is_default');
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
            $table->string('sku')->nullable(true)->change();
            $table->double('cost_price')->nullable(true)->change();
            $table->double('sale_price')->nullable(true)->change();
        });
        Schema::table('material_inouts', function (Blueprint $table) {
            $table->date('accounting_date')->nullable(true)->change();
           

        });
    }
};
