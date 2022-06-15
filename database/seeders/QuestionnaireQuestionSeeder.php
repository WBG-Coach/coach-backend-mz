<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class QuestionnaireQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\QuestionnaireQuestion::insert([
            [
                'id' => 1,
                'question_id' => 1,
                'order' => 1,
                'questionnaire_id' => 1
            ],
            [
                'id' => 2,
                'question_id' => 2,
                'order' => 2,
                'questionnaire_id' => 1
            ],
            [
                'id' => 3,
                'question_id' => 3,
                'order' => 3,
                'questionnaire_id' => 1
            ],
            [
                'id' => 4,
                'question_id' => 4,
                'order' => 4,
                'questionnaire_id' => 1
            ],
            [
                'id' => 5,
                'question_id' => 5,
                'order' => 5,
                'questionnaire_id' => 1
            ],
            [
                'id' => 6,
                'question_id' => 6,
                'order' => 6,
                'questionnaire_id' => 1
            ],
        ]);
    }
}
