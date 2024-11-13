<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DoctorSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = User::where('role', 'doctor')
            ->with(['doctorProfile', 'ratings']);

        if ($request->specialization) {
            $query->whereHas('doctorProfile', function ($q) use ($request) {
                $q->where('specialization', 'like', "%{$request->specialization}%");
            });
        }

        if ($request->rating) {
            $query->whereHas('ratings', function ($q) use ($request) {
                $q->havingRaw('AVG(rating) >= ?', [$request->rating]);
            });
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
