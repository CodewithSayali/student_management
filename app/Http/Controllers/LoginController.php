<?php

namespace App\Http\Controllers\Auth;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function adminlogin()
    {
        return view('auth.login');
    }



    public function login(Request $request)
    {
        // Validate the credentials
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if the email matches the default user
        if ($credentials['email'] !== 'admin@gmail.com') {
            return response()->json(['errors' => ['email' => 'Only the default user can log in.']], 422);
        }

        // Attempt to log in the user
        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return response()->json(['message' => 'Login successful.']);
        }

        // If authentication fails, return an error response
        return response()->json(['errors' => ['email' => 'Invalid credentials.']], 422);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
