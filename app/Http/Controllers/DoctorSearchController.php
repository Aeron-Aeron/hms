<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DoctorSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = User::where('role', 'doctor')
            ->with(['doctorProfile'])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings');

        if ($request->specialization) {
            $query->whereHas('doctorProfile', function ($q) use ($request) {
                $q->where('specialization', 'like', "%{$request->specialization}%");
            });
        }

        if ($request->rating) {
            // Use a subquery to filter by average rating to keep compatibility with pagination
            $min = (float) $request->rating;
            $query->whereRaw('(SELECT COALESCE(AVG(rating),0) FROM doctor_ratings WHERE doctor_ratings.doctor_id = users.id) >= ?', [$min]);
        }

        if ($request->available_day) {
            $query->whereHas('availabilities', function ($q) use ($request) {
                $q->where('day_of_week', $request->available_day);
            });
        }

        $doctors = $query->paginate(10);

        return view('doctors.search-results', compact('doctors'));
    }
}
