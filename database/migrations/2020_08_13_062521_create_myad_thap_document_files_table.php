<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMyadThapDocumentFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('myad_thap_document_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('myad_thap_document_id');
            $table->foreign('myad_thap_document_id')->references('id')->on('myad_thap_documents');
            $table->string('file');
            $table->string('date')->nullable();
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
        Schema::dropIfExists('myad_thap_document_files');
    }
}
