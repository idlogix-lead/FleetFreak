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
        Schema::table('partners', function (Blueprint $table) {
            //
            $table->string('nic_no')->nullable();
            $table->date('nic_expiry_date')->nullable();
            $table->string('license_country')->nullable();
            $table->date('licensee_expiry_date')->nullable();
            $table->string('emergency_contact_no1')->nullable();
            $table->string('emergency_contact_no2')->nullable();
            $table->string('emergency_contact_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            //
        });
    }
};
