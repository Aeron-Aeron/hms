<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Patient Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <a href="{{ route('patient.appointments.create') }}"
                   class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-gray-50">
                    <div class="text-blue-600 text-lg mb-2">Book Appointment</div>
                    <div class="text-gray-600">Schedule a new appointment with a doctor</div>
                </a>
                <a href="{{ route('patient.health-problems.create') }}"
                   class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-gray-50">
                    <div class="text-blue-600 text-lg mb-2">Report Health Problem</div>
                    <div class="text-gray-600">Document your health concerns</div>
                </a>
                <a href="{{ route('patient.doctors.recommended') }}"
                   class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-gray-50">
                    <div class="text-blue-600 text-lg mb-2">Find Doctors</div>
                    <div class="text-gray-600">Browse recommended doctors</div>
                </a>
            </div>

            <!-- Upcoming Appointments -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Your Appointments</h3>
                        <a href="{{ route('patient.appointments.index') }}" class="text-blue-600 hover:text-blue-500">View All</a>
                    </div>
                    @if($appointments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($appointments as $appointment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">Dr. {{ $appointment->doctor->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->scheduled_time->format('M d, Y H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($appointment->status === 'completed') bg-green-100 text-green-800
                                                @elseif($appointment->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800
                                                @else bg-blue-100 text-blue-800 @endif">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('patient.appointments.show', $appointment) }}"
                                               class="text-blue-600 hover:text-blue-900">View Details</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">No appointments scheduled yet.</p>
                    @endif
                </div>
            </div>

            <!-- Recommended Doctors -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Recommended Doctors</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($recommendedDoctors as $doctor)
                        <div class="border rounded-lg p-4">
                            <div class="font-semibold mb-2">Dr. {{ $doctor->name }}</div>
                            <div class="text-sm text-gray-600 mb-2">{{ $doctor->doctorProfile->specialization }}</div>
                            <div class="flex items-center mb-2">
                                <div class="text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($doctor->ratings_avg_rating))
                                            ★
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-600 ml-2">
                                    {{ number_format($doctor->ratings_avg_rating, 1) }} / 5.0
                                </span>
                            </div>
                            <a href="{{ route('patient.appointments.create', ['doctor' => $doctor->id]) }}"
                               class="block text-center bg-blue-600 text-white rounded-md px-4 py-2 hover:bg-blue-500">
                                Book Appointment
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
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
