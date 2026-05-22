<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student Profile</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
        }

        * { box-sizing: border-box; }

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

        .profile-shell {
            width: 100%;
            max-width: 780px;
            border: 1px solid var(--line);
            border-radius: 24px;
            overflow: hidden;
            background: var(--panel);
            box-shadow: 0 18px 35px rgba(25, 28, 22, 0.15);
        }

        .profile-header {
            padding: 1.5rem 1.8rem;
            background: linear-gradient(140deg, rgba(16, 58, 47, 0.95) 0%, rgba(25, 90, 72, 0.92) 65%, rgba(39, 125, 100, 0.88) 100%);
            color: #eef8f4;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .profile-header h1 {
            margin: 0;
            font-family: 'Fraunces', serif;
            font-size: clamp(1.5rem, 2.5vw, 2.1rem);
        }

        .profile-header p {
            margin: 0.35rem 0 0;
            color: rgba(240, 253, 248, 0.85);
        }

        .logout-form button {
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
            padding: 0.65rem 1rem;
            border-radius: 999px;
            font-weight: 800;
        }

        .logout-form button:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .profile-body {
            padding: 1.5rem 1.8rem 1.8rem;
            background: linear-gradient(180deg, var(--panel) 0%, var(--panel-soft) 100%);
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .detail-card {
            background: #fff;
            border: 1px solid #e2d8c7;
            border-radius: 16px;
            padding: 1rem 1.1rem;
        }

        .detail-label {
            display: block;
            font-size: 0.78rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 0.35rem;
            font-weight: 800;
        }

        .detail-value {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
            color: var(--ink);
        }

        .full-row {
            grid-column: 1 / -1;
        }

        .muted {
            color: var(--muted);
        }

        @media (max-width: 640px) {
            .profile-header,
            .profile-body {
                padding-left: 1.1rem;
                padding-right: 1.1rem;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <main class="profile-shell">
        <header class="profile-header">
            <div>
                <h1>Student Profile</h1>
                <p>Only your account details are shown here.</p>
            </div>

            <div class="logout-form m-0" data-js-form data-action="{{ route('logout') }}">
                @csrf
                <button type="button" data-js-submit>Logout</button>
            </div>
        </header>

        <section class="profile-body" id="studentProfileDetailsContainer" data-autoreload>
            <div class="detail-grid">
                <div class="detail-card">
                    <span class="detail-label">First Name</span>
                    <p class="detail-value">{{ $student->fname }}</p>
                </div>

                <div class="detail-card">
                    <span class="detail-label">Middle Name</span>
                    <p class="detail-value">{{ $student->mname ?? '-' }}</p>
                </div>

                <div class="detail-card">
                    <span class="detail-label">Last Name</span>
                    <p class="detail-value">{{ $student->lname }}</p>
                </div>

                <div class="detail-card">
                    <span class="detail-label">Email</span>
                    <p class="detail-value">{{ $student->userAccount?->email ?? '-' }}</p>
                </div>

                <div class="detail-card">
                    <span class="detail-label">Username</span>
                    <p class="detail-value">{{ $student->userAccount?->username ?? '-' }}</p>
                </div>

                <div class="detail-card">
                    <span class="detail-label">Password</span>
                    <p class="detail-value">********</p>
                </div>

                <div class="detail-card">
                    <span class="detail-label">Degree</span>
                    <p class="detail-value">{{ $student->degree?->name ?? '-' }}</p>
                </div>

                <div class="detail-card">
                    <span class="detail-label">Contact Info</span>
                    <p class="detail-value">{{ $student->contactInfo }}</p>
                </div>
            </div>

            <p class="muted mb-0 mt-3">Password is hidden for security. You can use the logout button above to end your session.</p>
        </section>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>