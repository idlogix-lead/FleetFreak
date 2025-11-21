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
        Schema::create('account_transactions', function (Blueprint $table) {
            $table->id();
            
            $table->date('transaction_date');
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('account_id')->constrained('accounts');

            $table->decimal('quantity', 10, 2)->default(0);
            $table->decimal('debit', 10, 2)->default(0);
            $table->decimal('credit', 10, 2)->default(0);
            $table->foreignId('currency_id')->constrained('currencies');

            $table->unsignedBigInteger('record_id')->nullable()->comment('parent table record id');
            $table->unsignedBigInteger('line_id')->nullable()->comment('child and main table record id');
            $table->foreignId('table_id')->constrained('accounts');

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
        Schema::dropIfExists('account_transactions');
    }
};
