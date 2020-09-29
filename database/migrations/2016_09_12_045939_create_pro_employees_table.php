<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pro_employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('alias');
            $table->string('image');
            $table->integer('district_id')->unsigned();
            $table->foreign('district_id')->references('id')->on('pro_districts')->onDelete('cascade');
            $table->string('address');
            $table->string('phone');
            $table->string('mobile');
            $table->date('date_of_birth');
            $table->boolean('marital_status')->default(0);
            $table->bigInteger('designation_id')->unsigned();
            $table->foreign('designation_id')->references('id')->on('pro_designation')->onDelete('cascade');
            $table->date('date_of_join');
            $table->boolean('employement_type')->default(0);
            $table->boolean('citizen_investment_trust')->default(0);
            $table->boolean('providend_fund')->default(0);

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
        Schema::drop('pro_employees');
    }
}
