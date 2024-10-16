<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->datetime('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('remember_token')->nullable();
            $table->string('known_as')->nullable();
            $table->string('gender')->nullable();
            $table->string('mobile_no')->nullable();
            $table->longText('address')->nullable();
            $table->string('client_code')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
