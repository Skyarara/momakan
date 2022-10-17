<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Users extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = "InnoDB"; 
            $table->increments('id');
            $table->string('email', 255);
            $table->string('password', 255);
            $table->string('name', 255);
            $table->string('phone_number', 255);
            $table->integer('role_id')->unsigned();
            $table->foreign('role_id')->references('id')->on('role')->onDelete('cascade');
            $table->rememberToken();
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
        Schema::dropIfExists('Users');
    }
}
