<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AdminAppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['patient', 'doctor'])
            ->latest()
            ->paginate(10);

        return view('admin.appointments.index', compact('appointments'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,completed,cancelled'
        ]);

        $appointment->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Appointment status updated successfully.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor']);

        return view('admin.appointments.show', compact('appointment'));
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return back()->with('success', 'Appointment deleted successfully.');
    }
}
