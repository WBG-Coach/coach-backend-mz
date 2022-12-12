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

        \App\Models\User::insert(
        [
            'id' => 1,
            'name' => 'Admin User',
            'email' => 'admin@wbg.org',
            'profile_id' => 1,
            'password' => bcrypt('Pass@321'),
            'image_url' => 'https://www.tenforums.com/attachments/tutorials/146359d1501443008-change-default-account-picture-windows-10-a-user.png',
            'subject' => null,
            'api_token' => Str::random(80)
        ]);
    }
}
