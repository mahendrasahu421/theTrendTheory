{{-- resources/views/froentend/auth/register.blade.php --}}
@extends('froentend.layouts.app')

@section('custom_seo')
    <title>Create Account | THE TREND THEORY — Luxury Streetwear</title>
    <meta name="description" content="Join THE TREND THEORY for exclusive streetwear drops, wishlist sync, and fast checkout.">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('main')
<style>
/* ═══════════════════════════════════════════════════════════════════
   THE TREND THEORY — LUXURY REGISTRATION UI
   ═══════════════════════════════════════════════════════════════════ */
:root {
    --auth-navy: #00285a;
    --auth-navy-dark: #001838;
    --auth-accent: #ff3f6c;
    --auth-accent-hover: #e0325d;
    --auth-bg: #f8fafc;
    --auth-border: #e2e8f0;
    --auth-text-dark: #0f172a;
    --auth-text-muted: #64748b;
    --auth-font-head: 'Cinzel', serif;
    --auth-font-body: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
}

.auth-page-wrapper {
    min-height: calc(100vh - 80px);
    background: linear-gradient(180deg, #f8fafc 0%, #edf2f8 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 36px 20px;
    font-family: var(--auth-font-body);
}

/* ── COMPACT BALANCED DUAL CARD CONTAINER ── */
.auth-main-layout {
    width: 100%;
    max-width: 920px;
    display: flex;
    align-items: stretch;
    justify-content: center;
    gap: 28px;
}

/* ── LEFT: COMPACT EDITORIAL PHOTO CARD ── */
.auth-visual-card {
    flex: 0 0 360px;
    width: 360px;
    min-height: 520px;
    border-radius: 20px;
    overflow: hidden;
    position: relative;
    background: #001838;
    box-shadow: 0 16px 36px rgba(0, 40, 90, 0.1), 0 2px 8px rgba(0, 0, 0, 0.04);
    border: 1px solid rgba(226, 232, 240, 0.8);
    display: flex;
}

.auth-visual-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.6s cubic-bezier(0.2, 0.8, 0.2, 1);
}

.auth-visual-tag {
    position: absolute;
    bottom: 18px;
    left: 18px;
    background: rgba(0, 24, 56, 0.78);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 6px 13px;
    border-radius: 999px;
    font-size: 10.5px;
    font-weight: 800;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    color: #ffffff;
    display: flex;
    align-items: center;
    gap: 7px;
}

.auth-visual-tag .tag-dot {
    width: 5px;
    height: 5px;
    background: #ff3f6c;
    border-radius: 50%;
    box-shadow: 0 0 6px #ff3f6c;
}

/* ── RIGHT: COMPACT AUTHENTICATION CARD ── */
.auth-card-panel {
    flex: 1;
    max-width: 500px;
    background: #ffffff;
    border-radius: 20px;
    padding: 34px 32px;
    box-shadow: 0 16px 36px rgba(0, 40, 90, 0.07), 0 2px 8px rgba(0, 0, 0, 0.03);
    border: 1px solid rgba(226, 232, 240, 0.9);
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.auth-card-header {
    margin-bottom: 18px;
}

.auth-brand-mini {
    font-family: var(--auth-font-head);
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 1.2px;
    color: var(--auth-navy);
    text-transform: uppercase;
    margin-bottom: 6px;
}

.auth-card-title {
    font-size: 23px;
    font-weight: 800;
    color: var(--auth-text-dark);
    letter-spacing: -0.3px;
    margin: 0 0 6px 0;
}

.auth-card-sub {
    font-size: 13.5px;
    color: var(--auth-text-muted);
    line-height: 1.45;
    margin: 0;
}

/* ── FORM ELEMENTS ── */
.form-grid-2col {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.form-floating-group {
    margin-bottom: 14px;
    position: relative;
}

.form-label-custom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 11.5px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #475569;
    margin-bottom: 6px;
}

.label-optional {
    color: #94a3b8;
    font-size: 10.5px;
    font-weight: 600;
    text-transform: none;
    letter-spacing: normal;
}

.input-with-icon {
    position: relative;
}

.input-icon-left {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 15px;
    pointer-events: none;
    transition: color 0.2s;
}

.input-custom {
    width: 100%;
    height: 46px;
    padding: 0 14px 0 42px;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    color: var(--auth-text-dark);
    background: #ffffff;
    outline: none;
    transition: all 0.2s ease;
}

.input-custom:focus {
    border-color: var(--auth-navy);
    box-shadow: 0 0 0 3px rgba(0, 40, 90, 0.1);
}

.input-custom:focus + .input-icon-left,
.input-with-icon:focus-within .input-icon-left {
    color: var(--auth-navy);
}

.btn-toggle-pwd {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #94a3b8;
    font-size: 16px;
    cursor: pointer;
    padding: 4px;
    transition: color 0.15s;
}

/* ── SUBMIT BUTTON ── */
.btn-auth-submit {
    width: 100%;
    height: 46px;
    background: var(--auth-navy);
    color: #ffffff;
    border: none;
    border-radius: 10px;
    font-size: 14.5px;
    font-weight: 800;
    letter-spacing: 0.5px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.25s ease;
    box-shadow: 0 4px 14px rgba(0, 40, 90, 0.2);
    margin-top: 4px;
}

.btn-auth-submit:active {
    transform: translateY(0);
}

.auth-terms-note {
    color: #94a3b8;
    font-size: 11px;
    line-height: 1.5;
    text-align: center;
    margin: 10px 0 0 0;
}

.auth-terms-note a {
    color: #64748b;
    font-weight: 700;
    text-decoration: underline;
}

/* ── FOOTER & SWITCH ── */
.auth-divider-box {
    display: flex;
    align-items: center;
    margin: 18px 0 14px;
    gap: 12px;
}

.auth-divider-box::before,
.auth-divider-box::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e2e8f0;
}

.auth-divider-box span {
    font-size: 11px;
    color: #94a3b8;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.auth-bottom-switch {
    text-align: center;
    font-size: 13.5px;
    color: var(--auth-text-muted);
}

.auth-bottom-switch a {
    color: var(--auth-navy);
    font-weight: 800;
    text-decoration: none;
    margin-left: 5px;
    transition: color 0.15s;
}

/* ── ALERT NOTICES ── */
.auth-alert-box {
    padding: 12px 14px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
}

.auth-alert-box.error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #b91c1c;
}

/* ── RESPONSIVE ADAPTATION ── */
@media (max-width: 900px) {
    .auth-main-layout {
        flex-direction: column;
        align-items: center;
        gap: 0;
        max-width: 480px;
    }
    .auth-visual-card {
        display: none;
    }
    .auth-card-panel {
        width: 100%;
        max-width: 100%;
        padding: 34px 24px;
    }
    .form-grid-2col {
        grid-template-columns: 1fr;
        gap: 0;
    }
}
</style>

<div class="auth-page-wrapper">
    <div class="auth-main-layout">
        
        {{-- ── LEFT: COMPACT EDITORIAL PHOTO CARD (NO CONTENT CLUTTER) ── --}}
        <div class="auth-visual-card">
            <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=1200&auto=format&fit=crop&q=85" 
                 alt="THE TREND THEORY Luxury Streetwear" 
                 class="auth-visual-img">
            
            <div class="auth-visual-tag">
                <span class="tag-dot"></span>
                THE TREND THEORY &bull; LUXURY STREETWEAR
            </div>
        </div>

        {{-- ── RIGHT: REGISTRATION CARD PANEL ── --}}
        <div class="auth-card-panel">
            <div class="auth-card-header">
                <div class="auth-brand-mini">THE TREND THEORY</div>
                <h1 class="auth-card-title">Create Account</h1>
                <p class="auth-card-sub">Join THE TREND THEORY for exclusive drops &amp; rewards</p>
            </div>

            {{-- Error Alerts --}}
            @if($errors->any())
                <div class="auth-alert-box error">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}">
                @csrf

                {{-- Full Name --}}
                <div class="form-floating-group">
                    <label class="form-label-custom" for="regName">Full Name</label>
                    <div class="input-with-icon">
                        <input type="text" id="regName" name="name" class="input-custom"
                               value="{{ old('name') }}"
                               style="text-transform: capitalize;"
                               onblur="this.value = this.value.replace(/\b\w/g, function(l){return l.toUpperCase();})"
                               required autocomplete="name">
                        <i class="bi bi-person-fill input-icon-left"></i>
                    </div>
                </div>

                {{-- Email & Phone Grid --}}
                <div class="form-grid-2col">
                    <div class="form-floating-group">
                        <label class="form-label-custom" for="regEmail">Email Address</label>
                        <div class="input-with-icon">
                            <input type="email" id="regEmail" name="email" class="input-custom"
                                   value="{{ old('email') }}"
                                   required autocomplete="email">
                            <i class="bi bi-envelope-fill input-icon-left"></i>
                        </div>
                    </div>

                    <div class="form-floating-group">
                        <label class="form-label-custom" for="regPhone">
                            Phone Number
                            <span class="label-optional">Optional</span>
                        </label>
                        <div class="input-with-icon">
                            <input type="tel" id="regPhone" name="phone" class="input-custom"
                                   value="{{ old('phone') }}"
                                   autocomplete="tel">
                            <i class="bi bi-phone-fill input-icon-left"></i>
                        </div>
                    </div>
                </div>

                {{-- Password & Confirm Password Grid --}}
                <div class="form-grid-2col">
                    <div class="form-floating-group">
                        <label class="form-label-custom" for="regPassword">Password</label>
                        <div class="input-with-icon">
                            <input type="password" id="regPassword" name="password" class="input-custom"
                                   required autocomplete="new-password">
                            <i class="bi bi-lock-fill input-icon-left"></i>
                            <button type="button" class="btn-toggle-pwd" onclick="togglePasswordVisibility('regPassword', this)" aria-label="Toggle password visibility">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-floating-group">
                        <label class="form-label-custom" for="regPasswordConfirm">Confirm Password</label>
                        <div class="input-with-icon">
                            <input type="password" id="regPasswordConfirm" name="password_confirmation" class="input-custom"
                                   required autocomplete="new-password">
                            <i class="bi bi-shield-lock-fill input-icon-left"></i>
                            <button type="button" class="btn-toggle-pwd" onclick="togglePasswordVisibility('regPasswordConfirm', this)" aria-label="Toggle password confirmation visibility">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-auth-submit">
                    <span>CREATE ACCOUNT</span>
                    <i class="bi bi-arrow-right"></i>
                </button>

                <p class="auth-terms-note">
                    By signing up, you agree to our 
                    <a href="/pages/terms-of-use">Terms of Use</a> &amp; 
                    <a href="/pages/privacy-policy">Privacy Policy</a>.
                </p>
            </form>

            {{-- Divider --}}
            <div class="auth-divider-box">
                <span>ALREADY A MEMBER?</span>
            </div>

            {{-- Switch to Login --}}
            <div class="auth-bottom-switch">
                Already have an account?
                <a href="{{ route('login') }}">Sign In</a>
            </div>

        </div>

    </div>
</div>

@push('scripts')
<script>
function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash-fill';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye-fill';
    }
}
</script>
@endpush
@endsection
