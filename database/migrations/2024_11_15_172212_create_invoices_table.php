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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            $table->string('document_no');

            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('vehicle_id')->constrained('vehicles');
            $table->foreignId('business_partner_id')->constrained('partners');

            $table->date('date');
            $table->text('description')->nullable();

            $table->double('total_amount');
            $table->double('grand_total_amount');

            $table->enum('document_status', ['draft', 'completed']);
            $table->enum('document_type', ['maintenance', 'toll_tax', 'entertainment', 'fuel']);


            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
