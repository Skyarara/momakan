<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Contract extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract', function (Blueprint $table) {
            $table->engine = "InnoDB"; 
            $table->increments('id');
            $table->integer('corporate_id')->unsigned();
            $table->string('contract_code', 10);
            $table->tinyInteger('contract_status');
            $table->integer('budget_max_order');
            $table->date('date_start');
            $table->date('date_end');
            $table->timestamps();
            $table->foreign('corporate_id')->references('id')->on('corporate')->onDelete('cascade');
          
           

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract');
    }
}
