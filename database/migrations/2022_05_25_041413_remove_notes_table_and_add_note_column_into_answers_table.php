<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveNotesTableAndAddNoteColumnIntoAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('notes');

        Schema::table('answers', function (Blueprint $table) {
            $table->text('notes', 4000)->nullable();
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
            $table->dropColumn('notes');
        });
        
        Schema::create('notes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('questionnaire_question_id')->unsigned();
            $table->foreign('questionnaire_question_id')->references('id')->on('questionnaire_questions');
            $table->timestamps();
        });
    }
}
