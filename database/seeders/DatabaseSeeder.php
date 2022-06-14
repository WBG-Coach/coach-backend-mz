<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Profile::insert([
            [
                'id' => 1,
                'name' => 'admin'
            ],
            [
                'id' => 2,
                'name' => 'teacher'
            ],
            [
                'id' => 3,
                'name' => 'coach'
            ]
        ]);

        \App\Models\User::insert([
            [
                'id' => 1,
                'name' => 'admin 1',
                'email' => 'admin_1@email.com',
                'profile_id' => 1,
                'password' => bcrypt('pass123')
            ],
            [
                'id' => 2,
                'name' => 'teacher 1',
                'email' => 'teacher_1@email.com',
                'profile_id' => 2,
                'password' => bcrypt('pass123')
            ],
            [
                'id' => 3,
                'name' => 'teacher 2',
                'email' => 'teacher_2@email.com',
                'profile_id' => 2,
                'password' => bcrypt('pass123')
            ],
            [
                'id' => 4,
                'name' => 'coach 1',
                'email' => 'coach_1@email.com',
                'profile_id' => 3,
                'password' => bcrypt('pass123')
            ],
        ]);

        \App\Models\School::insert([
            [
                'id' => 1,
                'name' => 'school 1'
            ]
        ]);

        \App\Models\UserSchool::insert([
            [
                'id' => 1,
                'user_id' => 2,
                'school_id' => 1
            ],
            [
                'id' => 2,
                'user_id' => 3,
                'school_id' => 1
            ],
            [
                'id' => 3,
                'user_id' => 4,
                'school_id' => 1
            ],
        ]);

        \App\Models\Questionnaire::insert([
            [
                'id' => 1,
                'title' => 'Questions about XPTO'
            ]
        ]);

        \App\Models\Matrix::insert([
            [
                'id' => 1,
                'name' => 'matrix 1'
            ]
        ]);

        \App\Models\Competence::insert([
            [
                'id' => 1,
                'name' => 'competence 1',
                'matrix_id' => 1
            ]
        ]);

        \App\Models\Question::insert([
            [
                'id' => 1,
                'competency_id' => 1,
                'text' => 'question 1'
            ],
            [
                'id' => 2,
                'competency_id' => 1,
                'text' => 'question 2'
            ],
            [
                'id' => 3,
                'competency_id' => 1,
                'text' => 'question 3'
            ]
        ]);

        \App\Models\QuestionnaireApplication::insert([
            [
                'id' => 1,
                'questionnaire_id' => 1,
                'coach_id' => 4,
                'teacher_id' => 2,
                'school_id' => 1
            ],
            [
                'id' => 2,
                'questionnaire_id' => 1,
                'coach_id' => 4,
                'teacher_id' => 3,
                'school_id' => 1
            ]
        ]);


        \App\Models\QuestionnaireQuestion::insert([
            [
                'id' => 1,
                'question_id' => 1,
                'questionnaire_id' => 1
            ],
            [
                'id' => 2,
                'question_id' => 2,
                'questionnaire_id' => 1
            ],
            [
                'id' => 3,
                'question_id' => 3,
                'questionnaire_id' => 1
            ],
        ]);
        
        \App\Models\Option::insert([
            [
                'id' => 1,
                'question_id' => 1,
                'text' => 'yes?'
            ],
            [
                'id' => 2,
                'question_id' => 1,
                'text' => 'no?'
            ],
            [
                'id' => 3,
                'question_id' => 2,
                'text' => 'yes?'
            ],
            [
                'id' => 4,
                'question_id' => 2,
                'text' => 'no?'
            ],
            [
                'id' => 5,
                'question_id' => 3,
                'text' => 'yes?'
            ],
            [
                'id' => 6,
                'question_id' => 3,
                'text' => 'no?'
            ]
        ]);
        
        \App\Models\Answer::insert([
            [
                'id' => 1,
                'questionnaire_application_id' => 1,
                'questionnaire_question_id' => 1,
                'option_id' => 1
            ],
            [
                'id' => 2,
                'questionnaire_application_id' => 1,
                'questionnaire_question_id' => 2,
                'option_id' => 3
            ],
            [
                'id' => 3,
                'questionnaire_application_id' => 1,
                'questionnaire_question_id' => 3,
                'option_id' => 4
            ],
            [
                'id' => 4,
                'questionnaire_application_id' => 2,
                'questionnaire_question_id' => 1,
                'option_id' => 2
            ],
            [
                'id' => 5,
                'questionnaire_application_id' => 2,
                'questionnaire_question_id' => 2,
                'option_id' => 3
            ],
            [
                'id' => 6,
                'questionnaire_application_id' => 2,
                'questionnaire_question_id' => 3,
                'option_id' => 5
            ]
        ]);


    }
}
