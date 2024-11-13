@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6">Book Appointment with Dr. {{ $doctor->name }}</h2>

        <div class="mb-6">
            <div class="flex items-center mb-4">
                @if($doctor->doctorProfile->profile_image)
                    <img src="{{ Storage::url($doctor->doctorProfile->profile_image) }}"
                         class="w-16 h-16 rounded-full object-cover mr-4">
                @endif
                <div>
                    <h3 class="font-semibold">{{ $doctor->doctorProfile->specialization }}</h3>
                    <p class="text-gray-600">Consultation Fee: ${{ $doctor->doctorProfile->consultation_fee }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('appointments.store') }}" method="POST">
            @csrf
            <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

            <!-- Date Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Date</label>
                <div class="grid grid-cols-7 gap-2">
                    @foreach($availableDates as $date)
                        <button type="button"
                                class="date-selector p-2 text-center rounded-md border
                                       {{ $loop->first ? 'bg-blue-50 border-blue-500' : 'border-gray-300' }}"
                                data-date="{{ $date->format('Y-m-d') }}">
                            <div class="text-xs text-gray-600">{{ $date->format('M') }}</div>
                            <div class="font-semibold">{{ $date->format('d') }}</div>
                            <div class="text-xs">{{ $date->format('D') }}</div>
                        </button>
                    @endforeach
                </div>
                <input type="hidden" name="appointment_date" id="selected_date">
            </div>

            <!-- Time Slots -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Time</label>
                <div class="grid grid-cols-3 gap-2" id="time-slots">
                    @foreach($timeSlots as $slot)
                        <button type="button"
                                class="time-selector p-2 text-center rounded-md border border-gray-300
                                       {{ $slot->is_available ? 'hover:bg-blue-50' : 'bg-gray-100 cursor-not-allowed' }}"
                                {{ !$slot->is_available ? 'disabled' : '' }}
                                data-slot-id="{{ $slot->id }}">
                            {{ $slot->formatted_time }}
                        </button>
                    @endforeach
                </div>
                <input type="hidden" name="time_slot_id" id="selected_time_slot">
            </div>

            <!-- Symptoms -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Describe Your Symptoms</label>
                <textarea name="symptoms" rows="4" required
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"></textarea>
            </div>

            <button type="submit"
                    class="w-full bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Book Appointment
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Date selection
    const dateButtons = document.querySelectorAll('.date-selector');
    dateButtons.forEach(button => {
        button.addEventListener('click', function() {
            dateButtons.forEach(btn => btn.classList.remove('bg-blue-50', 'border-blue-500'));
            this.classList.add('bg-blue-50', 'border-blue-500');
            document.getElementById('selected_date').value = this.dataset.date;
            fetchTimeSlots(this.dataset.date);
        });
    });

    // Time slot selection
    document.getElementById('time-slots').addEventListener('click', function(e) {
        if (e.target.classList.contains('time-selector') && !e.target.disabled) {
            document.querySelectorAll('.time-selector').forEach(btn =>
                btn.classList.remove('bg-blue-50', 'border-blue-500'));
            e.target.classList.add('bg-blue-50', 'border-blue-500');
            document.getElementById('selected_time_slot').value = e.target.dataset.slotId;
        }
    });
});

function fetchTimeSlots(date) {
    fetch(`/api/doctors/${doctorId}/time-slots?date=${date}`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('time-slots');
            container.innerHTML = data.map(slot => `
                <button type="button"
                        class="time-selector p-2 text-center rounded-md border border-gray-300
                               ${slot.is_available ? 'hover:bg-blue-50' : 'bg-gray-100 cursor-not-allowed'}"
                        ${!slot.is_available ? 'disabled' : ''}
                        data-slot-id="${slot.id}">
                    ${slot.formatted_time}
                </button>
            `).join('');
        });
}
</script>
@endpush
@endsection
