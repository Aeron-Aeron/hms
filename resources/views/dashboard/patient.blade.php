@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Symptom Input Section -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Find a Doctor</h2>
        <form action="{{ route('doctors.search') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Describe your symptoms</label>
                    <textarea name="symptoms" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Find Recommended Doctors
                </button>
            </div>
        </form>
    </div>

    <!-- Appointments Section -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Your Appointments</h2>
        <div class="space-y-4">
            @forelse($appointments as $appointment)
                <div class="border p-4 rounded-lg {{ $appointment->status === 'approved' ? 'border-green-500' : 'border-gray-200' }}">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold">Dr. {{ $appointment->doctor->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $appointment->appointment_date->format('M d, Y h:i A') }}</p>
                            <p class="text-sm text-gray-600">Status:
                                <span class="font-medium
                                    {{ $appointment->status === 'approved' ? 'text-green-600' :
                                       ($appointment->status === 'rejected' ? 'text-red-600' : 'text-yellow-600') }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">No appointments scheduled yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
