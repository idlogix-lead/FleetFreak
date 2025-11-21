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
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('requirements',['personal','business'])->nullable();
            $table->enum('direction',['oneway','return'])->nullable();
            $table->dropColumn('trip_type');
            // $table->enum('trip_type',['passenger_trip', 'cargo_trip'])->default('passenger_trip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('trip_type');
            //
        });
    }
};
