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
        Schema::create('sidebar_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('icon');
            $table->integer('position')->default(0);
            $table->foreignId('sidebar_group_id')->nullable()->constrained('sidebar_groups');
            $table->softDeletes();
            $table->timestamps();
        });
        // DB::statement("ALTER SEQUENCE sidebar_groups_id_seq RESTART WITH 201;");

        Schema::table('sidebar_items', function (Blueprint $table) {
            $table->foreignId('sidebar_group_id')->nullable()->constrained('sidebar_groups');
            $table->integer('position')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sidebar_groups');
    }
};
