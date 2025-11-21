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
        Schema::table('material_inout_lines', function (Blueprint $table) {
            $table->double('movement_qty')->nullable();
            $table->renameColumn('picked_quantity', 'picked_qty');
            $table->renameColumn('target_quantity', 'target_qty');
            $table->renameColumn('confirmed_quantity', 'confirmed_qty');
            $table->renameColumn('scrapped_quantity', 'scrapped_qty');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_inout_lines', function (Blueprint $table) {
            $table->dropColumn('movement_qty');
            $table->renameColumn('picked_qty', 'picked_quantity');
            $table->renameColumn('target_qty','target_quantity');
            $table->renameColumn('confirmed_qty','confirmed_quantity');
            $table->renameColumn('scrapped_qty','scrapped_quantity');

        });
    }
};
