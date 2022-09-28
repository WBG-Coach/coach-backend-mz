<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuestionnaireIdColumnIntoAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('answers', function (Blueprint $table) {
            $table->integer('questionnaire_id')->unsigned()->nullable();
            $table->foreign('questionnaire_id')->references('id')->on('questionnaires');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('answers', function (Blueprint $table) {
            $table->dropForeign('answers_questionnaire_id_foreign');
        });
        Schema::table('answers', function (Blueprint $table) {
            $table->dropColumn('questionnaire_id');
        });
    }
}
