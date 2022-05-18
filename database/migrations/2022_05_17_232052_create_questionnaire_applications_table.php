<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnaireApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaire_applications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('questionnaire_id')->unsigned();
            $table->foreign('questionnaire_id')->references('id')->on('questionnaires');
            $table->integer('coach_id')->unsigned();
            $table->foreign('coach_id')->references('id')->on('users');
            $table->integer('teacher_id')->unsigned();
            $table->foreign('teacher_id')->references('id')->on('users');
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
        Schema::dropIfExists('questionnaire_applications');
    }
}
