<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('questionnaire_application_id')->unsigned();
            $table->foreign('questionnaire_application_id')->references('id')->on('questionnaire_applications');
            $table->integer('questionnaire_question_id')->unsigned();
            $table->foreign('questionnaire_question_id')->references('id')->on('questionnaire_questions');
            $table->integer('scale_id')->unsigned();
            $table->foreign('scale_id')->references('id')->on('scales');
            $table->integer('value', false, true)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answers');
    }
}
