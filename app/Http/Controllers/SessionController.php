<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function create()
    {
        // Step 1: Check if the user is already authenticated
        if(Auth::check()){
            return redirect("/home");
        }

        // Step 2: If not authenticated, show the login form
        return view('auth.login');
    }

    public function store(Request $request)
    {
        // Step 1: Validate the login credentials
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        // Step 2: Attempt to log in the user with the provided credentials
        if(!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials.',
            ]);
        }

        try {
            // Step 3: Regenerate the session to prevent session fixation attacks
            $request->session()->regenerate();
            
            // Step 4: Redirect the user to the home page with a success message
            return redirect('/home')->with('success', 'You have successfully logged in.');
        } catch (\Exception $th) {
            // Step 5: Log the error and redirect back with an error message
            \Log::error('Login failed: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Failed to log in. Please try again.');
        }
    }

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
