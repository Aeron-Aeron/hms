<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
    $phoneCountry = isset($input['phone_country']) ? trim((string) $input['phone_country']) : '+1';
    $phoneCountry = str_starts_with($phoneCountry, '+') ? $phoneCountry : '+'.$phoneCountry;
    $phoneNumber = preg_replace('/\D/', '', $input['phone_number'] ?? '');
        $input['phone_country'] = $phoneCountry;
        $input['phone_number'] = $phoneNumber;
        $input['phone'] = $phoneCountry.$phoneNumber;

        $validated = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_country' => ['required', 'regex:/^\+[0-9]{1,3}$/'],
            'phone_number' => ['required', 'digits_between:7,12'],
            'phone' => ['required', 'regex:/^\+[0-9]{8,15}$/', 'unique:users,phone'],
            'date_of_birth' => ['required', 'date', 'before_or_equal:today', 'after_or_equal:1900-01-01'],
            'role' => ['required', 'in:admin,doctor,patient'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'date_of_birth' => $validated['date_of_birth'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);
    }
}
