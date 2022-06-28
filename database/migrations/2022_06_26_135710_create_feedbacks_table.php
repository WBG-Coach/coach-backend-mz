<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('questionnaire_application_id')->unsigned();
            $table->integer('answer_id')->unsigned();
            $table->integer('competence_id')->unsigned();
            $table->foreign('questionnaire_application_id', 'questionnaire_application_fk')->references('id')->on('questionnaire_applications');
            $table->foreign('answer_id')->references('id')->on('answers');
            $table->foreign('competence_id')->references('id')->on('competencies');
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
        Schema::dropIfExists('feedbacks');
    }
}
