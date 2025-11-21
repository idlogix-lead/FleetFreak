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
        Schema::create('physical_inventory_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('physical_inventory_id')->nullable()->constrained('physical_inventories');
            $table->integer('seq_no')->nullable();
            $table->foreignId('product_id')->nullable()->constrained('products');
            $table->foreignId('locator_id')->nullable()->constrained('locators');
            $table->double('system_qty')->nullable();
            $table->double('physical_qty')->nullable();
            $table->double('adjusted_qty')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('physical_inventory_lines');
    }
};
