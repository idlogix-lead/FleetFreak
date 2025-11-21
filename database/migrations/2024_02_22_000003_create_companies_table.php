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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // $table->string('email')->nullable();
            $table->text('description');
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('address3')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            // $table->foreignId('created_by')->constrained('users');
            // $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
        // DB::statement("ALTER SEQUENCE companies_id_seq RESTART WITH 201;");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
