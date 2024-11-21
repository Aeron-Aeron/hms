<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;

class AppointmentsTableSeeder extends Seeder
{
    public function run()
    {
        $doctors = User::where('role', 'doctor')->pluck('id');
        $patients = User::where('role', 'patient')->pluck('id');
        $statuses = ['pending', 'accepted', 'declined', 'rescheduled', 'completed'];

        // Create 50 appointments
        for ($i = 0; $i < 50; $i++) {
            $status = fake()->randomElement($statuses);
            $scheduledDate = fake()->dateTimeBetween('-6 months', '+1 month');

            Appointment::create([
                'doctor_id' => fake()->randomElement($doctors),
                'patient_id' => fake()->randomElement($patients),
                'scheduled_time' => Carbon::parse($scheduledDate),
                'proposed_time' => $status === 'rescheduled' ? Carbon::parse($scheduledDate)->addDays(rand(1, 14)) : null,
                'status' => $status,
                'patient_notes' => fake()->optional(0.7)->sentence(),
                'doctor_notes' => $status !== 'pending' ? fake()->optional(0.5)->sentence() : null,
                'created_at' => Carbon::parse($scheduledDate)->subDays(rand(1, 30)),
            ]);
        }
    }
}
