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
        Schema::create('rate_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('vehicle_class_id')->constrained('vehicle_classes');
            $table->foreignId('route_id')->constrained('routes');
            $table->text('description')->nullable();
            $table->decimal('price',20,2); //added
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
