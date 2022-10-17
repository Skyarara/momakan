<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Corporate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corporate', function (Blueprint $table) {
            $table->engine = "InnoDB"; 
            $table->increments('id');
            $table->string('name', 255);
            $table->string('address', 255);
            $table->string('telp', 255);
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
        Schema::dropIfExists('Corporate');
    }
}
