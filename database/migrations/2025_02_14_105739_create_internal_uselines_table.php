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
        Schema::create('internal_uselines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('inventory_consumption_id')->nullable()->constrained('inventory_consumptions');
            $table->integer('seq_no')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('product_id')->nullable()->constrained('products');
            $table->foreignId('account_id')->nullable()->constrained('accounts');
            $table->double('movement_qty')->nullable();
            $table->foreignId('locator_id')->nullable()->constrained('locators');
            $table->tinyInteger('is_active')->default(1);
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
        Schema::dropIfExists('internal_uselines');
    }
};
