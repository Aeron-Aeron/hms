<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\DoctorRating;
use App\Models\Appointment;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500'
        ]);

        // Check if patient has a completed appointment with this doctor
        $appointment = Appointment::where('doctor_id', $validated['doctor_id'])
            ->where('patient_id', auth()->id())
            ->where('status', 'completed')
            ->latest()
            ->firstOrFail();

        // Check if patient has already reviewed this appointment
        $existingRating = DoctorRating::where('appointment_id', $appointment->id)->first();
        if ($existingRating) {
            return redirect()->back()->with('error', 'You have already reviewed this appointment.');
        }

        // Create the rating
        DoctorRating::create([
            'doctor_id' => $validated['doctor_id'],
            'patient_id' => auth()->id(),
            'appointment_id' => $appointment->id,
            'rating' => $validated['rating'],
            'review' => $validated['review'],
            'helpful_votes' => 0,
            'total_votes' => 0,
            'verified_appointment' => true,
            'review_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Thank you for your review!');
    }

    public function vote(DoctorRating $rating)
    {
        // Prevent voting on own review
        if ($rating->patient_id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot vote on your own review.');
        }

        // Check if user has already voted
        // In a real app, you'd want to track this in a separate table
        $rating->increment('helpful_votes');
        $rating->increment('total_votes');

        return redirect()->back()->with('success', 'Thank you for your feedback!');
    }
}
