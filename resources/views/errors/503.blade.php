<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Service Unavailable</title>
    <style>
        body { font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; background:#f8fafc; color:#111827; display:flex; align-items:center; justify-content:center; height:100vh; margin:0 }
        .card { background:white; padding:28px; border-radius:12px; box-shadow:0 6px 18px rgba(15,23,42,0.08); max-width:720px; width:100% }
        h1 { margin:0 0 8px; font-size:24px }
        p { margin:0 0 12px; color:#374151 }
        .meta { font-size:13px; color:#6b7280 }
        .actions { margin-top:18px }
        .btn { display:inline-block; padding:8px 14px; border-radius:8px; background:#111827; color:white; text-decoration:none }
    </style>
</head>
<body>
    <div class="card">
        <h1>Service temporarily unavailable</h1>
        <p>We're having trouble connecting to our database. Please try again in a few minutes.</p>
        <p class="meta">Error: {{ $message ?? 'Service Unavailable' }}</p>
        <div class="actions">
            <a class="btn" href="{{ url()->current() }}">Try again</a>
            <a style="margin-left:8px; color:#374151" href="/">Home</a>
        </div>
    </div>
</body>
</html>
