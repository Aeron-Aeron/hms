<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DoctorRating;
use App\Models\Appointment;
use Carbon\Carbon;

class DoctorRatingsTableSeeder extends Seeder
{
    public function run()
    {
        // Only seed ratings for completed appointments
        $completedAppointments = Appointment::where('status', 'completed')
            ->where('scheduled_time', '<', now())
            ->get();

        foreach ($completedAppointments as $appointment) {
            // 80% chance of having a rating
            if (fake()->boolean(80)) {
                DoctorRating::create([
                    'doctor_id' => $appointment->doctor_id,
                    'patient_id' => $appointment->patient_id,
                    'appointment_id' => $appointment->id,
                    'rating' => fake()->numberBetween(3, 5), // Slightly biased towards positive ratings
                    'review' => fake()->optional(0.7)->paragraph(), // 70% chance of having a review
                    'helpful_votes' => fake()->numberBetween(0, 15),
                    'total_votes' => fake()->numberBetween(15, 30),
                    'verified_appointment' => true,
                    'review_date' => Carbon::parse($appointment->scheduled_time)->addDays(rand(1, 7)),
                    'created_at' => Carbon::parse($appointment->scheduled_time)->addDays(rand(1, 7)),
                ]);
            }
        }
    }
}
