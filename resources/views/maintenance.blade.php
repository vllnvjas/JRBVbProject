<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Down for Maintenance' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f3efe9;
            --card: #fffdf8;
            --card-soft: #f7f1e6;
            --ink: #26251f;
            --muted: #6a685f;
            --brand: #163f54;
            --accent: #d59d4a;
            --line: #ded4c4;
            --shadow: 0 24px 45px rgba(34, 31, 24, 0.16);
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
                radial-gradient(circle at 18% 18%, rgba(213, 157, 74, 0.28), transparent 28%),
                radial-gradient(circle at 82% 12%, rgba(22, 63, 84, 0.18), transparent 32%),
                linear-gradient(160deg, #f6f2ec 0%, var(--bg) 100%);
            display: grid;
            place-items: center;
            padding: 1.25rem;
        }

        .maintenance-shell {
            width: min(100%, 520px);
            border-radius: 24px;
            border: 1px solid var(--line);
            background: linear-gradient(180deg, var(--card) 0%, var(--card-soft) 100%);
            box-shadow: var(--shadow);
            padding: 2.2rem 2rem 1.8rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            animation: rise 0.45s ease;
        }

        .maintenance-shell::before {
            content: '';
            position: absolute;
            inset: 0 auto auto 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--accent), #e6c27a, #89b1c2);
        }

        .icon-wrap {
            width: 76px;
            height: 76px;
            margin: 0 auto 1rem;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: rgba(213, 157, 74, 0.14);
            border: 1px solid rgba(213, 157, 74, 0.3);
        }

        .icon-wrap span {
            font-size: 2rem;
        }

        h1 {
            margin: 0;
            font-family: 'Fraunces', serif;
            font-size: clamp(1.8rem, 3vw, 2.35rem);
            line-height: 1.12;
        }

        .subtitle {
            margin: 0.65rem auto 1.15rem;
            color: var(--muted);
            max-width: 34ch;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            border-radius: 999px;
            padding: 0.45rem 0.8rem;
            background: #fff4d8;
            border: 1px solid #e9d39b;
            color: #765317;
            font-size: 0.86rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }

        .message-box {
            padding: 1rem 1rem 1.05rem;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid rgba(222, 212, 196, 0.9);
            text-align: left;
        }

        .message-box p {
            margin: 0;
            color: #41403a;
            line-height: 1.65;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .meta-card {
            border-radius: 14px;
            padding: 0.85rem 0.9rem;
            background: rgba(255, 255, 255, 0.65);
            border: 1px solid rgba(222, 212, 196, 0.85);
            text-align: left;
        }

        .meta-card span {
            display: block;
        }

        .meta-label {
            color: var(--muted);
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 800;
        }

        .meta-value {
            margin-top: 0.2rem;
            font-weight: 800;
            color: var(--ink);
        }

        .actions {
            margin-top: 1.35rem;
            display: flex;
            justify-content: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            border-radius: 12px;
            padding: 0.78rem 1.05rem;
            text-decoration: none;
            font-weight: 800;
            transition: transform 0.2s ease, filter 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: linear-gradient(180deg, var(--brand), #123346);
            color: #fff;
        }

        .btn-secondary {
            background: #fff;
            border: 1px solid var(--line);
            color: var(--ink);
        }

        .footer-note {
            margin-top: 1rem;
            color: var(--muted);
            font-size: 0.84rem;
        }

        @keyframes rise {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 520px) {
            .maintenance-shell {
                padding: 1.7rem 1.15rem 1.35rem;
                border-radius: 20px;
            }

            .meta-grid {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <main class="maintenance-shell" role="status" aria-live="polite">
        <div class="icon-wrap" aria-hidden="true">
            <span>🛠</span>
        </div>

        <div class="status-pill">
            <span>Maintenance</span>
        </div>

        <h1>{{ $title ?? 'Down for Maintenance' }}</h1>
        <p class="subtitle">We’re making improvements and the current site content is temporarily hidden.</p>

        <div class="message-box">
            <p>{{ $message ?? 'The site is temporarily unavailable while we apply updates. Your normal pages will return as soon as maintenance is turned off.' }}</p>
        </div>

        <div class="meta-grid" aria-hidden="true">
            <div class="meta-card">
                <span class="meta-label">Status</span>
                <span class="meta-value">Offline for updates</span>
            </div>
            <div class="meta-card">
                <span class="meta-label">Return</span>
                <span class="meta-value">When maintenance ends</span>
            </div>
        </div>

        <div class="actions">
            <a class="btn btn-primary" href="{{ route('students.index') }}">Back to Students</a>
            <a class="btn btn-secondary" href="{{ route('login') }}">Back to login</a>
        </div>

        <div class="footer-note">Normal pages will reappear automatically once maintenance is disabled.</div>
    </main>
</body>
</html>