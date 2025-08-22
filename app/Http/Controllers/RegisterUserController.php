<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterUserController extends Controller
{
    public function create()
    {
        if(Auth::check()){
            // dd("User already authenticated");
            return redirect("/home");
        }

        // dd("redirected to register page");

        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,   //=> Assosiate array
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'phone' => $request->phone,
            'college_name' => $request->college_name,
        ]);

        Auth::login($user); //Class

        if(!Auth::check()){
            return redirect('/login');
            // dd("Redirecting to login page");
        }

        return redirect('/home')->with('success', 'You have successfully registered.');
        // dd("User registered");
    }  
}
