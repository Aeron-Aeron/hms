<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <h2 class="text-2xl font-bold">Specialists for {{ $diseaseName }}</h2>
                <p class="text-gray-600">Find and book appointments with specialists</p>
            </div>

            @if($doctors->isEmpty())
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-yellow-700">No exact specialists found for {{ $diseaseName }}. Here are some recommended doctors:</p>
                    
                    {{-- Show general physicians or related specialists --}}
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach(App\Models\User::where('role', 'doctor')
                            ->whereHas('doctorProfile', function($q) {
                                $q->where('specialization', 'LIKE', '%General%')
                                    ->orWhere('specialization', 'LIKE', '%Internal Medicine%');
                            })
                            ->take(6)
                            ->get() as $doctor)
                            
                            {{-- Display doctor card --}}
                            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                                <div class="p-6">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h3 class="text-lg font-semibold">Dr. {{ $doctor->name }}</h3>
                                            <p class="text-gray-600">{{ $doctor->doctorProfile->specialization }}</p>
                                            <div class="mt-2 flex items-center">
                                                <span class="text-yellow-400">⭐</span>
                                                <span class="ml-1 text-gray-600">
                                                    {{ number_format($doctor->overall_rating ?? 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4 space-y-2">
                                        <a href="{{ route('patient.appointments.create', ['doctor' => $doctor->id]) }}"
                                           class="block w-full text-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Book Appointment
                                        </a>

                                        <a href="{{ route('patient.doctors.show', $doctor) }}"
                                           class="block w-full text-center border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-2 px-4 rounded">
                                            View Profile
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($doctors as $doctor)
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="p-6">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold">Dr. {{ $doctor->name }}</h3>
                                        <p class="text-gray-600">{{ $doctor->doctorProfile->specialization }}</p>
                                        <div class="mt-2 flex items-center">
                                            <span class="text-yellow-400">⭐</span>
                                            <span class="ml-1 text-gray-600">
                                                {{ number_format($doctor->overall_rating ?? 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 space-y-2">
                                    <a href="{{ route('patient.appointments.create', ['doctor' => $doctor->id]) }}"
                                       class="block w-full text-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Book Appointment
                                    </a>

                                    <a href="{{ route('patient.doctors.show', $doctor) }}"
                                       class="block w-full text-center border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-2 px-4 rounded">
                                        View Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
