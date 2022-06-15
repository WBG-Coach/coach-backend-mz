<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCompetencyIdColumnFromQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("delete from questions");
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign('questions_competency_id_foreign');
        });
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('competency_id');
        });
        Schema::table('questions', function (Blueprint $table) {
            $table->integer('competency_id')->unsigned()->nullable();
            $table->foreign('competency_id')->references('id')->on('competencies');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // without rollback
    }
}
