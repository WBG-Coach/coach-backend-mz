<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDocQuestionnaireIdColumnIntoProjectAndQuestionnaireApplicationsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questionnaire_applications', function (Blueprint $table) {
            $table->integer('doc_questionnaire_id')->unsigned()->nullable();
            $table->foreign('doc_questionnaire_id', 'doc_questionnaire_fk')->references('id')->on('questionnaires');
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->integer('doc_questionnaire_id')->unsigned()->nullable();
            $table->foreign('doc_questionnaire_id', 'dc_questionnaire_fk')->references('id')->on('questionnaires');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questionnaire_applications', function (Blueprint $table) {
            $table->dropForeign('doc_questionnaire_fk');
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign('dc_questionnaire_fk');
        });
        Schema::table('questionnaire_applications', function (Blueprint $table) {
            $table->dropColumn('doc_questionnaire_id');
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('doc_questionnaire_id');
        });
    }
}
