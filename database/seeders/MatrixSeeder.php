<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MatrixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Matrix::insert([
            [
                'id' => 1,
                'name' => 'Matrix 1'
            ]
        ]);
    }
}
