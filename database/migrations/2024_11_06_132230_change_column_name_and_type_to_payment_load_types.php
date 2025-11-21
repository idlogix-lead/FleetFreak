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
        Schema::table('load_types', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });
        Schema::table('unit_measures', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('load_types', function (Blueprint $table) {

        });
        Schema::table('unit_measures', function (Blueprint $table) {
            
        });
    }
};
