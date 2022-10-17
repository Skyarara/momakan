<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtraEmployeeFood extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extra_employee_food', function (Blueprint $table) {
            $table->engine = "InnoDB"; 
            $table->increments('id');
            $table->integer('employee_food_id')->unsigned();
            $table->integer('food_id')->unsigned();
            $table->string('notes', 255);
            $table->integer('qty');
            $table->foreign('employee_food_id')->references('id')->on('employee_food')->onDelete('cascade');
            $table->foreign('food_id')->references('id')->on('food')->onDelete('cascade');
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
        Schema::dropIfExists('extra_employee_food');
    }
}
