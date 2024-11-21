<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Appointment Details') }}
            </h2>
            <a href="{{ route('doctor.dashboard') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                <span>← Back to Appointments</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Status Banner -->
                    <div class="mb-6 p-4 rounded-lg
                        @if($appointment->status === 'completed') bg-green-100
                        @elseif($appointment->status === 'pending') bg-yellow-100
                        @elseif($appointment->status === 'cancelled') bg-red-100
                        @else bg-blue-100 @endif">
                        <div class="font-semibold text-lg">
                            Status: {{ ucfirst($appointment->status) }}
                        </div>
                    </div>

                    <!-- Appointment Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Patient Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="font-medium">Name:</span>
                                    <span class="ml-2">{{ $appointment->patient->name }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Email:</span>
                                    <span class="ml-2">{{ $appointment->patient->email }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Scheduled Time:</span>
                                    <span class="ml-2">{{ $appointment->scheduled_time->format('M d, Y h:i A') }}</span>
                                </div>
                                @if($appointment->patient_notes)
                                    <div>
                                        <span class="font-medium">Patient Notes:</span>
                                        <p class="mt-1 text-gray-600">{{ $appointment->patient_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4">Actions</h3>
                            <div class="space-y-3">
                                @if($appointment->status === 'pending')
                                    <form action="{{ route('doctor.appointments.updateStatus', $appointment) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="accepted">
                                        <button type="submit"
                                                class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mb-2">
                                            Accept Appointment
                                        </button>
                                    </form>

                                    <button onclick="showRescheduleModal({{ $appointment->id }})"
                                            class="w-full bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mb-2">
                                        Reschedule
                                    </button>

                                    <form action="{{ route('doctor.appointments.updateStatus', $appointment) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="declined">
                                        <button type="submit"
                                                class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                            Decline Appointment
                                        </button>
                                    </form>
                                @endif

                                @if($appointment->status === 'accepted')
                                    <form action="{{ route('doctor.appointments.updateStatus', $appointment) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit"
                                                class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                            Mark as Completed
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reschedule Modal -->
    @include('doctor.appointments.partials.reschedule-modal')
</x-app-layout>
