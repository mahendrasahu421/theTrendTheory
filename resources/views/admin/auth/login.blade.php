<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal | The Trend Theory</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --bg-deep: #070d1e;
            --bg-card: #0f172a;
            --accent-gold: #c5a880;
            --accent-blue: #3b82f6;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border-glow: rgba(59, 130, 246, 0.2);
            --danger: #ef4444;
            --success: #10b981;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at 50% 20%, #172554 0%, #070d1e 80%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-main);
            padding: 24px 16px;
        }

        .admin-login-box {
            width: 100%;
            max-width: 440px;
            background: rgba(15, 23, 42, 0.92);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px 36px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7), 0 0 40px rgba(59, 130, 246, 0.15);
            backdrop-filter: blur(16px);
            position: relative;
            overflow: hidden;
        }

        .admin-login-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #c5a880, #ec4899);
        }

        .portal-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .portal-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 999px;
            background: rgba(59, 130, 246, 0.12);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #60a5fa;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }

        .portal-title {
            font-family: 'Cinzel', serif;
            font-size: 26px;
            font-weight: 900;
            letter-spacing: 2px;
            color: #ffffff;
            margin-bottom: 6px;
        }

        .portal-subtitle {
            font-size: 13px;
            color: var(--text-muted);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #cbd5e1;
            margin-bottom: 8px;
        }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            color: #64748b;
            font-size: 16px;
            pointer-events: none;
        }

        .input-field {
            width: 100%;
            height: 48px;
            padding: 0 14px 0 42px;
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            color: #ffffff;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s ease;
        }

        .input-field:focus {
            outline: none;
            border-color: #3b82f6;
            background: rgba(30, 41, 59, 0.95);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        }

        .toggle-pwd {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            font-size: 16px;
            padding: 4px;
        }

        .toggle-pwd:hover {
            color: #94a3b8;
        }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            font-size: 13px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: var(--text-muted);
            user-select: none;
        }

        .checkbox-label input {
            accent-color: #3b82f6;
            width: 16px;
            height: 16px;
        }

        .btn-submit {
            width: 100%;
            height: 50px;
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            border: none;
            border-radius: 10px;
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.5px;
            cursor: pointer;
            box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            transform: translateY(-1px);
            box-shadow: 0 14px 26px -6px rgba(37, 99, 235, 0.55);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #fca5a5;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.12);
            border: 1px solid rgba(16, 185, 129, 0.35);
            color: #6ee7b7;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-note {
            text-align: center;
            margin-top: 28px;
            font-size: 11px;
            color: #64748b;
        }

        .footer-note a {
            color: #94a3b8;
            text-decoration: none;
        }

        .footer-note a:hover {
            color: #cbd5e1;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="admin-login-box">
    <div class="portal-header">
        <div class="portal-badge">
            <i class="bi bi-shield-lock-fill"></i> Restricted Staff Access
        </div>
        <h1 class="portal-title">VAYU CONTROL</h1>
        <p class="portal-subtitle">The Trend Theory Administrative Console</p>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="alert-error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.post') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="adminEmail">Staff Email</label>
            <div class="input-wrap">
                <i class="bi bi-envelope input-icon"></i>
                <input type="email" name="email" id="adminEmail" class="input-field" placeholder="admin@thetrend.test" value="{{ old('email') }}" required autofocus>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="adminPassword">Password</label>
            <div class="input-wrap">
                <i class="bi bi-lock input-icon"></i>
                <input type="password" name="password" id="adminPassword" class="input-field" placeholder="••••••••••••" required>
                <button type="button" class="toggle-pwd" onclick="togglePasswordVisibility()" aria-label="Toggle password visibility">
                    <i class="bi bi-eye" id="toggleIcon"></i>
                </button>
            </div>
        </div>

        <div class="remember-row">
            <label class="checkbox-label">
                <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                <span>Keep me signed in</span>
            </label>
        </div>

        <button type="submit" class="btn-submit">
            <i class="bi bi-box-arrow-in-right"></i> Sign In to Console
        </button>
    </form>

    <div class="footer-note">
        Authorized administrative personnel only. &bull; <a href="{{ route('home') }}">&larr; Return to Store</a>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        var pwd = document.getElementById('adminPassword');
        var icon = document.getElementById('toggleIcon');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            pwd.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>

</body>
</html>
