<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DebugController extends Controller
{
    public function checkRole()
    {
        if (auth()->check()) {
            $user = auth()->user();
            return response()->json([
                'user' => $user->toArray(),
                'role' => $user->role,
                'authenticated' => true,
                'session_id' => session()->getId(),
                'auth_check' => auth()->check(),
            ]);
        }

        return response()->json([
            'authenticated' => false,
            'message' => 'User not logged in'
        ]);
    }
}
