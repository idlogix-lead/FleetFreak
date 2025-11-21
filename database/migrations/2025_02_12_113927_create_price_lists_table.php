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
        Schema::create('price_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->foreignId('company_id')->nullable()->constrained('companies');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('currency')->nullable();
            $table->double('price_precision')->nullable();
            $table->tinyInteger('sales_price_list')->default(0);
            $table->tinyInteger('price_includes_tax')->default(0);
            $table->tinyInteger('enforce_price_limit')->default(0);
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
        Schema::dropIfExists('price_lists');
    }
};
