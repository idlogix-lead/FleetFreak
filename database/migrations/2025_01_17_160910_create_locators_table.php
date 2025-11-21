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
        Schema::create('locators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('warehouse_id')->constrained('ware_houses');
            $table->string('code');
            $table->string('locator_type')->nullable();
            $table->tinyInteger('is_active')->default(0);
            $table->tinyInteger('is_default')->default(0);
            $table->string('relative_priority')->nullable();
            $table->double('aisle')->nullable();
            $table->double('bin')->nullable();
            $table->double('level')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locators');
    }
};
