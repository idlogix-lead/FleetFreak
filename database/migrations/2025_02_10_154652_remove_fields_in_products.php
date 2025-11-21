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
        Schema::table('product_sub_categories', function (Blueprint $table) {
            $table->dropColumn('code');
        });
        Schema::table('product_types', function (Blueprint $table) {
            $table->dropColumn('code');
        });
        Schema::table('unit_measures', function (Blueprint $table) {
            $table->dropColumn('uom_code');
        });
        Schema::table('material_inouts', function (Blueprint $table) {
            $table->string('delivery_no')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_sub_categories', function (Blueprint $table) {
            $table->string('code')->nullable();
        });
        Schema::table('product_types', function (Blueprint $table) {
            $table->string('code')->nullable();
        });
        Schema::table('unit_measures', function (Blueprint $table) {
            $table->string('uom_code')->nullable();
        });
        Schema::table('material_inouts', function (Blueprint $table) {
            $table->integer('delivery_no')->nullable();
        });
    }
};
