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
    $appointmentsQuery = $user->appointments();

    $appointments = (clone $appointmentsQuery)
      ->with(['doctor:id,name'])
      ->select(['id', 'doctor_id', 'patient_id', 'scheduled_time', 'status'])
      ->latest()
      ->take(5)
      ->get();

    // Get user's health problems
    $healthProblemsQuery = $user->healthProblems();

    $healthProblems = (clone $healthProblemsQuery)
      ->select(['id', 'user_id', 'title', 'description', 'created_at'])
      ->latest()
      ->take(5)
      ->get();

    // Get featured doctors (those with highest ratings)
    $featuredDoctors = User::where('role', 'doctor')
      ->select(['id', 'name'])
      ->with(['doctorProfile:id,user_id,specialization'])
      ->withAvg('ratings', 'rating')
      ->orderByDesc('ratings_avg_rating')
      ->take(4)
      ->get();

    $dashboardSummary = [
      'upcomingAppointments' => (clone $appointmentsQuery)
        ->where('scheduled_time', '>=', now())
        ->count(),
      'totalReports' => $healthProblemsQuery->count(),
      'doctorConnections' => $user->appointments()
        ->distinct('doctor_id')
        ->count('doctor_id'),
    ];

    // Get all symptoms for the symptom checker
    $symptoms = Symptom::orderBy('name')->get(['id', 'name']);

    return view('patient.dashboard', compact(
      'user',
      'appointments',
      'healthProblems',
      'featuredDoctors',
      'dashboardSummary',
      'symptoms'
    ));
  }

  public function checkSymptoms(Request $request)
  {
    try {
      $validated = $request->validate([
        'symptoms' => 'required|array|min:1',
        'symptoms.*' => 'exists:symptoms,id',
    'blood_pressure_systolic' => 'nullable|integer|between:50,250|required_with:blood_pressure_diastolic',
    'blood_pressure_diastolic' => 'nullable|integer|between:30,150|required_with:blood_pressure_systolic',
    'temperature' => 'nullable|numeric',
    'temperature_unit' => 'nullable|in:c,f|required_with:temperature',
    'weight' => 'nullable|numeric|between:1,500',
      ]);

      if (config('app.debug')) {
        \Log::debug('Selected symptoms', $validated['symptoms']);
      }

      $vitals = collect($validated)
        ->only([
          'blood_pressure_systolic',
          'blood_pressure_diastolic',
          'temperature',
          'temperature_unit',
          'weight',
        ])
        ->filter(fn($value) => $value !== null && $value !== '');

      if ($vitals->isNotEmpty() && config('app.debug')) {
        \Log::debug('Submitted vitals', $vitals->toArray());
      }

      $selectedSymptoms = collect($validated['symptoms']);

      // Get all diseases that have any of the selected symptoms
      $diseases = Disease::whereHas('symptoms', function ($query) use ($selectedSymptoms) {
        $query->whereIn('symptoms.id', $selectedSymptoms);
      })
        ->with(['symptoms:id'])
        ->get(['id', 'name']);

      if (config('app.debug')) {
        \Log::debug('Potential diseases found', ['count' => $diseases->count()]);
      }

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
        $totalSymptoms = max($diseaseSymptoms->count(), 1);

        return [
          'disease' => [
            'name' => $disease->name,
            'id' => $disease->id
          ],
          'match_percentage' => round(($matchingSymptoms->count() / $totalSymptoms) * 100, 2),
          'matching_symptoms' => $matchingSymptoms->count()
        ];
      })
        ->sortByDesc('match_percentage')
        ->take(5)
        ->values();

      if (config('app.debug')) {
        \Log::debug('Predictions generated', $predictions->toArray());
      }

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
