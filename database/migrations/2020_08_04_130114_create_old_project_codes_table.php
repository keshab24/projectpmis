<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOldProjectCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('old_project_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('old_project_code');
            
            $table->integer('project_id')->unsigned()->nullable();
            $table->foreign('project_id')->references('id')->on('pro_projects');
            
            $table->integer('fy_id')->unsigned();
            $table->foreign('fy_id')->references('id')->on('pro_fiscalyears');

            $table->integer('updated_by')->unsigned()->nullable();
            $table->foreign('updated_by')->references('id')->on('pro_users');

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
        Schema::dropIfExists('old_project_codes');
    }
}
