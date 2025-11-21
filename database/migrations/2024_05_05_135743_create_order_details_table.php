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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained('orders');
            $table->foreignId('company_id')->nullable()->constrained('companies');
            $table->foreignId('rate_list_id')->nullable()->constrained('rate_lists');
            $table->string('rate')->nullable();
            $table->enum('status',['approved','unapproved','completed','incomplete','pending','cancelled','in_progress','draft','paid']);
            $table->string('adult')->nullable();
            $table->string('child')->nullable();
            $table->string('bags')->nullable();
            $table->string('flight_num')->nullable();
            $table->string('airline_name')->nullable();
            $table->date('date')->nullable();
            $table->time('pickup_time')->nullable();
            $table->text('estimated_time')->nullable();
            $table->time('checkout_time')->nullable();
            $table->tinyInteger('is_ac')->nullable();
            $table->string('driver_rate')->nullable();
            $table->string('driver_pickup_loc')->nullable();
            $table->string('driver_dropoff_loc')->nullable();
            $table->string('ride_start_mileage')->nullable();
            $table->string('ride_end_mileage')->nullable();
            $table->string('from_loc')->nullable();
            $table->string('to_loc')->nullable();
            $table->foreignId('driver_id')->nullable()->constrained('partners'); //added later
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles');//added later

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
