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
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('bus_id')->nullable()->constrained('businesses');
            $table->foreignId('role_module_id')->constrained('role_modules');
            $table->foreignId('role_id')->constrained('roles');
            $table->foreignId('role_permission_type_id')->constrained('role_permission_types');

            $table->tinyInteger('permission')->default(0);
            // $table->tinyInteger('read')->default(0);
            // $table->tinyInteger('update')->default(0);
            // $table->tinyInteger('delete')->default(0);
            // $table->tinyInteger('recover')->default(0);
            // $table->tinyInteger('global')->default(0);
            // $table->foreignId('created_by')->constrained('users');
            // $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
