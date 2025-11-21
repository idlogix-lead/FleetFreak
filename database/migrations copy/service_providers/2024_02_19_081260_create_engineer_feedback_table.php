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
        Schema::create('engineer_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('engineer_id')->constrained('engineers');
            $table->string('before_image')->nullable();
            $table->string('after_image')->nullable();
            $table->string('audio_file')->nullable();
            $table->text('description')->nullable();
            $table->string('working_days');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engineer_feedback');
    }
};
