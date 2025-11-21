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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no');
            $table->foreignId('customer_partner_id')->constrained('partners');
            $table->foreignId('business_partner_id')->constrained('partners');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->enum('overall_status',['approved','rejected','completed','draft','pending','approved_but_not_completed']);
            $table->string('overall_adult');
            $table->string('overall_child');
            $table->string('overall_bags');
            $table->string('booking_amount');
            $table->string('final_amount');
            $table->text('reason');
            $table->text('description')->nullable(); // added

            // $table->string('whatsapp_no');
            // $table->date('date');
            // $table->time('pickup_time');
            // $table->text('pickup_loc');
            // $table->text('dropoff_loc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
