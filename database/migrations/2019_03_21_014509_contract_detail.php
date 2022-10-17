<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContractDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_detail', function (Blueprint $table) {
            $table->engine = "InnoDB"; 
            $table->increments('id');
            $table->integer('contract_id')->unsigned();
            $table->integer('budget');
            $table->string('contract_detail_name');
            $table->foreign('contract_id')->references('id')->on('contract');
        });
            
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_detail');
    }
}
