<?php

namespace Database\Seeders;

use App\Models\PatientType;
use Illuminate\Database\Seeder;

class PatientTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patientTypes = [
            [
                'name' => 'New Patient',
                'status' => 1,
            ],
            [
                'name' => 'Old Patient',
                'status' => 1,
            ],
            [
                'name' => 'Report Patient',
                'status' => 1,
            ],
        ];

        PatientType::insert($patientTypes);
    }
}
