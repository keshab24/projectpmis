<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProGroupCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pro_group_category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('name_nep');
            $table->string('description')->nullable();
            $table->string('description_nep')->nullable();
            $table->integer('group_category_id')->unsigned();
            $table->foreign('group_category_id')->references('id')->on('pro_group_category')->onDelete('cascade');
            $table->integer('type');
            $table->integer('level');
            $table->integer('order');
            $table->integer('page_category_order')->unsigned();

            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('pro_users')->onDelete('cascade');
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('pro_users')->onDelete('cascade');
            $table->softDeletes();
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
        Schema::drop('pro_group_category');
    }
}
