<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $application = \App\Models\QuestionnaireApplication::find(1);
        $application->status = 'PENDING_FEEDBACK';
        $application->update();

        \App\Models\Answer::insert([
            [
                'id' => 1,
                'questionnaire_application_id' => 1,
                'questionnaire_question_id' => 1,
                'notes' => null,
                'option_id' => 2,
                'city' => 'Fortaleza'
            ],
            [
                'id' => 2,
                'questionnaire_application_id' => 1,
                'questionnaire_question_id' => 2,
                'notes' => null,
                'option_id' => 4,
                'city' => 'Fortaleza'
            ],
            [
                'id' => 3,
                'questionnaire_application_id' => 1,
                'questionnaire_question_id' => 3,
                'notes' => null,
                'option_id' => 5,
                'city' => 'Eusebio'
            ],
            [
                'id' => 4,
                'questionnaire_application_id' => 1,
                'questionnaire_question_id' => 4,
                'notes' => null,
                'option_id' => 8,
                'city' => 'Eusebio'
            ],
            [
                'id' => 5,
                'questionnaire_application_id' => 1,
                'questionnaire_question_id' => 5,
                'notes' => null,
                'option_id' => 10,
                'city' => 'Fortaleza'
            ],
            [
                'id' => 6,
                'questionnaire_application_id' => 1,
                'questionnaire_question_id' => 6,
                'notes' => null,
                'option_id' => 12,
                'city' => 'Fortaleza'
            ],
            [
                'id' => 7,
                'questionnaire_application_id' => 1,
                'questionnaire_question_id' => 1,
                'notes' => null,
                'option_id' => 2,
                'city' => 'Fortaleza'
            ],
            [
                'id' => 8,
                'questionnaire_application_id' => 1,
                'questionnaire_question_id' => 2,
                'notes' => null,
                'option_id' => 4,
                'city' => 'Fortaleza'
            ],
            [
                'id' => 9,
                'questionnaire_application_id' => 1,
                'questionnaire_question_id' => 3,
                'notes' => null,
                'option_id' => 5,
                'city' => 'Eusebio'
            ],
            [
                'id' => 10,
                'questionnaire_application_id' => 1,
                'questionnaire_question_id' => 4,
                'notes' => null,
                'option_id' => 8,
                'city' => 'Eusebio'
            ],
            [
                'id' => 11,
                'questionnaire_application_id' => 1,
                'questionnaire_question_id' => 5,
                'notes' => null,
                'option_id' => 10,
                'city' => 'Fortaleza'
            ],
            [
                'id' => 12,
                'questionnaire_application_id' => 1,
                'questionnaire_question_id' => 6,
                'notes' => null,
                'option_id' => 12,
                'city' => 'Fortaleza'
            ]
        ]);

        \App\Models\AnswerFile::insert([
            [
                'answer_id' => 1,
                'name' => 'File 1',
                'url' => 'https://marciookabe.com.br/wp-content/uploads/2016/01/coaching-digital.jpg'
            ],
            [
                'answer_id' => 1,
                'name' => 'File 2',
                'url' => 'https://marciookabe.com.br/wp-content/uploads/2016/01/coaching-digital.jpg'
            ],
            [
                'answer_id' => 2,
                'name' => 'File 3',
                'url' => 'https://marciookabe.com.br/wp-content/uploads/2016/01/coaching-digital.jpg'
            ],
            [
                'answer_id' => 2,
                'name' => 'File 4',
                'url' => 'https://marciookabe.com.br/wp-content/uploads/2016/01/coaching-digital.jpg'
            ],
            [
                'answer_id' => 2,
                'name' => 'File 5',
                'url' => 'https://marciookabe.com.br/wp-content/uploads/2016/01/coaching-digital.jpg'
            ],
            [
                'answer_id' => 3,
                'name' => 'File 6',
                'url' => 'https://marciookabe.com.br/wp-content/uploads/2016/01/coaching-digital.jpg'
            ]
        ]);
    }
}
