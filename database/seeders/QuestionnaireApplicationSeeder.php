<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class QuestionnaireApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\QuestionnaireApplication::insert([
            [
                'id' => 1,
                'questionnaire_id' => 1,
                'coach_id' => 2,
                'teacher_id' => 3,
                'school_id' => 1
            ],
            [
                'id' => 2,
                'questionnaire_id' => 1,
                'coach_id' => 2,
                'teacher_id' => 4,
                'school_id' => 1
            ],
            [
                'id' => 3,
                'questionnaire_id' => 1,
                'coach_id' => 2,
                'teacher_id' => 3,
                'school_id' => 2
            ],
            [
                'id' => 4,
                'questionnaire_id' => 1,
                'coach_id' => 2,
                'teacher_id' => 4,
                'school_id' => 3
            ]
        ]);
    }
}
