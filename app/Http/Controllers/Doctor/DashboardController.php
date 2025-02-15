<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_appointments' => Appointment::where('doctor_id', $user->id)->count(),
            'pending_appointments' => Appointment::where('doctor_id', $user->id)->where('status', 'pending')->count(),
            'today_appointments' => Appointment::where('doctor_id', $user->id)->whereDate('scheduled_time', today())->count(),
            'completed_appointments' => Appointment::where('doctor_id', $user->id)->where('status', 'completed')->count(),
        ];

        $todayAppointments = Appointment::where('doctor_id', $user->id)
            ->whereDate('scheduled_time', today())
            ->with('patient')
            ->get();

        $pendingAppointments = Appointment::where('doctor_id', $user->id)
            ->where('status', 'pending')
            ->with('patient')
            ->get();

        $upcomingAppointments = Appointment::where('doctor_id', $user->id)
            ->where('status', 'accepted')
            ->whereDate('scheduled_time', '>', today())
            ->with('patient')
            ->take(5)
            ->get();

        return view('doctor.dashboard', compact(
            'stats',
            'todayAppointments',
            'pendingAppointments',
            'upcomingAppointments'
        ));
    }
}
