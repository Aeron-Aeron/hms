<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Doctor Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900 text-xl">{{ $stats['total_appointments'] }}</div>
                    <div class="text-gray-600">Total Appointments</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900 text-xl">{{ $stats['pending_appointments'] }}</div>
                    <div class="text-gray-600">Pending Appointments</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900 text-xl">{{ $stats['today_appointments'] }}</div>
                    <div class="text-gray-600">Today's Appointments</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900 text-xl">{{ $stats['completed_appointments'] }}</div>
                    <div class="text-gray-600">Completed Appointments</div>
                </div>
            </div>

            <!-- Today's Appointments -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Today's Appointments</h3>
                    @if($todayAppointments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($todayAppointments as $appointment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $appointment->scheduled_time->format('H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $appointment->patient->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($appointment->status === 'completed') bg-green-100 text-green-800
                                                    @elseif($appointment->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800
                                                    @else bg-blue-100 text-blue-800 @endif">
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('doctor.appointments.show', $appointment) }}"
                                                   class="text-blue-600 hover:text-blue-900">View Details</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">No appointments scheduled for today.</p>
                    @endif
                </div>
            </div>

            <!-- Pending Appointments -->

@if($pendingAppointments->count() > 0)
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4">Pending Appointments</h3>
            <div class="space-y-4">
                @foreach($pendingAppointments as $appointment)
                    <div class="border rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="font-semibold">{{ $appointment->patient->name }}</div>
                                <div class="text-sm text-gray-600">
                                    {{ $appointment->scheduled_time->format('M d, Y H:i') }}
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <form action="{{ route('doctor.appointments.updateStatus', $appointment) }}"
                                      method="POST"
                                      class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="accepted">
                                    <button type="submit"
                                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Accept
                                    </button>
                                </form>

                                <button onclick="showRescheduleModal({{ $appointment->id }})"
                                        type="button"
                                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                    Reschedule
                                </button>

                                <form action="{{ route('doctor.appointments.updateStatus', $appointment) }}"
                                      method="POST"
                                      class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="declined">
                                    <button type="submit"
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        Decline
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@else
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4">Pending Appointments</h3>
            <p class="text-gray-500">No pending appointments.</p>
        </div>
    </div>
@endif

            <!-- Upcoming Appointments -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Upcoming Appointments</h3>
                        <a href="{{ route('doctor.appointments.index') }}" class="text-blue-600 hover:text-blue-500">View All</a>
                    </div>
                    @if($upcomingAppointments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($upcomingAppointments as $appointment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $appointment->scheduled_time->format('M d, Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $appointment->patient->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('doctor.appointments.show', $appointment) }}"
                                                   class="text-blue-600 hover:text-blue-900">View Details</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">No upcoming appointments.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Reschedule Modal -->
    <div id="rescheduleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white" onclick="event.stopPropagation()">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Reschedule Appointment</h3>
                    <button type="button" onclick="closeRescheduleModal()" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="rescheduleForm" method="POST" onsubmit="return confirmReschedule(event)">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="appointment_id" id="appointment_id">
                    <div class="mb-4">
                        <label for="proposed_time" class="block text-sm font-medium text-gray-700">New Date & Time</label>
                        <input type="datetime-local"
                               name="proposed_time"
                               id="proposed_time"
                               required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('proposed_time')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button"
                                onclick="closeRescheduleModal()"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancel
                        </button>
                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Propose New Time
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Add this function to handle opening the modal
        function showRescheduleModal(appointmentId) {
            // Get the modal and form elements
            const modal = document.getElementById('rescheduleModal');
            const form = document.getElementById('rescheduleForm');

            // Set the form action URL
            form.action = `/doctor/appointments/${appointmentId}/reschedule`;

            // Show the modal
            modal.classList.remove('hidden');

            // Set minimum date to today
            const dateInput = document.getElementById('proposed_time');
            const today = new Date();
            const formattedDate = today.toISOString().slice(0, 16);
            dateInput.min = formattedDate;
        }

        // Add this function to handle closing the modal
        function closeRescheduleModal() {
            const modal = document.getElementById('rescheduleModal');
            modal.classList.add('hidden');
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('rescheduleModal');
            if (event.target === modal) {
                closeRescheduleModal();
            }
        });

        // Existing confirmReschedule function
        function confirmReschedule(event) {
            event.preventDefault();
            if (confirm('Are you sure you want to reschedule this appointment?')) {
                event.target.submit();
            }
        }
    </script>
    @endpush
</x-app-layout>
