<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RenameOrderDetailsToOrderLines extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('order_details', 'order_lines');
        // Use DB statement to rename the sequence and set ownership
        DB::statement("ALTER SEQUENCE order_details_id_seq RENAME TO order_lines_id_seq");
        DB::statement("ALTER SEQUENCE order_lines_id_seq OWNED BY order_lines.id");
        DB::statement("ALTER TABLE order_lines ALTER COLUMN id SET DEFAULT nextval('order_lines_id_seq')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('order_lines', 'order_details');
    }
};
