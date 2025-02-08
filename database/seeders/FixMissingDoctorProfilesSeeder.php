<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\DoctorProfile;
use Illuminate\Database\Seeder;

class FixMissingDoctorProfilesSeeder extends Seeder
{
    public function run()
    {
        $doctors = User::where('role', 'doctor')
            ->whereDoesntHave('doctorProfile')
            ->get();

        foreach ($doctors as $doctor) {
            DoctorProfile::create([
                'user_id' => $doctor->id,
                'specialization' => 'General Medicine',
                'experience_years' => 0,
                'bio' => 'Profile pending update'
            ]);
        }
    }
}
