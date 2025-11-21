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
        Schema::table('material_inouts', function (Blueprint $table) {
            $table->date('date_ordered')->nullable();
            $table->string('rma')->nullable()->comment('Return Merchandise Authorization');
            $table->dropColumn('delivery_time');
        });
        Schema::table('material_inouts', function (Blueprint $table) {
            $table->time('delivery_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_inouts', function (Blueprint $table) {
            $table->dropColumn('date_ordered');
            $table->dropColumn('rma');
            $table->dropColumn('delivery_time');
            

        });
        Schema::table('material_inouts', function (Blueprint $table) {
            $table->date('delivery_time');
        });
    }
};
