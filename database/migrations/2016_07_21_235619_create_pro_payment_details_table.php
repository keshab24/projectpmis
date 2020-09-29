<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProPaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pro_payment_details', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('payment_id')->unsigned();
            $table->foreign('payment_id')->references('id')->on('pro_payment')->onDelete('cascade');

            $table->bigInteger('release_id')->unsigned();
            $table->foreign('release_id')->references('id')->on('pro_release')->onDelete('cascade');
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
        Schema::drop('pro_payment_details');
    }
}
