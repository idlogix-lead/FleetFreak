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
        Schema::create('sidebar_item_grouplines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('sidebar_item_groups');
            $table->foreignId('sidebar_item_id')->constrained('sidebar_items');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sidebar_item_grouplines');
    }
};
