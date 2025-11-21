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
        Schema::create('role_permission_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_module_id')->constrained('role_modules');
            //$table->foreignIdFor(RoleModule::class)->constrained()->cascadeOnDelete();
            $table->string('action');

            // $table->text('link')->nullable();
            // $table->string('link_name')->nullable();
            // $table->text('link_icon')->nullable();
            // $table->integer('link_position')->nullable();
            // $table->string('function');
            // $table->enum('return',['view','json']);
            $table->tinyInteger('is_read');
            $table->text('denial_msg');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permission_types');
    }
};
