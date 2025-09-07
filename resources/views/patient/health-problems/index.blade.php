<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Health Problems') }}
            </h2>
            <a href="{{ route('patient.health-problems.create') }}"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Report New Problem
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($healthProblems->count() > 0)
                        <div class="space-y-4">
                            @foreach($healthProblems as $problem)
                                <div class="border rounded-lg p-4">
                                    <div class="font-semibold mb-2">{{ $problem->title }}</div>
                                    <div class="text-sm text-gray-600 mb-2">{{ Str::limit($problem->description, 200) }}</div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-500">{{ $problem->created_at->format('M d, Y') }}</span>
                                        <a href="{{ route('patient.health-problems.show', $problem) }}" class="text-blue-600 hover:text-blue-900">View Details</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            {{ $healthProblems->links() }}
                        </div>
                    @else
                        <p class="text-gray-500 text-center">No health problems reported yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
