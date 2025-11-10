<x-guest-layout>
    <form method="POST" action="{{ route('custom.register') }}">
        @csrf

        @php
            $countryCodes = config('country_codes', []);
        @endphp

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone Number -->
        <div class="mt-4">
            <x-input-label for="phone_country" :value="__('Phone Number')" />
            <div class="mt-1 flex">
                <select id="phone_country" name="phone_country"
                        class="w-44 rounded-md rounded-r-none border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                    @foreach($countryCodes as $code => $label)
                        <option value="{{ $code }}" {{ old('phone_country', '+1') === $code ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <x-text-input id="phone_number" name="phone_number" type="tel"
                               :value="old('phone_number')"
                               class="flex-1 rounded-l-none"
                               inputmode="numeric" pattern="[0-9]{7,12}" maxlength="12"
                               placeholder="{{ __('Phone number without country code') }}" required />
            </div>
            <input type="hidden" name="phone" id="phone" value="{{ old('phone') }}">
            <p class="mt-2 text-sm text-gray-500" id="phone_preview"
               data-default-message="{{ __('Include only digits, 7-12 numbers long.') }}"
               data-current-label="{{ __('Current') }}">
                {{ old('phone') ? __('Current: :phone', ['phone' => old('phone')]) : __('Include only digits, 7-12 numbers long.') }}
            </p>
            <x-input-error :messages="$errors->get('phone_country')" class="mt-2" />
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Date of Birth -->
        <div class="mt-4">
            <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
            <x-text-input id="date_of_birth" class="block mt-1 w-full" type="date" name="date_of_birth" :value="old('date_of_birth')" required max="{{ now()->toDateString() }}" min="1900-01-01" />
            <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
        </div>

        <!-- Age -->
        <div class="mt-4">
            <x-input-label for="age" :value="__('Age')" />
            <x-text-input id="age" class="block mt-1 w-full bg-gray-100" type="text" :value="old('date_of_birth') ? \Carbon\Carbon::parse(old('date_of_birth'))->age : ''" readonly />
        </div>

        <!-- Role Selection -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('Register as')" />
            <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="patient" {{ old('role', 'patient') === 'patient' ? 'selected' : '' }}>Patient</option>
                <option value="doctor" {{ old('role') === 'doctor' ? 'selected' : '' }}>Doctor</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        (function () {
            const dobInput = document.getElementById('date_of_birth');
            const ageInput = document.getElementById('age');
            const phoneCountry = document.getElementById('phone_country');
            const phoneNumber = document.getElementById('phone_number');
            const phoneHidden = document.getElementById('phone');
            const phonePreview = document.getElementById('phone_preview');
            const defaultPhoneMessage = phonePreview?.dataset.defaultMessage ?? 'Include only digits, 7-12 numbers long.';
            const currentPhoneLabel = phonePreview?.dataset.currentLabel ?? 'Current';

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
                if (!dobInput || !ageInput) {
                    return;
                }

                ageInput.value = calculateAge(dobInput.value);
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

            if (dobInput) {
                dobInput.addEventListener('change', updateAge);
                dobInput.addEventListener('keyup', updateAge);
                updateAge();
            }

            if (phoneNumber) {
                phoneNumber.addEventListener('input', updatePhone);
                phoneNumber.addEventListener('blur', updatePhone);
            }

            if (phoneCountry) {
                phoneCountry.addEventListener('change', updatePhone);
            }

            updatePhone();
        })();
    </script>
</x-guest-layout>
