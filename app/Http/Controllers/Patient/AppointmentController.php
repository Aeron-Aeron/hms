<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the appointments.
     */
    public function index()
    {
        $appointments = Appointment::where('patient_id', auth()->id())
            ->with('doctor')
            ->latest()
            ->paginate(10);

        return view('patient.appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new appointment.
     */
    public function create()
    {
        $doctors = User::where('role', 'doctor')
            ->with('doctorProfile')
            ->get();

        return view('patient.appointments.create', compact('doctors'));
    }

    /**
     * Store a newly created appointment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'scheduled_time' => 'required|date|after:now',
            'patient_notes' => 'nullable|string|max:1000',
        ]);

        $appointment = Appointment::create([
            'patient_id' => auth()->id(),
            'doctor_id' => $validated['doctor_id'],
            'scheduled_time' => $validated['scheduled_time'],
            'patient_notes' => $validated['patient_notes'],
            'status' => 'pending',
        ]);

        return redirect()
            ->route('patient.appointments.show', $appointment)
            ->with('success', 'Appointment scheduled successfully!');
    }

    /**
     * Display the specified appointment.
     */
    public function show(Appointment $appointment)
    {
        // Check if the appointment belongs to the authenticated patient
        if ($appointment->patient_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('patient.appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment.
     */
    public function edit(Appointment $appointment)
    {
        // Check if the appointment belongs to the authenticated patient
        if ($appointment->patient_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $doctors = User::where('role', 'doctor')
            ->with('doctorProfile')
            ->get();

        return view('patient.appointments.edit', compact('appointment', 'doctors'));
    }

    /**
     * Update the specified appointment in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        // Check if the appointment belongs to the authenticated patient
        if ($appointment->patient_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'scheduled_time' => 'required|date|after:now',
            'patient_notes' => 'nullable|string|max:1000',
        ]);

        $appointment->update($validated);

        return redirect()
            ->route('patient.appointments.show', $appointment)
            ->with('success', 'Appointment updated successfully!');
    }

    /**
     * Remove the specified appointment from storage.
     */
    public function destroy(Appointment $appointment)
    {
        // Check if the appointment belongs to the authenticated patient
        if ($appointment->patient_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $appointment->delete();

        return redirect()
            ->route('patient.appointments.index')
            ->with('success', 'Appointment cancelled successfully!');
    }

    /**
     * Cancel the specified appointment.
     */
    public function cancel(Appointment $appointment)
    {
        // Check if the appointment belongs to the authenticated patient
        if ($appointment->patient_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $appointment->update(['status' => 'cancelled']);

        return redirect()
            ->route('patient.appointments.index')
            ->with('success', 'Appointment cancelled successfully!');
    }
}
