@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6">Create Prescription</h2>

        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <h3 class="font-semibold mb-2">Appointment Details</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Patient Name</p>
                    <p class="font-medium">{{ $appointment->patient->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Appointment Date</p>
                    <p class="font-medium">{{ $appointment->appointment_date->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('prescriptions.store') }}" method="POST">
            @csrf
            <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

            <!-- Diagnosis -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Diagnosis</label>
                <textarea name="diagnosis" rows="3" required
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"></textarea>
            </div>

            <!-- Medicines -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Medicines</label>
                <div id="medicine-list" class="space-y-4">
                    <div class="medicine-item grid grid-cols-6 gap-4">
                        <div class="col-span-2">
                            <input type="text" name="medicines[]" placeholder="Medicine name"
                                   class="w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <input type="text" name="dosage[]" placeholder="Dosage"
                                   class="w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <input type="text" name="frequency[]" placeholder="Frequency"
                                   class="w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <input type="text" name="duration[]" placeholder="Duration"
                                   class="w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <button type="button" class="text-red-600 hover:text-red-800"
                                    onclick="removeMedicine(this)">Remove</button>
                        </div>
                    </div>
                </div>
                <button type="button"
                        onclick="addMedicine()"
                        class="mt-2 text-blue-600 hover:text-blue-800">
                    + Add Medicine
                </button>
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                <textarea name="notes" rows="3"
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"></textarea>
            </div>

            <button type="submit"
                    class="w-full bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                Create Prescription
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function addMedicine() {
    const template = document.querySelector('.medicine-item').cloneNode(true);
    template.querySelectorAll('input').forEach(input => input.value = '');
    document.getElementById('medicine-list').appendChild(template);
}

function removeMedicine(button) {
    const items = document.querySelectorAll('.medicine-item');
    if (items.length > 1) {
        button.closest('.medicine-item').remove();
    }
}
</script>
@endpush
@endsection
