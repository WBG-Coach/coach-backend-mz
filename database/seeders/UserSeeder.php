<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::insert([
            [
                'id' => 1,
                'name' => 'USER 1 - ADMIN',
                'email' => 'user1@email.com',
                'profile_id' => 1,
                'password' => bcrypt('pass123'),
                'image_url' => 'https://www.tenforums.com/attachments/tutorials/146359d1501443008-change-default-account-picture-windows-10-a-user.png',
                'subject' => null,
                'api_token' => Str::random(80)
            ],
            [
                'id' => 2,
                'name' => 'Marcos Soledade',
                'email' => 'user2@email.com',
                'profile_id' => 2,
                'password' => bcrypt('pass123'),
                'image_url' => 'http://www.lamedichi.info/wp-content/uploads/2020/12/TAW9U8HJ5-UAW9UCKMX-e8b2706043e3-512.jpeg',
                'subject' => null,
                'api_token' => Str::random(80)
            ],
            [
                'id' => 3,
                'name' => 'Guilherme Fernandes',
                'email' => 'user3@email.com',
                'profile_id' => 3,
                'password' => bcrypt('pass123'),
                'image_url' => 'http://www.lamedichi.info/wp-content/uploads/2020/07/E3AF0638-C511-4394-88E1-34514FB4F698.png',
                'subject' => 'Português',
                'api_token' => Str::random(80)
            ],
            [
                'id' => 4,
                'name' => 'Josias Nascimento',
                'email' => 'user4@email.com',
                'profile_id' => 3,
                'password' => bcrypt('pass123'),
                'image_url' => 'http://www.lamedichi.info/wp-content/uploads/2020/12/josias-cunha.png',
                'subject' => 'Matemática',
                'api_token' => Str::random(80)
            ],
            [
                'id' => 5,
                'name' => 'Janderson Souza',
                'email' => 'user5@email.com',
                'profile_id' => 2,
                'password' => bcrypt('pass123'),
                'image_url' => 'http://www.lamedichi.info/wp-content/uploads/2020/12/13595853.jpg',
                'subject' => null,
                'api_token' => Str::random(80)
            ]
        ]);
    }
}
