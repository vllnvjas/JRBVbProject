<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Account Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-1: #f2efe8;
            --bg-2: #e8e5dd;
            --panel: #fffdf8;
            --panel-soft: #f7f1e4;
            --ink: #1f1f1a;
            --muted: #5e655b;
            --brand: #165443;
            --brand-strong: #113f33;
            --accent: #c08239;
            --line: #ddd3c0;
            --danger-bg: #ffe7e5;
            --danger-ink: #8a2d23;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Manrope', sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 10% 15%, rgba(192, 130, 57, 0.25), transparent 28%),
                radial-gradient(circle at 86% 8%, rgba(22, 84, 67, 0.22), transparent 34%),
                linear-gradient(160deg, var(--bg-1) 0%, var(--bg-2) 100%);
            display: grid;
            place-items: center;
            padding: 1.25rem;
        }

        .login-shell {
            width: 100%;
            max-width: 980px;
            border: 1px solid var(--line);
            border-radius: 24px;
            overflow: hidden;
            background: var(--panel);
            box-shadow: 0 18px 35px rgba(25, 28, 22, 0.15);
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            animation: enter 0.5s ease;
        }

        .visual-side {
            position: relative;
            background:
                linear-gradient(140deg, rgba(16, 58, 47, 0.95) 0%, rgba(25, 90, 72, 0.92) 65%, rgba(39, 125, 100, 0.88) 100%);
            color: #eef8f4;
            padding: 2.2rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .visual-side::after {
            content: '';
            position: absolute;
            width: 220px;
            height: 220px;
            right: -70px;
            top: -70px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            filter: blur(1px);
        }

        .app-badge {
            position: relative;
            z-index: 1;
            width: fit-content;
            padding: 0.38rem 0.8rem;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.35);
            background: rgba(255, 255, 255, 0.12);
            text-transform: uppercase;
            letter-spacing: 0.09em;
            font-size: 0.74rem;
            font-weight: 800;
        }

        .hero-copy {
            position: relative;
            z-index: 1;
            margin-top: 1.8rem;
        }

        .hero-copy h1 {
            margin: 0;
            font-family: 'Fraunces', serif;
            font-weight: 700;
            font-size: clamp(1.65rem, 2.3vw, 2.25rem);
            line-height: 1.2;
        }

        .hero-copy p {
            margin: 0.9rem 0 0;
            color: rgba(240, 253, 248, 0.86);
            max-width: 28ch;
        }

        .hero-stats {
            position: relative;
            z-index: 1;
            display: flex;
            gap: 0.7rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .hero-pill {
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.22);
            background: rgba(255, 255, 255, 0.1);
            padding: 0.5rem 0.72rem;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .form-side {
            padding: 2.1rem 2rem;
            background: linear-gradient(180deg, var(--panel) 0%, var(--panel-soft) 100%);
        }

        .form-side h2 {
            margin: 0;
            font-family: 'Fraunces', serif;
            font-weight: 700;
            font-size: 1.75rem;
        }

        .subtitle {
            margin: 0.4rem 0 1.4rem;
            color: var(--muted);
            font-size: 0.95rem;
        }

        .notice {
            margin-bottom: 1rem;
            border-radius: 12px;
            padding: 0.72rem 0.85rem;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .notice-error {
            background: var(--danger-bg);
            border: 1px solid #f1b5ae;
            color: var(--danger-ink);
        }

        .notice-success {
            background: #e7f7ee;
            border: 1px solid #b9e3c9;
            color: #1f6b3f;
        }

        .field-group {
            margin-bottom: 1rem;
        }

        .password-wrap {
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 0.35rem;
            font-weight: 700;
            color: #2d332d;
            font-size: 0.92rem;
        }

        input[type='text'],
        input[type='password'] {
            width: 100%;
            border-radius: 12px;
            border: 1px solid #cfc4ae;
            padding: 0.72rem 2.9rem 0.72rem 0.78rem;
            font-size: 0.95rem;
            color: #1f211c;
            background: #fffdfa;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 0.5rem;
            transform: translateY(-50%);
            width: 2.1rem;
            height: 2.1rem;
            border: 0;
            border-radius: 10px;
            background: transparent;
            color: #3b4338;
            display: grid;
            place-items: center;
            cursor: pointer;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .toggle-password:hover {
            background: rgba(22, 84, 67, 0.09);
            color: var(--brand);
        }

        .toggle-password:focus-visible {
            outline: 2px solid rgba(22, 84, 67, 0.45);
            outline-offset: 2px;
        }

        .toggle-password svg {
            width: 1.05rem;
            height: 1.05rem;
            pointer-events: none;
        }

        input[type='text']:focus,
        input[type='password']:focus {
            outline: none;
            border-color: #5f9989;
            box-shadow: 0 0 0 3px rgba(22, 84, 67, 0.14);
        }

        .field-error {
            margin-top: 0.35rem;
            color: #8a2d23;
            font-size: 0.82rem;
            font-weight: 600;
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.9rem;
            margin: 0.25rem 0 1rem;
        }

        .remember-wrap {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.86rem;
            color: #334035;
            font-weight: 600;
            cursor: pointer;
            user-select: none;
        }

        .remember-wrap input[type='checkbox'] {
            width: 1rem;
            height: 1rem;
            accent-color: var(--brand);
            margin: 0;
            cursor: pointer;
        }

        .forgot-link {
            color: var(--brand);
            text-decoration: none;
            font-size: 0.86rem;
            font-weight: 700;
        }

        .forgot-link:hover {
            text-decoration: underline;
            color: var(--brand-strong);
        }

        .forgot-link:focus-visible {
            outline: 2px solid rgba(22, 84, 67, 0.45);
            outline-offset: 2px;
            border-radius: 6px;
        }

        .btn-submit {
            width: 100%;
            border: 0;
            border-radius: 12px;
            padding: 0.78rem 0.95rem;
            background: linear-gradient(180deg, var(--brand) 0%, var(--brand-strong) 100%);
            color: #fff;
            font-size: 0.96rem;
            font-weight: 800;
            letter-spacing: 0.02em;
            cursor: pointer;
            transition: transform 0.2s ease, filter 0.2s ease;
        }

        .btn-submit:hover {
            filter: brightness(1.04);
            transform: translateY(-1px);
        }

        .helper-note {
            margin-top: 0.9rem;
            color: var(--muted);
            font-size: 0.83rem;
            text-align: center;
        }

        .redirect-note {
            margin-top: 0.75rem;
            color: var(--muted);
            font-size: 0.85rem;
            text-align: center;
            font-weight: 600;
        }

        @keyframes enter {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 900px) {
            .login-shell {
                grid-template-columns: 1fr;
                max-width: 560px;
            }

            .visual-side {
                padding: 1.7rem 1.4rem;
            }

            .hero-copy p {
                max-width: 100%;
            }

            .form-side {
                padding: 1.5rem 1.2rem;
            }
        }
    </style>
</head>
<body>
    <section class="login-shell" aria-label="Login panel">
        <aside class="visual-side">
            <span class="app-badge">Account Access</span>

            <div class="hero-copy">
                <h1>Student Sign In</h1>
                <p>Log in to your student account to continue your session and access your tools securely.</p>
            </div>

            <div class="hero-stats" aria-hidden="true">
                <span class="hero-pill">Private</span>
                <span class="hero-pill">Verified</span>
                <span class="hero-pill">Encrypted</span>
            </div>
        </aside>

        <div class="form-side">
            <h2>Login</h2>
            <p class="subtitle">Use your account credentials to continue.</p>

            @if (session('success'))
                <div class="notice notice-success" role="status">{{ session('success') }}</div>
                @if (session('redirect_to'))
                    <p class="redirect-note">Redirecting...</p>
                @endif
            @endif

            @if (session('msg'))
                <div class="notice notice-error" role="alert">{{ session('msg') }}</div>
            @endif

            @if (session('lockout_seconds'))
                <p class="redirect-note" id="lockoutTimer" data-seconds="{{ (int) session('lockout_seconds') }}">
                    Try again in {{ (int) session('lockout_seconds') }} second(s).
                </p>
            @endif

            <form id="loginForm" method="POST" action="{{ route('login.submit') }}" data-js-form data-native-submit="true" data-action="{{ route('login.submit') }}" novalidate>
                @csrf

                <div class="field-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required autocomplete="username">
                    @error('username')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field-group">
                    <label for="password">Password</label>
                    <div class="password-wrap">
                        <input type="password" id="password" name="password" required autocomplete="current-password">
                        <button type="button" class="toggle-password" id="togglePassword" aria-label="Show password" aria-pressed="false" aria-controls="password">
                            <svg id="eyeOpen" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg id="eyeClosed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" style="display:none;">
                                <path d="M17.94 17.94A10.96 10.96 0 0 1 12 19c-7 0-11-7-11-7a21.78 21.78 0 0 1 5.06-5.94"></path>
                                <path d="M9.9 4.24A10.94 10.94 0 0 1 12 5c7 0 11 7 11 7a21.84 21.84 0 0 1-3.16 4.19"></path>
                                <path d="M1 1l22 22"></path>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-options">
                    <label for="remember" class="remember-wrap">
                        <input type="checkbox" id="remember" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                        <span>Remember me</span>
                    </label>

                    <a class="forgot-link" href="{{ Route::has('password.request') ? route('password.request') : url('/forgot-password') }}">Forgot password?</a>
                </div>

                <button type="submit" class="btn-submit" id="loginSubmitButton" data-js-submit>Login</button>
            </form>
        </div>
    </section>

    @if (session('success') && session('redirect_to'))
        <script>
            window.setTimeout(function () {
                window.location.href = @json(session('redirect_to'));
            }, 1800);
        </script>
    @endif

    <script>
        (function () {
            var passwordInput = document.getElementById('password');
            var toggleButton = document.getElementById('togglePassword');
            var eyeOpen = document.getElementById('eyeOpen');
            var eyeClosed = document.getElementById('eyeClosed');
            var lockoutTimer = document.getElementById('lockoutTimer');
            var loginForm = document.getElementById('loginForm');
            var loginSubmitButton = document.getElementById('loginSubmitButton');

            if (!passwordInput || !toggleButton || !eyeOpen || !eyeClosed) {
                return;
            }

            toggleButton.addEventListener('click', function () {
                var isHidden = passwordInput.type === 'password';
                passwordInput.type = isHidden ? 'text' : 'password';
                toggleButton.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
                toggleButton.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
                eyeOpen.style.display = isHidden ? 'none' : 'block';
                eyeClosed.style.display = isHidden ? 'block' : 'none';
            });

            if (lockoutTimer && loginSubmitButton) {
                var seconds = parseInt(lockoutTimer.getAttribute('data-seconds') || '0', 10);

                if (!isNaN(seconds) && seconds > 0) {
                    loginSubmitButton.disabled = true;
                    loginSubmitButton.style.opacity = '0.7';
                    loginSubmitButton.style.cursor = 'not-allowed';

                    var intervalId = window.setInterval(function () {
                        seconds -= 1;

                        if (seconds > 0) {
                            lockoutTimer.textContent = 'Try again in ' + seconds + ' second(s).';
                            return;
                        }

                        window.clearInterval(intervalId);
                        lockoutTimer.textContent = 'You can try logging in again now.';
                        loginSubmitButton.disabled = false;
                        loginSubmitButton.style.opacity = '';
                        loginSubmitButton.style.cursor = '';

                        if (loginForm) {
                            loginForm.classList.add('lockout-ended');
                        }
                    }, 1000);
                }
            }
        })();
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>