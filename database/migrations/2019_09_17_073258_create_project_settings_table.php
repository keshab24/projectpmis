<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pro_project_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('pro_projects');
            $table->integer('fy')->unsigned();
            $table->foreign('fy')->references('id')->on('pro_fiscalyears');
            $table->integer('budget_id')->unsigned();
            $table->foreign('budget_id')->references('id')->on('pro_budget_topics');
            $table->integer('expenditure_id')->unsigned();
            $table->foreign('expenditure_id')->references('id')->on('pro_expenditure_topics');
            $table->integer('implementing_id')->unsigned();
            $table->foreign('implementing_id')->references('id')->on('pro_implementing_offices');
            $table->integer('monitoring_id')->unsigned();
            $table->foreign('monitoring_id')->references('id')->on('pro_implementing_offices');

            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('pro_users')->onDelete('cascade');
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('pro_users')->onDelete('cascade');
            $table->softDeletes();
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
        Schema::dropIfExists('pro_project_settings');
    }
}
