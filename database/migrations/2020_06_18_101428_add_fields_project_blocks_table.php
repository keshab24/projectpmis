<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsProjectBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_blocks', function (Blueprint $table) {
            $table->string('structure_type')->nullable();
            $table->string('story_area_unite')->nullable();
            $table->string('plinth_area')->nullable();
            $table->string('floor_area')->nullable();
            $table->string('roof_type')->nullable();
            $table->string('door_window')->nullable();
            $table->string('wall_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_blocks', function (Blueprint $table) {
            //
        });
    }
}
