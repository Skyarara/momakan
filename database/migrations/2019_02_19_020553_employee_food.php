<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmployeeFood extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_food', function (Blueprint $table) {
            $table->engine = "InnoDB"; 
            $table->increments('id');
            $table->integer('employee_id')->unsigned();
            $table->integer('food_id')->unsigned();
            $table->string('notes', 255);
            $table->boolean('isActive');
            $table->foreign('employee_id')->references('id')->on('employee')->onDelete('cascade');
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
        Schema::dropIfExists('employee_food');
    }
}
