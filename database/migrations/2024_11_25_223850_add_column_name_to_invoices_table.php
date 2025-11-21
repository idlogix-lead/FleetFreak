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
        Schema::table('invoices', function (Blueprint $table) {
            //
             $table->enum('document_status', ['draft', 'pending','completed']);

        });
    }

    /**
     * Reverse the migrations.
     */
    // public function down(): void
    // {
    //     Schema::table('invoices', function (Blueprint $table) {
    //         //
    //     });
    // }
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
