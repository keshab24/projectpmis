<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProIncomeTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pro_income_topics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('name_eng');
            $table->string('code');
            $table->integer('order');
            $table->integer('level');

            $table->integer('parent_id')->unsigned();
            $table->foreign('parent_id')->references('id')->on('pro_income_topics')->onDelete('cascade');

            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('pro_users')->onDelete('cascade');
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('pro_users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();



            $table->boolean('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pro_income_topics');
    }
}
