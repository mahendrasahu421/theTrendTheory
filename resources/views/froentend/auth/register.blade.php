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

.auth-alert-box.warning {
    background: #fffbeb;
    border: 1px solid #fde68a;
    color: #b45309;
}

.auth-alert-box.success {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #15803d;
}

/* ── SOCIAL AUTH BUTTONS ── */
.social-auth-buttons {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 18px;
}

.btn-social {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    height: 46px;
    border-radius: 10px;
    font-size: 13.5px;
    font-weight: 700;
    letter-spacing: 0.3px;
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
    box-sizing: border-box;
}

.btn-social svg {
    flex-shrink: 0;
}

.btn-google {
    background: #ffffff;
    color: #1e293b;
    border: 1.5px solid #cbd5e1;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.btn-google:hover {
    background: #f8fafc;
    border-color: #94a3b8;
    color: #0f172a;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transform: translateY(-1px);
}

.btn-facebook {
    background: #1877f2;
    color: #ffffff;
    border: 1.5px solid #1877f2;
    box-shadow: 0 2px 6px rgba(24, 119, 242, 0.25);
}

.btn-facebook:hover {
    background: #166fe5;
    border-color: #166fe5;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(24, 119, 242, 0.35);
    transform: translateY(-1px);
}

.btn-social:active {
    transform: translateY(0);
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

            {{-- Status & Warning Alerts --}}
            @if(session('warning'))
                <div class="auth-alert-box warning">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>{{ session('warning') }}</span>
                </div>
            @endif

            @if(session('status'))
                <div class="auth-alert-box success">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            {{-- Error Alerts --}}
            @if($errors->any())
                <div class="auth-alert-box error">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            {{-- Client-side Alert Box --}}
            <div id="clientAuthAlert" class="auth-alert-box error" style="display: none;">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span id="clientAuthAlertText"></span>
            </div>

            {{-- ── SOCIAL SIGN UP OPTIONS ── --}}
            <div class="social-auth-buttons">
                <button type="button" class="btn-social btn-google" id="btnGoogleRegister" onclick="handleGoogleSignIn(event)">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335"/>
                    </svg>
                    <span>Sign up with Google</span>
                </button>

                <button type="button" class="btn-social btn-facebook" id="btnFacebookRegister" onclick="handleFacebookSignIn(event)">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#ffffff" xmlns="http://www.w3.org/2000/svg">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    <span>Sign up with Facebook</span>
                </button>
            </div>

            <div class="auth-divider-box" style="margin: 14px 0 16px;">
                <span>OR REGISTER WITH EMAIL</span>
            </div>

            <form method="POST" action="{{ route('register.post') }}" id="registerForm">
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
                        <div id="regEmailFeedback" style="font-size: 11.5px; margin-top: 5px; font-weight: 600; display: none; line-height: 1.4;"></div>
                    </div>

                    <div class="form-floating-group">
                        <label class="form-label-custom" for="regPhone">
                            Phone Number
                            <span class="label-optional">Optional</span>
                        </label>
                        <div class="input-with-icon">
                            <input type="tel" id="regPhone" name="phone" class="input-custom"
                                   value="{{ old('phone') }}"
                                   placeholder="+91 or 10 digits"
                                   autocomplete="tel">
                            <i class="bi bi-phone-fill input-icon-left"></i>
                        </div>
                        <div id="regPhoneFeedback" style="font-size: 11.5px; margin-top: 5px; font-weight: 600; display: none; line-height: 1.4;"></div>
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
{{-- Firebase Web SDK v10 compat --}}
<script src="https://www.gstatic.com/firebasejs/10.13.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.13.0/firebase-auth-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.13.0/firebase-firestore-compat.js"></script>
<script src="{{ asset('js/firebase-ecommerce.js') }}"></script>

<script>
// ── Client-side Alert Helpers ──
function showAuthAlert(msg, type = 'error') {
    const box = document.getElementById('clientAuthAlert');
    const text = document.getElementById('clientAuthAlertText');
    if (!box || !text) return;
    text.innerText = msg;
    box.className = 'auth-alert-box ' + type;
    box.style.display = 'flex';
    box.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function hideAuthAlert() {
    const box = document.getElementById('clientAuthAlert');
    if (box) box.style.display = 'none';
}

// ── Firebase Google Sign-Up Handler ──
async function handleGoogleSignIn(e) {
    if (e) e.preventDefault();
    hideAuthAlert();
    const btn = document.getElementById('btnGoogleRegister');
    const origHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Connecting with Google...';

    try {
        if (!window.TrendFirebase) {
            throw new Error('Firebase Authentication is loading. Please wait a moment and try again.');
        }

        const res = await window.TrendFirebase.signInWithGoogle();
        if (res && res.redirecting) return;
        if (res && res.syncResult && res.syncResult.success) {
            window.location.href = res.syncResult.redirect || '{{ route("home") }}';
            return;
        } else if (res && res.syncResult && res.syncResult.message) {
            throw new Error(res.syncResult.message);
        }
    } catch (err) {
        console.error('Firebase Google Sign-up error:', err);
        btn.disabled = false;
        btn.innerHTML = origHtml;

        if (err && (err.code === 'auth/popup-closed-by-user' || err.code === 'auth/cancelled-popup-request')) {
            return;
        }

        let userMsg = 'Google sign-up could not be completed. Please try again.';
        if (err.code === 'auth/unauthorized-domain') {
            userMsg = 'Domain "' + window.location.hostname + '" is not authorized in Firebase Console yet. Please add it in Firebase Console > Authentication > Settings > Authorized domains.';
        } else if (err.code === 'auth/operation-not-allowed') {
            userMsg = 'Google sign-in is disabled in Firebase Console. Please turn on Google under Authentication > Sign-in method.';
        } else if (err.code === 'auth/network-request-failed') {
            userMsg = 'Network connection issue. Please check your internet connection.';
        } else if (err.message) {
            userMsg = err.message;
        }

        showAuthAlert(userMsg, 'error');
    }
}

// ── Firebase Facebook Sign-Up Handler ──
async function handleFacebookSignIn(e) {
    if (e) e.preventDefault();
    hideAuthAlert();
    const btn = document.getElementById('btnFacebookRegister');
    const origHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Connecting with Facebook...';

    try {
        if (!window.TrendFirebase) {
            throw new Error('Firebase Authentication is loading. Please try again.');
        }

        const res = await window.TrendFirebase.signInWithFacebook();
        if (res && res.redirecting) return;
        if (res && res.syncResult && res.syncResult.success) {
            window.location.href = res.syncResult.redirect || '{{ route("home") }}';
            return;
        } else if (res && res.syncResult && res.syncResult.message) {
            throw new Error(res.syncResult.message);
        }
    } catch (err) {
        console.error('Firebase Facebook Sign-up error:', err);
        btn.disabled = false;
        btn.innerHTML = origHtml;

        if (err && (err.code === 'auth/popup-closed-by-user' || err.code === 'auth/cancelled-popup-request')) {
            return;
        }

        let userMsg = 'Facebook sign-up could not be completed.';
        if (err.code === 'auth/operation-not-allowed') {
            userMsg = 'Facebook sign-in is not enabled in Firebase Console. Please enable Facebook under Authentication > Sign-in method.';
        } else if (err.message) {
            userMsg = err.message;
        }
        showAuthAlert(userMsg, 'warning');
    }
}

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

// ── Email/Password Registration Firebase & Firestore Synchronization ──
document.addEventListener('DOMContentLoaded', function () {
    const regForm = document.getElementById('registerForm');
    if (!regForm) return;

    regForm.addEventListener('submit', async function (e) {
        const email = document.getElementById('regEmail')?.value?.trim();
        const pwd = document.getElementById('regPassword')?.value;
        const name = document.getElementById('regName')?.value?.trim();
        const phone = document.getElementById('regPhone')?.value?.trim();

        if (window.TrendFirebase && email && pwd && pwd.length >= 6) {
            try {
                // Background sync to Firebase Auth & Firestore
                await window.TrendFirebase.signUpWithEmail(email, pwd, name, phone);
                console.log('Firebase user and Firestore record synchronized.');
            } catch (fbErr) {
                // If user already exists in Firebase Auth or network issue, proceed to standard Laravel submission
                console.warn('Firebase background sign-up note:', fbErr.message);
            }
        }
    });

    // ── Real-Time Duplicate Validation for Email & Mobile ──
    let emailCheckTimer = null;
    let phoneCheckTimer = null;

    const emailInput = document.getElementById('regEmail');
    const phoneInput = document.getElementById('regPhone');
    const emailFeedback = document.getElementById('regEmailFeedback');
    const phoneFeedback = document.getElementById('regPhoneFeedback');

    async function performDuplicateCheck(type, value, feedbackEl, inputEl) {
        if (!value) {
            feedbackEl.style.display = 'none';
            feedbackEl.innerHTML = '';
            inputEl.style.borderColor = '';
            return;
        }

        try {
            const response = await fetch("{{ route('auth.check.duplicate') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ type: type, value: value })
            });
            const data = await response.json();

            if (data.exists) {
                feedbackEl.style.display = 'block';
                feedbackEl.style.color = '#dc2626';
                feedbackEl.innerHTML = `<i class="bi bi-exclamation-circle-fill"></i> ${data.message} <a href="{{ route('login') }}" style="color:#00285a;font-weight:700;text-decoration:underline;margin-left:4px;">Sign in</a>`;
                inputEl.style.borderColor = '#dc2626';
            } else if (data.message) {
                feedbackEl.style.display = 'block';
                feedbackEl.style.color = '#16a34a';
                feedbackEl.innerHTML = `<i class="bi bi-check-circle-fill"></i> ${data.message}`;
                inputEl.style.borderColor = '#16a34a';
            } else {
                feedbackEl.style.display = 'none';
                inputEl.style.borderColor = '';
            }
        } catch (e) {
            console.warn('Duplicate check request error:', e);
        }
    }

    if (emailInput && emailFeedback) {
        emailInput.addEventListener('input', function () {
            clearTimeout(emailCheckTimer);
            const val = this.value.trim();
            if (val.length < 5 || !val.includes('@')) {
                emailFeedback.style.display = 'none';
                this.style.borderColor = '';
                return;
            }
            emailCheckTimer = setTimeout(() => performDuplicateCheck('email', val, emailFeedback, emailInput), 400);
        });

        emailInput.addEventListener('blur', function () {
            const val = this.value.trim();
            if (val.length >= 5 && val.includes('@')) {
                performDuplicateCheck('email', val, emailFeedback, emailInput);
            }
        });
    }

    if (phoneInput && phoneFeedback) {
        phoneInput.addEventListener('input', function () {
            clearTimeout(phoneCheckTimer);
            const val = this.value.trim();
            const digits = val.replace(/\D/g, '');
            if (digits.length < 10) {
                phoneFeedback.style.display = 'none';
                this.style.borderColor = '';
                return;
            }
            phoneCheckTimer = setTimeout(() => performDuplicateCheck('phone', val, phoneFeedback, phoneInput), 400);
        });

        phoneInput.addEventListener('blur', function () {
            const val = this.value.trim();
            const digits = val.replace(/\D/g, '');
            if (digits.length >= 10) {
                performDuplicateCheck('phone', val, phoneFeedback, phoneInput);
            }
        });
    }
});
</script>
@endpush
@endsection
