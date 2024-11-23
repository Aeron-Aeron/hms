<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Disease;
use App\Models\Symptom;
use Illuminate\Http\Request;

class DiseasePredictorController extends Controller
{
    public function index()
    {
        $symptoms = Symptom::orderBy('name')->get();
        return view('patient.disease-predictor.index', compact('symptoms'));
    }

    public function predict(Request $request)
    {
        $validated = $request->validate([
            'symptoms' => 'required|array|min:1',
            'symptoms.*' => 'exists:symptoms,id'
        ]);

        $selectedSymptoms = collect($validated['symptoms']);

        // Get all diseases that have any of the selected symptoms
        $diseases = Disease::whereHas('symptoms', function($query) use ($selectedSymptoms) {
            $query->whereIn('symptoms.id', $selectedSymptoms);
        })->with('symptoms')->get();

        // Calculate match percentage for each disease
        $predictions = $diseases->map(function($disease) use ($selectedSymptoms) {
            $diseaseSymptoms = $disease->symptoms->pluck('id');
            $matchingSymptoms = $diseaseSymptoms->intersect($selectedSymptoms);

            return [
                'disease' => $disease,
                'match_percentage' => round(($matchingSymptoms->count() / $diseaseSymptoms->count()) * 100, 2),
                'matching_symptoms' => $matchingSymptoms->count()
            ];
        })->sortByDesc('match_percentage')
          ->take(5);  // Get top 5 matches

        return view('patient.disease-predictor.results', compact('predictions'));
    }
}
