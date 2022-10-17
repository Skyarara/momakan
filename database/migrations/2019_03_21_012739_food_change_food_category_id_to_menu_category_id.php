<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FoodChangeFoodCategoryIdToMenuCategoryId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('food', function (Blueprint $table) {
            $table->dropForeign(['food_category_id']);
            $table->dropColumn('food_category_id');

            $table->integer('menu_category_id')->unsigned();
            $table->foreign('menu_category_id')
            ->references('id')
            ->on('menu_category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_category_id', function (Blueprint $table) {
            //
        });
    }
}
