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
            $table->integer('seq_no')->nullable();
            $table->double('total_line_amount')->nullable();
            $table->foreignId('material_inout_line_id')->nullable()->constrained('material_inout_lines');

            
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('material_inout_id')->nullable()->constrained('material_inouts');
            $table->dropColumn('price_list');

            
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('price_list_id')->nullable()->constrained('price_lists');

            
        });
        Schema::table('material_inout_lines', function (Blueprint $table) {
            $table->string('status')->nullable();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_lines', function (Blueprint $table) {
            $table->dropColumn('seq_no');
            $table->dropColumn('total_line_amount');
            $table->dropForeign(['material_inout_line_id']);
            $table->dropColumn('material_inout_line_id');
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['material_inout_id']);
            $table->dropColumn('material_inout_id');
            $table->string('price_list');

            
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['price_list_id']);
            $table->dropColumn('price_list_id');

            
        });
        Schema::table('material_inout_lines', function (Blueprint $table) {
            $table->dropColumn('status');

            
        });
    }
};
