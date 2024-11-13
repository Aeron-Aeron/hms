<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\DoctorProfile;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // Create some doctors if they don't exist
        for ($i = 1; $i <= 3; $i++) {
            $email = "doctor{$i}@example.com";

            // Check if doctor already exists
            $doctor = User::where('email', $email)->first();

            if (!$doctor) {
                $doctor = User::create([
                    'name' => "Dr. John Doe {$i}",
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'role' => 'doctor',
                ]);

                DoctorProfile::create([
                    'user_id' => $doctor->id,
                    'specialization' => 'General Medicine',
                    'bio' => 'Experienced healthcare professional with over 10 years of practice.',
                    'qualification' => 'MD, MBBS',
                    'experience' => '10 years'
                ]);
            }
        }

        // Create a patient if doesn't exist
        $patientEmail = 'patient@example.com';
        $patient = User::where('email', $patientEmail)->first();

        if (!$patient) {
            $patient = User::create([
                'name' => 'Patient User',
                'email' => $patientEmail,
                'password' => Hash::make('password'),
                'role' => 'patient',
            ]);
        }

        // Create testimonials only if none exist
        if (Testimonial::count() === 0) {
            for ($i = 1; $i <= 3; $i++) {
                Testimonial::create([
                    'user_id' => $patient->id,
                    'comment' => 'Great experience with the doctor. Very professional and knowledgeable.',
                    'rating' => 5,
                    'is_featured' => true,
                ]);
            }
        }
    }
}
