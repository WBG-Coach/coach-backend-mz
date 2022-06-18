<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->text('text', 5000);
            $table->integer('answer_id')->unsigned();
            $table->foreign('answer_id')->references('id')->on('answers');
            $table->timestamps();
        });
        
        Schema::table('answers', function (Blueprint $table) {
            $table->dropColumn('notes');
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
            $table->text('notes', 4000)->nullable();
        });
        
        Schema::dropIfExists('notes');
    }
}
