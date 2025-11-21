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
            //
            $table->double('meter_reading_km')->nullable();
            $table->string('meter_reading_image')->nullable();
            $table->string('bill_image')->nullable();
            $table->double('fuel_quantity_liters')->nullable();
            $table->string('driver_selfie')->nullable();
            $table->string('petrol_machine_image')->nullable();         
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_lines', function (Blueprint $table) {
            //
            $table->double('meter_reading_km')->nullable();
            $table->dropColumn('meter_reading_image')->nullable();
            $table->dropColumn('bill_image')->nullable();
            $table->dropColumn('fuel_quantity_liters')->nullable();
            $table->dropColumn('driver_selfie')->nullable();
            $table->dropColumn('petrol_machine_image')->nullable(); 
        });
    }
};
