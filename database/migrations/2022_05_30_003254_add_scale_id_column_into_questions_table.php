<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddScaleIdColumnIntoQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->integer('scale_id')->unsigned()->nullable();
            $table->foreign('scale_id')->references('id')->on('scales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign('questions_scale_id_foreign');
        });
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('scale_id');
        });
    }
}
