<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total_doctors' => User::where('role', 'doctor')->count(),
            'total_patients' => User::where('role', 'patient')->count(),
            'total_appointments' => Appointment::count(),
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
        ];

        $recent_appointments = Appointment::with(['doctor', 'patient'])
            ->latest()
            ->take(5)
            ->get();

        $usersQuery = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $usersQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        }

        if ($request->view !== 'all') {
            $users = $usersQuery->latest()->take(5)->get();
        } else {
            $users = $usersQuery->latest()->paginate(10)->withQueryString();
        }

        return view('admin.dashboard', compact('stats', 'recent_appointments', 'users'));
    }
}
