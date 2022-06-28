<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveReviewQuestionnaireColumnFromQuestionnaireApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questionnaire_applications', function (Blueprint $table) {
            $table->dropForeign('review_questionnaire_fk');
        });
        Schema::table('questionnaire_applications', function (Blueprint $table) {
            $table->dropColumn('review_questionnaire_id');
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
            $table->integer('review_questionnaire_id')->unsigned()->nullable();
            $table->foreign('review_questionnaire_id', 'review_questionnaire_fk')->references('id')->on('questionnaires');
        });
    }
}
