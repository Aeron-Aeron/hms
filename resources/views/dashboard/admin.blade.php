@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-gray-500 text-sm">Total Doctors</h3>
            <p class="text-3xl font-bold">{{ $stats['doctors'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-gray-500 text-sm">Total Patients</h3>
            <p class="text-3xl font-bold">{{ $stats['patients'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-gray-500 text-sm">Total Appointments</h3>
            <p class="text-3xl font-bold">{{ $stats['appointments'] }}</p>
        </div>
    </div>

    <!-- Doctor Management -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Manage Doctors</h2>
            <button onclick="window.location.href='{{ route('doctors.create') }}'"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Add New Doctor
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Specialization</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($doctors as $doctor)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $doctor->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $doctor->doctorProfile->specialization }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('doctors.edit', $doctor) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Appointments -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Recent Appointments</h2>
        <div class="space-y-4">
            @foreach($recentAppointments as $appointment)
            <div class="border p-4 rounded-lg">
                <div class="flex justify-between">
                    <div>
                        <h3 class="font-semibold">Patient: {{ $appointment->patient->name }}</h3>
                        <p class="text-sm text-gray-600">Doctor: Dr. {{ $appointment->doctor->name }}</p>
                        <p class="text-sm text-gray-600">{{ $appointment->appointment_date->format('M d, Y h:i A') }}</p>
                    </div>
                    <span class="px-2 py-1 rounded text-sm
                        {{ $appointment->status === 'approved' ? 'bg-green-100 text-green-800' :
                           ($appointment->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst($appointment->status) }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
