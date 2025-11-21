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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->text('description')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('is_summary')->default(0);
            $table->foreignId('company_id')->constrained('companies');
            // $table->enum('account_type', ['Asset', 'Liability', 'Expense', 'Equity', 'Revenue']);
            // $table->enum('account_subtype', ['Long Term Asset', 'Current Asset', 'Long Term Liability', 'Current Liability']);
            $table->foreignId('account_type_id')->constrained('account_types');
            $table->foreignId('account_subtype_id')->constrained('account_types');

            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
