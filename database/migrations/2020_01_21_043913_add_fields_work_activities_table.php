<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsWorkActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('work_activities', function (Blueprint $table) {
            $table->string('code')->nullable();
            $table->string('unit')->nullable();
            $table->tinyInteger('batch')->default(1);
            $table->dateTime('date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_activities', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->dropColumn('unit');
            $table->dropColumn('batch');
            $table->dropColumn('date');
        });
    }
}
