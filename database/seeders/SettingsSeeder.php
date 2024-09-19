<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'company_name' => 'ABC',
            'email' => 'info@gmail.com',
            'phone' => '(+88)01603283901',
            'address' => 'Mymensingh, Bangladesh',
            'logo_light' => 'light_logo.png',
            'logo_dark' => 'dark_logo.png',
            'fave_icon' => 'favicon.ico',
        ];

        Settings::insert($data);

    }
}
