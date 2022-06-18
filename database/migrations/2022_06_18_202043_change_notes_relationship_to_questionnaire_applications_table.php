<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeNotesRelationshipToQuestionnaireApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::beginTransaction();

        try {
            Schema::table('notes', function (Blueprint $table) {
                $table->integer('questionnaire_application_id')->unsigned();
                $table->foreign('questionnaire_application_id')->references('id')->on('questionnaire_applications');
                $table->dropForeign('notes_answer_id_foreign');
            });
            Schema::table('notes', function (Blueprint $table) {
                $table->dropColumn('answer_id');
            });

            \DB::commit();
        } catch (\Throwable $th) {
            \DB::rollback();
            throw $th;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::beginTransaction();

        try {
            Schema::table('notes', function (Blueprint $table) {
                $table->integer('answer_id')->unsigned();
                $table->foreign('answer_id')->references('id')->on('answers');
                $table->dropForeign('notes_questionnaire_application_id_foreign');
            });
            Schema::table('notes', function (Blueprint $table) {
                $table->dropColumn('questionnaire_application_id');
            });

            \DB::commit();
        } catch (\Throwable $th) {
            \DB::rollback();
            throw $th;
        }
    }
}
