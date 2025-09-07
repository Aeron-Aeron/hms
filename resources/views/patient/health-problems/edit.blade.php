<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Health Problem') }}
            </h2>
            <a href="{{ route('patient.health-problems.index') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                <span>← Back</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('patient.health-problems.update', $healthProblem) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Title</label>
                            <input type="text" name="title" value="{{ old('title', $healthProblem->title) }}" required
                                   class="w-full px-3 py-2 border rounded-lg">
                            @error('title') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                            <textarea name="description" rows="5" required class="w-full px-3 py-2 border rounded-lg">{{ old('description', $healthProblem->description) }}</textarea>
                            @error('description') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Symptoms (comma separated)</label>
                            <input type="text" name="symptoms" value="{{ old('symptoms', implode(',', $healthProblem->symptoms ?? [])) }}" placeholder="e.g. fever,cough,headache"
                                   class="w-full px-3 py-2 border rounded-lg">
                            <p class="text-sm text-gray-500">Enter symptoms as a comma-separated list.</p>
                            @error('symptoms') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Severity</label>
                            <select name="severity" required class="w-full px-3 py-2 border rounded-lg">
                                <option value="mild" {{ old('severity', $healthProblem->severity) === 'mild' ? 'selected' : '' }}>Mild</option>
                                <option value="moderate" {{ old('severity', $healthProblem->severity) === 'moderate' ? 'selected' : '' }}>Moderate</option>
                                <option value="severe" {{ old('severity', $healthProblem->severity) === 'severe' ? 'selected' : '' }}>Severe</option>
                            </select>
                            @error('severity') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
