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
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('company_id')->nullable()->constrained('companies');
            $table->foreignId('from_loc')->nullable()->constrained('locations');
            $table->foreignId('to_loc')->nullable()->constrained('locations');
            $table->decimal('distance',20 ,2);
            $table->tinyInteger('is_flight')->default(0);
            $table->enum('distance_unit',['km','m','miles'])->default('km'); //enum (km,M,Miles)
            // $table->foreignId('route_rate_id')->constrained('route_rates');
            //
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
