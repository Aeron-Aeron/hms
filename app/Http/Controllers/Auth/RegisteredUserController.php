<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
    $phoneCountry = trim((string) $request->input('phone_country', '+1'));
        $phoneCountry = str_starts_with($phoneCountry, '+') ? $phoneCountry : '+'.$phoneCountry;
        $phoneNumber = preg_replace('/\D/', '', (string) $request->input('phone_number', ''));
        $combinedPhone = $phoneCountry.$phoneNumber;

        $request->merge([
            'phone_country' => $phoneCountry,
            'phone_number' => $phoneNumber,
            'phone' => $combinedPhone,
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone_country' => ['required', 'regex:/^\+[0-9]{1,3}$/'],
            'phone_number' => ['required', 'digits_between:7,12'],
            'phone' => ['required', 'regex:/^\+[0-9]{8,15}$/', 'unique:users,phone'],
            'date_of_birth' => ['required', 'date', 'before_or_equal:today', 'after_or_equal:1900-01-01'],
            'role' => ['required', 'in:admin,doctor,patient'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'date_of_birth' => $validated['date_of_birth'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
