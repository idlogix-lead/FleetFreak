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
        Schema::create('payment_headers', function (Blueprint $table) {
            $table->id();
            $table->string('payment_no');
            $table->foreignId('agent_id')->constrained('partners');
            $table->foreignId('customer_id')->constrained('partners');
            $table->foreignId('driver_id')->constrained('partners');
            $table->date('date');
            $table->string('total_amount');
            $table->text('description')->nullable(); // added
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_headers');
    }
};
