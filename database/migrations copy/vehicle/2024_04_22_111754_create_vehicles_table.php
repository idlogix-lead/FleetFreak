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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            //$table->string('owner_name'); //it should be added
            $table->string('vehicle_identification_number');
            $table->foreignId('driver_id')->nullable()->constrained('partners');
            $table->foreignId('company_id')->nullable()->constrained('car_companies');
            $table->string('model');
            $table->unsignedSmallInteger('year');
            $table->string('color')->nullable();
            $table->string('vehicle_no'); //name changed to vehicle_no
            $table->string('registration_no');
            $table->enum('ownership', ['owned', 'leased']);
            $table->tinyInteger('is_ac'); // -> (Air_Conditioner)
            $table->tinyInteger('is_status'); // -> (is car available or not) needs to migrate
            $table->enum('fuel_type', ['gasoline', 'diesel','electric']);
            $table->string('engine_type')->nullable();
            $table->enum('transmission_type', ['automatic', 'manual']);
            $table->foreignId('vehicle_class_id')->nullable()->constrained('vehicle_classes');
            $table->Integer('weight')->nullable();
            $table->string('milage')->nullable(); //added
            $table->enum('car_condition',['new','used','excellent','fair']); //added
            $table->string('image')->nullable();//added
            $table->text('reason')->nullable();
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
        Schema::dropIfExists('vehicles');
    }
};
