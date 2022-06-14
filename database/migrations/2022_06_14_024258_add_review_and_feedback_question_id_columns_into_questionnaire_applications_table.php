<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReviewAndFeedbackQuestionIdColumnsIntoQuestionnaireApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questionnaire_applications', function (Blueprint $table) {
            $table->integer('feedback_questionnaire_id')->unsigned()->nullable();
            $table->foreign('feedback_questionnaire_id', 'feedback_questionnaire_fk')->references('id')->on('questionnaires');
            $table->integer('review_questionnaire_id')->unsigned()->nullable();
            $table->foreign('review_questionnaire_id', 'review_questionnaire_fk')->references('id')->on('questionnaires');
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
            $table->dropForeign('feedback_questionnaire_fk');
            $table->dropForeign('review_questionnaire_fk');
        });
        Schema::table('questionnaire_applications', function (Blueprint $table) {
            $table->dropColumn('feedback_questionnaire_id');
            $table->dropColumn('review_questionnaire_id');
        });
    }
}
