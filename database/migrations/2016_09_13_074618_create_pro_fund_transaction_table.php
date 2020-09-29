<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProFundTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pro_fund_transaction', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('expenditure_topic_id')->unsigned();
            $table->foreign('expenditure_topic_id')->references('id')->on('pro_expenditure_topics')->onDelete('cascade');

            $table->integer('fund_store_id')->unsigned();
            $table->foreign('fund_store_id')->references('id')->on('pro_fund_store')->onDelete('cascade');

            $table->string('type'); // current or liability

            //withdraw
            $table->string('cheque_no')->nullable();
            $table->string('cheque_name')->nullable();
            $table->integer('cheque_type')->nullable(); // 0->AC/Payee 1->General

            //deposit
            $table->text('voucher_no')->nullable();
            $table->text('deposited_by')->nullable();

            //if related to implementing office
            $table->integer('implementing_office_id')->unsigned()->nullable();
            $table->foreign('implementing_office_id')->references('id')->on('pro_implementing_offices')->onDelete('cascade');


            $table->float('amount');

            $table->text('image')->nullable();
            $table->text('description')->nullable(); //statement

            $table->timestamps();
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('pro_users')->onDelete('cascade');
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('pro_users')->onDelete('cascade');
            $table->softDeletes();
            $table->integer('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pro_fund_transaction');
    }
}
