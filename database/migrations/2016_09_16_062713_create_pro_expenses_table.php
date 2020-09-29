<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pro_expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('amount');
            $table->string('receiver_name');
            $table->string('receiver_address');
            $table->string('receiver_contact');

            $table->string('file');

            $table->text('description');

            $table->bigInteger('vendor_id')->unsigned()->nullable();
            $table->foreign('vendor_id')->references('id')->on('pro_vendors')->onDelete('cascade');

            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->foreign('employee_id')->references('id')->on('pro_employees')->onDelete('cascade');

            $table->integer('expenditure_topic_id')->unsigned();
            $table->foreign('expenditure_topic_id')->references('id')->on('pro_expenditure_topics')->onDelete('cascade');

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
        Schema::drop('pro_expenses');
    }
}
