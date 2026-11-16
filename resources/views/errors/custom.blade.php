<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SLTES System Notification</title>
    <link rel="icon" type="image/webp" href="{{ asset('favicon.webp') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.webp') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: #0b2228;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .box {
            background: #11333a;
            border-top: 4px solid #d9a21b;
            padding: 30px;
            border-radius: 8px;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
        }
        h1 { font-size: 20px; margin-bottom: 12px; color: #f87171; }
        p { font-size: 14px; color: #cbd5e1; line-height: 1.5; margin-bottom: 20px; }
        .btn {
            background: #0f5257;
            color: #fff;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
        }
        .btn:hover { background: #156d73; }
    </style>
</head>
<body>
    <div class="box">
        <h1>ආරක්ෂක නිවේදනයයි (Security Alert)</h1>
        <p>{{ $message ?? 'A system condition was encountered. For security, technical details have been protected and logged securely.' }}</p>
        <a href="{{ url('/') }}" class="btn">Return to Dashboard</a>
    </div>
</body>
</html>

