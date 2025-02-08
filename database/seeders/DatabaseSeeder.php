<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            DoctorProfilesTableSeeder::class,
            AppointmentsTableSeeder::class,
            DoctorRatingsTableSeeder::class,
            DoctorSpecializationSeeder::class,
        ]);
    }
}
