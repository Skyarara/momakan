<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Payment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('pembayaran');

        Schema::create('payment', function (Blueprint $table) {
            $table->increments('id');
            // $table->integer('invoice_id')->unsigned();
            // $table->foreign('invoice_id')->references('id')->on('invoice');
            $table->string('payment_Number');
            $table->date('date');
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
        //
    }
}
