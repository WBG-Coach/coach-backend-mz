<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class QuestionnaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Questionnaire::insert([
            [
                'id' => 1,
                'title' => 'Questionario 1',
                'type' => 'OBSERVATION'
            ]
        ]);
    }
}
