<?php

namespace App\Http\Controllers;

use App\Models\DoctorRating;
use App\Models\Appointment;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500'
        ]);

        DoctorRating::create([
            'doctor_id' => $appointment->doctor_id,
            'patient_id' => auth()->id(),
            'appointment_id' => $appointment->id,
            'rating' => $validated['rating'],
            'review' => $validated['review']
        ]);

        return redirect()->back()->with('success', 'Thank you for your feedback!');
    }
}
