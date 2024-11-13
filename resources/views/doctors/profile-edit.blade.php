@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-6">Edit Profile</h2>

        <form action="{{ route('doctor.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Profile Image -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Profile Image</label>
                @if($profile->profile_image)
                    <div class="mb-2">
                        <img src="{{ Storage::url($profile->profile_image) }}"
                             class="w-32 h-32 rounded-full object-cover">
                    </div>
                @endif
                <input type="file" name="profile_image" accept="image/*"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <!-- Basic Info -->
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Specialization</label>
                    <input type="text" name="specialization" value="{{ old('specialization', $profile->specialization) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Qualification</label>
                    <input type="text" name="qualification" value="{{ old('qualification', $profile->qualification) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                </div>
            </div>

            <!-- Experience and Fee -->
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Experience (years)</label>
                    <input type="text" name="experience" value="{{ old('experience', $profile->experience) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Consultation Fee</label>
                    <input type="number" name="consultation_fee" value="{{ old('consultation_fee', $profile->consultation_fee) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                </div>
            </div>

            <!-- Bio -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                <textarea name="bio" rows="4"
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">{{ old('bio', $profile->bio) }}</textarea>
            </div>

            <!-- Certificates -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Certificates</label>
                <input type="file" name="certificates[]" multiple accept=".pdf,.jpg,.jpeg,.png"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Update Profile
            </button>
        </form>
    </div>
</div>
@endsection
