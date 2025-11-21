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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable();
            $table->text('detail')->nullable();
            $table->foreignId('receiver_partner_id')->nullable()->constrained('partners');
            $table->foreignId('receiver_id')->nullable()->constrained('users');
            $table->tinyInteger('is_read')->default(0);
            $table->foreignId('sender_id')->nullable()->constrained('users');
            $table->bigInteger('source_id')->nullable();
            $table->tinyInteger('whatsapp')->default(0);
            $table->enum('status',['sent','unsent'])->nullable();
            $table->enum('sent_type',['direct','scheduled'])->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->tinyInteger('email')->default(0);
            $table->tinyInteger('sms')->default(0);
            $table->tinyInteger('fcm_web_push')->default(0);
            $table->tinyInteger('fcm_mobile_push')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
