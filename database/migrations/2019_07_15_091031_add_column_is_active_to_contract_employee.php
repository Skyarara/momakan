<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIsActiveToContractEmployee extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('contract_employee', function (Blueprint $table) {
            $table->boolean('isActive')->after('contract_detail_id')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    { }
}
