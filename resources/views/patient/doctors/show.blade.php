<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Doctor Profile') }}
            </h2>
            <a href="{{ route('patient.dashboard') }}"
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

    <!-- Ratings & Reviews Section -->
    <div class="mt-8">
        <h3 class="text-lg font-semibold mb-4">Ratings & Reviews</h3>

        <!-- Add Rating Section (only show if patient has a completed appointment) -->
        @if(auth()->user()->appointments()
            ->where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->whereDoesntHave('rating')
            ->exists())
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h3 class="text-lg font-semibold mb-4">Rate Your Experience</h3>
                <form action="{{ route('patient.ratings.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Rating</label>
                        <div class="flex gap-2 mt-1">
                            @for($i = 1; $i <= 5; $i++)
                                <input type="radio" id="rating{{ $i }}" name="rating" value="{{ $i }}" class="hidden peer" required>
                                <label for="rating{{ $i }}" class="cursor-pointer text-2xl peer-checked:text-yellow-400">⭐</label>
                            @endfor
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Review (Optional)</label>
                        <textarea name="review" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>

                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Submit Review
                    </button>
                </form>
            </div>
        @endif

        <!-- Display Existing Reviews -->
        <div class="space-y-4">
            @forelse($doctor->ratings as $rating)
                <div class="border rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-1">
                                @for($i = 0; $i < 5; $i++)
                                    <span class="text-yellow-400">{{ $i < $rating->rating ? '★' : '☆' }}</span>
                                @endfor
                            </div>
                            @if($rating->review)
                                <p class="mt-2 text-gray-600">{{ $rating->review }}</p>
                            @endif
                            <div class="mt-1 text-sm text-gray-500">
                                By {{ $rating->patient->name }} • {{ $rating->created_at->diffForHumans() }}
                            </div>
                        </div>

                        <!-- Helpful Votes -->
                        @if(auth()->id() !== $rating->patient_id)
                            <form action="{{ route('patient.ratings.vote', $rating) }}" method="POST" class="flex items-center">
                                @csrf
                                <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">
                                    👍 Helpful ({{ $rating->helpful_votes }})
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-500">No reviews yet.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
