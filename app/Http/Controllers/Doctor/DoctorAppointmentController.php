<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class DoctorAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::where('doctor_id', auth()->id())
            ->with('patient');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $appointments = $query->latest()->paginate(10);

        return view('doctor.appointments.index', compact('appointments'));
    }

    public function show(Appointment $appointment)
    {
        if ($appointment->doctor_id !== auth()->id()) {
            abort(403);
        }

        return view('doctor.appointments.show', compact('appointment'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:accepted,declined,completed'
        ]);

        $appointment->update([
            'status' => $request->status
        ]);

        return redirect()->route('doctor.appointments.show', $appointment)
            ->with('success', 'Appointment status updated successfully.');
    }

    public function reschedule(Request $request, Appointment $appointment)
    {
        $request->validate([
            'proposed_time' => 'required|date|after:now',
        ]);

        $appointment->update([
            'scheduled_time' => $request->proposed_time,
            'status' => 'rescheduled'
        ]);

        return redirect()->back()->with('success', 'Appointment rescheduled successfully.');
    }
}
