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
        Schema::create('gl_journal_lines', function (Blueprint $table) {
            $table->id();

            $table->foreignId('account_id')->constrained('accounts');

            $table->bigInteger('quantity')->default(0);
            $table->bigInteger('debit')->default(0);
            $table->bigInteger('credit')->default(0);

            $table->text('description')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gl_journal_lines');
    }
};
