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
        Schema::create('partner_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('partners');
            $table->text('address1')->nullable();
            $table->text('address2')->nullable();
            $table->text('address3')->nullable();
            $table->string('primary_contact_person')->nullable();
            $table->string('secondary_contact_person')->nullable();
            $table->string('prefix_phone')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('prefix_whatsapp')->nullable();
            $table->string('whatsapp_no')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->tinyInteger('is_default')->default(0)->comment('purpose: every user have one active default locations');
            $table->tinyInteger('ship_address')->default(1);
            $table->tinyInteger('invoice_address')->default(1);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('company_id')->nullable()->constrained('companies');
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_locations');
    }
};
