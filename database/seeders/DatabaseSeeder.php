<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

        $this->call(UserSeeder::class);
        $this->call(PatientTypeSeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(SpecializationsSeeder::class);

        $this->call(MedicalSeeder::class);
        $this->call(DegreeSeeder::class);
        $this->call(DesignationSeeder::class);
        $this->call(ChamberSeeder::class);
        $this->call(AppontmentSeeder::class);
    }
}
