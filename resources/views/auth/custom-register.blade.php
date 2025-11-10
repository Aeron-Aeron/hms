<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <h2 class="text-2xl font-bold text-center mb-6">Register</h2>

            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                @php
                    $countryCodes = config('country_codes', []);
                @endphp

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Phone -->
                <div class="mb-4">
                    <label for="phone_country" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <div class="mt-1 flex">
                        <select id="phone_country" name="phone_country" required
                                class="w-44 rounded-md rounded-r-none border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach($countryCodes as $code => $label)
                                <option value="{{ $code }}" {{ old('phone_country', '+1') === $code ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
               <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" required
                   inputmode="numeric" pattern="[0-9]{7,12}" maxlength="12"
                   class="flex-1 rounded-md rounded-l-none border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="Phone number without country code">
                    </div>
                    <input type="hidden" name="phone" id="phone" value="{{ old('phone') }}">
                    <p class="mt-2 text-sm text-gray-500" id="phonePreview"
                       data-default-message="{{ __('Include only digits, 7-12 numbers long.') }}"
                       data-current-label="{{ __('Current') }}">
                        {{ old('phone') ? __('Current: :phone', ['phone' => old('phone')]) : __('Include only digits, 7-12 numbers long.') }}
                    </p>
                    @error('phone_country')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('phone_number')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('phone')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date of Birth -->
                <div class="mb-4">
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}" required
                           max="{{ now()->toDateString() }}" min="1900-01-01"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Age -->
                <div class="mb-4">
                    <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                    <input type="text" id="age" value="{{ old('date_of_birth') ? \Carbon\Carbon::parse(old('date_of_birth'))->age : '' }}" readonly
                           class="mt-1 block w-full rounded-md border-gray-200 bg-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           placeholder="Auto-calculated">
                </div>

                <!-- Role -->
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700">Register as</label>
                    <select name="role" id="role" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Role</option>
                        <option value="patient" {{ old('role') == 'patient' ? 'selected' : '' }}>Patient</option>
                        <option value="doctor" {{ old('role') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <!-- Doctor Fields -->
                <div id="doctorFields" style="display: none;">
                    <div class="mb-4">
                        <label for="specialization" class="block text-sm font-medium text-gray-700">Specialization</label>
                        <input type="text" name="specialization" id="specialization" value="{{ old('specialization') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="mb-4">
                        <label for="qualification" class="block text-sm font-medium text-gray-700">Qualification</label>
                        <input type="text" name="qualification" id="qualification" value="{{ old('qualification') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="mb-4">
                        <label for="experience" class="block text-sm font-medium text-gray-700">Experience (years)</label>
                        <input type="number" name="experience" id="experience" value="{{ old('experience') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-500">
                        Already registered?
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registerForm');
            const roleSelect = document.getElementById('role');
            const doctorFields = document.getElementById('doctorFields');
            const doctorInputs = ['specialization', 'qualification', 'experience'];
            const dobInput = document.getElementById('date_of_birth');
            const ageField = document.getElementById('age');
            const phoneCountry = document.getElementById('phone_country');
            const phoneNumber = document.getElementById('phone_number');
            const phoneHidden = document.getElementById('phone');
            const phonePreview = document.getElementById('phonePreview');
            const defaultPhoneMessage = phonePreview?.dataset.defaultMessage ?? 'Include only digits, 7-12 numbers long.';
            const currentPhoneLabel = phonePreview?.dataset.currentLabel ?? 'Current';

            function toggleDoctorFields() {
                const isDoctor = roleSelect.value === 'doctor';
                doctorFields.style.display = isDoctor ? 'block' : 'none';

                doctorInputs.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    field.required = isDoctor;
                    if (!isDoctor) {
                        field.value = '';
                    }
                });
            }

            function calculateAge(dateString) {
                const dob = new Date(dateString);
                if (Number.isNaN(dob.getTime())) {
                    return '';
                }

                const today = new Date();
                let age = today.getFullYear() - dob.getFullYear();
                const monthDiff = today.getMonth() - dob.getMonth();

                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                    age--;
                }

                return age >= 0 ? age : '';
            }

            function updateAge() {
                if (!dobInput || !ageField) {
                    return;
                }

                ageField.value = calculateAge(dobInput.value);
            }

            function sanitizePhoneNumber(value) {
                return value.replace(/\D/g, '').slice(0, 12);
            }

            function updatePhone() {
                if (!phoneCountry || !phoneNumber || !phoneHidden) {
                    return;
                }

                phoneNumber.value = sanitizePhoneNumber(phoneNumber.value);
                const combined = phoneNumber.value ? `${phoneCountry.value}${phoneNumber.value}` : '';
                phoneHidden.value = combined;

                if (phonePreview) {
                    phonePreview.textContent = combined
                        ? `${currentPhoneLabel}: ${combined}`
                        : defaultPhoneMessage;
                }
            }

            // Initial toggle
            toggleDoctorFields();
            updateAge();
            updatePhone();

            // Toggle on role change
            roleSelect.addEventListener('change', toggleDoctorFields);

            if (dobInput) {
                dobInput.addEventListener('change', updateAge);
                dobInput.addEventListener('keyup', updateAge);
            }

            if (phoneNumber) {
                phoneNumber.addEventListener('input', updatePhone);
                phoneNumber.addEventListener('blur', updatePhone);
            }

            if (phoneCountry) {
                phoneCountry.addEventListener('change', updatePhone);
            }

            // Form submission handling
            form.addEventListener('submit', function(e) {
                updatePhone();

                if (roleSelect.value === 'doctor') {
                    let isValid = true;
                    doctorInputs.forEach(fieldId => {
                        const field = document.getElementById(fieldId);
                        if (!field.value) {
                            isValid = false;
                            field.classList.add('border-red-500');
                        } else {
                            field.classList.remove('border-red-500');
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                        alert('Please fill in all required doctor fields');
                    }
                }
            });

            // Show doctor fields if there are validation errors and role is doctor
            @if(old('role') === 'doctor')
                roleSelect.value = 'doctor';
                toggleDoctorFields();
            @endif
        });
    </script>
</body>
</html>
