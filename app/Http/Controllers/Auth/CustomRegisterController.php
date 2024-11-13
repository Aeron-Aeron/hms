<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DoctorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class CustomRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.custom-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,doctor,patient',
            // Doctor specific fields
            'specialization' => 'required_if:role,doctor',
            'qualification' => 'required_if:role,doctor',
            'experience' => 'required_if:role,doctor',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($request->role === 'doctor') {
            DoctorProfile::create([
                'user_id' => $user->id,
                'specialization' => $request->specialization,
                'qualification' => $request->qualification,
                'experience' => $request->experience,
                'bio' => $request->bio ?? '',
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
