<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Doctor Profile') }}
            </h2>
            <a href="{{ url()->previous() }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                <span>← Back</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Doctor Information -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Doctor Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="font-medium">Name:</span>
                                    <span class="ml-2">Dr. {{ $doctor->name }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Specialization:</span>
                                    <span class="ml-2">{{ $doctor->doctorProfile->specialization }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Rating:</span>
                                    <span class="ml-2">{{ number_format($doctor->ratings_avg_rating ?? 0, 1) }} ⭐</span>
                                </div>
                                @if($doctor->doctorProfile->bio)
                                    <div>
                                        <span class="font-medium">Bio:</span>
                                        <p class="mt-1 text-gray-600">{{ $doctor->doctorProfile->bio }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Book Appointment Button -->
                            <div class="mt-6">
                                <a href="{{ route('patient.appointments.create', ['doctor_id' => $doctor->id]) }}"
                                   class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Book Appointment
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
