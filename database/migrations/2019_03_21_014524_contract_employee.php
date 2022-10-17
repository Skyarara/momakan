<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContractEmployee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_employee', function (Blueprint $table) {
            $table->integer('employee_id')->unsigned();
            $table->integer('contract_detail_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('employee');
            $table->foreign('contract_detail_id')->references('id')->on('contract_detail');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_employee');
    }
}
