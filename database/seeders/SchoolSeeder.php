<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\School::insert([
            [
                'id' => 1,
                'name' => 'Cheerful Beginnings',
                'image_url' => 'https://media.istockphoto.com/vectors/black-and-white-illustration-of-a-school-logo-vector-id1136343416?k=20&m=1136343416&s=170667a&w=0&h=0oxnn5rS1hMyMc2qt0qtD5u6JRPD1vAAA_D_iyXRbF4='
            ],
            [
                'id' => 2,
                'name' => 'Shining Stars',
                'image_url' => 'https://i.pinimg.com/originals/ad/a0/6a/ada06a90a694a6995f6273c99ee8eb35.jpg'
            ],
            [
                'id' => 3,
                'name' => 'Morning Roots School',
                'image_url' => 'https://t4.ftcdn.net/jpg/03/48/08/57/360_F_348085725_K5q0WeP3GZyooO5w0vkEDN1vOJWqefTo.jpg'
            ],
        ]);
    }
}
