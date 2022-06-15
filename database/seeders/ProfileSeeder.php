<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
    }
}
