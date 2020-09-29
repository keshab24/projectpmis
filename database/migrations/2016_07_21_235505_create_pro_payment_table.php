<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pro_payment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('payment_method')->nullable();
            $table->float('total_amount')->nullable();
            $table->string('cheque_office')->nullable();
            $table->string('payment_detail')->nullable();
            $table->string('cheque_no')->nullable();
            $table->string('file_path')->nullable(); //             cheque / voucher scanned
            $table->string('cheque_date')->nullable();

            $table->integer('fy_id')->unsigned();
            $table->foreign('fy_id')->references('id')->on('pro_fiscalyears')->onDelete('cascade');
            $table->timestamps();
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('pro_users')->onDelete('cascade');
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('pro_users')->onDelete('cascade');
            $table->softDeletes();
            $table->boolean('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pro_payment');
    }
}
