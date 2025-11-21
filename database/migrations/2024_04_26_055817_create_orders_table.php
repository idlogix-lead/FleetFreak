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
            $table->foreignId('business_partner_id')->nullable()->constrained('partners');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->enum('overall_status',['approved','cancelled','completed','draft','pending','unapproved'])->nullable();
            $table->string('overall_adult')->nullable();
            $table->string('overall_child')->nullable();
            $table->string('overall_bags')->nullable();
            $table->string('booking_amount')->nullable();
            $table->string('final_amount')->nullable();
            $table->text('reason')->nullable();
            $table->text('description')->nullable(); // added
            $table->foreignId('company_id')->nullable()->constrained('companies');
            $table->foreignId('vehicle_class_id')->nullable()->constrained('vehicle_classes');
            $table->foreignId('vehicle_model_id')->nullable()->constrained('vehicle_models');
            $table->string('trip_type')->nullable();


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
