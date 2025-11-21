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
        Schema::create('material_inout_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('m_inout_id')->constrained('material_inouts')->comment('Material Inout ID');
            $table->foreignId('order_detail_line_id')->nullable()->constrained('order_details'); //may be its a input field
            $table->string('line_no')->nullable();
            $table->foreignId('product_id')->nullable()->constrained('products'); //may be its a input field
            $table->foreignId('locator_id')->nullable()->constrained('locators'); //may be its a input field
            $table->text('description')->nullable();
            $table->double('quantity')->nullable();
            $table->double('picked_quantity')->nullable();
            $table->double('target_quantity')->nullable();
            $table->double('confirmed_quantity')->nullable();
            $table->double('scrapped_quantity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_inout_lines');
    }
};
