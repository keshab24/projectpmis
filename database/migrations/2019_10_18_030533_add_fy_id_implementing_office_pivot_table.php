<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFyIdImplementingOfficePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pro_implementingoffice_pivot', function (Blueprint $table){
            $table->integer('fy_id')->unsigned()->nullable()->default(16);//075/76  (last fy on this system)
            $table->foreign('fy_id')->references('id')->on('pro_fiscalyears')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pro_implementingoffice_pivot', function (Blueprint $table) {
            $table->dropForeign(['fy_id']);
            $table->dropColumn('fy_id');
        });
    }
}
