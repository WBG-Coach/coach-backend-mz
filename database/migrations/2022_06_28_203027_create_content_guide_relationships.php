<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentGuideRelationships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('options', function (Blueprint $table) {
            $table->integer('content_guide_id')->unsigned()->nullable();
            $table->foreign('content_guide_id')->references('id')->on('content_guides');
        });
        Schema::table('competencies', function (Blueprint $table) {
            $table->integer('content_guide_id')->unsigned()->nullable();
            $table->foreign('content_guide_id')->references('id')->on('content_guides');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('options', function (Blueprint $table) {
            $table->dropForeign('options_content_guide_id_foreign');
        });
        Schema::table('competencies', function (Blueprint $table) {
            $table->dropForeign('competencies_content_guide_id_foreign');
        });
        Schema::table('options', function (Blueprint $table) {
            $table->dropColumn('content_guide_id');
        });
        Schema::table('competencies', function (Blueprint $table) {
            $table->dropColumn('content_guide_id');
        });
    }
}
