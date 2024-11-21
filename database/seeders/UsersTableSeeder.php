<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Create 10 doctors
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => "Dr. " . fake()->name(),
                'email' => "doctor{$i}@example.com",
                'password' => Hash::make('password'),
                'role' => 'doctor',
            ]);
        }

        // Create 20 patients
        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'name' => fake()->name(),
                'email' => "patient{$i}@example.com",
                'password' => Hash::make('password'),
                'role' => 'patient',
            ]);
        }
    }
}
