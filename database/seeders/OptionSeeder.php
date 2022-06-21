<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Option::insert([
            [
                'id' => 1,
                'question_id' => 1,
                'text' => 'Introdução as expressões de saudação "Bom dia" e "Boa noite"',
                'selected_color' => null,
                'selected_icon' => null
            ],
            [
                'id' => 2,
                'question_id' => 1,
                'text' => 'Introdução as expressões de saudação "Boa tarde" e "Olá"',
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

        for ($i=0; $i < 65; $i++) { 
            \App\Models\Option::create([
                'question_id' => 1,
                'text' => 'Introdução as expressões de saudação "Bom dia" e "Boa noite"',
                'selected_color' => null,
                'selected_icon' => null
            ]);
            \App\Models\Option::create([
                'question_id' => 1,
                'text' => 'Introdução as expressões de saudação "Boa tarde" e "Olá"',
                'selected_color' => null,
                'selected_icon' => null
            ]);
        }
    }
}
