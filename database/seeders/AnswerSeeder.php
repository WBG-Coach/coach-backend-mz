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
                'option_id' => 2
            ],
            [
                'id' => 2,
                'questionnaire_application_id' => 1,
                'questionnaire_question_id' => 2,
                'notes' => null,
                'option_id' => 4
            ],
            [
                'id' => 3,
                'questionnaire_application_id' => 1,
                'questionnaire_question_id' => 3,
                'notes' => null,
                'option_id' => 5
            ],
            [
                'id' => 4,
                'questionnaire_application_id' => 1,
                'questionnaire_question_id' => 4,
                'notes' => null,
                'option_id' => 8
            ],
            [
                'id' => 5,
                'questionnaire_application_id' => 1,
                'questionnaire_question_id' => 5,
                'notes' => null,
                'option_id' => 10
            ],
            [
                'id' => 6,
                'questionnaire_application_id' => 1,
                'questionnaire_question_id' => 6,
                'notes' => null,
                'option_id' => 12
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
