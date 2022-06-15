<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
    }
}
