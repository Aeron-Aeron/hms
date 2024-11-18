<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin')
            ->withCount('appointments')
            ->latest()
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:patient,doctor,admin'
        ]);

        // Prevent changing own role
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $user->update([
            'role' => $request->role
        ]);

        return back()->with('success', 'User role updated successfully.');
    }

    public function show(User $user)
    {
        $user->load(['appointments', 'doctorProfile']);

        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Cannot delete admin users.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
