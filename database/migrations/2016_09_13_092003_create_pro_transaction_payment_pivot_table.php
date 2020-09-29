<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProTransactionPaymentPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pro_transaction_payment_pivot', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('payment_id')->unsigned();
            $table->foreign('payment_id')->references('id')->on('pro_payment')->onDelete('cascade');

            $table->bigInteger('transaction_id')->unsigned();
            $table->foreign('transaction_id')->references('id')->on('pro_fund_transaction')->onDelete('cascade');


            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pro_transaction_payment_pivot');
    }
}
