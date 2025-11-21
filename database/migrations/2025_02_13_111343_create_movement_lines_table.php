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
        Schema::create('movement_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->foreignId('company_id')->constrained('companies');
            $table->integer('seq_no')->nullable();
            $table->foreignId('inventory_move_id')->nullable()->constrained('inventory_moves');
            $table->foreignId('product_id')->nullable()->constrained('products');
            $table->double('movement_qty')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('locator_from')->nullable()->constrained('locators');
            $table->foreignId('locator_to')->nullable()->constrained('locators');
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
        Schema::dropIfExists('movement_lines');
    }
};
