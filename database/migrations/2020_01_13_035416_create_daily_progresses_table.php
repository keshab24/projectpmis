<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyProgressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_progresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('project_id');
            $table->foreign('project_id')->references('id')->on('pro_projects')->onDelete('cascade');
            $table->date('date');
            $table->longText('manpower')->nullable();
            $table->longText('equipments')->nullable();
            $table->longText('materials')->nullable();
            $table->text('weather')->nullable();
            $table->text('temperature')->nullable();
            $table->longText('problems')->nullable();
            $table->longText('activities')->nullable();
            
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('pro_users')->onDelete('cascade');
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('pro_users')->onDelete('cascade');
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('daily_progresses');
    }
}
