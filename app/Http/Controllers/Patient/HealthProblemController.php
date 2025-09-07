<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\HealthProblem;
use App\Models\User;
use Illuminate\Http\Request;

class HealthProblemController extends Controller
{
    public function index()
    {
        $healthProblems = HealthProblem::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('patient.health-problems.index', compact('healthProblems'));
    }

    public function create()
    {
        // List of common symptoms for the form
        $commonSymptoms = [
            'Fever',
            'Cough',
            'Headache',
            'Fatigue',
            'Nausea',
            'Body ache',
            'Sore throat',
            'Shortness of breath',
            'Chest pain',
            'Dizziness'
        ];

        return view('patient.health-problems.create', compact('commonSymptoms'));
    }

    public function store(Request $request)
    {
        // Normalize symptoms: accept comma-separated string or array
        if (is_string($request->input('symptoms'))) {
            $parsed = array_filter(array_map('trim', explode(',', $request->input('symptoms'))));
            $request->merge(['symptoms' => array_values($parsed)]);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'symptoms' => 'required|array|min:1',
            'symptoms.*' => 'string',
            'severity' => 'required|in:mild,moderate,severe',
        ]);

        $validated['user_id'] = auth()->id();

        $healthProblem = HealthProblem::create($validated);

        // Find recommended doctors based on symptoms
        $recommendedDoctors = $this->findRecommendedDoctors($validated['symptoms']);

        // Redirect to patient dashboard so the new problem appears in Recent Health Problems
        return redirect()
            ->route('patient.dashboard')
            ->with('success', 'Health problem recorded successfully.')
            ->with('recommendedDoctors', $recommendedDoctors);
    }

    public function show(HealthProblem $healthProblem)
    {
        if ($healthProblem->user_id !== auth()->id()) {
            abort(403);
        }

        $recommendedDoctors = $this->findRecommendedDoctors($healthProblem->symptoms);

        return view('patient.health-problems.show', compact('healthProblem', 'recommendedDoctors'));
    }

    public function edit(HealthProblem $healthProblem)
    {
        if ($healthProblem->user_id !== auth()->id()) {
            abort(403);
        }

        $commonSymptoms = [
            'Fever',
            'Cough',
            'Headache',
            'Fatigue',
            'Nausea',
            'Body ache',
            'Sore throat',
            'Shortness of breath',
            'Chest pain',
            'Dizziness'
        ];

        return view('patient.health-problems.edit', compact('healthProblem', 'commonSymptoms'));
    }

    public function update(Request $request, HealthProblem $healthProblem)
    {
        if ($healthProblem->user_id !== auth()->id()) {
            abort(403);
        }

        // Normalize symptoms input for update as well
        if (is_string($request->input('symptoms'))) {
            $parsed = array_filter(array_map('trim', explode(',', $request->input('symptoms'))));
            $request->merge(['symptoms' => array_values($parsed)]);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'symptoms' => 'required|array|min:1',
            'symptoms.*' => 'string',
            'severity' => 'required|in:mild,moderate,severe',
        ]);

        $healthProblem->update($validated);

        return redirect()
            ->route('patient.health-problems.index')
            ->with('success', 'Health problem updated successfully.');
    }

    public function destroy(HealthProblem $healthProblem)
    {
        if ($healthProblem->user_id !== auth()->id()) {
            abort(403);
        }

        $healthProblem->delete();

        return redirect()
            ->route('patient.health-problems.index')
            ->with('success', 'Health problem deleted successfully.');
    }

    private function findRecommendedDoctors(array $symptoms)
    {
        // Logic to match symptoms with doctor specializations
        $specializations = $this->mapSymptomsToSpecializations($symptoms);

        return User::where('role', 'doctor')
            ->whereHas('doctorProfile', function ($query) use ($specializations) {
                $query->whereIn('specialization', $specializations);
            })
            ->with(['doctorProfile', 'ratings'])
            ->withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->take(5)
            ->get();
    }

    private function mapSymptomsToSpecializations(array $symptoms)
    {
        // Enhanced mapping based on the disease dataset
        $mapping = [
            // Common symptoms to specialties mapping
            'fever' => ['Internal Medicine', 'Infectious Disease'],
            'cough' => ['Pulmonology', 'ENT'],
            'headache' => ['Neurology', 'General Medicine'],
            'chest_pain' => ['Cardiology', 'Emergency Medicine'],
            'skin_rash' => ['Dermatology', 'Allergy and Immunology'],
            'fatigue' => ['Internal Medicine', 'Endocrinology'],
            'joint_pain' => ['Rheumatology', 'Orthopedics'],
            'abdominal_pain' => ['Gastroenterology', 'General Surgery'],
            
            // Disease-specific mappings
            'Fungal infection' => ['Dermatology', 'Infectious Disease'],
            'GERD' => ['Gastroenterology'],
            'Peptic ulcer diseae' => ['Gastroenterology'],
            'AIDS' => ['Infectious Disease', 'Internal Medicine'],
            'Diabetes' => ['Endocrinology'],
            'Bronchial Asthma' => ['Pulmonology'],
            'Hypertension' => ['Cardiology'],
            'Migraine' => ['Neurology'],
            'Cervical spondylosis' => ['Orthopedics', 'Neurology'],
            'Paralysis' => ['Neurology'],
            'Jaundice' => ['Gastroenterology', 'Hepatology'],
            'Malaria' => ['Infectious Disease'],
            'Hepatitis' => ['Gastroenterology', 'Hepatology']
        ];
        
        $specializations = [];
        foreach ($symptoms as $symptom) {
            $normalizedSymptom = strtolower(trim($symptom));
            if (isset($mapping[$normalizedSymptom])) {
                $specializations = array_merge($specializations, $mapping[$normalizedSymptom]);
            }
        }
        
        return array_unique($specializations);
    }
}
