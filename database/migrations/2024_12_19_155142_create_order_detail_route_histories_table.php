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
        Schema::create('order_detail_route_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_detail_id')->nullable()->constrained('order_details');
            $table->string('latitude');
            $table->string('longitude');
            $table->tinyInteger('is_permanent')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_detail_route_histories');
    }
};
