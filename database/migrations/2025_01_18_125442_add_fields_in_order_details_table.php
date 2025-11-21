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
        Schema::table('order_details', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->foreignId('product_id')->nullable()->constrained('products')->comment('Product ID');
            $table->date('date_promised')->nullable();
            $table->date('date_ordered')->nullable();
            $table->double('quantity')->nullable();
            $table->double('order_qty')->nullable()->comment('ordered quantity');
            $table->double('delivered_qty')->nullable()->comment('delivered quantity');
            $table->double('reserved_qty')->nullable()->comment('quantity ordered on purchase orders');
            $table->double('invoiced_qty')->nullable()->comment('invoiced quantity');
            $table->double('unit_price')->nullable();
            $table->double('list_price')->nullable();
            $table->double('tax')->nullable();
            $table->double('discount')->nullable();
            $table->double('tax_value')->nullable();
            $table->double('line_amount')->nullable();
            $table->double('total_line_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['client_id']);
            $table->dropForeign(['product_id']);

            // Drop columns
            $table->dropColumn('client_id');
            $table->dropColumn('product_id');
            $table->dropColumn('date_promised');
            $table->dropColumn('date_ordered');
            $table->dropColumn('quantity');
            $table->dropColumn('order_qty');
            $table->dropColumn('delivered_qty');
            $table->dropColumn('reserved_qty');
            $table->dropColumn('invoiced_qty');
            $table->dropColumn('unit_price');
            $table->dropColumn('list_price');
            $table->dropColumn('tax');
            $table->dropColumn('discount');
            $table->dropColumn('tax_value');
            $table->dropColumn('line_amount');
            $table->dropColumn('total_line_amount');
        });
    }
};
