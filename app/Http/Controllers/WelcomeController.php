<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $featuredDoctors = User::where('role', 'doctor')
            ->with('doctorProfile')
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->orderByDesc('ratings_avg_rating')
            ->take(3)
            ->get();

        $testimonials = Testimonial::with('user')
            ->latest()
            ->take(3)
            ->get();

        return view('welcome', compact('featuredDoctors', 'testimonials'));
    }
}
