<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_doctors' => User::where('role', 'doctor')->count(),
            'total_patients' => User::where('role', 'patient')->count(),
            'total_appointments' => Appointment::count(),
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
        ];

        $recent_appointments = Appointment::with(['patient', 'doctor'])
            ->latest()
            ->take(5)
            ->get();

        $users = User::latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_appointments', 'users'));
    }
}
