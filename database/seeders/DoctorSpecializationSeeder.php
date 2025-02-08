<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\DoctorProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DoctorSpecializationSeeder extends Seeder
{
    public function run()
    {
        $specializations = [
            [
                'name' => 'Dermatology',
                'diseases' => ['Fungal infection', 'Acne', 'Psoriasis', 'Impetigo'],
                'doctors' => [
                    ['name' => 'Dr. Sarah Wilson', 'experience' => 8, 'education' => 'MD, FAAD'],
                    ['name' => 'Dr. Michael Chen', 'experience' => 12, 'education' => 'MD, Dermatology'],
                ]
            ],
            [
                'name' => 'Internal Medicine',
                'diseases' => ['GERD', 'Chronic cholestasis', 'Drug Reaction', 'Peptic ulcer disease', 'Diabetes', 'Hypertension'],
                'doctors' => [
                    ['name' => 'Dr. James Rodriguez', 'experience' => 15, 'education' => 'MD, Internal Medicine'],
                    ['name' => 'Dr. Emily Parker', 'experience' => 10, 'education' => 'DO, Internal Medicine'],
                ]
            ],
            [
                'name' => 'Infectious Disease',
                'diseases' => ['AIDS', 'Malaria', 'Tuberculosis', 'Typhoid', 'Hepatitis A', 'Hepatitis B', 'Hepatitis C', 'Hepatitis D', 'Hepatitis E'],
                'doctors' => [
                    ['name' => 'Dr. Robert Kumar', 'experience' => 20, 'education' => 'MD, Infectious Disease'],
                    ['name' => 'Dr. Lisa Thompson', 'experience' => 14, 'education' => 'MD, MPH'],
                ]
            ],
            [
                'name' => 'Pulmonology',
                'diseases' => ['Bronchial Asthma', 'Common Cold', 'Pneumonia'],
                'doctors' => [
                    ['name' => 'Dr. David Cohen', 'experience' => 11, 'education' => 'MD, Pulmonology'],
                    ['name' => 'Dr. Maria Garcia', 'experience' => 9, 'education' => 'MD, FCCP'],
                ]
            ],
            [
                'name' => 'Neurology',
                'diseases' => ['Migraine', 'Cervical spondylosis', 'Paralysis (brain hemorrhage)', '(vertigo) Paroymsal Positional Vertigo'],
                'doctors' => [
                    ['name' => 'Dr. William Stone', 'experience' => 16, 'education' => 'MD, Neurology'],
                    ['name' => 'Dr. Patricia Lee', 'experience' => 13, 'education' => 'MD, FAAN'],
                ]
            ],
            [
                'name' => 'Cardiology',
                'diseases' => ['Heart attack', 'Varicose veins', 'Hypertension'],
                'doctors' => [
                    ['name' => 'Dr. Thomas Wright', 'experience' => 18, 'education' => 'MD, FACC'],
                    ['name' => 'Dr. Jennifer Adams', 'experience' => 15, 'education' => 'MD, Cardiology'],
                ]
            ],
            [
                'name' => 'Endocrinology',
                'diseases' => ['Hypothyroidism', 'Hypoglycemia', 'Diabetes'],
                'doctors' => [
                    ['name' => 'Dr. Richard Mills', 'experience' => 12, 'education' => 'MD, Endocrinology'],
                    ['name' => 'Dr. Susan Taylor', 'experience' => 10, 'education' => 'MD, FACE'],
                ]
            ],
            [
                'name' => 'Orthopedics',
                'diseases' => ['Osteoarthistis', 'Arthritis'],
                'doctors' => [
                    ['name' => 'Dr. Kevin Martinez', 'experience' => 14, 'education' => 'MD, Orthopedics'],
                    ['name' => 'Dr. Amanda White', 'experience' => 11, 'education' => 'MD, FAAOS'],
                ]
            ],
            [
                'name' => 'Gastroenterology',
                'diseases' => ['Gastroenteritis', 'Jaundice', 'Dimorphic hemmorhoids(piles)', 'Alcoholic hepatitis'],
                'doctors' => [
                    ['name' => 'Dr. Charles Brown', 'experience' => 13, 'education' => 'MD, Gastroenterology'],
                    ['name' => 'Dr. Rachel Green', 'experience' => 9, 'education' => 'MD, FACG'],
                ]
            ],
            [
                'name' => 'Urology',
                'diseases' => ['Urinary tract infection'],
                'doctors' => [
                    ['name' => 'Dr. Andrew Wilson', 'experience' => 10, 'education' => 'MD, Urology'],
                    ['name' => 'Dr. Nicole Carter', 'experience' => 8, 'education' => 'MD, FACS'],
                ]
            ]
        ];

        foreach ($specializations as $specialization) {
            foreach ($specialization['doctors'] as $doctor) {
                // Fix email generation by removing titles and extra dots
                $email = strtolower(
                    str_replace(
                        ['Dr. ', ' '], 
                        ['', '.'], 
                        $doctor['name']
                    )
                ) . '@hospital.com';
                
                $user = User::create([
                    'name' => $doctor['name'],
                    'email' => $email,
                    'password' => Hash::make('password123'),
                    'role' => 'doctor'
                ]);

                DoctorProfile::create([
                    'user_id' => $user->id,
                    'specialization' => $specialization['name'],
                    'experience' => $doctor['experience'],
                    'education' => $doctor['education'],
                    'diseases' => $specialization['diseases']
                ]);
            }
        }
    }
}
