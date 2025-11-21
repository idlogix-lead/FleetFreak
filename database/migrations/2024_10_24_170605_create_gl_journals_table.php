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
        Schema::create('gl_journals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')->constrained('companies');

            $table->date('transaction_date')->nullable();
            $table->bigInteger('debit')->comment('lines total amount')->default(0);
            $table->bigInteger('credit')->comment('lines total amount')->default(0);

            $table->text('description')->nullable();
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
        Schema::dropIfExists('gl_journals');
    }
};
