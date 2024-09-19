<?php

namespace Database\Seeders;

use App\Models\Chamber;
use Illuminate\Database\Seeder;

class ChamberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Popular Diagnostic Centre Ltd., Mymensingh',
                'map_location' => '<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14493.849595179923!2d90.4087841!3d24.7453303!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x37564fa682e7f843%3A0xff705ad5e320e866!2sPopular%20Diagnostic%20Centre%20Ltd.%2C%20Mymensingh!5e0!3m2!1sen!2sbd!4v1696335366310!5m2!1sen!2sbd" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
                'city' => 'Mymensingh',
                'division' => 'Mymensingh',
                'address' => '255 Charpara Rd, Mymensingh',
            ],
        ];

        Chamber::insert($data);
    }
}
