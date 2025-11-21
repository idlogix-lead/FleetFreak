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
        Schema::table('vehicles', function (Blueprint $table) {
            //
            $table->string('chassis_no')->nullable();
            $table->string('route_permits_no')->nullable();
            $table->string('route_permits_expiry_date')->nullable();
            $table->string('fitness_certificate_no')->nullable();
            $table->string('insurance_no')->nullable();
            $table->string('insurance_provider')->nullable();
            $table->date('insurance_start_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            //
        });
    }
};
