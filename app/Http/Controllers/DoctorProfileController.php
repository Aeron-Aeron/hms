<?php

namespace App\Http\Controllers;

use App\Models\DoctorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DoctorProfileController extends Controller
{
    public function edit()
    {
        $profile = auth()->user()->doctorProfile ?? new DoctorProfile();
        return view('doctors.profile-edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'specialization' => 'required|string|max:255',
            'bio' => 'required|string',
            'qualification' => 'required|string|max:255',
            'experience' => 'required|string|max:255',
            'consultation_fee' => 'required|numeric',
            'profile_image' => 'nullable|image|max:1024',
            'certificates' => 'nullable|array',
            'certificates.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $profile = auth()->user()->doctorProfile ?? new DoctorProfile();

        if ($request->hasFile('profile_image')) {
            if ($profile->profile_image) {
                Storage::delete($profile->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('profile-images');
        }

        if ($request->hasFile('certificates')) {
            $certificates = [];
            foreach ($request->file('certificates') as $certificate) {
                $certificates[] = $certificate->store('certificates');
            }
            $validated['certificates'] = $certificates;
        }

        $profile->fill($validated);
        $profile->user_id = auth()->id();
        $profile->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
