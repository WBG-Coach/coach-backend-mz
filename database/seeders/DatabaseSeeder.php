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
        
        \DB::statement("delete from answers");
        \DB::statement("delete from questionnaire_questions");
        \DB::statement("delete from options");
        \DB::statement("delete from questions");
        \DB::statement("delete from questionnaire_applications");
        \DB::statement("delete from questionnaires");
        \DB::statement("delete from competencies");
        \DB::statement("delete from matrixes");
        \DB::statement("delete from user_schools");
        \DB::statement("delete from schools");
        \DB::statement("delete from users");
        \DB::statement("delete from profiles");
        
        \App\Models\Profile::insert([
            [
                'id' => 1,
                'name' => 'ADMIN'
            ],
            [
                'id' => 2,
                'name' => 'COACH'
            ],
            [
                'id' => 3,
                'name' => 'TEACHER'
            ]
        ]);
        
        \App\Models\User::insert([
            [
                'id' => 1,
                'name' => 'USER 1 - ADMIN',
                'email' => 'user1@email.com',
                'profile_id' => 1,
                'password' => bcrypt('pass123'),
                'image_url' => 'https://www.tenforums.com/attachments/tutorials/146359d1501443008-change-default-account-picture-windows-10-a-user.png',
                'subject' => null
            ],
            [
                'id' => 2,
                'name' => 'Marcos Soledade',
                'email' => 'user2@email.com',
                'profile_id' => 2,
                'password' => bcrypt('pass123'),
                'image_url' => 'http://www.lamedichi.info/wp-content/uploads/2020/12/TAW9U8HJ5-UAW9UCKMX-e8b2706043e3-512.jpeg',
                'subject' => null
            ],
            [
                'id' => 3,
                'name' => 'Guilherme Fernandes',
                'email' => 'user3@email.com',
                'profile_id' => 3,
                'password' => bcrypt('pass123'),
                'image_url' => 'http://www.lamedichi.info/wp-content/uploads/2020/07/E3AF0638-C511-4394-88E1-34514FB4F698.png',
                'subject' => 'Português'
            ],
            [
                'id' => 4,
                'name' => 'Josias Nascimento',
                'email' => 'user4@email.com',
                'profile_id' => 3,
                'password' => bcrypt('pass123'),
                'image_url' => 'http://www.lamedichi.info/wp-content/uploads/2020/12/josias-cunha.png',
                'subject' => 'Matemática'
            ],
            [
                'id' => 5,
                'name' => 'Janderson Souza',
                'email' => 'user5@email.com',
                'profile_id' => 2,
                'password' => bcrypt('pass123'),
                'image_url' => 'http://www.lamedichi.info/wp-content/uploads/2020/12/13595853.jpg',
                'subject' => null
            ]
        ]);

        \App\Models\School::insert([
            [
                'id' => 1,
                'name' => 'Cheerful Beginnings',
                'image_url' => 'https://media.istockphoto.com/vectors/black-and-white-illustration-of-a-school-logo-vector-id1136343416?k=20&m=1136343416&s=170667a&w=0&h=0oxnn5rS1hMyMc2qt0qtD5u6JRPD1vAAA_D_iyXRbF4='
            ],
            [
                'id' => 2,
                'name' => 'Shining Stars',
                'image_url' => 'https://i.pinimg.com/originals/ad/a0/6a/ada06a90a694a6995f6273c99ee8eb35.jpg'
            ],
            [
                'id' => 3,
                'name' => 'Morning Roots School',
                'image_url' => 'https://t4.ftcdn.net/jpg/03/48/08/57/360_F_348085725_K5q0WeP3GZyooO5w0vkEDN1vOJWqefTo.jpg'
            ],
        ]);

        \App\Models\UserSchool::insert([
            [
                'id' => 1,
                'user_id' => 3,
                'school_id' => 1
            ],
            [
                'id' => 2,
                'user_id' => 4,
                'school_id' => 1
            ],
            [
                'id' => 3,
                'user_id' => 3,
                'school_id' => 2
            ],
            [
                'id' => 4,
                'user_id' => 4,
                'school_id' => 3
            ]
        ]);

        \App\Models\Questionnaire::insert([
            [
                'id' => 1,
                'title' => 'Questionario 1',
                'type' => 'OBSERVATION'
            ]
        ]);

        \App\Models\Matrix::insert([
            [
                'id' => 1,
                'name' => 'Matrix 1'
            ]
        ]);

        \App\Models\Competence::insert([
            [
                'id' => 1,
                'name' => 'COMPETÊNCIA 1',
                'description' => 'USAR O GUIÃO DO PROFESSOR COM EFICÁCIA',
                'matrix_id' => 1
            ],
            [
                'id' => 2,
                'name' => 'COMPETÊNCIA 2',
                'description' => 'DEMONSTRAR E PRATICAR',
                'matrix_id' => 1
            ],
            [
                'id' => 3,
                'name' => 'COMPETÊNCIA 3',
                'description' => 'VERIFICAR A COMPREENSÃO',
                'matrix_id' => 1
            ],
            [
                'id' => 4,
                'name' => 'COMPETÊNCIA 4',
                'description' => 'CONSTRUIR RELACIONAMENTOS',
                'matrix_id' => 1
            ],
            [
                'id' => 5,
                'name' => 'COMPETÊNCIA 5',
                'description' => 'ESTABELECER ROTINAS',
                'matrix_id' => 1
            ]
        ]);

        \App\Models\Question::insert([
            [
                'id' => 1,
                'competency_id' => null,
                'text' => 'Qual é o plano de aula que o professor está a leccionar?'
            ],
            [
                'id' => 2,
                'competency_id' => 1,
                'text' => 'O professor tem o Guião do Professor aberto E segue a sequência de actividades no plano da aula?'
            ],
            [
                'id' => 3,
                'competency_id' => 2,
                'text' => 'O professor aplica a estrutura eu faço, nós fazemos, tu fazes como indicado no plano da aula?'
            ],
            [
                'id' => 4,
                'competency_id' => 3,
                'text' => 'O professor faz as perguntas e aplica as estratégias indicadas no plano de aula para verificar a compreensão dos alunos?'
            ],
            [
                'id' => 5,
                'competency_id' => 4,
                'text' => 'O professor segue as orientações dadas, no fim do plano de aula, para construir um relacionamento com cada aluno?'
            ],
            [
                'id' => 6,
                'competency_id' => 5,
                'text' => 'O professor conclui todas as actividades do plano de aula?'
            ]
        ]);
        
        \App\Models\Option::insert([
            [
                'id' => 1,
                'question_id' => 1,
                'text' => '1.1: Introdução as expressões de saudação "Bom dia" e "Boa noite"',
                'selected_color' => null,
                'selected_icon' => null
            ],
            [
                'id' => 2,
                'question_id' => 1,
                'text' => '1.1: Introdução as expressões de saudação "Boa tarde" e "Olá"',
                'selected_color' => null,
                'selected_icon' => null
            ],
            [
                'id' => 3,
                'question_id' => 2,
                'text' => 'Sim',
                'selected_color' => '#33CC5A',
                'selected_icon' => 'thumbs-up'
            ],
            [
                'id' => 4,
                'question_id' => 2,
                'text' => 'Não',
                'selected_color' => '#FF3333',
                'selected_icon' => 'thumbs-down'
            ],
            [
                'id' => 5,
                'question_id' => 3,
                'text' => 'Sim',
                'selected_color' => '#33CC5A',
                'selected_icon' => 'thumbs-up'
            ],
            [
                'id' => 6,
                'question_id' => 3,
                'text' => 'Não',
                'selected_color' => '#FF3333',
                'selected_icon' => 'thumbs-down'
            ],
            [
                'id' => 7,
                'question_id' => 4,
                'text' => 'Sim',
                'selected_color' => '#33CC5A',
                'selected_icon' => 'thumbs-up'
            ],
            [
                'id' => 8,
                'question_id' => 4,
                'text' => 'Não',
                'selected_color' => '#FF3333',
                'selected_icon' => 'thumbs-down'
            ],
            [
                'id' => 9,
                'question_id' => 5,
                'text' => 'Sim',
                'selected_color' => '#33CC5A',
                'selected_icon' => 'thumbs-up'
            ],
            [
                'id' => 10,
                'question_id' => 5,
                'text' => 'Não',
                'selected_color' => '#FF3333',
                'selected_icon' => 'thumbs-down'
            ],
            [
                'id' => 11,
                'question_id' => 6,
                'text' => 'Sim',
                'selected_color' => '#33CC5A',
                'selected_icon' => 'thumbs-up'
            ],
            [
                'id' => 12,
                'question_id' => 6,
                'text' => 'Não',
                'selected_color' => '#FF3333',
                'selected_icon' => 'thumbs-down'
            ]
        ]);

        
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
