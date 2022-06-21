<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Question::insert([
            [
                'id' => 1,
                'competency_id' => null,
                'text' => 'Qual é o plano de aula que o professor está a leccionar?',
                'type' => 'LIST'
            ],
            [
                'id' => 2,
                'competency_id' => 1,
                'text' => 'O professor tem o Guião do Professor aberto E segue a sequência de actividades no plano da aula?',
                'type' => 'OPTION'
            ],
            [
                'id' => 3,
                'competency_id' => 2,
                'text' => 'O professor aplica a estrutura eu faço, nós fazemos, tu fazes como indicado no plano da aula?',
                'type' => 'OPTION'
            ],
            [
                'id' => 4,
                'competency_id' => 3,
                'text' => 'O professor faz as perguntas e aplica as estratégias indicadas no plano de aula para verificar a compreensão dos alunos?',
                'type' => 'OPTION'
            ],
            [
                'id' => 5,
                'competency_id' => 4,
                'text' => 'O professor segue as orientações dadas, no fim do plano de aula, para construir um relacionamento com cada aluno?',
                'type' => 'OPTION'
            ],
            [
                'id' => 6,
                'competency_id' => 5,
                'text' => 'O professor conclui todas as actividades do plano de aula?',
                'type' => 'OPTION'
            ]
        ]);
    }
}
