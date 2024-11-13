<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DoctorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.custom-register');
    }

    public function register(Request $request)
    {
        // Dump the request data to see what's being received
        \Log::info('Registration data:', $request->all());

        // Validate the request
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,doctor,patient',
        ];

        // Add doctor-specific validation rules
        if ($request->input('role') === 'doctor') {
            $rules['specialization'] = 'required|string|max:255';
            $rules['qualification'] = 'required|string|max:255';
            $rules['experience'] = 'required|numeric|min:0';
        }

        $validated = $request->validate($rules);

        try {
            \DB::beginTransaction();

            // Create the user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            // If doctor, create the profile
            if ($validated['role'] === 'doctor') {
                DoctorProfile::create([
                    'user_id' => $user->id,
                    'specialization' => $validated['specialization'],
                    'qualification' => $validated['qualification'],
                    'experience' => $validated['experience'],
                ]);
            }

            \DB::commit();

            // Log in the user
            Auth::login($user);

            // Redirect based on role
            return redirect()->route($user->role . '.dashboard')->with('success', 'Registration successful!');

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Registration failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->withErrors(['error' => 'Registration failed. Please try again.']);
        }
    }
}
