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
            $table->double('price_list')->nullable();
            $table->dropForeign(['customer_partner_id']);
            $table->bigInteger('customer_partner_id')->nullable()->change();
            $table->foreign('customer_partner_id')->references('id')->on('partners');     
            $table->string('trip_type')->nullable()->change();
            $table->dropColumn('document_no');
            $table->string('document_status')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('price_list');
        });
    }
};
