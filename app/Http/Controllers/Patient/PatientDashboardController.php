<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Symptom;
use App\Models\Disease;
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

        // Get all symptoms for the symptom checker
        $symptoms = Symptom::orderBy('name')->get();

        // Debug symptoms
        \Log::info('Symptoms count: ' . $symptoms->count());

        return view('patient.dashboard', compact(
            'appointments',
            'healthProblems',
            'featuredDoctors',
            'symptoms'
        ));
    }

    public function checkSymptoms(Request $request)
    {
        try {
            $validated = $request->validate([
                'symptoms' => 'required|array|min:1',
                'symptoms.*' => 'exists:symptoms,id'
            ]);

            \Log::info('Selected symptoms:', $validated['symptoms']); // Debug log

            $selectedSymptoms = collect($validated['symptoms']);

            // Get all diseases that have any of the selected symptoms
            $diseases = Disease::whereHas('symptoms', function($query) use ($selectedSymptoms) {
                $query->whereIn('symptoms.id', $selectedSymptoms);
            })->with('symptoms')->get();

            \Log::info('Found diseases count: ' . $diseases->count()); // Debug log

            if ($diseases->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'predictions' => []
                ]);
            }

            // Calculate match percentage for each disease
            $predictions = $diseases->map(function($disease) use ($selectedSymptoms) {
                $diseaseSymptoms = $disease->symptoms->pluck('id');
                $matchingSymptoms = $diseaseSymptoms->intersect($selectedSymptoms);

                return [
                    'disease' => [
                        'name' => $disease->name,
                        'id' => $disease->id
                    ],
                    'match_percentage' => round(($matchingSymptoms->count() / $diseaseSymptoms->count()) * 100, 2),
                    'matching_symptoms' => $matchingSymptoms->count()
                ];
            })
            ->sortByDesc('match_percentage')
            ->take(5)
            ->values();

            \Log::info('Predictions generated:', $predictions->toArray()); // Debug log

            return response()->json([
                'success' => true,
                'predictions' => $predictions
            ]);

        } catch (\Exception $e) {
            \Log::error('Symptom checking error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing symptoms',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function findSpecialists($diseaseName)
    {
        // Find doctors specialized in treating this disease
        $doctors = User::where('role', 'doctor')
            ->whereHas('doctorProfile', function($query) use ($diseaseName) {
                $query->where('specialization', 'LIKE', '%' . $diseaseName . '%');
            })
            ->with(['doctorProfile', 'ratings'])
            ->withAvg('ratings', 'rating')
            ->get();

        return view('patient.specialists', compact('doctors', 'diseaseName'));
    }
}
