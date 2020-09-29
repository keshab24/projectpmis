<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMyadThapDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('myad_thap_documents', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('project_id');
            $table->foreign('project_id')->references('id')->on('pro_projects');

            $table->unsignedBigInteger('apug_kagajat_id');
            $table->foreign('apug_kagajat_id')->references('id')->on('apug_kagajats');

            $table->string('remarks')->nullable();

            $table->integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('pro_users');

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
        Schema::dropIfExists('myad_thap_documents');
    }
}
