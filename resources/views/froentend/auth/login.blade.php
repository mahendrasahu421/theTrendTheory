{{-- resources/views/froentend/auth/login.blade.php --}}
@extends('froentend.layouts.app')

@push('seo')
    <title>Login | The Trend Theory</title>
    <meta name="description" content="Login to your The Trend Theory account.">
    <meta name="robots" content="noindex, nofollow">
@endpush

@section('main')
<style>
.auth-section { min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 40px 16px; background: #f8f8f8; }
.auth-card { background: #fff; border-radius: 16px; padding: 40px; width: 100%; max-width: 420px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); }
.auth-logo { text-align: center; font-family: 'Cinzel', serif; font-size: 1.4rem; font-weight: 700; color: #00285a; margin-bottom: 8px; }
.auth-title { text-align: center; font-size: 1.5rem; font-weight: 700; color: #111; margin-bottom: 4px; }
.auth-sub { text-align: center; font-size: 13px; color: #888; margin-bottom: 28px; }
.form-group { margin-bottom: 18px; }
.form-group label { display: block; font-size: 13px; font-weight: 600; color: #444; margin-bottom: 6px; }
.form-group input { width: 100%; padding: 11px 14px; border: 1.5px solid #e0e0e0; border-radius: 10px; font-size: 14px; outline: none; transition: border .2s; }
.form-group input:focus { border-color: #ff3f6c; }
.error-msg { font-size: 12px; color: #ff3f6c; margin-top: 4px; }
.auth-btn { width: 100%; padding: 13px; background: #ff3f6c; color: #fff; border: none; border-radius: 50px; font-size: 15px; font-weight: 700; cursor: pointer; transition: .2s; margin-top: 6px; }
.auth-btn:hover { background: #00285a; }
.auth-footer { text-align: center; font-size: 13px; color: #888; margin-top: 20px; }
.auth-footer a { color: #ff3f6c; text-decoration: none; font-weight: 600; }
.forgot-link { text-align: right; font-size: 12px; margin-top: -12px; margin-bottom: 16px; }
.forgot-link a { color: #888; text-decoration: none; }
.forgot-link a:hover { color: #ff3f6c; }
.divider { display: flex; align-items: center; gap: 10px; margin: 20px 0; }
.divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #e8e8e8; }
.divider span { font-size: 12px; color: #aaa; }
.remember-row { display: flex; align-items: center; gap: 8px; margin-bottom: 16px; }
.remember-row input { width: auto; }
.remember-row label { font-size: 13px; color: #555; margin: 0; font-weight: 400; }
</style>

<section class="auth-section">
    <div class="auth-card">
        <div class="auth-logo">THE TREND THEORY</div>
        <h1 class="auth-title">Welcome back</h1>
        <p class="auth-sub">Login to your account</p>

        {{-- Success message --}}
        @if(session('status'))
            <div style="background:#e8f5e9;color:#2e7d32;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;">
                {{ session('status') }}
            </div>
        @endif

        {{-- Error message --}}
        @if($errors->any())
            <div style="background:#fce4ec;color:#c62828;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}"
                       placeholder="you@example.com"
                       required autocomplete="email">
                @error('email') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                       placeholder="••••••••"
                       required autocomplete="current-password">
                @error('password') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="forgot-link">
                <a href="{{ route('password.request') }}">Forgot password?</a>
            </div>

            <div class="remember-row">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Keep me logged in</label>
            </div>

            <button type="submit" class="auth-btn">LOGIN</button>
        </form>

        <div class="divider"><span>or</span></div>

        <div class="auth-footer">
            Don't have an account?
            <a href="{{ route('register') }}">Create account</a>
        </div>
    </div>
</section>
@endsection