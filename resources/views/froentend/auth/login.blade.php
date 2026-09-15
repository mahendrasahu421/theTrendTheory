{{-- resources/views/froentend/auth/login.blade.php --}}
@extends('froentend.layouts.app')

@section('custom_seo')
    <title>Login | Vayu — Luxury Streetwear</title>
    <meta name="description" content="Login to your Vayu account to access orders, wishlist, and exclusive member drops.">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('main')
<style>
/* ═══════════════════════════════════════════════════════════════════
   Vayu — SPACIOUS LUXURY AUTH UI
   ═══════════════════════════════════════════════════════════════════ */
:root {
    --auth-navy: #00285a;
    --auth-navy-dark: #001838;
    --auth-accent: #ff3f6c;
    --auth-accent-hover: #e0325d;
    --auth-bg: #f4f6fa;
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
    max-width: 880px;
    display: flex;
    align-items: stretch;
    justify-content: center;
    gap: 28px;
}

/* ── LEFT: COMPACT EDITORIAL PHOTO CARD ── */
.auth-visual-card {
    flex: 0 0 360px;
    width: 360px;
    min-height: 460px;
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
    max-width: 460px;
    background: #ffffff;
    border-radius: 20px;
    padding: 36px 32px;
    box-shadow: 0 16px 36px rgba(0, 40, 90, 0.07), 0 2px 8px rgba(0, 0, 0, 0.03);
    border: 1px solid rgba(226, 232, 240, 0.9);
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.auth-card-header {
    margin-bottom: 20px;
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

/* ── AUTH TABS (PASSWORD vs OTP) ── */
.auth-tabs-nav {
    display: flex;
    background: #f1f5f9;
    padding: 4px;
    border-radius: 12px;
    margin-bottom: 20px;
    gap: 4px;
}

.auth-tab-btn {
    flex: 1;
    padding: 9px 12px;
    border: none;
    background: transparent;
    border-radius: 9px;
    font-size: 13px;
    font-weight: 700;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.auth-tab-btn.active {
    background: #ffffff;
    color: var(--auth-navy);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
}

/* ── FORM INPUTS ── */
.form-floating-group {
    margin-bottom: 16px;
    position: relative;
}

.form-label-custom {
    display: block;
    font-size: 11.5px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #475569;
    margin-bottom: 6px;
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

.form-options-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: -4px 0 18px 0;
    font-size: 13px;
}

.custom-checkbox-wrap {
    display: flex;
    align-items: center;
    gap: 7px;
    cursor: pointer;
    color: #475569;
    user-select: none;
}

.custom-checkbox-wrap input {
    accent-color: var(--auth-navy);
    width: 16px;
    height: 16px;
    cursor: pointer;
}

.forgot-link-btn {
    color: var(--auth-navy);
    font-weight: 700;
    text-decoration: none;
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
}

.btn-auth-submit:active {
    transform: translateY(0);
}

/* ── OTP BOXES ── */
.otp-inputs-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 8px;
    margin-bottom: 16px;
}

.otp-box-digit {
    width: 100%;
    height: 48px;
    text-align: center;
    font-size: 19px;
    font-weight: 800;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    color: var(--auth-navy);
    outline: none;
    transition: all 0.2s;
}

.otp-box-digit:focus {
    border-color: var(--auth-navy);
    box-shadow: 0 0 0 3px rgba(0, 40, 90, 0.12);
}

.otp-timer-text {
    font-size: 12.5px;
    color: var(--auth-text-muted);
    text-align: center;
    margin-bottom: 16px;
}

.btn-resend-otp {
    background: none;
    border: none;
    color: var(--auth-navy);
    font-weight: 700;
    cursor: pointer;
    text-decoration: underline;
    padding: 0;
}

.btn-resend-otp:disabled {
    color: #94a3b8;
    cursor: not-allowed;
    text-decoration: none;
}

/* ── FOOTER & SWITCH ── */
.auth-divider-box {
    display: flex;
    align-items: center;
    margin: 20px 0 16px;
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
    margin-bottom: 18px;
}

.auth-alert-box.success {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #15803d;
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
        padding: 38px 26px;
    }
}
</style>

<div class="auth-page-wrapper">
    <div class="auth-main-layout">
        
        {{-- ── LEFT: SPACIOUS EDITORIAL PHOTO CARD (NO CONTENT CLUTTER) ── --}}
        <div class="auth-visual-card">
            <img src="https://images.unsplash.com/photo-1509631179647-0177331693ae?w=1200&auto=format&fit=crop&q=85" 
                 alt="Vayu Luxury Streetwear" 
                 class="auth-visual-img">
            
            <div class="auth-visual-tag">
                <span class="tag-dot"></span>
                Vayu &bull; LUXURY STREETWEAR
            </div>
        </div>

        {{-- ── RIGHT: SPACIOUS AUTHENTICATION CARD PANEL ── --}}
        <div class="auth-card-panel">
            <div class="auth-card-header">
                <div class="auth-brand-mini">Vayu</div>
                <h1 class="auth-card-title">Welcome Back</h1>
                <p class="auth-card-sub">Sign in to your account to continue shopping</p>
            </div>

            {{-- Status & Error Alerts --}}
            @if(session('status'))
                <div class="auth-alert-box success">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="auth-alert-box error">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            {{-- Mode Switch Tabs --}}
            <div class="auth-tabs-nav">
                <button type="button" class="auth-tab-btn active" id="tabBtnPassword" onclick="switchAuthTab('password')">
                    <i class="bi bi-key-fill"></i> Password Login
                </button>
                <button type="button" class="auth-tab-btn" id="tabBtnOtp" onclick="switchAuthTab('otp')">
                    <i class="bi bi-phone-vibrate-fill"></i> Instant OTP
                </button>
            </div>

            {{-- ── 1. PASSWORD LOGIN FORM ── --}}
            <form method="POST" action="{{ route('login.post') }}" id="passwordLoginForm">
                @csrf

                <div class="form-floating-group">
                    <label class="form-label-custom" for="loginEmail">Email Address</label>
                    <div class="input-with-icon">
                        <input type="email" id="loginEmail" name="email" class="input-custom"
                               value="{{ old('email') }}"
                               required autocomplete="email">
                        <i class="bi bi-envelope-fill input-icon-left"></i>
                    </div>
                </div>

                <div class="form-floating-group">
                    <label class="form-label-custom" for="loginPassword">Password</label>
                    <div class="input-with-icon">
                        <input type="password" id="loginPassword" name="password" class="input-custom"
                               required autocomplete="current-password">
                        <i class="bi bi-lock-fill input-icon-left"></i>
                        <button type="button" class="btn-toggle-pwd" onclick="togglePasswordVisibility('loginPassword', this)" aria-label="Toggle password visibility">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                    </div>
                </div>

                <div class="form-options-row">
                    <label class="custom-checkbox-wrap">
                        <input type="checkbox" name="remember" id="rememberMe" {{ old('remember') ? 'checked' : '' }}>
                        <span>Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-link-btn">Forgot Password?</a>
                </div>

                <button type="submit" class="btn-auth-submit" id="btnPasswordSubmit">
                    <span>SIGN IN</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            {{-- ── 2. INSTANT OTP LOGIN FORM (Hidden by default) ── --}}
            <div id="otpLoginForm" style="display: none;">
                <div id="otpStepSend">
                    <div class="form-floating-group">
                        <label class="form-label-custom" for="otpIdentifier">Phone Number or Email</label>
                        <div class="input-with-icon">
                            <input type="text" id="otpIdentifier" class="input-custom">
                            <i class="bi bi-person-badge-fill input-icon-left"></i>
                        </div>
                    </div>
                    <button type="button" class="btn-auth-submit" id="btnSendOtp" onclick="handleSendOtp()">
                        <span>SEND LOGIN OTP</span>
                        <i class="bi bi-send-fill"></i>
                    </button>
                </div>

                <div id="otpStepVerify" style="display: none;">
                    <div style="font-size: 13.5px; color: #475569; margin-bottom: 16px; text-align: center;">
                        Enter 6-digit OTP sent to <strong id="otpTargetDisplay" style="color: #00285a;"></strong>
                    </div>

                    <div class="otp-inputs-grid">
                        <input type="text" maxlength="1" class="otp-box-digit" oninput="moveToNextOtp(this, 1)" onkeydown="otpKeyHandle(event, 0)" autofocus>
                        <input type="text" maxlength="1" class="otp-box-digit" oninput="moveToNextOtp(this, 2)" onkeydown="otpKeyHandle(event, 1)">
                        <input type="text" maxlength="1" class="otp-box-digit" oninput="moveToNextOtp(this, 3)" onkeydown="otpKeyHandle(event, 2)">
                        <input type="text" maxlength="1" class="otp-box-digit" oninput="moveToNextOtp(this, 4)" onkeydown="otpKeyHandle(event, 3)">
                        <input type="text" maxlength="1" class="otp-box-digit" oninput="moveToNextOtp(this, 5)" onkeydown="otpKeyHandle(event, 4)">
                        <input type="text" maxlength="1" class="otp-box-digit" oninput="moveToNextOtp(this, 6)" onkeydown="otpKeyHandle(event, 5)">
                    </div>

                    <div class="otp-timer-text">
                        Resend OTP in <strong id="otpCountdown">30</strong>s &bull;
                        <button type="button" class="btn-resend-otp" id="btnResendOtp" onclick="handleSendOtp()" disabled>Resend Now</button>
                    </div>

                    <button type="button" class="btn-auth-submit" id="btnVerifyOtp" onclick="handleVerifyOtp()">
                        <span>VERIFY &amp; LOGIN</span>
                        <i class="bi bi-shield-check"></i>
                    </button>
                    
                    <button type="button" style="width:100%; background:none; border:none; color:#64748b; font-size:13px; font-weight:600; margin-top:14px; cursor:pointer;" onclick="resetOtpSteps()">
                        &larr; Change Number / Email
                    </button>
                </div>
            </div>

            {{-- Divider --}}
            <div class="auth-divider-box">
                <span>NEW TO Vayu?</span>
            </div>

            {{-- Switch to Register --}}
            <div class="auth-bottom-switch">
                Don't have an account?
                <a href="{{ route('register') }}">Create Free Account</a>
            </div>

        </div>

    </div>
</div>

@push('scripts')
<script>
// ── Switch between Password & OTP Tabs ──
function switchAuthTab(tab) {
    const pwdForm = document.getElementById('passwordLoginForm');
    const otpForm = document.getElementById('otpLoginForm');
    const tabPwd = document.getElementById('tabBtnPassword');
    const tabOtp = document.getElementById('tabBtnOtp');

    if (tab === 'password') {
        pwdForm.style.display = 'block';
        otpForm.style.display = 'none';
        tabPwd.classList.add('active');
        tabOtp.classList.remove('active');
    } else {
        pwdForm.style.display = 'none';
        otpForm.style.display = 'block';
        tabOtp.classList.add('active');
        tabPwd.classList.remove('active');
    }
}

// ── Toggle Password Visibility ──
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

// ── OTP Handling ──
let otpTimer = null;
let currentIdentifier = '';

function handleSendOtp() {
    const input = document.getElementById('otpIdentifier');
    const val = input.value.trim();
    if (!val || val.length < 5) {
        alert('Please enter a valid phone number or email address.');
        input.focus();
        return;
    }

    currentIdentifier = val;
    const btn = document.getElementById('btnSendOtp');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> SENDING OTP...';

    fetch('{{ route("otp.send") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ identifier: val })
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<span>SEND LOGIN OTP</span> <i class="bi bi-send-fill"></i>';

        if (data.success) {
            document.getElementById('otpStepSend').style.display = 'none';
            document.getElementById('otpStepVerify').style.display = 'block';
            document.getElementById('otpTargetDisplay').innerText = val;
            startOtpTimer(30);

            // Focus first digit box
            const firstBox = document.querySelectorAll('.otp-box-digit')[0];
            if (firstBox) firstBox.focus();

            if (data.otp) {
                console.log('Development OTP:', data.otp);
            }
        } else {
            alert(data.message || 'Could not send OTP. Please try again.');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<span>SEND LOGIN OTP</span> <i class="bi bi-send-fill"></i>';
        alert('Something went wrong. Please check your connection.');
    });
}

function startOtpTimer(seconds) {
    clearInterval(otpTimer);
    let rem = seconds;
    const countdownEl = document.getElementById('otpCountdown');
    const resendBtn = document.getElementById('btnResendOtp');
    resendBtn.disabled = true;

    otpTimer = setInterval(() => {
        rem--;
        if (countdownEl) countdownEl.innerText = rem;
        if (rem <= 0) {
            clearInterval(otpTimer);
            resendBtn.disabled = false;
        }
    }, 1000);
}

function resetOtpSteps() {
    document.getElementById('otpStepSend').style.display = 'block';
    document.getElementById('otpStepVerify').style.display = 'none';
    clearInterval(otpTimer);
}

function moveToNextOtp(current, nextIndex) {
    current.value = current.value.replace(/[^0-9]/g, '');
    if (current.value.length === 1 && nextIndex < 6) {
        const boxes = document.querySelectorAll('.otp-box-digit');
        if (boxes[nextIndex]) {
            boxes[nextIndex].focus();
        }
    }
    // If all filled, auto trigger verify
    const code = getOtpCode();
    if (code.length === 6) {
        handleVerifyOtp();
    }
}

function otpKeyHandle(e, idx) {
    const boxes = document.querySelectorAll('.otp-box-digit');
    if (e.key === 'Backspace' && !boxes[idx].value && idx > 0) {
        boxes[idx - 1].focus();
    }
}

function getOtpCode() {
    let code = '';
    document.querySelectorAll('.otp-box-digit').forEach(b => code += b.value.trim());
    return code;
}

function handleVerifyOtp() {
    const code = getOtpCode();
    if (code.length !== 6) {
        alert('Please enter complete 6-digit OTP.');
        return;
    }

    const btn = document.getElementById('btnVerifyOtp');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> VERIFYING...';

    fetch('{{ route("otp.verify") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            identifier: currentIdentifier,
            otp: code
        })
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<span>VERIFY &amp; LOGIN</span> <i class="bi bi-shield-check"></i>';

        if (data.success) {
            window.location.href = data.redirect || '{{ route("home") }}';
        } else {
            alert(data.message || 'Invalid OTP code.');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<span>VERIFY &amp; LOGIN</span> <i class="bi bi-shield-check"></i>';
        alert('Verification failed. Please try again.');
    });
}
</script>
@endpush
@endsection