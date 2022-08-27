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
        $this->call([
            ClearAll::class,
            ProfileSeeder::class,
            ProjectSeeder::class,
            UserSeeder::class,
            SchoolSeeder::class,
            UserSchoolSeeder::class,
            QuestionnaireSeeder::class,
            MatrixSeeder::class,
            CompetenceSeeder::class,
            QuestionSeeder::class,
            OptionSeeder::class,
            QuestionnaireApplicationSeeder::class,
            QuestionnaireQuestionSeeder::class
        ]);
    }
}
