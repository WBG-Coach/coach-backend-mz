<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Project::insert([
            [
                'id' => 1,
                'name' => 'Project 1',
                'image_url' => null,
                'primary_color' => null,
                'country' => null
            ]
        ]);
    }
}
