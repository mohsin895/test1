<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Assistant Professor',
                'status' => 1,
            ],
            [
                'name' => 'Professor',
                'status' => 1,
            ],
            [
                'name' => 'Associate Professor',
                'status' => 1,
            ],
        ];

        Designation::insert($data);
    }
}
