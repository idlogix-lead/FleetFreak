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
        Schema::create('machines', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_id')->constrained('customers');
                $table->string('serial_number')->nullable();
                $table->string('name');
                $table->string('model');
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                // $table->unsignedBigInteger('customer_id');
                // $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
                $table->foreignId('created_by')->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->softDeletes();
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machines');
    }
};
