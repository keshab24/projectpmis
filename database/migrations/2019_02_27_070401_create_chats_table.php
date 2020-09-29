<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pro_chats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('message')->nullable();

            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('pro_projects')->onDelete('cascade')->onUpdate('cascade');

            $table->bigInteger('activity_id')->unsigned()->nullable();
            $table->foreign('activity_id')->references('id')->on('pro_activity_log')->onDelete('NO ACTION')->onUpdate('cascade');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('pro_users')->onDelete('NO ACTION')->onUpdate('cascade');

            $table->boolean('status')->default(0);
            $table->integer('type')->default(0);
            $table->tinyInteger('order')->default(1);
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
        Schema::dropIfExists('pro_chats');
    }
}
