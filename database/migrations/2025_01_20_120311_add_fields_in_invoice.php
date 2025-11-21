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
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->foreignId('order_id')->nullable()->constrained('orders')->comment('Order ID');
            $table->date('date_ordered')->nullable()->comment('Date Order');
            $table->tinyInteger('is_active')->default(0)->comment('Is Active');
            $table->date('date_invoiced')->nullable();
            $table->date('account_date')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users'); //may be its a foreign field
            $table->foreignId('partner_location_id')->nullable()->constrained('partner_locations');
            $table->string('price_list')->nullable();
            $table->string('currency')->nullable();
            $table->string('company_agent')->nullable();
            $table->tinyInteger('discount_printed')->default(0);
            $table->string('payment_rule')->nullable();
            $table->string('payment_term')->nullable();
            $table->tinyInteger('is_pay_schedule_valid')->default(0);
            $table->string('document_action')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropForeign(['order_id']);
    
            // Drop columns
            $table->dropColumn('client_id');
            $table->dropColumn('order_id');
            $table->dropColumn('date_ordered');
            $table->dropColumn('is_active');
            $table->dropColumn('date_invoiced');
            $table->dropColumn('account_date');
            $table->dropColumn('partner_location');
            $table->dropColumn('price_list');
            $table->dropColumn('currency');
            $table->dropColumn('company_agent');
            $table->dropColumn('discount_printed');
            $table->dropColumn('payment_rule');
            $table->dropColumn('payment_term');
            $table->dropColumn('is_pay_schedule_valid');
            $table->dropColumn('document_action');
        });
    }
};
