<?php

namespace Database\Seeders;

use App\Models\Medical;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Dhaka medical',
                'status' => true
            ],
            [
                'name' => 'Mymensingh medical',
                'status' => true
            ],
        ];

        Medical::insert($data);
    }
}
