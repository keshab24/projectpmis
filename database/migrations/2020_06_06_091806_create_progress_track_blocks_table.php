<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgressTrackBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('progress_track_blocks', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('storey_area');
            $table->string('slug')->unique();
            $table->string('progress');
            $table->string('progress_eng');
            $table->string('physical_percentage');
            $table->integer('progress_type')->unsigned();
            $table->foreign('progress_type')->references('id')->on('pro_construction_types');
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('pro_projects');
            $table->unsignedBigInteger('block_id')->unsigned();
            $table->foreign('block_id')->references('id')->on('project_blocks');

            $table->integer('monitoring_office_id');

            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('progress_track_blocks');
    }
}
