<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Available Doctors') }}
            </h2>
            <a href="{{ route('patient.dashboard') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                <span>← Back to Dashboard</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filter Section -->
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('patient.doctors.index') }}" method="GET" class="flex gap-4">
                    <div class="flex-1">
                        <input type="text"
                               name="search"
                               placeholder="Search by name or specialization..."
                               value="{{ request('search') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Search
                    </button>
                </form>
            </div>

            <!-- Doctors Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($doctors as $doctor)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-semibold">Dr. {{ $doctor->name }}</h3>
                                    <p class="text-gray-600">{{ $doctor->doctorProfile->specialization }}</p>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-yellow-400 mr-1">⭐</span>
                                    <span>{{ number_format($doctor->ratings_avg_rating ?? 0, 1) }}</span>
                                </div>
                            </div>

                            @if($doctor->doctorProfile->bio)
                                <p class="mt-2 text-gray-600 text-sm line-clamp-2">
                                    {{ $doctor->doctorProfile->bio }}
                                </p>
                            @endif

                            <div class="mt-4 flex justify-between items-center">
                                <span class="text-sm text-gray-500">
                                    {{ $doctor->ratings_count ?? 0 }} reviews
                                </span>
                                <a href="{{ route('patient.doctors.show', $doctor) }}"
                                   class="inline-flex items-center text-blue-600 hover:text-blue-900">
                                    View Profile
                                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $doctors->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
