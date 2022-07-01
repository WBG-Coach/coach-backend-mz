<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ClearAll extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement("delete from feedback_answers");
        \DB::statement("delete from feedbacks");
        \DB::statement("delete from answer_files");
        \DB::statement("delete from answers");
        \DB::statement("delete from questionnaire_questions");
        \DB::statement("delete from content_guides");
        \DB::statement("delete from options");
        \DB::statement("delete from questions");
        \DB::statement("delete from notes");
        \DB::statement("delete from questionnaire_applications");
        \DB::statement("delete from questionnaires");
        \DB::statement("delete from competencies");
        \DB::statement("delete from matrixes");
        \DB::statement("delete from user_schools");
        \DB::statement("delete from schools");
        \DB::statement("delete from users");
        \DB::statement("delete from profiles");
    }
}
