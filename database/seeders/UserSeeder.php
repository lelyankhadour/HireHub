<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Founder / Admin (Client)
        User::create([
            'first_name' => 'Admin',
            'last_name'  => 'User',
            'email'      => 'admin@hirehub.com',
            'password'   => Hash::make('password'),
            'role'       => 'client',
            'is_verified' => true,
            'phone'      => '1111111111',
            'city_id'    => 1,
        ]);

        // Clients
        User::create([
            'first_name' => 'Christine',
            'last_name'  => 'Mann',
            'email'      => 'client1@hirehub.com',
            'password'   => Hash::make('password'),
            'role'       => 'client',
            'is_verified' => true,
            'phone'      => '14303900293',
            'city_id'    => 1,
        ]);

        User::create([
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'email'      => 'client2@hirehub.com',
            'password'   => Hash::make('password'),
            'role'       => 'client',
            'is_verified' => true,
            'phone'      => '2222222222',
            'city_id'    => 2,
        ]);

        // Freelancers
        User::create([
            'first_name' => 'Sarah',
            'last_name'  => 'Smith',
            'email'      => 'freelancer1@hirehub.com',
            'password'   => Hash::make('password'),
            'role'       => 'freelancer',
            'is_verified' => true,
            'phone'      => '3333333333',
            'city_id'    => 1,
        ]);

        User::create([
            'first_name' => 'Michael',
            'last_name'  => 'Brown',
            'email'      => 'freelancer2@hirehub.com',
            'password'   => Hash::make('password'),
            'role'       => 'freelancer',
            'is_verified' => true,
            'phone'      => '4444444444',
            'city_id'    => 2,
        ]);


     
        $f1 = User::create([
            'first_name' => 'Sarah',
            'last_name'  => 'Smith',
            'email'      => 'freelancer1223@hirehub.com',
            'password'   => Hash::make('password'),
            'role'       => 'freelancer',
            'is_verified' => true,
            'phone'      => '3333333333',
            'city_id'    => 1,
        ]);

        $f1->skills()->attach([
            1 => ['years_of_experience' => 3], // PHP
            2 => ['years_of_experience' => 2], // Laravel
            4 => ['years_of_experience' => 1], // Vue.js
        ]);


        $f2 = User::create([
            'first_name' => 'Michael',
            'last_name'  => 'Brown',
            'email'      => 'freelancer29@hirehub.com',
            'password'   => Hash::make('password'),
            'role'       => 'freelancer',
            'is_verified' => true,
            'phone'      => '4444444444',
            'city_id'    => 2,
        ]);

        $f2->skills()->attach([
            3 => ['years_of_experience' => 4], // JavaScript
            5 => ['years_of_experience' => 2], // React
            7 => ['years_of_experience' => 3], // MySQL
        ]);

   
        $f3 = User::create([
            'first_name' => 'Lina',
            'last_name'  => 'Kareem',
            'email'      => 'freelancer13@hirehub.com',
            'password'   => Hash::make('password'),
            'role'       => 'freelancer',
            'is_verified' => true,
            'phone'      => '5555555555',
            'city_id'    => 1,
        ]);

        $f3->skills()->attach([
            8 => ['years_of_experience' => 5], // UI/UX
            10 => ['years_of_experience' => 2], // Python
        ]);
    }
}
