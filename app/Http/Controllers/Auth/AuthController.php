<?php
// app/Http/Controllers/Auth/AuthController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    // ── LOGIN ──────────────────────────────────────────────
    public function loginForm()
    {
        return view('froentend.auth.login');
    }

    public function login(Request $request)
    {
       
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
               
            if (Auth::user()->role === 'super_admin') {
                return redirect()->route('admin.dashboard.super');
            }

            if (Auth::user()->isStaff()) {
                return redirect()->route('admin.dashboard');
            }

            // Intended URL ya home
            return redirect()->intended(route('home'));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Email or password is incorrect.']);
    }

    // ── REGISTER ───────────────────────────────────────────
    public function registerForm()
    {
        return view('froentend.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:150',
            'email'    => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone'    => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,
            'role'     => 'customer',
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('home')->with('success', 'Welcome to The Trend Theory!');
    }

    // ── LOGOUT ─────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    // ── FORGOT PASSWORD ────────────────────────────────────
    public function forgotForm()
    {
        return view('froentend.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Password reset link sent to your email!')
            : back()->withErrors(['email' => __($status)]);
    }

    // ── RESET PASSWORD ─────────────────────────────────────
    public function resetForm(Request $request, string $token)
    {
        return view('froentend.auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Password reset successfully!')
            : back()->withErrors(['email' => __($status)]);
    }

    // ── EMAIL VERIFICATION ─────────────────────────────────
    public function verifyNotice()
    {
        return view('froentend.auth.verify-email');
    }

    public function verifyEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('home');
        }
        if ($request->user()->markEmailAsVerified()) {
            return redirect()->route('home')->with('success', 'Email verified!');
        }
        return redirect()->route('home');
    }

    public function resendVerification(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'Verification email sent!');
    }
}
