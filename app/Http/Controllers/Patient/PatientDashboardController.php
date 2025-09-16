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
      ->with('doctorProfile')
      ->withAvg('ratings', 'rating')
      ->withCount('ratings')
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
      $diseases = Disease::whereHas('symptoms', function ($query) use ($selectedSymptoms) {
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
      $predictions = $diseases->map(function ($disease) use ($selectedSymptoms) {
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
    // Get mapped specializations for the disease
    $specializations = $this->getSpecializationsForDisease($diseaseName);

    // Find doctors with matching specializations
    $doctors = User::where('role', 'doctor')
      ->whereHas('doctorProfile', function ($query) use ($specializations) {
        $query->where(function ($q) use ($specializations) {
          foreach ($specializations as $specialization) {
            $q->orWhere('specialization', 'LIKE', '%' . $specialization . '%');
          }
        });
      })
      ->with(['doctorProfile', 'ratings'])
      ->withAvg('ratings', 'rating')
      ->orderByDesc('ratings_avg_rating')
      ->get();

    return view('patient.specialists', compact('doctors', 'diseaseName'));
  }

  private function getSpecializationsForDisease($diseaseName)
  {
    // Disease to specialization mapping
    $diseaseSpecializations = [
      'Fungal infection' => ['Dermatology', 'Infectious Disease'],
      'GERD' => ['Gastroenterology'],
      'Chronic cholestasis' => ['Gastroenterology', 'Hepatology'],
      'Drug Reaction' => ['Allergy and Immunology', 'Dermatology'],
      'Peptic ulcer disease' => ['Gastroenterology'],
      'AIDS' => ['Infectious Disease', 'Internal Medicine'],
      'Diabetes' => ['Endocrinology', 'Internal Medicine'],
      'Gastroenteritis' => ['Gastroenterology'],
      'Bronchial Asthma' => ['Pulmonology', 'Allergy and Immunology'],
      'Hypertension' => ['Cardiology', 'Internal Medicine'],
      'Migraine' => ['Neurology'],
      'Cervical spondylosis' => ['Orthopedics', 'Neurology'],
      'Paralysis (brain hemorrhage)' => ['Neurology', 'Neurosurgery'],
      'Jaundice' => ['Gastroenterology', 'Hepatology'],
      'Malaria' => ['Infectious Disease'],
      'Chicken pox' => ['Infectious Disease', 'Dermatology'],
      'Dengue' => ['Infectious Disease'],
      'Typhoid' => ['Infectious Disease'],
      'Hepatitis A' => ['Gastroenterology', 'Hepatology'],
      'Hepatitis B' => ['Gastroenterology', 'Hepatology'],
      'Hepatitis C' => ['Gastroenterology', 'Hepatology'],
      'Hepatitis D' => ['Gastroenterology', 'Hepatology'],
      'Hepatitis E' => ['Gastroenterology', 'Hepatology'],
      'Alcoholic hepatitis' => ['Gastroenterology', 'Hepatology'],
      'Tuberculosis' => ['Pulmonology', 'Infectious Disease'],
      'Common Cold' => ['General Medicine', 'ENT'],
      'Pneumonia' => ['Pulmonology', 'Infectious Disease'],
      'Dimorphic hemmorhoids(piles)' => ['Gastroenterology', 'Colorectal Surgery'],
      'Heart attack' => ['Cardiology', 'Emergency Medicine'],
      'Varicose veins' => ['Vascular Surgery', 'Cardiovascular'],
      'Hypothyroidism' => ['Endocrinology'],
      'Hypoglycemia' => ['Endocrinology'],
      'Osteoarthritis' => ['Orthopedics', 'Rheumatology'],
      'Arthritis' => ['Orthopedics', 'Rheumatology'],
      '(vertigo) Paroymsal Positional Vertigo' => ['ENT', 'Neurology'],
      'Acne' => ['Dermatology'],
      'Urinary tract infection' => ['Urology', 'Internal Medicine'],
      'Psoriasis' => ['Dermatology'],
      'Impetigo' => ['Dermatology', 'Infectious Disease']
    ];

    return $diseaseSpecializations[$diseaseName] ?? ['General Medicine', 'Internal Medicine'];
  }
}
