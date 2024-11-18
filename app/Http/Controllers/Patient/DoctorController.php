<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function recommended()
    {
        $doctors = User::where('role', 'doctor')
            ->with('doctorProfile')
            ->withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->get();

        return view('patient.doctors.recommended', compact('doctors'));
    }

    public function index()
    {
        $doctors = User::where('role', 'doctor')
            ->with('doctorProfile')
            ->withAvg('ratings', 'rating')
            ->paginate(12);

        return view('patient.doctors.index', compact('doctors'));
    }

    public function show(User $doctor)
    {
        if ($doctor->role !== 'doctor') {
            abort(404);
        }

        return view('patient.doctors.show', [
            'doctor' => $doctor->load(['doctorProfile', 'ratings'])
        ]);
    }
}
