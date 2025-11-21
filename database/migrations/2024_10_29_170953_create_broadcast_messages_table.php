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
        Schema::create('broadcast_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->string('title')->nullable();
            $table->text('message')->nullable();
            $table->text('description')->nullable();
            $table->enum('broadcast_type',['immediate'])->default('immediate');
            $table->enum('broadcast_frequency',['just_once','until_acknowledge','until_expiration','until_expiration_acknowledge'])->nullable();
            $table->date('expired_date')->nullable();
            $table->tinyInteger('expired')->default(false);
            $table->tinyInteger('published')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('broadcast_messages');
    }
};
