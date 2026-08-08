<?php
// routes/auth.php
// Authentication routes — Login, Register, Password Reset

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/otp/send', [AuthController::class, 'sendOtp'])->name('otp.send');
Route::post('/otp/verify', [AuthController::class, 'verifyOtp'])->name('otp.verify');

// ═══════════════════════════════════════════════════
// GUEST ONLY (already logged in users redirect to home)
// ═══════════════════════════════════════════════════
Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Register
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // Forgot Password
    Route::get('/forgot-password', [AuthController::class, 'forgotForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

    // Reset Password
    Route::get('/reset-password/{token}', [AuthController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

});

// ═══════════════════════════════════════════════════
// AUTH REQUIRED
// ═══════════════════════════════════════════════════
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Email Verification
    Route::get('/verify-email', [AuthController::class, 'verifyNotice'])->name('verification.notice');
    Route::get('/verify-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
    Route::post('/email/resend', [AuthController::class, 'resendVerification'])->name('verification.resend');

});
