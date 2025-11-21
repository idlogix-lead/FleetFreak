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
        Schema::create('payment_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders');
            $table->foreignId('order_detail_id')->constrained('order_details');
            $table->foreignId('payment_header_id')->constrained('payment_headers');
            $table->foreignId('company_id')->nullable()->constrained('companies');
            $table->string('total_amount');
            $table->date('transaction_date');
            $table->text('description')->nullable();
            $table->enum('payment_type',['cash'])->default('cash');
            $table->string('reference_no')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_lines');
    }
};
