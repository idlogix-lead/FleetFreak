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
        Schema::table('gl_journal_lines', function (Blueprint $table) {
            $table->foreignId('account_id')->nullable()->change();
            $table->foreignId('gl_journal_id')->constrained('gl_journals');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gl_journal_lines', function (Blueprint $table) {
            //
        });
    }
};
