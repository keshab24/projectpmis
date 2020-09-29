<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pro_incomes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('amount');
            $table->string('depositor_name');
            $table->string('depositor_address');
            $table->string('depositor_contact');

            $table->string('file');

            $table->text('description');

            $table->integer('income_topic_id')->unsigned();
            $table->foreign('income_topic_id')->references('id')->on('pro_income_topics')->onDelete('cascade');

            $table->bigInteger('vendor_id')->unsigned()->nullable();
            $table->foreign('vendor_id')->references('id')->on('pro_vendors')->onDelete('cascade');

            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->foreign('employee_id')->references('id')->on('pro_employees')->onDelete('cascade');

            $table->integer('implementing_office_id')->unsigned()->nullable();
            $table->foreign('implementing_office_id')->references('id')->on('pro_implementing_offices')->onDelete('cascade');

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
        Schema::drop('pro_incomes');
    }
}
