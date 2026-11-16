<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>419 - Session Expired | SLTES Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: linear-gradient(135deg, #07191d 0%, #0f5257 60%, #153c40 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 24px;
        }
        .error-card {
            background: #ffffff;
            color: #1b2a2f;
            border-top: 5px solid #d9a21b;
            padding: 40px 32px;
            border-radius: 12px;
            max-width: 480px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,0.4);
        }
        .error-code {
            font-size: 54px;
            font-weight: 800;
            color: #d9a21b;
            line-height: 1;
            margin-bottom: 12px;
        }
        h1 { font-size: 20px; font-weight: 700; margin-bottom: 8px; color: #1b2a2f; }
        p { font-size: 14px; color: #5e7379; line-height: 1.6; margin-bottom: 24px; }
        .btn {
            background: #0f5257;
            color: #fff;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .btn:hover { background: #0a3a3e; transform: translateY(-1px); }
        .credit { margin-top: 24px; font-size: 11px; color: #8da4a0; }
        .credit a { color: #d9a21b; font-weight: 700; text-decoration: none; }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-code">419</div>
        <h1>ආරක්ෂක සැසිය කල් ඉකුත් වී ඇත (Session Expired)</h1>
        <p>Your security session has expired due to inactivity. Please refresh or sign in again to verify your official credentials.</p>
        <a href="{{ route('login') }}" class="btn">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
            Sign In Again
        </a>
        <div class="credit">
            Engineered by <a href="https://neocraftix.com/" target="_blank" rel="noopener">NeoCraftix</a>
        </div>
    </div>
</body>
</html>

