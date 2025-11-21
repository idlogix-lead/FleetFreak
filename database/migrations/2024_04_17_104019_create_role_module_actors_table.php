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
        Schema::create('role_module_actors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_id')->nullable()->constrained('actors');
            $table->foreignId('role_module_id')->nullable()->constrained('role_modules');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_module_actors');
    }
};
