<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDefaultValueFromStatusColumnFromQuestionnaireApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questionnaire_applications', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('questionnaire_applications', function (Blueprint $table) {
            $table->string('status')->default('PENDING_RESPONSE')->nullable();
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
