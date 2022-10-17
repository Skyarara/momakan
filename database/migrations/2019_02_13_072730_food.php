<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Food extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id');
            $table->integer('food_category_id')->unsigned();
            $table->integer('vendor_id')->unsigned();
            $table->foreign('food_category_id')->references('id')->on('food_category');
            $table->foreign('vendor_id')->references('id')->on('vendor');
            $table->string('name', 255);
            $table->string('description', 255);
            $table->BigInteger('price');
            $table->string('image', 255);
            $table->tinyInteger('isPackage');
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
        Schema::dropIfExists('food');
    }
}
