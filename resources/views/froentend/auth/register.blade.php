{{-- resources/views/froentend/auth/register.blade.php --}}
@extends('froentend.layouts.app')

@push('seo')
    <title>Create Account | The Trend Theory</title>
    <meta name="description" content="Create your The Trend Theory account and start shopping.">
    <meta name="robots" content="noindex, nofollow">
@endpush

@section('main')
<style>
.auth-section { min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 40px 16px; background: #f8f8f8; }
.auth-card { background: #fff; border-radius: 16px; padding: 40px; width: 100%; max-width: 440px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); }
.auth-logo { text-align: center; font-family: 'Cinzel', serif; font-size: 1.4rem; font-weight: 700; color: #00285a; margin-bottom: 8px; }
.auth-title { text-align: center; font-size: 1.5rem; font-weight: 700; color: #111; margin-bottom: 4px; }
.auth-sub { text-align: center; font-size: 13px; color: #888; margin-bottom: 28px; }
.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-size: 13px; font-weight: 600; color: #444; margin-bottom: 6px; }
.form-group input { width: 100%; padding: 11px 14px; border: 1.5px solid #e0e0e0; border-radius: 10px; font-size: 14px; outline: none; transition: border .2s; }
.form-group input:focus { border-color: #ff3f6c; }
.error-msg { font-size: 12px; color: #ff3f6c; margin-top: 4px; }
.auth-btn { width: 100%; padding: 13px; background: #ff3f6c; color: #fff; border: none; border-radius: 50px; font-size: 15px; font-weight: 700; cursor: pointer; transition: .2s; margin-top: 6px; }
.auth-btn:hover { background: #00285a; }
.auth-footer { text-align: center; font-size: 13px; color: #888; margin-top: 20px; }
.auth-footer a { color: #ff3f6c; text-decoration: none; font-weight: 600; }
.terms-note { font-size: 11px; color: #aaa; text-align: center; margin-top: 12px; line-height: 1.6; }
.terms-note a { color: #888; }
</style>

<section class="auth-section">
    <div class="auth-card">
        <div class="auth-logo">THE TREND THEORY</div>
        <h1 class="auth-title">Create Account</h1>
        <p class="auth-sub">Join the movement</p>

        @if($errors->any())
            <div style="background:#fce4ec;color:#c62828;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name"
                       value="{{ old('name') }}"
                       placeholder="Your name"
                       required autocomplete="name">
                @error('name') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}"
                       placeholder="you@example.com"
                       required autocomplete="email">
                @error('email') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="phone">Phone Number <span style="color:#aaa;font-weight:400">(optional)</span></label>
                <input type="tel" id="phone" name="phone"
                       value="{{ old('phone') }}"
                       placeholder="+91 XXXXX XXXXX">
                @error('phone') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                       placeholder="Min 8 characters"
                       required autocomplete="new-password">
                @error('password') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       placeholder="Repeat password"
                       required>
            </div>

            <button type="submit" class="auth-btn">CREATE ACCOUNT</button>

            <p class="terms-note">
                By creating an account you agree to our
                <a href="/pages/terms-of-use">Terms of Use</a> and
                <a href="/pages/privacy-policy">Privacy Policy</a>
            </p>
        </form>

        <div class="auth-footer">
            Already have an account?
            <a href="{{ route('login') }}">Login</a>
        </div>
    </div>
</section>
@endsection