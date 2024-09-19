<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $admin = Role::create([
        //     'slug' => 'admin',
        //     'title' => 'Admin',
        //     'deletable' => false,
        // ]);

        // $user = Role::create([
        //     'slug' => 'user',
        //     'title' => 'User',
        //     'deletable' => false,
        // ]);

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => ADMIN,
            'phone' => '01521306527',
            'password' => bcrypt('password')
        ]);
        // \App\Models\User::factory()->create([
        //     'name' => 'User',
        //     'email' => 'user@example.com',
        //     'role' => DOCTOR,
        //     'phone' => '01700000000',
        // ]);
        // \App\Models\User::factory()->create([
        //     'name' => 'User',
        //     'email' => 'user@example.com',
        //     'role' => DOCTOR,
        //     'phone' => '01700000000',
        // ]);
        // \App\Models\User::factory()->create([
        //     'name' => 'Advertiser',
        //     'email' => 'advertiser@example.com',
        //     'role' => ADVERTISER,
        //     'phone' => '01700000001',
        // ]);
        // \App\Models\User::factory()->create([
        //     'name' => 'Pharmaceutical',
        //     'email' => 'pharmaceutical@example.com',
        //     'role' => PHARMACEUTICAL,
        //     'phone' => '01700000002',
        // ]);
        // \App\Models\User::factory()->create([
        //     'name' => 'Compounder',
        //     'email' => 'compounder@example.com',
        //     'role' => COMPOUNDER,
        //     'phone' => '01700000003',
        // ]);

        // User::factory(50)->create();
        // $users = User::all();
        // $data = [];
        // foreach($users as $user) {
        //     $data[] = [
        //         'user_id' => $user->id,
        //         'specialization_ids' => json_encode([1,2]),
        //         'designation_ids' => json_encode([1,3]),
        //         'degree_info' => json_encode(['id' => 1,'note' => 'more']),
        //     ];
        // }
        // DoctorDetails::insert($data);
    }
}
