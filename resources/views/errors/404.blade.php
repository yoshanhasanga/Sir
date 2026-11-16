<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Page Not Found | SLTES Portal</title>
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
            color: #0f5257;
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
        <div class="error-code">404</div>
        <h1>පිටුව සොයාගත නොහැක (Page Not Found)</h1>
        <p>The requested page or resource could not be found on the SLTES Central Portal. Please check the URL or return to the main dashboard.</p>
        <a href="{{ url('/') }}" class="btn">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Return to Dashboard
        </a>
        <div class="credit">
            Engineered by <a href="https://neocraftix.com/" target="_blank" rel="noopener">NeoCraftix</a>
        </div>
    </div>
</body>
</html>

