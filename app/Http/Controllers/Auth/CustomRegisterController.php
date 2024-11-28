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
        Log::info('Registration attempt with data:', $request->all());

        try {
            // Basic validation for all users
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|in:admin,doctor,patient',
            ]);

            // Additional validation for doctors
            if ($request->role === 'doctor') {
                $request->validate([
                    'specialization' => 'required|string|max:255',
                    'qualification' => 'required|string|max:255',
                    'experience' => 'required|numeric|min:0',
                ]);
            }

            \DB::beginTransaction();

            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            // Create doctor profile if applicable
            if ($validated['role'] === 'doctor') {
                DoctorProfile::create([
                    'user_id' => $user->id,
                    'specialization' => $request->specialization,
                    'qualification' => $request->qualification,
                    'experience' => $request->experience,
                ]);
            }

            \DB::commit();

            event(new Registered($user));
            Auth::login($user);

            // Redirect based on role
            return redirect()->route($user->role . '.dashboard')
                           ->with('success', 'Registration successful!');

        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Registration failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }
}
