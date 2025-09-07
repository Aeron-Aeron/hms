@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Search Filters -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <form action="{{ route('doctors.search') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Specialization</label>
                <input type="text" name="specialization" value="{{ request('specialization') }}"
                       class="w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Rating</label>
                <select name="rating" class="w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">Any Rating</option>
                    @foreach(range(5, 1) as $rating)
                        <option value="{{ $rating }}" {{ request('rating') == $rating ? 'selected' : '' }}>
                            {{ $rating }}+ Stars
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Available Day</label>
                <select name="available_day" class="w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">Any Day</option>
                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                        <option value="{{ $day }}" {{ request('available_day') == $day ? 'selected' : '' }}>
                            {{ $day }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- Results -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($doctors as $doctor)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    @if($doctor->doctorProfile->profile_image)
                        <img src="{{ Storage::url($doctor->doctorProfile->profile_image) }}"
                             class="w-16 h-16 rounded-full object-cover">
                    @endif
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold">Dr. {{ $doctor->name }}</h3>
                        <p class="text-gray-600">{{ $doctor->doctorProfile->specialization }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center">
                        <div class="flex text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $doctor->overall_rating)
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 fill-current text-gray-300" viewBox="0 0 24 24">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        <span class="ml-2 text-sm text-gray-600">
                            ({{ $doctor->ratings_count ?? 0 }} reviews)
                        </span>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">{{ Str::limit($doctor->doctorProfile->bio, 100) }}</p>
                </div>
                <div class="mt-4">
                    <a href="{{ route('appointments.create', ['doctor' => $doctor->id]) }}"
                       class="block text-center bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                        Book Appointment
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <p class="text-gray-500">No doctors found matching your criteria.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $doctors->links() }}
    </div>
</div>
@endsection
