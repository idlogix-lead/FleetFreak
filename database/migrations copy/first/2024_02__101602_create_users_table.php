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
            $table->enum('type',['customer','service_provider','admin']);
            $table->foreignId('actor_id')->nullable()->constrained('actors');
            $table->foreignId('customer_id')->nullable()->constrained('customers');
            $table->foreignId('service_provider_id')->nullable()->constrained('service_providers');
            $table->string('name');
            $table->string('email')->unique();
            $table->foreignId('role_id')->nullable()->constrained('roles');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            // $table->string('role');
            $table->rememberToken();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
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
