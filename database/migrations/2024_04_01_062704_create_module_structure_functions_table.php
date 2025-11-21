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
        Schema::create('role_permission_type_functions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_permission_type_id')->constrained('role_permission_types');
            $table->string('method');
            $table->enum('return_type',['view','json']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permission_type_functions');
    }
};
