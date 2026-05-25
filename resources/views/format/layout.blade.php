<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- <script src="{{ asset('js/jQuery.js') }}"></script> -->
<script src="{{ asset('js/app.js') }}"></script>
@if(request()->routeIs('admin.*'))
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endif
<style>
:root {
    --app-bg: #f4f1ea;
    --app-paper: #fffdfa;
    --app-paper-soft: #f8f4ec;
    --app-ink: #1c1d1a;
    --app-muted: #6a6f64;
    --app-brand: #17594a;
    --app-brand-strong: #0f4438;
    --app-accent: #bf7f35;
    --app-line: #dfd8ca;
}

* {
    box-sizing: border-box;
}

body {
    font-family: 'Manrope', sans-serif;
    color: var(--app-ink);
    min-height: 100vh;
    background:
        radial-gradient(circle at 8% 8%, rgba(191, 127, 53, 0.2), transparent 30%),
        radial-gradient(circle at 92% 0%, rgba(23, 89, 74, 0.14), transparent 38%),
        linear-gradient(160deg, #f6f3ee 0%, #f0ece5 100%);
    position: relative;
}

body::before,
body::after {
    content: '';
    position: fixed;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    pointer-events: none;
    z-index: 0;
    filter: blur(2px);
}

body::before {
    right: -130px;
    top: 120px;
    background: rgba(23, 89, 74, 0.1);
}

body::after {
    left: -120px;
    bottom: 80px;
    background: rgba(191, 127, 53, 0.14);
}

.app-shell {
    position: relative;
    z-index: 1;
}

.navbar {
    background: rgba(20, 35, 31, 0.9) !important;
    backdrop-filter: blur(8px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.12);
}

.navbar-brand {
    font-family: 'Fraunces', serif;
    letter-spacing: 0.2px;
}

.nav-link {
    color: rgba(255, 255, 255, 0.82) !important;
    border-radius: 999px;
    padding: 0.45rem 0.9rem !important;
    transition: all 0.2s ease;
}

.nav-link:hover,
.nav-link.active {
    color: #ffffff !important;
    background: rgba(255, 255, 255, 0.13);
}

.page-wrap {
    max-width: 1100px;
    margin: 1.75rem auto 2.25rem;
    padding: 0 0.75rem;
}

.page-title,
h1, h2 {
    font-family: 'Fraunces', serif;
    letter-spacing: 0.15px;
}

.card {
    border: 1px solid var(--app-line) !important;
    border-radius: 18px;
    background: linear-gradient(180deg, var(--app-paper) 0%, var(--app-paper-soft) 100%);
    box-shadow: 0 8px 22px rgba(40, 40, 30, 0.06) !important;
}

.table {
    --bs-table-bg: transparent;
}

.table > :not(caption) > * > * {
    border-color: #e6dfd1;
}

thead th {
    background: #f3ede2 !important;
    color: #3d463e;
    text-transform: uppercase;
    font-size: 0.78rem;
    letter-spacing: 0.06em;
}

.form-control,
.form-select {
    border-radius: 12px;
    border: 1px solid #d9d2c4;
    background: #fff;
    padding: 0.65rem 0.8rem;
}

.form-control:focus,
.form-select:focus {
    border-color: #7aa495;
    box-shadow: 0 0 0 0.2rem rgba(23, 89, 74, 0.14);
}

.form-label {
    font-weight: 700;
    color: #39443c;
}

.btn {
    border-radius: 10px;
    font-weight: 700;
    padding: 0.55rem 1rem;
}

.btn-primary {
    background: linear-gradient(180deg, var(--app-brand), var(--app-brand-strong));
    border-color: var(--app-brand-strong);
}

.btn-primary:hover {
    background: #0f4438;
    border-color: #0f4438;
}

.btn-outline-secondary {
    border-color: #9ca29a;
    color: #3e463f;
}

.btn-warning {
    color: #31220b;
}

.alert {
    border-radius: 12px;
    border: 1px solid #b7d9c7;
}

.middleware-banner {
    border-radius: 16px;
    border: 1px solid transparent;
    box-shadow: 0 10px 24px rgba(40, 40, 30, 0.08);
    overflow: hidden;
}

.middleware-banner .banner-badge {
    display: inline-block;
    border-radius: 999px;
    padding: 0.3rem 0.7rem;
    font-size: 0.72rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.07em;
}

.middleware-banner .banner-title {
    font-family: 'Fraunces', serif;
    letter-spacing: 0.12px;
    margin-bottom: 0.35rem;
}

.middleware-banner .banner-code {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    border-radius: 10px;
    padding: 0.45rem 0.75rem;
    font-weight: 800;
    letter-spacing: 0.04em;
}

.middleware-banner.promotion {
    background: linear-gradient(130deg, #fef7df 0%, #f4e7bf 55%, #eedaa1 100%);
    border-color: #d9c79c;
}

.middleware-banner.promotion .banner-badge {
    background: #145348;
    color: #fff;
}

.middleware-banner.promotion .banner-code {
    background: #fff;
    border: 1px dashed #7f682a;
    color: #4f3d11;
}

.middleware-banner.maintenance {
    background: linear-gradient(130deg, #fff7e6 0%, #ffe2b8 100%);
    border-color: #e7bf7d;
}

.middleware-banner.maintenance .banner-badge {
    background: #7a4b11;
    color: #fff;
}

.middleware-banner.maintenance .banner-code {
    background: #fff;
    border: 1px solid #ddb67a;
    color: #7a4b11;
}

footer {
    border-top: 1px solid var(--app-line);
    background: rgba(255, 252, 246, 0.72);
    backdrop-filter: blur(6px);
}

.fade-rise {
    animation: fadeRise 0.45s ease;
}

@keyframes fadeRise {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .page-wrap {
        margin-top: 1rem;
        padding: 0 0.4rem;
    }

    .card {
        border-radius: 14px;
    }
}
</style>
</head>

<body class="d-flex flex-column">
<div class="app-shell d-flex flex-column min-vh-100">
<nav class="navbar navbar-dark navbar-expand-lg shadow-sm">
    <div class="container">
        @if(request()->routeIs('admin.*'))
        <a class="navbar-brand fw-semibold" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
        @elseif(request()->routeIs('teacher.*'))
        <a class="navbar-brand fw-semibold" href="{{ route('teacher.dashboard') }}">Teacher Dashboard</a>
        @else
        <a class="navbar-brand fw-semibold" href="{{ route('home') }}">Student Dashboard</a>
        @endif

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto gap-lg-2">
                @if(request()->routeIs('admin.*'))
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('admin.dashboard')) active fw-semibold @endif" href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('admin.admins.*')) active fw-semibold @endif" href="{{ route('admin.admins.index') }}">Admins</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('admin.teachers.*')) active fw-semibold @endif" href="{{ route('admin.teachers.index') }}">Teachers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('admin.students.*')) active fw-semibold @endif" href="{{ route('admin.students.index') }}">Students</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('admin.degrees.*')) active fw-semibold @endif" href="{{ route('admin.degrees.index') }}">Degrees</a>
                </li>
                @elseif(request()->routeIs('teacher.*'))
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('teacher.dashboard')) active fw-semibold @endif" href="{{ route('teacher.dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('teacher.students.*')) active fw-semibold @endif" href="{{ route('teacher.students.index') }}">Students</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('teacher.profile.*')) active fw-semibold @endif" href="{{ route('teacher.profile.edit') }}">My Account</a>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('home')) active fw-semibold @endif" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('students.*')) active fw-semibold @endif" href="{{ route('students.index') }}">Students</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('demo')) active fw-semibold @endif" href="{{ route('demo') }}">Demo</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('about')) active fw-semibold @endif" href="{{ route('about') }}">About</a>
                </li>
                @endif
                <li class="nav-item">
                    <form class="m-0" method="POST" action="{{ route('logout') }}" data-js-form data-action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start" data-js-submit>Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="page-wrap flex-grow-1 fade-rise">
    @isset($middlewareBanner)
    <section class="middleware-banner {{ $middlewareBanner['type'] }} p-3 p-md-4 mb-3">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
            <div>
                <span class="banner-badge mb-2">{{ $middlewareBanner['badge'] }}</span>
                <h3 class="banner-title h5">{{ $middlewareBanner['title'] }}</h3>
                <p class="mb-0">{{ $middlewareBanner['description'] }}</p>
            </div>

            @if(!empty($middlewareBanner['code']))
            <div class="banner-code">
                <i class="bi bi-ticket-perforated-fill"></i>
                {{ $middlewareBanner['code'] }}
            </div>
            @endif
        </div>

        @if(!empty($middlewareBanner['expires_at']))
        <div class="mt-3 small text-muted fw-semibold">
            <i class="bi bi-clock-history me-1"></i>
            Valid until {{ $middlewareBanner['expires_at'] }}
        </div>
        @endif
    </section>
    @endisset

    @yield('content')
</main>

<footer class="text-center py-3 mt-auto">
    <p class="mb-0 text-muted small">Student Management Dashboard</p>
</footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
