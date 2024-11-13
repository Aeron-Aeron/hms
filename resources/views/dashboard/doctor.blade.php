@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-gray-500 text-sm">Pending Appointments</h3>
            <p class="text-3xl font-bold">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-gray-500 text-sm">Approved Appointments</h3>
            <p class="text-3xl font-bold">{{ $stats['approved'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-gray-500 text-sm">Total Appointments</h3>
            <p class="text-3xl font-bold">{{ $stats['total'] }}</p>
        </div>
    </div>

    <!-- Availability Management -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Manage Availability</h2>
        <form action="{{ route('doctor.availability.update') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">{{ $day }}</label>
                    <div class="flex space-x-2">
                        <input type="time" name="schedule[{{ $day }}][start]" class="form-input rounded-md shadow-sm">
                        <input type="time" name="schedule[{{ $day }}][end]" class="form-input rounded-md shadow-sm">
                    </div>
                </div>
                @endforeach
            </div>
            <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Update Availability
            </button>
        </form>
    </div>

    <!-- Appointments List -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Appointments</h2>
        <div class="space-y-4">
            @forelse($appointments as $appointment)
            <div class="border p-4 rounded-lg">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-semibold">{{ $appointment->patient->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $appointment->appointment_date->format('M d, Y h:i A') }}</p>
                        <p class="text-sm text-gray-600 mt-2">Symptoms: {{ $appointment->symptoms }}</p>
                    </div>
                    @if($appointment->status === 'pending')
                    <div class="flex space-x-2">
                        <form action="{{ route('appointments.updateStatus', $appointment) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="approved">
                            <button class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                Approve
                            </button>
                        </form>
                        <form action="{{ route('appointments.updateStatus', $appointment) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="rejected">
                            <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                Reject
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <p class="text-gray-500">No appointments found.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
