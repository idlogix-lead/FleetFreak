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
        Schema::create('role_modules', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('bus_id')->constrained('businesses');
            $table->string('name');
            // $table->foreignId('role_permission_type_id')->nullable()->constrained('role_permission_types');
            $table->foreignId('actor_id')->nullable()->constrained('actors');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
        // DB::statement("ALTER SEQUENCE role_modules_id_seq RESTART WITH 501;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_modules');
    }
};
