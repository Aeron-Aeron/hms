<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Dashboard') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <!-- Quick Actions Section -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <!-- View Doctors Button -->
        <a href="{{ route('patient.doctors.index') }}"
          class="flex items-center p-4 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
          View All Doctors
        </a>

        <!-- Symptom Checker Button -->
        <button onclick="toggleSymptomChecker()"
          class="flex items-center p-4 bg-green-500 hover:bg-green-700 text-white font-bold rounded-lg shadow-sm transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          Check Your Symptoms
        </button>
      </div>

      <!-- Recommended Doctors (after creating a health problem) -->
      @if(session()->has('recommendedDoctors'))
      <div class="mb-8">
        <h3 class="text-lg font-semibold mb-4">Recommended Doctors for your reported problem</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          @foreach(session('recommendedDoctors') as $doctor)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6">
                <div class="font-semibold text-lg mb-2">Dr. {{ $doctor->name }}</div>
                <div class="text-gray-600 mb-2">{{ $doctor->doctorProfile->specialization ?? '' }}</div>
                <a href="{{ route('patient.doctors.show', $doctor) }}"
                   class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                  View Profile
                </a>
              </div>
            </div>
          @endforeach
        </div>
      </div>
      @endif

      <!-- Symptom Checker Section (Hidden by default) -->
      <div id="symptomCheckerSection" class="mb-8 hidden">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <h3 class="text-lg font-semibold mb-4">Symptom Checker</h3>

            <!-- Debug info -->
            @if(empty($symptoms))
            <div class="text-red-500 mb-4">No symptoms loaded</div>
            @else
            <div class="text-green-500 mb-4">{{ $symptoms->count() }} symptoms available</div>
            @endif

            <form action="{{ route('patient.symptoms.check') }}" method="POST" id="symptomForm">
              @csrf
              <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                  Select Your Symptoms (Check all that apply)
                </label>

                <!-- Search Box -->
                <div class="mb-4">
                  <input type="text"
                    id="symptomSearch"
                    placeholder="Search symptoms..."
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Symptoms Checklist -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-h-60 overflow-y-auto p-4 border rounded-lg" id="symptomsContainer">
                  @foreach($symptoms ?? [] as $symptom)
                  <div class="flex items-center symptom-item">
                    <input type="checkbox"
                      name="symptoms[]"
                      value="{{ $symptom->id }}"
                      id="symptom_{{ $symptom->id }}"
                      class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="symptom_{{ $symptom->id }}"
                      class="ml-2 text-sm text-gray-600 cursor-pointer select-none">
                      {{ ucfirst(str_replace('_', ' ', $symptom->name)) }}
                    </label>
                  </div>
                  @endforeach
                </div>

                <!-- Selected Symptoms Summary -->
                <div class="mt-4">
                  <div class="text-sm font-semibold text-gray-700">Selected Symptoms:</div>
                  <div id="selectedSymptoms" class="mt-2 flex flex-wrap gap-2"></div>
                </div>
              </div>

              <div class="mt-6 border-t pt-4">
                <h4 class="text-base font-semibold text-gray-800 mb-2">Optional Vitals (if known)</h4>
                <p class="text-sm text-gray-500 mb-4">Providing vitals helps doctors understand your current state, but you can leave these blank.</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                    <label for="blood_pressure_systolic" class="block text-sm font-medium text-gray-700">Blood Pressure (mmHg)</label>
                    <div class="mt-1 flex items-center space-x-2">
                      <input type="number" name="blood_pressure_systolic" id="blood_pressure_systolic"
                             min="50" max="250" step="1"
                             class="w-20 rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                             placeholder="Sys" />
                      <span class="text-gray-500">/</span>
                      <input type="number" name="blood_pressure_diastolic" id="blood_pressure_diastolic"
                             min="30" max="150" step="1"
                             class="w-20 rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                             placeholder="Dia" />
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Example: 120 / 80</p>
                  </div>

                  <div>
                    <label for="temperature" class="block text-sm font-medium text-gray-700">Temperature</label>
                    <div class="mt-1 flex space-x-2">
                      <input type="number" name="temperature" id="temperature"
                             step="0.1"
                             class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                             placeholder="e.g. 98.6" />
                      <select name="temperature_unit" id="temperature_unit"
                              class="w-28 rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="c">°C</option>
                        <option value="f">°F</option>
                      </select>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Provide a number and choose Celsius or Fahrenheit.</p>
                  </div>

                  <div>
                    <label for="weight" class="block text-sm font-medium text-gray-700">Weight (kg)</label>
                    <input type="number" name="weight" id="weight"
                           min="1" max="500" step="0.1"
                           class="mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                           placeholder="e.g. 70.5" />
                    <p class="mt-1 text-xs text-gray-500">Optional. Use kilograms.</p>
                  </div>
                </div>
              </div>

              <button type="submit"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Analyze Symptoms
              </button>
            </form>

            <!-- Results Section -->
            <div id="symptomResults" class="mt-6 hidden">
              <h4 class="font-semibold mb-2">Possible Conditions:</h4>
              <div class="space-y-2" id="resultsList"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Featured Doctors Section -->
      <div class="mb-8">

        <h3 class="text-lg font-semibold mb-4">Featured Doctors</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          @foreach($featuredDoctors as $doctor)
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <div class="font-semibold text-lg mb-2">Dr. {{ $doctor->name }}</div>
              <div class="text-gray-600 mb-2">{{ $doctor->doctorProfile->specialization }}</div>
                 <div class="text-sm text-gray-500 mb-4">
                   Rating: {{ number_format($doctor->overall_rating ?? 0, 1) }} ⭐
              </div>
              <a href="{{ route('patient.doctors.show', $doctor) }}"
                class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                View Profile
              </a>
            </div>
          </div>
          @endforeach
        </div>
      </div>

      <!-- Recent Appointments Section -->
      <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold">Recent Appointments</h3>
          <div class="space-x-4">
            <a href="{{ route('patient.appointments.create') }}"
              class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
              Book New Appointment
            </a>
            <a href="{{ route('patient.appointments.index') }}"
              class="text-blue-600 hover:text-blue-500">
              View All
            </a>
          </div>
        </div>
        @if($appointments->count() > 0)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            @foreach($appointments as $appointment)
            <div class="mb-4 last:mb-0 pb-4 last:pb-0 border-b last:border-0">
              <div class="flex justify-between items-start">
                <div>
                  <div class="font-semibold">Dr. {{ $appointment->doctor->name }}</div>
                  <div class="text-sm text-gray-600">
                    {{ $appointment->scheduled_time->format('M d, Y h:i A') }}
                  </div>
                </div>

                @php
                $colors = [
                'completed' => 'bg-green-100 text-green-800',
                'pending' => 'bg-yellow-100 text-yellow-800',
                'cancelled' => 'bg-red-100 text-red-800',
                ];
                $class = $colors[$appointment->status] ?? 'bg-blue-100 text-blue-800';
                @endphp
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $class }}">
                  {{ ucfirst($appointment->status) }}
                </span>
              </div>
              <a href="{{ route('patient.appointments.show', $appointment) }}"
                class="text-blue-600 hover:text-blue-900">View Details</a>
            </div>
            @endforeach
          </div>
        </div>
        @else
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
          <p class="text-gray-500 mb-4">No appointments scheduled yet.</p>
          <a href="{{ route('patient.appointments.create') }}"
            class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Schedule Your First Appointment
          </a>
        </div>
        @endif
      </div>

      <!-- Recent Health Problems -->
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Recent Health Problems</h3>
            <a href="{{ route('patient.health-problems.index') }}" class="text-blue-600 hover:text-blue-500">View All</a>
          </div>


          @if($healthProblems->count() > 0)
          <div class="space-y-4">
            @foreach($healthProblems as $problem)
            <div class="border rounded-lg p-4">
              <div class="font-semibold mb-2">{{ $problem->title }}</div>
              <div class="text-sm text-gray-600 mb-2">{{ Str::limit($problem->description, 150) }}</div>
              <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500">{{ $problem->created_at->format('M d, Y') }}</span>
                <a href="{{ route('patient.health-problems.show', $problem) }}"
                  class="text-blue-600 hover:text-blue-900">View Details </a>
              </div>
            </div>
            @endforeach
          </div>
          @else
          <p class="text-gray-500">No health problems reported yet.</p>
          @endif
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
    // Symptom search functionality
    const searchInput = document.getElementById('symptomSearch');
    const symptomsContainer = document.getElementById('symptomsContainer');
    const selectedSymptomsDiv = document.getElementById('selectedSymptoms');

    searchInput?.addEventListener('input', function(e) {
      const searchTerm = e.target.value.toLowerCase();
      const symptomItems = symptomsContainer.getElementsByClassName('symptom-item');

      Array.from(symptomItems).forEach(item => {
        const label = item.querySelector('label').textContent.toLowerCase();
        item.style.display = label.includes(searchTerm) ? '' : 'none';
      });
    });

    // Update selected symptoms display
    function updateSelectedSymptoms() {
      const checkboxes = symptomsContainer.querySelectorAll('input[type="checkbox"]:checked');
      selectedSymptomsDiv.innerHTML = '';

      checkboxes.forEach(checkbox => {
        const label = checkbox.nextElementSibling.textContent.trim();
        const pill = document.createElement('span');
        pill.className = 'px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm';
        pill.textContent = label;
        selectedSymptomsDiv.appendChild(pill);
      });
    }

    // Add event listeners to checkboxes
    symptomsContainer?.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
      checkbox.addEventListener('change', updateSelectedSymptoms);
    });

    function toggleSymptomChecker() {
      const section = document.getElementById('symptomCheckerSection');
      section.classList.toggle('hidden');
    }

    // Form submission code
    document.getElementById('symptomForm')?.addEventListener('submit', async (e) => {
      e.preventDefault();
      const form = e.target;
      const resultsList = document.getElementById('resultsList');
      const symptomResults = document.getElementById('symptomResults');

      try {
        const response = await fetch(form.action, {
          method: 'POST',
          body: new FormData(form),
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          }
        });

        if (!response.ok) {
          const errorData = await response.json();
          throw new Error(errorData.message || 'Server error occurred');
        }

        const data = await response.json();

        // Clear previous results
        resultsList.innerHTML = '';

        // Check if predictions exist and is an array
        if (data.predictions && Array.isArray(data.predictions)) {
          // Display new results
          data.predictions.forEach(prediction => {
            const div = document.createElement('div');
            div.className = 'p-4 border rounded-lg mb-2';
            div.innerHTML = `
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="font-semibold">${prediction.disease.name}</div>
                                    <div class="text-sm text-gray-600">Match: ${prediction.match_percentage}%</div>
                                </div>
                                <a href="/patient/specialists/${encodeURIComponent(prediction.disease.name)}"
                                   class="bg-green-500 hover:bg-green-700 text-white text-sm font-bold py-1 px-3 rounded">
                                    Find Specialist
                                </a>
                            </div>
                        `;
            resultsList.appendChild(div);
          });

          symptomResults.classList.remove('hidden');
        } else {
          throw new Error('No predictions received from server');
        }
      } catch (error) {
        console.error('Error details:', error);
        alert('An error occurred while processing your symptoms. Please try again.');
      }
    });
  </script>
  @endpush
</x-app-layout>