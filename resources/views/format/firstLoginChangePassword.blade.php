<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Change Password</title>
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

        .panel {
            width: 100%;
            max-width: 540px;
            border: 1px solid var(--line);
            border-radius: 20px;
            overflow: hidden;
            background: linear-gradient(180deg, var(--panel) 0%, var(--panel-soft) 100%);
            box-shadow: 0 18px 35px rgba(25, 28, 22, 0.15);
            padding: 2rem;
        }

        h1 {
            margin: 0;
            font-family: 'Fraunces', serif;
            font-weight: 700;
            font-size: 1.8rem;
        }

        .subtitle {
            margin: 0.5rem 0 1.4rem;
            color: var(--muted);
            font-size: 0.95rem;
        }

        .notice {
            margin-bottom: 1rem;
            border-radius: 12px;
            padding: 0.72rem 0.85rem;
            font-size: 0.9rem;
            font-weight: 600;
            background: var(--danger-bg);
            border: 1px solid #f1b5ae;
            color: var(--danger-ink);
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

        input[type='password'],
        input[type='text'] {
            width: 100%;
            border-radius: 12px;
            border: 1px solid #cfc4ae;
            padding: 0.72rem 2.8rem 0.72rem 0.78rem;
            font-size: 0.95rem;
            color: #1f211c;
            background: #fffdfa;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        input[type='password']:focus,
        input[type='text']:focus {
            outline: none;
            border-color: #5f9989;
            box-shadow: 0 0 0 3px rgba(22, 84, 67, 0.14);
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 0.45rem;
            transform: translateY(-50%);
            width: 2rem;
            height: 2rem;
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

        .field-error {
            margin-top: 0.35rem;
            color: #8a2d23;
            font-size: 0.82rem;
            font-weight: 600;
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
    </style>
</head>
<body>
    <main class="panel" aria-label="Change first login password">
        <h1>Change Your Password</h1>
        <p class="subtitle">For first-time login, you must set a new password before continuing.</p>

        @if (session('msg'))
            <div class="notice" role="alert">{{ session('msg') }}</div>
        @endif

        <form method="POST" action="{{ route('first-login.password.update') }}" data-js-form data-native-submit="true" data-action="{{ route('first-login.password.update') }}">
            @csrf

            <div class="field-group">
                <label for="old_password">Old Password</label>
                <div class="password-wrap">
                    <input type="password" id="old_password" name="old_password" required autocomplete="current-password">
                    <button type="button" class="toggle-password" data-target="old_password" aria-label="Show old password" aria-pressed="false">
                        <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <svg class="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" style="display:none;">
                            <path d="M17.94 17.94A10.96 10.96 0 0 1 12 19c-7 0-11-7-11-7a21.78 21.78 0 0 1 5.06-5.94"></path>
                            <path d="M9.9 4.24A10.94 10.94 0 0 1 12 5c7 0 11 7 11 7a21.84 21.84 0 0 1-3.16 4.19"></path>
                            <path d="M1 1l22 22"></path>
                        </svg>
                    </button>
                </div>
                @error('old_password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="field-group">
                <label for="password">New Password</label>
                <div class="password-wrap">
                    <input type="password" id="password" name="password" required autocomplete="new-password">
                    <button type="button" class="toggle-password" data-target="password" aria-label="Show new password" aria-pressed="false">
                        <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <svg class="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" style="display:none;">
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

            <div class="field-group">
                <label for="password_confirmation">Confirm New Password</label>
                <div class="password-wrap">
                    <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                    <button type="button" class="toggle-password" data-target="password_confirmation" aria-label="Show password confirmation" aria-pressed="false">
                        <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <svg class="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" style="display:none;">
                            <path d="M17.94 17.94A10.96 10.96 0 0 1 12 19c-7 0-11-7-11-7a21.78 21.78 0 0 1 5.06-5.94"></path>
                            <path d="M9.9 4.24A10.94 10.94 0 0 1 12 5c7 0 11 7 11 7a21.84 21.84 0 0 1-3.16 4.19"></path>
                            <path d="M1 1l22 22"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit" data-js-submit>Save New Password</button>
        </form>
    </main>

    <script>
        (function () {
            var toggleButtons = document.querySelectorAll('.toggle-password');

            toggleButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var targetId = button.getAttribute('data-target');
                    var input = document.getElementById(targetId);
                    var eyeOpen = button.querySelector('.eye-open');
                    var eyeClosed = button.querySelector('.eye-closed');

                    if (!input || !eyeOpen || !eyeClosed) {
                        return;
                    }

                    var isHidden = input.type === 'password';
                    input.type = isHidden ? 'text' : 'password';
                    button.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
                    button.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
                    eyeOpen.style.display = isHidden ? 'none' : 'block';
                    eyeClosed.style.display = isHidden ? 'block' : 'none';
                });
            });
        })();
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
