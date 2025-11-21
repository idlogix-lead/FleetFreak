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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            
            $table->text('about_us')->nullable();
            $table->text('create_an_order')->nullable();
            $table->text('manage_booking')->nullable();
            $table->text('customer_management')->nullable();
            $table->text('ledger')->nullable();
            $table->text('notifications')->nullable();
            $table->text('profile')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
