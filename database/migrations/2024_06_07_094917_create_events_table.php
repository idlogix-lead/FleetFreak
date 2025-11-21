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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            // $table->string('source')->nullable();
            $table->unsignedBigInteger('source_id');
            $table->string('source_name')->nullable();
            $table->string('action');
            $table->text('action_details')->nullable(); // nullable
            $table->text('action_details2')->nullable();// nullable
            $table->text('action_details3')->nullable();// nullable
            // $table->unsignedBigInteger('action_target_id'); //nullable
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('company_id')->nullable()->constrained('companies');
            $table->string('created_by_name')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
