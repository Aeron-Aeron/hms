<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Role Selection -->
            <div class="mt-4">
                <x-input-label for="role" :value="__('Register as')" />
                <select id="role" name="role" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="">Select Role</option>
                    <option value="patient" {{ old('role') == 'patient' ? 'selected' : '' }}>Patient</option>
                    <option value="doctor" {{ old('role') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>

            <!-- Doctor Specific Fields -->
            <div id="doctor-fields" class="hidden">
                <div class="mt-4">
                    <x-input-label for="specialization" :value="__('Specialization')" />
                    <x-text-input id="specialization" class="block mt-1 w-full" type="text" name="specialization" :value="old('specialization')" />
                    <x-input-error :messages="$errors->get('specialization')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="qualification" :value="__('Qualification')" />
                    <x-text-input id="qualification" class="block mt-1 w-full" type="text" name="qualification" :value="old('qualification')" />
                    <x-input-error :messages="$errors->get('qualification')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="experience" :value="__('Experience (years)')" />
                    <x-text-input id="experience" class="block mt-1 w-full" type="text" name="experience" :value="old('experience')" />
                    <x-input-error :messages="$errors->get('experience')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="bio" :value="__('Bio')" />
                    <textarea id="bio" name="bio"
                              class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                              rows="3">{{ old('bio') }}</textarea>
                    <x-input-error :messages="$errors->get('bio')" class="mt-2" />
                </div>
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
                                name="password_confirmation" required />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button class="ml-4">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </x-auth-card>

    @push('scripts')
    <script>
        document.getElementById('role').addEventListener('change', function() {
            const doctorFields = document.getElementById('doctor-fields');
            if (this.value === 'doctor') {
                doctorFields.classList.remove('hidden');
            } else {
                doctorFields.classList.add('hidden');
            }
        });

        // Pre-select role from URL parameter if present
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const role = urlParams.get('role');
            if (role) {
                const roleSelect = document.getElementById('role');
                roleSelect.value = role;
                // Trigger change event if it's a doctor
                if (role === 'doctor') {
                    roleSelect.dispatchEvent(new Event('change'));
                }
            }
        });
    </script>
    @endpush
</x-guest-layout>
