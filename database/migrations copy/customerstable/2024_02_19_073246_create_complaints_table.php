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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('machine_id')->constrained('machines');
            $table->text('problem_statment');
            $table->date('date');
            $table->enum('status', ['pending', 'in_progress', 'resolved', 'rejected', 'refused'])->default('pending');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->softDeletes();
            $table->timestamps();
           
        });
    }

    /**
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
