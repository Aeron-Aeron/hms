<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DoctorProfile;
use App\Models\User;

class DoctorProfilesTableSeeder extends Seeder
{
    public function run()
    {
        $specializations = [
            'Cardiologist',
            'Dermatologist',
            'Pediatrician',
            'Neurologist',
            'Psychiatrist',
            'Orthopedist',
            'Gynecologist',
            'Ophthalmologist',
            'ENT Specialist',
            'General Physician'
        ];

        $doctors = User::where('role', 'doctor')->get();

        foreach ($doctors as $index => $doctor) {
            DoctorProfile::create([
                'user_id' => $doctor->id,
                'specialization' => $specializations[$index % count($specializations)],
                'bio' => fake()->paragraph(),
                'education' => fake()->randomElement(['MBBS', 'MD', 'MS']) . ', ' .
                             fake()->randomElement(['FRCS', 'MRCP', 'DNB']),
                'experience_years' => fake()->numberBetween(2, 25),
            ]);
        }
    }
}
