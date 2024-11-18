<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Appointment Details') }}
            </h2>
            <a href="{{ route('patient.appointments.index') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                <span>← Back to Appointments</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Appointment Status Banner -->
                    <div class="mb-6 p-4 rounded-lg
                        @if($appointment->status === 'completed') bg-green-100
                        @elseif($appointment->status === 'pending') bg-yellow-100
                        @elseif($appointment->status === 'cancelled') bg-red-100
                        @else bg-blue-100 @endif">
                        <div class="font-semibold text-lg mb-2">
                            Status: {{ ucfirst($appointment->status) }}
                        </div>
                        @if($appointment->status === 'rescheduled')
                            <div class="text-sm">
                                New Proposed Time: {{ $appointment->proposed_time->format('M d, Y h:i A') }}
                            </div>
                        @endif
                    </div>

                    <!-- Appointment Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Appointment Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="font-medium">Doctor:</span>
                                    <span class="ml-2">Dr. {{ $appointment->doctor->name }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Specialization:</span>
                                    <span class="ml-2">{{ $appointment->doctor->doctorProfile->specialization }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Scheduled Time:</span>
                                    <span class="ml-2">{{ $appointment->scheduled_time->format('M d, Y h:i A') }}</span>
                                </div>
                                @if($appointment->patient_notes)
                                    <div>
                                        <span class="font-medium">Your Notes:</span>
                                        <p class="mt-1 text-gray-600">{{ $appointment->patient_notes }}</p>
                                    </div>
                                @endif
                                @if($appointment->doctor_notes)
                                    <div>
                                        <span class="font-medium">Doctor's Notes:</span>
                                        <p class="mt-1 text-gray-600">{{ $appointment->doctor_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4">Actions</h3>
                            <div class="space-y-3">
                                @if($appointment->status === 'pending')
                                    <form action="{{ route('patient.appointments.destroy', $appointment) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                            Cancel Appointment
                                        </button>
                                    </form>
                                @endif

                                @if($appointment->status === 'rescheduled')
                                    <form action="{{ route('patient.appointments.update', $appointment) }}"
                                          method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="accept_reschedule" value="1">
                                        <button type="submit"
                                                class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                            Accept New Time
                                        </button>
                                    </form>
                                @endif

                                @if($appointment->status === 'completed')
                                    <a href="{{ route('patient.appointments.create', ['doctor' => $appointment->doctor_id]) }}"
                                       class="block text-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Book Another Appointment
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
