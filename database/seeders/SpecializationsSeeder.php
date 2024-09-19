<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\Specialization;
use Illuminate\Database\Seeder;

class SpecializationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specializations = [
            'Cardiology',
            'Gastroenterologist',
            'Ear-Nose-Throat',
            'Ophthalmologist',
            'Nephrologist',
            'Pulmonologist',
            'Neurologist',
            'Orthopedic Surgeon',
        ];

        foreach ($specializations as $key => $specializationName) {
            $media = Media::create([
                'path' => '/frontend/home-page-img/catagory-'.$key + 1 .'.webp',
                'real_path' => '/frontend/home-page-img/catagory-'.$key + 1 .'.webp',
            ]);
            Specialization::create([
                'name' => $specializationName,
                'media_id' => $media->id,
            ]);

        }

    }
}
