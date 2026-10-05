<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Supported social providers
     */
    protected array $allowedProviders = ['google', 'facebook'];

    /**
     * Dynamically resolve & set provider credentials from .env or SiteSetting table.
     */
    protected function setupProviderCredentials(string $provider): bool
    {
        $clientId = config("services.{$provider}.client_id");
        $clientSecret = config("services.{$provider}.client_secret");

        // Fallback to SiteSetting database table if not present in config/.env
        if (empty($clientId) && class_exists(SiteSetting::class)) {
            $clientId = SiteSetting::get("{$provider}_client_id");
            $clientSecret = SiteSetting::get("{$provider}_client_secret");

            if (!empty($clientId) && !empty($clientSecret)) {
                config([
                    "services.{$provider}.client_id"     => $clientId,
                    "services.{$provider}.client_secret" => $clientSecret,
                ]);
            }
        }

        if (empty($clientId) || empty($clientSecret) || str_starts_with($clientId, 'your-') || str_starts_with($clientSecret, 'your-')) {
            return false;
        }

        return true;
    }

    /**
     * Redirect the user to the provider authentication page.
     */
    public function redirect(string $provider)
    {
        if (!in_array($provider, $this->allowedProviders, true)) {
            return redirect()->route('login')->withErrors(['email' => 'Unsupported social login provider.']);
        }

        if (!$this->setupProviderCredentials($provider)) {
            $providerName = ucfirst($provider);
            return redirect()->route('login')->with('warning', "{$providerName} login is pending API credentials in .env. Please configure {$providerName} Client ID & Secret or use Email / OTP login.");
        }

        try {
            return Socialite::driver($provider)->redirect();
        } catch (\Throwable $e) {
            Log::error("Socialite redirect error for {$provider}: " . $e->getMessage());
            return redirect()->route('login')->withErrors(['email' => "Unable to connect with " . ucfirst($provider) . ". Please try again or use standard login."]);
        }
    }

    /**
     * Obtain the user information from the provider callback.
     */
    public function callback(Request $request, string $provider)
    {
        if (!in_array($provider, $this->allowedProviders, true)) {
            return redirect()->route('login')->withErrors(['email' => 'Unsupported social login provider.']);
        }

        // Handle user cancellation / denied consent
        if ($request->has('error') || $request->has('error_code')) {
            $desc = $request->get('error_description') ?: 'Authentication was canceled.';
            return redirect()->route('login')->with('warning', ucfirst($provider) . " sign-in was cancelled: {$desc}");
        }

        if (!$this->setupProviderCredentials($provider)) {
            $providerName = ucfirst($provider);
            return redirect()->route('login')->with('warning', "{$providerName} login credentials are missing. Please use Email or OTP to sign in.");
        }

        try {
            // Attempt standard OAuth retrieval first, fallback to stateless if session state mismatch occurs
            try {
                $socialUser = Socialite::driver($provider)->user();
            } catch (\Throwable $statelessFallback) {
                $socialUser = Socialite::driver($provider)->stateless()->user();
            }
        } catch (\Throwable $e) {
            Log::error("Socialite callback error for {$provider}: " . $e->getMessage());
            return redirect()->route('login')->withErrors([
                'email' => "Failed to authenticate with " . ucfirst($provider) . ". Please try again or use Email / OTP."
            ]);
        }

        $providerId = (string) $socialUser->getId();
        $email = $socialUser->getEmail();
        $name = $socialUser->getName() ?: ($socialUser->getNickname() ?: 'Trend Member');
        $avatar = $socialUser->getAvatar();
        $columnId = "{$provider}_id";

        // 1. Check if user already linked this provider ID
        $user = User::where($columnId, $providerId)->first();

        // 2. If not found by provider ID, check if user exists with the same email
        if (!$user && !empty($email)) {
            $user = User::where('email', $email)->first();
            if ($user) {
                // Link this social account to the existing user profile
                $user->{$columnId} = $providerId;
                if (empty($user->social_avatar) && !empty($avatar)) {
                    $user->social_avatar = $avatar;
                }
                $user->save();
            }
        }

        // 3. If still no user, create a brand new account
        if (!$user) {
            $userEmail = !empty($email) ? $email : "{$provider}_{$providerId}@" . config('app.url_host', 'thetrendtheory.com');

            $user = User::create([
                'name'              => $name,
                'email'             => $userEmail,
                $columnId           => $providerId,
                'social_avatar'     => $avatar,
                'password'          => Hash::make(Str::random(32)),
                'role'              => 'customer',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]);

            event(new Registered($user));
        }

        // Ensure user account is active
        if (!$user->is_active) {
            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been deactivated. Please contact support.'
            ]);
        }

        // Login user
        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(route('home'))->with('success', "Welcome to THE TREND THEORY, {$user->name}!");
    }
}
