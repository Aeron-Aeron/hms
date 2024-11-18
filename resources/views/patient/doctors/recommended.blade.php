<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Recommended Doctors') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($doctors as $doctor)
                            <div class="border rounded-lg p-4">
                                <div class="font-semibold text-lg mb-2">Dr. {{ $doctor->name }}</div>
                                <div class="text-gray-600 mb-2">{{ $doctor->doctorProfile->specialization }}</div>
                                <div class="text-sm text-gray-500 mb-2">
                                    {{ $doctor->doctorProfile->qualification }}
                                </div>
                                <div class="flex items-center mb-4">
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
                                <div class="flex justify-between items-center">
                                    <a href="{{ route('patient.doctors.show', $doctor) }}"
                                       class="text-blue-600 hover:text-blue-800">
                                        View Profile
                                    </a>
                                    <a href="{{ route('patient.appointments.create', ['doctor' => $doctor->id]) }}"
                                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Book Appointment
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
