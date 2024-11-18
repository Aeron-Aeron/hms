<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class DoctorDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get today's appointments
        $todayAppointments = Appointment::where('doctor_id', $user->id)
            ->whereDate('scheduled_time', today())
            ->with('patient')
            ->get();

        // Get pending appointments
        $pendingAppointments = Appointment::where('doctor_id', $user->id)
            ->where('status', 'pending')
            ->with('patient')
            ->get();

        // Get upcoming appointments
        $upcomingAppointments = Appointment::where('doctor_id', $user->id)
            ->where('status', 'accepted')
            ->whereDate('scheduled_time', '>', today())
            ->with('patient')
            ->get();

        // Get statistics
        $stats = [
            'total_appointments' => Appointment::where('doctor_id', $user->id)->count(),
            'pending_appointments' => Appointment::where('doctor_id', $user->id)->where('status', 'pending')->count(),
            'today_appointments' => Appointment::where('doctor_id', $user->id)->whereDate('scheduled_time', today())->count(),
            'completed_appointments' => Appointment::where('doctor_id', $user->id)->where('status', 'completed')->count(),
        ];

        return view('doctor.dashboard', compact(
            'todayAppointments',
            'pendingAppointments',
            'upcomingAppointments',
            'stats'
        ));
    }
}
