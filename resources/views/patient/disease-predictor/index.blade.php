<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-4">Symptom Checker</h2>

                    <form action="{{ route('patient.disease-predictor.predict') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Select Your Symptoms (minimum 1)
                            </label>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach($symptoms as $symptom)
                                    <div class="flex items-center">
                                        <input type="checkbox"
                                               name="symptoms[]"
                                               value="{{ $symptom->id }}"
                                               id="symptom_{{ $symptom->id }}"
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <label for="symptom_{{ $symptom->id }}"
                                               class="ml-2 text-sm text-gray-600">
                                            {{ ucfirst(str_replace('_', ' ', $symptom->name)) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Check Possible Conditions
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
