<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuestionnaireQuestionIdColumnToFeedbackAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feedback_answers', function (Blueprint $table) {
            $table->integer('questionnaire_question_id')->unsigned()->nullable();
            $table->foreign('questionnaire_question_id', 'questionnaire_question_fk')->references('id')->on('questionnaire_questions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feedback_answers', function (Blueprint $table) {
            $table->dropForeign('questionnaire_question_fk');
        });
        Schema::table('feedback_answers', function (Blueprint $table) {
            $table->dropColumn('questionnaire_question_id');
        });
    }
}
