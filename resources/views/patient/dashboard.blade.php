<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Add this section at the top of your dashboard -->
            <div class="mb-8">
                <a href="{{ route('patient.doctors.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd" />
                    </svg>
                    View All Doctors
                </a>
            </div>
            <!-- Featured Doctors Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">Featured Doctors</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($featuredDoctors as $doctor)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="font-semibold text-lg mb-2">Dr. {{ $doctor->name }}</div>
                                <div class="text-gray-600 mb-2">{{ $doctor->doctorProfile->specialization }}</div>
                                <div class="text-sm text-gray-500 mb-4">
                                    Rating: {{ number_format($doctor->ratings_avg_rating ?? 0, 1) }} ⭐
                                </div>
                                <a href="{{ route('patient.doctors.show', $doctor) }}"
                                   class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    View Profile
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Appointments Section -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Recent Appointments</h3>
                    <div class="space-x-4">
                        <a href="{{ route('patient.appointments.create') }}"
                           class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Book New Appointment
                        </a>
                        <a href="{{ route('patient.appointments.index') }}"
                           class="text-blue-600 hover:text-blue-500">
                            View All
                        </a>
                    </div>
                </div>
                @if($appointments->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            @foreach($appointments as $appointment)
                                <div class="mb-4 last:mb-0 pb-4 last:pb-0 border-b last:border-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="font-semibold">Dr. {{ $appointment->doctor->name }}</div>
                                            <div class="text-sm text-gray-600">
                                                {{ $appointment->scheduled_time->format('M d, Y h:i A') }}
                                            </div>
                                        </div>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($appointment->status === 'completed') bg-green-100 text-green-800
                                            @elseif($appointment->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-blue-100 text-blue-800 @endif">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </div>
                                    <a href="{{ route('patient.appointments.show', $appointment) }}"
                                       class="text-blue-600 hover:text-blue-900">View Details</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <p class="text-gray-500 mb-4">No appointments scheduled yet.</p>
                        <a href="{{ route('patient.appointments.create') }}"
                           class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Schedule Your First Appointment
                        </a>
                    </div>
                @endif
            </div>

            <!-- Recent Health Problems -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Recent Health Problems</h3>
                        <a href="{{ route('patient.health-problems.index') }}" class="text-blue-600 hover:text-blue-500">View All</a>
                    </div>
                    @if($healthProblems->count() > 0)
                        <div class="space-y-4">
                            @foreach($healthProblems as $problem)
                            <div class="border rounded-lg p-4">
                                <div class="font-semibold mb-2">{{ $problem->title }}</div>
                                <div class="text-sm text-gray-600 mb-2">{{ Str::limit($problem->description, 150) }}</div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">{{ $problem->created_at->format('M d, Y') }}</span>
                                    <a href="{{ route('patient.health-problems.show', $problem) }}"
                                       class="text-blue-600 hover:text-blue-900">View Details</a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No health problems reported yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
