<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FirebaseAuthSyncController extends Controller
{
    /**
     * Synchronize client-side Firebase Auth session with Laravel Auth Session.
     */
    public function sync(Request $request)
    {
        $request->validate([
            'uid'         => 'required|string|max:128',
            'email'       => 'nullable|email|max:150',
            'displayName' => 'nullable|string|max:150',
            'phoneNumber' => 'nullable|string|max:30',
            'photoURL'    => 'nullable|string|max:500',
        ]);

        $uid         = trim($request->input('uid'));
        $email       = trim((string) $request->input('email'));
        $displayName = trim((string) $request->input('displayName'));
        $phoneNumber = trim((string) $request->input('phoneNumber'));
        $photoURL    = trim((string) $request->input('photoURL'));

        try {
            // 1. Look for user by firebase_uid
            $user = User::where('firebase_uid', $uid)->first();

            // 2. If not found, look up by email
            if (!$user && !empty($email)) {
                $user = User::where('email', $email)->first();
            }

            // 3. If not found, look up by phone number
            if (!$user && !empty($phoneNumber)) {
                $cleanPhone = preg_replace('/\D+/', '', $phoneNumber);
                $user = User::where('phone', $phoneNumber)
                    ->orWhere('phone', $cleanPhone)
                    ->orWhere('phone', 'LIKE', '%' . substr($cleanPhone, -10))
                    ->first();
            }

            // 4. Create new user if not found
            if (!$user) {
                $fallbackName = !empty($displayName) 
                    ? $displayName 
                    : (!empty($email) ? Str::before($email, '@') : 'Customer ' . substr(preg_replace('/\D+/', '', $phoneNumber), -4));

                $fallbackEmail = !empty($email)
                    ? $email
                    : 'customer_' . ($phoneNumber ? preg_replace('/\D+/', '', $phoneNumber) : Str::lower(Str::random(8))) . '@thetrendtheory.com';

                $user = User::create([
                    'name'              => $fallbackName,
                    'email'             => $fallbackEmail,
                    'phone'             => !empty($phoneNumber) ? $phoneNumber : null,
                    'firebase_uid'      => $uid,
                    'social_avatar'     => !empty($photoURL) ? $photoURL : null,
                    'password'          => Hash::make(Str::random(32)),
                    'role'              => 'customer',
                    'is_active'         => true,
                    'email_verified_at' => now(),
                ]);

                event(new Registered($user));
            } else {
                // Link or update existing user
                $changed = false;
                if ($user->firebase_uid !== $uid) {
                    $user->firebase_uid = $uid;
                    $changed = true;
                }
                if (empty($user->social_avatar) && !empty($photoURL)) {
                    $user->social_avatar = $photoURL;
                    $changed = true;
                }
                if (empty($user->phone) && !empty($phoneNumber)) {
                    $user->phone = $phoneNumber;
                    $changed = true;
                }
                if ($changed) {
                    $user->save();
                }
            }

            // Check if user is active
            if (!$user->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Account is inactive. Please contact store support.',
                ], 403);
            }

            // Log user in Laravel session
            Auth::login($user, true);
            $intendedUrl = route('home');

            if ($request->hasSession()) {
                $request->session()->regenerate();
                $intendedUrl = session()->pull('url.intended', route('home'));
            }

            return response()->json([
                'success'  => true,
                'message'  => "Authenticated successfully as {$user->name}",
                'redirect' => $user->isStaff() ? route('admin.dashboard') : $intendedUrl,
                'user'     => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'role'  => $user->role,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Firebase auth sync error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to synchronize authentication session: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Return Firebase client-side configuration for frontend scripts.
     */
    public function config()
    {
        return response()->json([
            'apiKey'            => config('services.firebase.api_key', 'AIzaSyBxewN-r_TDJfHBwuzcdIq2Bme6dyRCWVo'),
            'authDomain'        => config('services.firebase.auth_domain', 'the-trend-theory.firebaseapp.com'),
            'projectId'         => config('services.firebase.project_id', 'the-trend-theory'),
            'storageBucket'     => config('services.firebase.storage_bucket', 'the-trend-theory.firebasestorage.app'),
            'messagingSenderId' => config('services.firebase.sender_id', '664156075505'),
            'appId'             => config('services.firebase.app_id', '1:664156075505:web:3b83c116e07b428bef0050'),
        ]);
    }
}
