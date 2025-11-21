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
        Schema::table('unit_measures', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->string('UNCEFACT_code')->nullable()->comment('code for units of measure used in international trade');
            $table->string('uom_code')->nullable()->comment('Unit of Measure Code');
            $table->string('uom_type')->nullable();
            $table->string('symbol')->nullable();
            $table->tinyInteger('is_active')->default(0);
            $table->tinyInteger('is_default')->default(0);
            $table->string('std_precision')->nullable()->comment('Standard Precision');
            $table->string('cost_precision')->nullable()->comment('Costing Precision');

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unit_measures', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
    
            // Drop other columns
            $table->dropColumn('UNCEFACT_code');
            $table->dropColumn('uom_code');
            $table->dropColumn('uom_type');
            $table->dropColumn('symbol');
            $table->dropColumn('is_active');
            $table->dropColumn('is_default');
            $table->dropColumn('std_precision');
            $table->dropColumn('cost_precision');
        });
    }
};
