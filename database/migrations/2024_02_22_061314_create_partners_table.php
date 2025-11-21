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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->enum('employee_type',['driver','management','office_staff'])->nullable();
            // $table->enum('partner_type', ['driver', 'management', 'office_staff']); //added
            $table->enum('partner_type', ['customer', 'employee', 'business','walkin_customer'])->default('employee');
            $table->foreignId('actor_id')->nullable()->constrained('actors');//added
            $table->foreignId('business_partner_id')->nullable()->constrained('partners');
            $table->foreignId('company_id')->nullable()->constrained('companies');
            $table->string('email')->nullable();
            $table->string('passport')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('whatsapp_no')->nullable();
            $table->string('prefix_phone')->nullable();
            $table->string('prefix_whatsapp')->nullable();
            $table->string('cnic')->nullable();
            $table->string('age')->nullable();
            $table->string('experience')->nullable();
            $table->string('akama')->nullable();
            $table->string('company_name')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('address3')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('iata_no')->nullable();
            $table->string('govt_license_no')->nullable();
            $table->string('source')->nullable();
            $table->tinyInteger('is_system')->default('0');
            $table->string('permission_status')->nullable();
            $table->string('pass_key')->nullable();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
