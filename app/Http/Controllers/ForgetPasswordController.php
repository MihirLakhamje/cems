<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class ForgetPasswordController extends Controller
{
    /**
     * Show the form for requesting a password reset link.
     */
    public function showForgotForm()
    {
        if(Auth::check()){
            return redirect()->route('users.home');
        }
        return view('auth.forgot-password');
    }

    /**
     * Handle sending the password reset link email.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        try {
            $status = Password::sendResetLink($request->only('email'));
            
            return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
        } catch (\Exception $e) {
            \Log::error('Password reset link sending failed: ' . $e->getMessage());
            return redirect()->back()->with('toast', ['type' => 'error', 'message' => 'Something went wrong.']);
        }
    }
}
