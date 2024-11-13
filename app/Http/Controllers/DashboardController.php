<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function patientDashboard()
    {
        $appointments = auth()->user()->appointments()->with('doctor')->latest()->get();
        $doctors = User::where('role', 'doctor')
            ->with('doctorProfile')
            ->get();

        return view('dashboard.patient', compact('appointments', 'doctors'));
    }

    public function doctorDashboard()
    {
        $appointments = auth()->user()->doctorAppointments()
            ->with('patient')
            ->latest()
            ->get();

        $stats = [
            'pending' => $appointments->where('status', 'pending')->count(),
            'approved' => $appointments->where('status', 'approved')->count(),
            'total' => $appointments->count(),
        ];

        return view('dashboard.doctor', compact('appointments', 'stats'));
    }

    public function adminDashboard()
    {
        $stats = [
            'doctors' => User::where('role', 'doctor')->count(),
            'patients' => User::where('role', 'patient')->count(),
            'appointments' => Appointment::count(),
        ];

        $recentAppointments = Appointment::with(['doctor', 'patient'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.admin', compact('stats', 'recentAppointments'));
    }
}
