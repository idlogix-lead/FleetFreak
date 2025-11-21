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
            $table->foreignId('rate_list_id')->constrained('rate_lists');
            $table->string('rate');
            $table->enum('status',['approved','rejected']);
            $table->string('adult');
            $table->string('child');
            $table->string('bags');
            $table->date('date');
            $table->time('checkin_time');
            $table->time('checkout_time');
            $table->tinyInteger('is_ac');
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
