<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-4">Possible Conditions</h2>

                    <div class="space-y-4">
                        @foreach($predictions as $prediction)
                            <div class="border rounded-lg p-4 {{ $loop->first ? 'bg-blue-50' : '' }}">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-lg font-semibold">
                                            {{ $prediction['disease']->name }}
                                        </h3>
                                        <p class="text-sm text-gray-600">
                                            Match: {{ $prediction['match_percentage'] }}%
                                        </p>
                                    </div>

                                    <a href="{{ route('patient.doctors.index', ['specialization' => $prediction['disease']->name]) }}"
                                       class="bg-green-500 hover:bg-green-700 text-white text-sm font-bold py-2 px-4 rounded">
                                        Find Specialists
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        <p class="text-red-500 text-sm">
                            Note: This is not a medical diagnosis. Always consult with qualified healthcare providers about medical concerns.
                        </p>

                        <a href="{{ route('patient.disease-predictor.index') }}"
                           class="mt-4 inline-block text-blue-500 hover:text-blue-700">
                            ← Check Different Symptoms
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
