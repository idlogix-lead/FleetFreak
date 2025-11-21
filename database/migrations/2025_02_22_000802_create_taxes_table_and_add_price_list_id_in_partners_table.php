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
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->foreignId('company_id')->constrained('companies');
            $table->string('name');
            $table->double('rate');
            $table->text('description')->nullable();
            $table->tinyInteger('is_default')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->date('valid_from')->nullable();
            $table->string('type')->default('fixed');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });
        Schema::table('partners', function (Blueprint $table) {
            $table->foreignId('price_list_id')->nullable()->constrained('price_lists');
        });
        Schema::table('order_lines', function (Blueprint $table) {
            $table->integer('seq_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxes');

        Schema::table('partners', function (Blueprint $table) {
            $table->dropForeign(['price_list_id']);
            $table->dropColumn('price_list_id');
        });
        Schema::table('order_lines', function (Blueprint $table) {
            $table->dropColumn('seq_no');
        });
    }
};
