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
    }
}
