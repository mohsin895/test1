<?php

namespace Database\Seeders;

use App\Models\Degree;
use Illuminate\Database\Seeder;

class DegreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'MBBS',
                'status' => 1,
            ],
            [
                'name' => 'MS',
                'status' => 1,
            ],
            [
                'name' => 'MD',
                'status' => 1,
            ],
            [
                'name' => 'BDS',
                'status' => 1,
            ],
            [
                'name' => 'BUMS',
                'status' => 1,
            ],
            [
                'name' => 'BHMS',
                'status' => 1,
            ],
            [
                'name' => 'BYMS',
                'status' => 1,
            ],
        ];

        Degree::insert($data);

    }
}
