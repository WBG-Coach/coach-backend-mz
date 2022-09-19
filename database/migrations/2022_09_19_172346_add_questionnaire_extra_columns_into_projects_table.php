<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuestionnaireExtraColumnsIntoProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->integer('observation_questionnaire_id')->unsigned()->nullable();
            $table->foreign('observation_questionnaire_id', 'observation_questionnaire_fk')->references('id')->on('questionnaires');
            $table->integer('feedback_questionnaire_id')->unsigned()->nullable();
            $table->foreign('feedback_questionnaire_id', 'feedback_fk')->references('id')->on('questionnaires');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign('observation_questionnaire_fk');
            $table->dropForeign('feedback_questionnaire_fk');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('observation_questionnaire_id');
            $table->dropColumn('feedback_questionnaire_id');
        });
    }
}
