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
        Schema::create('m_match_po', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->foreignId('company_id')->constrained('companies');
            $table->text('description')->nullable();
            $table->foreignId('product_id')->nullable()->constrained('products');
            $table->foreignId('po_line_id')->nullable()->constrained('order_lines');
            $table->foreignId('material_inout_line_id')->nullable()->constrained('material_inout_lines');
            $table->foreignId('pi_line_id')->nullable()->constrained('invoice_lines');
            $table->foreignId('document_type_id')->nullable()->constrained('invoice_document_types');
            $table->double('quantity')->nullable();
            $table->date('transaction_date')->nullable();
            $table->date('account_date')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_match_po');
    }
};
