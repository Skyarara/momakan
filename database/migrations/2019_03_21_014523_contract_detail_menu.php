<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContractDetailMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_detail_menu', function (Blueprint $table) {
            $table->engine = "InnoDB"; 
            $table->increments('id');
            $table->integer('contract_detail_id')->unsigned();
            $table->integer('menu_id')->unsigned();
            $table->foreign('contract_detail_id')->references('id')->on('contract_detail');
            $table->foreign('menu_id')->references('id')->on('menu');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_detail_menu');
    }
}
