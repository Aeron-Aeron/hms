<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Book New Appointment') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
          <form method="POST" action="{{ route('patient.appointments.store') }}">
            @csrf

            <!-- Doctor Selection -->
            <div class="mb-4">
              <label for="doctor_id" class="block text-sm font-medium text-gray-700">Select Doctor</label>
              <select name="doctor_id" id="doctor_id" required v
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Choose a doctor</option>
                @foreach($doctors as $doctor)
                <option value="{{ $doctor->id }}"
                  {{ (old('doctor_id', request('doctor_id', request('doctor'))) == $doctor->id) ? 'selected' : '' }}>
                  Dr. {{ $doctor->name }} - {{ $doctor->doctorProfile->specialization }}
                </option>

                @endforeach
              </select>
              @error('doctor_id')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <!-- Appointment Date and Time -->
            <div class="mb-4">
              <label for="scheduled_time" class="block text-sm font-medium text-gray-700">
                Preferred Date and Time
              </label>
              <input type="datetime-local"
                name="scheduled_time"
                id="scheduled_time"
                value="{{ old('scheduled_time') }}"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
              @error('scheduled_time')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <!-- Patient Notes -->
            <div class="mb-4">
              <label for="patient_notes" class="block text-sm font-medium text-gray-700">
                Notes (Optional)
              </label>
              <textarea name="patient_notes"
                id="patient_notes"
                rows="4"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="Describe your symptoms or reason for visit">{{ old('patient_notes') }}</textarea>
              @error('patient_notes')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div class="flex items-center justify-end mt-4">
              <a href="{{ route('patient.appointments.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                Cancel
              </a>
              <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                Book Appointment
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>