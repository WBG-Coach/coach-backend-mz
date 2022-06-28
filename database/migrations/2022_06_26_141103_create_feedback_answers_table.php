<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('feedback_answers');
        Schema::create('feedback_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('feedback_id')->unsigned();
            $table->foreign('feedback_id')->references('id')->on('feedbacks');
            $table->text('notes', 5000)->nullable();
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
        Schema::dropIfExists('feedback_answers');
    }
}
