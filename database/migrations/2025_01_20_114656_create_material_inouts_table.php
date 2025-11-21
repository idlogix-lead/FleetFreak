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
        Schema::create('material_inouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('order_id')->constrained('orders')->comment('Order ID');
            $table->string('document_no');
            $table->string('po_reference')->nullable()->comment('Purchase Order Reference');
            $table->text('description')->nullable();
            $table->string('document_type')->nullable();
            $table->date('movement_date');
            $table->date('accounting_date');
            $table->foreignId('business_partner_id')->nullable()->constrained('partners');
            $table->foreignId('partner_location_id')->nullable()->constrained('partner_locations');
            $table->foreignId('user_id')->nullable()->constrained('users'); //may be its a foreign field
            $table->foreignId('warehouse_id')->constrained('ware_houses');
            $table->string('delivery_man')->nullable();
            $table->string('delivery_vehicle')->nullable();
            $table->integer('delivery_no')->nullable();
            $table->date('delivery_time')->nullable();
            $table->string('gate_inout')->nullable();
            $table->string('create_lines_from')->nullable();
            $table->string('c_l_from_gatepass')->nullable()->comment('Create Lines From Gatepass');
            $table->string('document_action')->nullable();
            $table->string('document_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_inouts');
    }
};
