<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Health Problem Details') }}
            </h2>
            <a href="{{ route('patient.health-problems.index') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                <span>← Back</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold">{{ $healthProblem->title }}</h3>
                        <div class="text-sm text-gray-600">Reported on {{ $healthProblem->created_at->format('M d, Y') }}</div>
                    </div>

                    <div class="mb-6">
                        <h4 class="font-semibold">Description</h4>
                        <p class="text-gray-700 mt-2">{{ $healthProblem->description }}</p>
                    </div>

                    @if(!empty($recommendedDoctors) && $recommendedDoctors->count() > 0)
                        <div>
                            <h4 class="font-semibold mb-2">Recommended Doctors</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($recommendedDoctors as $doctor)
                                    <div class="border rounded p-3">
                                        <div class="font-semibold">Dr. {{ $doctor->name }}</div>
                                        <div class="text-sm text-gray-600">{{ $doctor->doctorProfile->specialization ?? '' }}</div>
                                        <a href="{{ route('patient.doctors.show', $doctor) }}" class="text-blue-600 hover:text-blue-900">View Profile</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
