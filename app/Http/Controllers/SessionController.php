<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    /**
     * Show the login form or redirect if already authenticated.
     */
    public function create()
    {
        // Step 1: Check if the user is already authenticated
        if(Auth::check()){
            return redirect("/home");
        }

        // Step 2: If not authenticated, show the login form
        return view('auth.login');
    }

    /**
     * Handle the login request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        if(!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials.',
            ]);
        }

        try {
            $request->session()->regenerate();
            
            return redirect()->route('users.home')->with('taost', ['type' => 'success', 'message' => 'Login successful. Welcome back!']);
        } catch (\Exception $th) {
            \Log::error('Login failed: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Failed to log in.');
        }
    }

    /**
     * Handle the logout request.
     */
    public function destroy()
    {
        // Step 1: Log out the user
        Auth::logout();

        // Step 2: Invalidate the session to prevent session hijacking
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        // Step 3: Redirect the user to the login page with a success message
        return redirect('/login')->with('success', 'You have successfully logged out.');
    }
}
