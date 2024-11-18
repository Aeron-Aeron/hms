<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PatientDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get user's appointments
        $appointments = $user->appointments()
            ->with('doctor')
            ->latest()
            ->take(5)
            ->get();

        // Get user's health problems
        $healthProblems = $user->healthProblems()
            ->latest()
            ->take(5)
            ->get();

        // Get featured doctors (those with highest ratings)
        $featuredDoctors = User::where('role', 'doctor')
            ->with(['doctorProfile', 'ratings'])
            ->withAvg('ratings', 'rating')
            ->withCount('appointments')
            ->orderByDesc('ratings_avg_rating')
            ->take(4)
            ->get();

        return view('patient.dashboard', compact(
            'appointments',
            'healthProblems',
            'featuredDoctors'
        ));
    }
}
