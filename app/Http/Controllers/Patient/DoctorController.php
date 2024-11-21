<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DoctorRating;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function recommended()
    {
        $doctors = User::where('role', 'doctor')
            ->with('doctorProfile')
            ->withCount('ratings')
            ->get()
            ->map(function ($doctor) {
                $doctor->weighted_rating = DoctorRating::getOverallDoctorRating($doctor->id);
                return $doctor;
            })
            ->sortByDesc('weighted_rating')
            ->values();

        return view('patient.doctors.recommended', compact('doctors'));
    }

    public function index(Request $request)
    {
        $query = User::where('role', 'doctor')
            ->with('doctorProfile')
            ->withAvg('ratings', 'rating')
            ->withCount('ratings');

        // Handle search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('doctorProfile', function($q) use ($search) {
                      $q->where('specialization', 'like', "%{$search}%");
                  });
            });
        }

        $doctors = $query->paginate(12)->withQueryString();

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
