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
        Schema::table('invoice_lines', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->foreignId('company_id')->nullable()->constrained('companies');
            $table->foreignId('order_detail_id')->nullable()->constrained('order_details');
            $table->foreignId('material_inout_line_id')->nullable()->constrained('material_inout_lines');
            $table->string('product')->nullable(); //may be its a foreign field
            $table->foreignId('uom_id')->nullable()->constrained('unit_measures')->comment('Unit of measure');
            $table->double('quantity_invoiced')->nullable();
            $table->double('unit_rate')->nullable();
            $table->double('list_rate')->nullable();
            $table->double('tax')->nullable();
            $table->double('tax_amount')->nullable();
            $table->string('project')->nullable(); //may be its a foreign field
            $table->string('production_process')->nullable(); //may be its a foreign field
            $table->string('campaign')->nullable(); //may be its a foreign field

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_lines', function (Blueprint $table) {
             // Drop foreign key constraints
            $table->dropForeign(['client_id']);
            $table->dropForeign(['company_id']);
            $table->dropForeign(['order_detail_id']);
            $table->dropForeign(['material_inout_line_id']);
            $table->dropForeign(['uom_id']);

            // Drop columns
            $table->dropColumn('client_id');
            $table->dropColumn('company_id');
            $table->dropColumn('order_detail_id');
            $table->dropColumn('material_inout_line_id');
            $table->dropColumn('product');
            $table->dropColumn('uom_id');
            $table->dropColumn('quantity_invoiced');
            $table->dropColumn('unit_rate');
            $table->dropColumn('list_rate');
            $table->dropColumn('tax');
            $table->dropColumn('tax_amount');
            $table->dropColumn('project');
            $table->dropColumn('production_process');
            $table->dropColumn('campaign');
        });
    }
};
