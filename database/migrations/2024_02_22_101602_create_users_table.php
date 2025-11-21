<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->foreignId('actor_id')->nullable()->constrained('actors');
            $table->foreignId('role_id')->nullable()->constrained('roles');
            $table->foreignId('partner_id')->nullable()->constrained('partners');
            $table->enum('user_type',['administrator','driver','agent'])->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->string('phone_no1')->nullable();
            $table->string('phone_no2')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->enum('theme',['light-theme', 'dark-theme', 'semi-dark'])->default('light-theme');
            $table->tinyInteger('flag')->default(true);
            $table->tinyInteger('permission')->default(true);
            $table->string('sidebar_color')->nullable();
            $table->string('header_color')->nullable();
            $table->string('otp')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->string('firebase_token')->nullable();
            $table->string('firebase_web_token')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
