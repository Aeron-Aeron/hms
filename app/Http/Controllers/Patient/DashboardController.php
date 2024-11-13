<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\HealthProblem;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $appointments = Appointment::where('patient_id', $user->id)
            ->with('doctor')
            ->latest()
            ->take(5)
            ->get();

        $healthProblems = HealthProblem::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Get recommended doctors based on specialization and ratings
        $recommendedDoctors = User::where('role', 'doctor')
            ->whereHas('doctorProfile')
            ->withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->take(3)
            ->get();

        return view('patient.dashboard', compact('appointments', 'healthProblems', 'recommendedDoctors'));
    }
}
