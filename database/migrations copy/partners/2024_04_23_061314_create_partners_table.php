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
            $table->string('name');
            $table->enum('partner_type', ['driver', 'management', 'office_staff']); //added
            $table->enum('partner_type', ['customer', 'employee', 'business']);
            $table->foreignId('actor_id')->nullable()->constrained('actors');//added
            $table->string('email')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('whatsapp_no')->nullable();
            $table->string('cnic');
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('address3')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('business_partner_id')->nullable()->constrained('partners');
            $table->string('passport');
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
