<x-app-layout>
  <x-slot name="header">
    <div class="flex flex-col gap-2">
      <p class="text-sm text-gray-500">Welcome back, {{ $user->name }} 👋</p>
      <h2 class="text-2xl font-semibold text-gray-900 leading-tight">
        Your Health Dashboard
      </h2>
      <p class="text-sm text-gray-500">Quickly review your health activity, appointments, and recommended doctors.</p>
    </div>
  </x-slot>

  <div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Overview Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 p-5 text-white shadow-lg">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-blue-100">Upcoming Appointments</p>
              <p class="text-2xl font-semibold mt-1">{{ $dashboardSummary['upcomingAppointments'] }}</p>
            </div>
            <div class="rounded-full bg-white/20 p-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10m-9 8h8a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm border border-gray-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Reported Health Issues</p>
              <p class="text-2xl font-semibold mt-1">{{ $dashboardSummary['totalReports'] }}</p>
            </div>
            <div class="rounded-full bg-blue-50 p-2 text-blue-500">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01m9-4a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm border border-gray-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Doctors Consulted</p>
              <p class="text-2xl font-semibold mt-1">{{ $dashboardSummary['doctorConnections'] }}</p>
            </div>
            <div class="rounded-full bg-green-50 p-2 text-green-500">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422A12.083 12.083 0 0112 21 12.083 12.083 0 015.84 10.578L12 14z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm border border-gray-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Latest Activity</p>
              <p class="text-2xl font-semibold mt-1">{{ now()->format('M d') }}</p>
            </div>
            <div class="rounded-full bg-purple-50 p-2 text-purple-500">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5h12M9 3v2m6 4H3m6 4H3m10 0h4m-4 4h4m-4 4h4" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions Section -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10">
        <!-- View Doctors Button -->
        <a href="{{ route('patient.doctors.index') }}"
          class="flex items-center justify-between gap-3 rounded-xl border border-blue-100 bg-blue-500/10 p-5 text-blue-700 shadow-sm transition-all hover:bg-blue-500 hover:text-white">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
          <div class="flex flex-col items-start">
            <span class="text-sm font-semibold uppercase tracking-wide">Browse Specialists</span>
            <span class="text-lg font-semibold">View All Doctors</span>
          </div>
          <span class="ml-auto rounded-full bg-blue-500/20 p-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
            </svg>
          </span>
        </a>

        <!-- Symptom Checker Button -->
        <button onclick="toggleSymptomChecker()"
          class="flex items-center justify-between gap-3 rounded-xl border border-green-100 bg-green-500/10 p-5 text-green-700 shadow-sm transition-all hover:bg-green-500 hover:text-white">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div class="flex flex-col items-start">
            <span class="text-sm font-semibold uppercase tracking-wide">Self Assessment</span>
            <span class="text-lg font-semibold">Check Your Symptoms</span>
          </div>
          <span class="ml-auto rounded-full bg-green-500/20 p-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
            </svg>
          </span>
        </button>
      </div>

      <!-- Recommended Doctors (after creating a health problem) -->
      @if(session()->has('recommendedDoctors'))
      <div class="mb-10">
        <h3 class="text-lg font-semibold mb-3 text-gray-900">Recommended Doctors for your reported problem</h3>
        <p class="mb-4 text-sm text-gray-500">Based on your latest report, here are specialists who might fit your needs.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          @foreach(session('recommendedDoctors') as $doctor)
          <div class="flex h-full flex-col justify-between rounded-xl border border-gray-100 bg-white p-6 shadow-sm transition hover:shadow-md">
            <div>
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm text-gray-400 uppercase tracking-wide">Specialist</p>
                  <p class="mt-1 text-lg font-semibold text-gray-900">Dr. {{ $doctor->name }}</p>
                </div>
                <div class="rounded-full bg-blue-50 p-2 text-blue-500">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 11c1.104 0 2-.672 2-1.5S13.104 8 12 8s-2 .672-2 1.5.896 1.5 2 1.5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11c0 2.21-3.582 4-8 4-1.436 0-2.785-.144-3.98-.402L5 16l.804-3.216C5.296 12.148 5 11.597 5 11c0-2.21 3.582-4 8-4s8 1.79 8 4z" />
                  </svg>
                </div>
              </div>
              <p class="mt-3 text-sm text-gray-500">{{ $doctor->doctorProfile->specialization ?? 'General Medicine' }}</p>
            </div>
            <a href="{{ route('patient.doctors.show', $doctor) }}"
               class="mt-6 inline-flex items-center justify-center rounded-lg bg-blue-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-600">
              View Profile
              <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
              </svg>
            </a>
          </div>
          @endforeach
        </div>
      </div>
      @endif

      <!-- Symptom Checker Section (Hidden by default) -->
      <div id="symptomCheckerSection" class="mb-10 hidden">
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
          <div class="border-b border-gray-100 bg-gradient-to-r from-green-50 via-white to-blue-50 px-6 py-5">
            <div class="flex flex-col gap-1">
              <h3 class="text-xl font-semibold text-gray-900">Symptom Checker</h3>
              <p class="text-sm text-gray-500">Select the symptoms you are experiencing and optionally add vitals to get more precise suggestions.</p>
            </div>
          </div>

          <div class="p-6">
            <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
              <div class="rounded-lg border border-gray-100 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Symptom Library</p>
                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $symptoms->count() }}</p>
                <p class="mt-1 text-xs text-gray-500">Different symptoms available for selection.</p>
              </div>
              <div class="rounded-lg border border-gray-100 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Recency</p>
                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $healthProblems->first()?->created_at?->diffForHumans() ?? 'No reports yet' }}</p>
                <p class="mt-1 text-xs text-gray-500">Your most recent reported health issue.</p>
              </div>
              <div class="rounded-lg border border-gray-100 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Vital Inputs</p>
                <p class="mt-1 text-lg font-semibold text-gray-900">Optional</p>
                <p class="mt-1 text-xs text-gray-500">Blood pressure, temperature, and weight fields are optional.</p>
              </div>
              <div class="rounded-lg border border-gray-100 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Result Accuracy</p>
                <p class="mt-1 text-lg font-semibold text-gray-900">Improves</p>
                <p class="mt-1 text-xs text-gray-500">More details help us narrow down conditions faster.</p>
              </div>
            </div>

            <form action="{{ route('patient.symptoms.check') }}" method="POST" id="symptomForm" class="space-y-6">
              @csrf
              <div class="mb-4">
                <label class="mb-2 block text-sm font-semibold text-gray-700">
                  Select Your Symptoms (Check all that apply)
                </label>

                <!-- Search Box -->
                <div class="relative mb-4">
                  <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" />
                    </svg>
                  </div>
                  <input type="text"
                    id="symptomSearch"
                    placeholder="Search symptoms..."
                    class="w-full rounded-lg border border-gray-200 py-2 pl-10 pr-3 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <!-- Symptoms Checklist -->
                <div class="grid max-h-64 grid-cols-1 gap-3 overflow-y-auto rounded-lg border border-gray-200 bg-gray-50 p-4 md:grid-cols-3" id="symptomsContainer">
                  @foreach($symptoms ?? [] as $symptom)
                  <label for="symptom_{{ $symptom->id }}" class="symptom-item flex cursor-pointer items-center rounded-lg border border-transparent bg-white px-3 py-2 text-sm text-gray-600 shadow-sm transition hover:border-blue-200 hover:bg-blue-50">
                    <input type="checkbox"
                      name="symptoms[]"
                      value="{{ $symptom->id }}"
                      id="symptom_{{ $symptom->id }}"
                      class="mr-3 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                    <span class="select-none">{{ ucfirst(str_replace('_', ' ', $symptom->name)) }}</span>
                  </label>
                  @endforeach
                </div>

                <!-- Selected Symptoms Summary -->
                <div class="mt-4 rounded-lg border border-dashed border-gray-300 bg-white p-4">
                  <div class="text-sm font-semibold text-gray-700">Selected Symptoms:</div>
                  <div id="selectedSymptoms" class="mt-2 flex flex-wrap gap-2"></div>
                </div>
              </div>

              <div class="rounded-xl border border-gray-100 bg-white p-4">
                <div class="mb-3 flex items-center justify-between">
                  <div>
                    <h4 class="text-base font-semibold text-gray-800">Optional Vitals (if known)</h4>
                    <p class="text-sm text-gray-500">Providing vitals helps doctors understand your current state, but you can leave these blank.</p>
                  </div>
                  <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-500">Optional</span>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                  <div>
                    <label for="blood_pressure_systolic" class="block text-sm font-semibold text-gray-700">Blood Pressure (mmHg)</label>
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
                    <label for="temperature" class="block text-sm font-semibold text-gray-700">Temperature</label>
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
                    <label for="weight" class="block text-sm font-semibold text-gray-700">Weight (kg)</label>
                    <input type="number" name="weight" id="weight"
                           min="1" max="500" step="0.1"
                           class="mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                           placeholder="e.g. 70.5" />
                    <p class="mt-1 text-xs text-gray-500">Optional. Use kilograms.</p>
                  </div>
                </div>
              </div>

              <button type="submit"
                class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white shadow transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7l4-4 4 4M12 3v14" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 17H4" />
                </svg>
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
      <div class="mb-10">
        <div class="flex items-center justify-between mb-3">
          <h3 class="text-lg font-semibold text-gray-900">Featured Doctors</h3>
          <a href="{{ route('patient.doctors.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">View all</a>
        </div>
        <p class="mb-4 text-sm text-gray-500">Popular doctors with excellent reviews from patients like you.</p>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
          @foreach($featuredDoctors as $doctor)
          <div class="flex h-full flex-col rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-xs uppercase tracking-wide text-gray-400">Doctor</p>
                <p class="mt-1 text-lg font-semibold text-gray-900">Dr. {{ $doctor->name }}</p>
                <p class="mt-1 text-sm text-gray-500">{{ $doctor->doctorProfile->specialization }}</p>
              </div>
              <div class="rounded-full bg-blue-50 p-3 text-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 11c1.104 0 2-.672 2-1.5S13.104 8 12 8s-2 .672-2 1.5.896 1.5 2 1.5z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11c0 2.21-3.582 4-8 4-1.436 0-2.785-.144-3.98-.402L5 16l.804-3.216C5.296 12.148 5 11.597 5 11c0-2.21 3.582-4 8-4s8 1.79 8 4z" />
                </svg>
              </div>
            </div>

            <div class="mt-4 flex items-center gap-2 text-sm text-gray-500">
              <span class="inline-flex items-center rounded-full bg-yellow-50 px-2 py-0.5 text-yellow-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.065 3.287a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.065 3.287c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.065-3.287a1 1 0 00-.364-1.118L2.98 8.714c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.069-3.287z" />
                </svg>
                {{ number_format($doctor->overall_rating ?? 0, 1) }} / 5
              </span>
            </div>

            <a href="{{ route('patient.doctors.show', $doctor) }}"
              class="mt-6 inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
              View Profile
              <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
              </svg>
            </a>
          </div>
          @endforeach
        </div>
      </div>

      <!-- Recent Appointments Section -->
      <div class="mb-10">
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
          <div>
            <h3 class="text-lg font-semibold text-gray-900">Recent Appointments</h3>
            <p class="text-sm text-gray-500">Track your latest visits and follow-ups with your doctors.</p>
          </div>
          <div class="flex flex-wrap gap-3">
            <a href="{{ route('patient.appointments.create') }}"
              class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
              </svg>
              Book Appointment
            </a>
            <a href="{{ route('patient.appointments.index') }}"
              class="inline-flex items-center rounded-lg border border-blue-200 bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-600 transition hover:bg-blue-100">
              View All
            </a>
          </div>
        </div>

        @if($appointments->count() > 0)
        <div class="space-y-3">
          @foreach($appointments as $appointment)
          @php
            $statusClasses = [
              'completed' => 'bg-green-50 text-green-700 border-green-100',
              'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
              'cancelled' => 'bg-red-50 text-red-700 border-red-100',
            ];
            $badgeClass = $statusClasses[$appointment->status] ?? 'bg-blue-50 text-blue-700 border-blue-100';
          @endphp
          <div class="flex flex-col gap-3 rounded-xl border border-gray-100 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md md:flex-row md:items-center md:justify-between">
            <div class="flex items-start gap-3">
              <div class="rounded-full bg-blue-50 p-3 text-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <p class="text-sm font-semibold text-gray-900">Dr. {{ $appointment->doctor->name }}</p>
                <p class="mt-1 text-sm text-gray-500">{{ $appointment->scheduled_time->format('M d, Y \a\t h:i A') }}</p>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                {{ ucfirst($appointment->status) }}
              </span>
              <a href="{{ route('patient.appointments.show', $appointment) }}"
                class="inline-flex items-center rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-600 transition hover:bg-blue-100">
                View Details
                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
                </svg>
              </a>
            </div>
          </div>
          @endforeach
        </div>
        @else
        <div class="rounded-2xl border border-dashed border-blue-200 bg-blue-50 p-6 text-center">
          <p class="text-sm text-blue-700">No appointments scheduled yet.</p>
          <a href="{{ route('patient.appointments.create') }}"
            class="mt-3 inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
            Schedule Your First Appointment
          </a>
        </div>
        @endif
      </div>

      <!-- Recent Health Problems -->
      <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
        <div class="mb-4 flex items-center justify-between">
          <div>
            <h3 class="text-lg font-semibold text-gray-900">Recent Health Problems</h3>
            <p class="text-sm text-gray-500">Your latest reports and notes for quick reference.</p>
          </div>
          <a href="{{ route('patient.health-problems.index') }}" class="inline-flex items-center rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-600 transition hover:bg-blue-100">
            View All
          </a>
        </div>

        @if($healthProblems->count() > 0)
        <div class="space-y-4">
          @foreach($healthProblems as $problem)
          <div class="rounded-xl border border-gray-100 p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-sm font-semibold text-gray-900">{{ $problem->title }}</p>
                <p class="mt-1 text-sm text-gray-500">{{ Str::limit($problem->description, 150) }}</p>
              </div>
              <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-500">{{ $problem->created_at->format('M d, Y') }}</span>
            </div>
            <div class="mt-3 flex items-center justify-between">
              <div class="flex items-center gap-2 text-xs text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Updated {{ $problem->updated_at->diffForHumans() }}
              </div>
              <a href="{{ route('patient.health-problems.show', $problem) }}"
                class="inline-flex items-center rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-600 transition hover:bg-blue-100">
                View Details
                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
                </svg>
              </a>
            </div>
          </div>
          @endforeach
        </div>
        @else
        <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50 p-6 text-center">
          <p class="text-sm text-gray-500">No health problems reported yet.</p>
          <a href="{{ route('patient.health-problems.create') }}" class="mt-3 inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
            Report a Health Problem
          </a>
        </div>
        @endif
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
    (function () {
      window.toggleSymptomChecker = function toggleSymptomChecker() {
        const section = document.getElementById('symptomCheckerSection');
        section?.classList.toggle('hidden');
      };

      const symptomForm = document.getElementById('symptomForm');
      const searchInput = document.getElementById('symptomSearch');
      const symptomsContainer = document.getElementById('symptomsContainer');
      const selectedSymptomsDiv = document.getElementById('selectedSymptoms');
      const resultsList = document.getElementById('resultsList');
      const symptomResults = document.getElementById('symptomResults');

      if (!symptomForm || !symptomsContainer) {
        return;
      }

      const symptomCheckboxes = Array.from(symptomsContainer.querySelectorAll('input[type="checkbox"]'));

      if (!resultsList || !symptomResults) {
        return;
      }

      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
      if (!csrfToken) {
        console.error('Missing CSRF token: symptom checker aborted.');
        return;
      }

      searchInput?.addEventListener('input', (event) => {
        const searchTerm = event.target.value.toLowerCase();

        symptomCheckboxes.forEach((checkbox) => {
          const label = checkbox.nextElementSibling?.textContent?.toLowerCase() ?? '';
          checkbox.parentElement.style.display = label.includes(searchTerm) ? '' : 'none';
        });
      });

      const updateSelectedSymptoms = () => {
        if (!selectedSymptomsDiv) {
          return;
        }

        selectedSymptomsDiv.innerHTML = '';

        symptomCheckboxes
          .filter((checkbox) => checkbox.checked)
          .forEach((checkbox) => {
            const label = checkbox.nextElementSibling?.textContent?.trim();
            if (!label) {
              return;
            }

            const pill = document.createElement('span');
            pill.className = 'px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm';
            pill.textContent = label;
            selectedSymptomsDiv.appendChild(pill);
          });
      };

      symptomCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', updateSelectedSymptoms);
      });

      updateSelectedSymptoms();

      symptomForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        try {
          const response = await fetch(symptomForm.action, {
            method: 'POST',
            body: new FormData(symptomForm),
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
            },
          });

          if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Server error occurred');
          }

          const data = await response.json();
          resultsList.innerHTML = '';

          if (Array.isArray(data.predictions) && data.predictions.length) {
            data.predictions.forEach((prediction) => {
              const wrapper = document.createElement('div');
              wrapper.className = 'p-4 border rounded-lg mb-2';
              wrapper.innerHTML = `
                <div class="flex justify-between items-start">
                  <div>
                    <div class="font-semibold">${prediction.disease.name}</div>
                    <div class="text-sm text-gray-600">Match: ${prediction.match_percentage}%</div>
                  </div>
                  <a href="/patient/specialists/${encodeURIComponent(prediction.disease.name)}"
                     class="bg-green-500 hover:bg-green-700 text-white text-sm font-bold py-1 px-3 rounded">
                    Find Specialist
                  </a>
                </div>`;

              resultsList.appendChild(wrapper);
            });

            symptomResults?.classList.remove('hidden');
          } else {
            throw new Error('No predictions received from server');
          }
        } catch (error) {
          console.error('Symptom checker error:', error);
          alert('An error occurred while processing your symptoms. Please try again.');
        }
      });
    })();
  </script>
  @endpush
</x-app-layout>