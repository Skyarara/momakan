<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnInEmployeeFood extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_food', function (Blueprint $table) {
            $table->dropForeign(['food_id']);
            $table->dropColumn(['food_id', 'isActive']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_food', function (Blueprint $table) {
            //
        });
    }
}
