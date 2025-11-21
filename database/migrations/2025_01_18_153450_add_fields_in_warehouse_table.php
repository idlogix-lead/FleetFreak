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
        Schema::table('ware_houses', function (Blueprint $table) {
            $table->foreignId('locator_id')->nullable()->constrained('locators');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ware_houses', function (Blueprint $table) {
            $table->dropForeign(['locator_id']);
            $table->dropColumn('locator_id');
        });
    }
};
