<?php

namespace App\Http\Controllers;

use App\Models\DoctorAvailability;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'schedule' => 'required|array',
            'schedule.*' => 'required|array',
            'schedule.*.start' => 'required|date_format:H:i',
            'schedule.*.end' => 'required|date_format:H:i|after:schedule.*.start',
        ]);

        $doctor = auth()->user();

        foreach ($validated['schedule'] as $day => $times) {
            DoctorAvailability::updateOrCreate(
                [
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $day,
                ],
                [
                    'start_time' => $times['start'],
                    'end_time' => $times['end'],
                ]
            );
        }

        return redirect()->back()->with('success', 'Availability updated successfully!');
    }
}
