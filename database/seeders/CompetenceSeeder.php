<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CompetenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Competence::insert([
            [
                'id' => 1,
                'title' => 'COMPETÊNCIA 1',
                'subtitle' => 'USAR O GUIÃO DO PROFESSOR COM EFICÁCIA',
                'description' => 'Usar o Guião do Professor com Eficácia significa preparar-se para a aula usando o Guião e, durante a aula, ensinar com base no Guião.',
                'matrix_id' => 1
            ],
            [
                'id' => 2,
                'title' => 'COMPETÊNCIA 2',
                'subtitle' => 'DEMONSTRAR E PRATICAR',
                'description' => 'Demonstrar e Praticar é quando o professor mostra aos alunos como executar uma nova tarefa e, de seguida, os alunos praticam essa mesma tarefa: As actividades nos planos de aulas que se baseiam nesta competência pedagógica estão assinaladas como Demonstrar e Praticar.',
                'matrix_id' => 1
            ],
            [
                'id' => 3,
                'title' => 'COMPETÊNCIA 3',
                'subtitle' => 'VERIFICAR A COMPREENSÃO',
                'description' => 'A verificação da compreensão ocorre quando o professor faz uma pausa e faz uma pergunta básica para ver se os alunos compreenderam aula até ao momento. As actividades nos planos de aulas que usam esta competência pedagógica estão assinaladas como Verificar a Compreensão',
                'matrix_id' => 1
            ],
            [
                'id' => 4,
                'title' => 'COMPETÊNCIA 4',
                'subtitle' => 'CONSTRUIR RELACIONAMENTOS',
                'description' => 'Construir Relacionamentos significa conhecer melhor os seus alunos como indivíduos. As actividades nos planos de aulas que se baseiam nesta competência pedagógica são designadas por Construir Relacionamentos.',
                'matrix_id' => 1
            ],
            [
                'id' => 5,
                'title' => 'COMPETÊNCIA 5',
                'subtitle' => 'ESTABELECER ROTINAS',
                'description' => 'Uma rotina é uma série de acções sequenciais que um professor pede aos alunos que sigam para criar uma aula segura, eficaz e produtiva. Existem várias rotinas principais que o ajudarão a ensinar as aulas do programa Aprender+ de uma forma mais eficaz e a executar cada uma das actividades no tempo disponível.',
                'matrix_id' => 1
            ]
        ]);
    }
}
