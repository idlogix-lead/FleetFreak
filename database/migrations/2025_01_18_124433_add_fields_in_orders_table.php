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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->string('document_no')->nullable();
            $table->string('po_reference')->nullable()->comment('Purchase Order Reference');
            $table->foreignId('document_type_id')->nullable()->constrained('invoice_document_types');
            $table->date('date_ordered')->nullable();
            $table->date('date_promised')->nullable();
            $table->foreignId('partner_location_id')->nullable()->constrained('partner_locations'); // for future development
            $table->foreignId('invoice_location_id')->nullable()->constrained('partner_locations');
            $table->foreignId('invoice_partner_id')->nullable()->constrained('partners');

            $table->foreignId('warehouse_id')->nullable()->constrained('ware_houses');
            $table->string('currency')->nullable();
            $table->string('payment_term')->nullable();
            $table->string('document_action')->nullable();



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropForeign(['document_type_id']);
            $table->dropForeign(['warehouse_id']);

            // Drop columns
            $table->dropColumn('client_id');
            $table->dropColumn('document_no');
            $table->dropColumn('po_reference');
            $table->dropColumn('document_type_id');
            $table->dropColumn('date_ordered');
            $table->dropColumn('date_promised');
            $table->dropColumn('warehouse_id');
            $table->dropColumn('currency');
            $table->dropColumn('payment_term');
            $table->dropColumn('document_action');
        });
    }
};
