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
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();
            $table->integer('seq_no')->nullable();
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->foreignId('company_id')->nullable()->constrained('companies');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('product_id')->nullable()->constrained('products');
            $table->foreignId('price_list_version_id')->nullable()->constrained('price_list_versions');
            $table->double('list_price')->nullable();
            $table->double('standard_price')->nullable();
            $table->double('limit_price')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('is_default');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_prices');
    }
};
