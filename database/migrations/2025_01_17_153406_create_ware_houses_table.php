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
        Schema::create('ware_houses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->foreignId('company_id')->constrained('companies');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('code')->nullable();
            $table->tinyInteger('is_active')->default(0);
            $table->tinyInteger('in_transit')->default(0);
            $table->text('address')->nullable();
            $table->foreignId('source_warehouse_id')->nullable()->constrained('ware_houses');
            $table->tinyInteger('is_disallow_negative_inv')->default(0)->comment('Is Disallow Negative Inventory');
            // $table->foreignId('locator_id')->nullable()->constrained('locators');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ware_houses');
    }
};
