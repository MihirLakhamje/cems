<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterUserController extends Controller
{
    /**
     * Show the user registration form.
     */
    public function create()
    {
        if (Auth::check()) {
            // dd("User already authenticated");
            return redirect("/home");
        }

        // dd("redirected to register page");

        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        try {
            $user = User::create([
                'name' => $request->name,   //=> Assosiate array
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'college_name' => $request->college_name,
            ]);

            Auth::login($user);

            if (!Auth::check()) {
                return redirect('/login');
            }

            return redirect()->route('users.home')->with('toast', ['type' => 'success', 'message' => 'Registration successful. Welcome!']);
        } catch (\Exception $e) {
            \Log::error('User registration failed: ' . $e->getMessage());
            return redirect()->back()->with('toast', ['type' => 'error', 'message' => 'Registration failed.']);
        }
    }
}
