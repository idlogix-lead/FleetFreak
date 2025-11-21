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
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('is_default')->default(0);
        });
        Schema::table('product_categories', function (Blueprint $table) {
            $table->tinyInteger('is_active')->default(1);
        });
        Schema::table('product_types', function (Blueprint $table) {
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('is_default')->default(0);

        });
        Schema::table('manufacturing_companies', function (Blueprint $table) {
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('is_default')->default(0);

        });
        Schema::table('brands', function (Blueprint $table) {
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('is_default')->default(0);

        });
        Schema::table('ware_houses', function (Blueprint $table) {
            $table->tinyInteger('is_active')->default(1)->change();
            $table->tinyInteger('is_default')->default(0);


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_sub_categories', function (Blueprint $table) {
            $table->dropColumn('is_active');
            $table->dropColumn('is_default');
        });
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
        Schema::table('product_types', function (Blueprint $table) {
            $table->dropColumn('is_active');
            $table->dropColumn('is_default');

        });
        Schema::table('manufacturing_companies', function (Blueprint $table) {
            $table->dropColumn('is_active');
            $table->dropColumn('is_default');

        });
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('is_active');
            $table->dropColumn('is_default');

        });
        Schema::table('ware_houses', function (Blueprint $table) {
            $table->tinyInteger('is_active')->default(0)->change();
            $table->dropColumn('is_default');


        });
    }
};
