<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    /**
     * Show the password reset form.
     */
    public function showResetForm($token)
    {
        if(Auth::check()){
            return redirect()->route('users.home');
        }
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Handle the password reset.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        try {
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    ])->save();
                }
            );
            
            return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
        } catch (\Exception $e) {
            \Log::error('Password reset failed: ' . $e->getMessage());
            return redirect()->back()->with('toast', ['type' => 'error', 'message' => 'Something went wrong.']);
        }
    }
}
