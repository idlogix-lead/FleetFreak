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
        Schema::create('physical_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->foreignId('company_id')->constrained('companies');
            $table->string('document_no');
            $table->foreignId('document_type_id')->nullable()->constrained('invoice_document_types');
            $table->text('description')->nullable();
            $table->date('inventory_date')->nullable();
            $table->foreignId('warehouse_id')->nullable()->constrained('ware_houses');
            $table->string('document_action')->nullable();
            $table->string('document_status')->nullable();
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
        Schema::dropIfExists('physical_inventories');
    }
};
