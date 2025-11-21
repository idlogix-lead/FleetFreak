<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngineerComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('engineer_complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('engineer_id')->constrained('engineers');
            $table->foreignId('complaint_id')->constrained('complaints');
            // $table->unsignedBigInteger('engineer_id');
            // $table->foreign('engineer_id')->references('id')->on('engineers')->onDelete('cascade');
            // $table->unsignedBigInteger('complaint_id');
            // $table->foreign('complaint_id')->references('id')->on('complaints')->onDelete('cascade');
            $table->enum('status', ['pending', 'in_progress', 'resolved'])->default('pending');
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
        Schema::dropIfExists('complaint_engineers');
    }
}