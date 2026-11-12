<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SLTES Security Portal – Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --a: #0f5257;
            --a-hover: #0a3a3e;
            --g: #d9a21b;
            --bg: #0b2228;
            --surface: #ffffff;
            --ink: #1b2a2f;
            --muted: #5e7379;
            --ln: #d2dedb;
            --danger: #d93829;
            --success: #1e824c;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #07191d 0%, #0f5257 60%, #153c40 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: var(--ink);
        }
        .login-container {
            width: 100%;
            max-width: 480px;
            background: var(--surface);
            border-radius: 12px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.1);
            overflow: hidden;
            border-top: 5px solid var(--g);
        }
        .login-header {
            background: #f7faf9;
            padding: 24px 28px 20px;
            border-bottom: 1px solid var(--ln);
            text-align: center;
        }
        .emblem-wrapper {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            background: var(--a);
            border-radius: 50%;
            color: #fff;
            margin-bottom: 12px;
            box-shadow: 0 4px 10px rgba(15, 82, 87, 0.3);
        }
        .emblem-wrapper svg { width: 28px; height: 28px; }
        .login-header h1 {
            font-size: 19px;
            font-weight: 700;
            color: var(--a);
            margin-bottom: 4px;
        }
        .login-header p {
            font-size: 12px;
            color: var(--muted);
            line-height: 1.4;
        }
        .security-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #e8f5e9;
            color: var(--success);
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
            margin-top: 10px;
            border: 1px solid rgba(46, 125, 50, 0.2);
        }
        .security-badge .dot {
            width: 6px;
            height: 6px;
            background: var(--success);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.85); }
            100% { opacity: 1; transform: scale(1); }
        }

        .login-body {
            padding: 28px;
        }
        .alert {
            padding: 12px 14px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            line-height: 1.4;
        }
        .alert-danger {
            background: #fdf2f2;
            color: var(--danger);
            border: 1px solid #f8b4b4;
        }
        .alert-success {
            background: #f0fdf4;
            color: var(--success);
            border: 1px solid #bbf7d0;
        }

        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 6px;
        }
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-wrapper input {
            width: 100%;
            padding: 11px 14px;
            font-size: 14px;
            font-family: inherit;
            border: 1.5px solid var(--ln);
            border-radius: 6px;
            background: #fff;
            color: var(--ink);
            transition: all 0.2s ease;
        }
        .input-wrapper input:focus {
            outline: none;
            border-color: var(--a);
            box-shadow: 0 0 0 3px rgba(15, 82, 87, 0.15);
        }
        .toggle-pwd {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            color: var(--muted);
            cursor: pointer;
            padding: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .form-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            margin-bottom: 24px;
        }
        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: var(--muted);
            user-select: none;
        }
        .remember-me input {
            cursor: pointer;
            width: 15px;
            height: 15px;
            accent-color: var(--a);
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background: var(--a);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(15, 82, 87, 0.25);
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-submit:hover {
            background: var(--a-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(15, 82, 87, 0.35);
        }

        .login-footer {
            background: #f7faf9;
            padding: 16px 28px;
            border-top: 1px solid var(--ln);
            text-align: center;
        }
        .btn-demo {
            background: #fff;
            border: 1px solid var(--ln);
            color: var(--a);
            padding: 7px 14px;
            border-radius: 6px;
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-demo:hover {
            background: #eef5f4;
            border-color: var(--a);
        }

        /* Demo Accounts Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.65);
            backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        .modal.open { display: flex; }
        .modal-card {
            background: #fff;
            width: 100%;
            max-width: 650px;
            max-height: 85vh;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 25px 60px rgba(0,0,0,0.5);
        }
        .modal-header {
            padding: 16px 20px;
            background: var(--a);
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-header h2 { font-size: 16px; font-weight: 700; }
        .modal-close {
            background: none; border: none; color: #fff; font-size: 20px; cursor: pointer;
        }
        .modal-body {
            padding: 20px;
            overflow-y: auto;
            flex: 1;
        }
        .role-group-title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 14px 0 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .role-group-title:first-child { margin-top: 0; }
        .badge-role {
            font-size: 10.5px;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 10px;
            text-transform: uppercase;
        }
        .badge-approver { background: #fee2e2; color: #991b1b; }
        .badge-admin { background: #fef3c7; color: #92400e; }
        .badge-view { background: #e0f2fe; color: #075985; }

        .user-picker-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
            margin-bottom: 12px;
        }
        .user-picker-table th, .user-picker-table td {
            padding: 6px 10px;
            border: 1px solid var(--ln);
            text-align: left;
        }
        .user-picker-table th { background: #f8faf9; font-weight: 600; }
        .user-picker-table tr:hover td { background: #f0fdf9; }
        .btn-select-user {
            background: var(--a);
            color: #fff;
            border: none;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-select-user:hover { background: var(--a-hover); }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-header">
        <div class="emblem-wrapper">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        </div>
        <h1>Ministry of Education – Sri Lanka</h1>
        <p>SLTES Orientation Programme 2026<br>Secure Central Timetable Planner</p>
        <div>
            <span class="security-badge">
                <span class="dot"></span>
                <span>Role-Based Access • Dual Approval Protected</span>
            </span>
        </div>
    </div>

    <div class="login-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" autocomplete="off">
            @csrf

            <div class="form-group">
                <label for="email">Government Portal Email</label>
                <div class="input-wrapper">
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="officer@sltes.gov.lk">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Security Password</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" required placeholder="••••••••••••">
                    <button type="button" class="toggle-pwd" id="togglePwd" onclick="togglePasswordVisibility()">Show</button>
                </div>
            </div>

            <div class="form-meta">
                <label class="remember-me">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>Remember this secure device</span>
                </label>
            </div>

            <button type="submit" class="btn-submit">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                Secure Sign In
            </button>
        </form>
    </div>

    <div class="login-footer">
        <button type="button" class="btn-demo" onclick="openDemoModal()">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Choose from 20 Pre-Configured Official Accounts
        </button>
    </div>
</div>

<div style="margin-top: 18px; text-align: center; font-size: 12px; color: rgba(255,255,255,0.7);">
    Designed, Developed & Engineered by 
    <a href="https://neocraftix.com/" target="_blank" rel="noopener noreferrer" style="color: var(--g); font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
        <span>NeoCraftix</span>
        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
    </a>
</div>

<!-- Demo Accounts Picker Modal -->
<div class="modal" id="demoModal">
    <div class="modal-card">
        <div class="modal-header">
            <h2>Select Official Account to Login</h2>
            <button class="modal-close" onclick="closeDemoModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p style="font-size:12px; color:var(--muted); margin-bottom:12px;">
                Password for all accounts is: <b>Pass@2026#Sec</b>. Click "Quick Select" to autofill credentials.
            </p>

            <!-- 1. Ministry of Education Approvers (2 users) -->
            <div class="role-group-title" style="color:#991b1b;">
                <span>Ministry of Education – Chief Approvers (2 Users)</span>
                <span class="badge-role badge-approver">Full Dual-Approval Authority</span>
            </div>
            <table class="user-picker-table">
                <tr><th>Name<th>Email<th>Role Permissions<th>Action</tr>
                @foreach ($users->where('role', 'moe_approver') as $u)
                <tr>
                    <td><b>{{ $u->name }}</b><br><small style="color:var(--muted)">{{ $u->designation }}</small></td>
                    <td>{{ $u->email }}</td>
                    <td><span class="badge-role badge-approver">Can Approve Deletions & Edits</span></td>
                    <td><button class="btn-select-user" onclick="autofill('{{ $u->email }}')">Quick Select</button></td>
                </tr>
                @endforeach
            </table>

            <!-- 2. Ministry of Education Admins / Editors (3 users) -->
            <div class="role-group-title" style="color:#92400e;">
                <span>Ministry of Education – Editors / Planners (3 Users)</span>
                <span class="badge-role badge-admin">View, Edit, Propose Deletions</span>
            </div>
            <table class="user-picker-table">
                <tr><th>Name<th>Email<th>Role Permissions<th>Action</tr>
                @foreach ($users->where('role', 'moe_admin') as $u)
                <tr>
                    <td><b>{{ $u->name }}</b><br><small style="color:var(--muted)">{{ $u->designation }}</small></td>
                    <td>{{ $u->email }}</td>
                    <td><span class="badge-role badge-admin">Edit • Delete requires MoE Approval</span></td>
                    <td><button class="btn-select-user" onclick="autofill('{{ $u->email }}')">Quick Select</button></td>
                </tr>
                @endforeach
            </table>

            <!-- 3. Center Managers (6 users) -->
            <div class="role-group-title" style="color:#075985;">
                <span>Center Managers (6 Users)</span>
                <span class="badge-role badge-view">View Only</span>
            </div>
            <table class="user-picker-table">
                <tr><th>Name<th>Email<th>Role Permissions<th>Action</tr>
                @foreach ($users->where('role', 'center_manager') as $u)
                <tr>
                    <td><b>{{ $u->name }}</b><br><small style="color:var(--muted)">{{ $u->designation }}</small></td>
                    <td>{{ $u->email }}</td>
                    <td><span class="badge-role badge-view">View Only</span></td>
                    <td><button class="btn-select-user" onclick="autofill('{{ $u->email }}')">Quick Select</button></td>
                </tr>
                @endforeach
            </table>

            <!-- 4. Topic Leaders (9 users) -->
            <div class="role-group-title" style="color:#075985;">
                <span>Topic Leaders (9 Users)</span>
                <span class="badge-role badge-view">View Only</span>
            </div>
            <table class="user-picker-table">
                <tr><th>Name<th>Email<th>Role Permissions<th>Action</tr>
                @foreach ($users->where('role', 'topic_leader') as $u)
                <tr>
                    <td><b>{{ $u->name }}</b><br><small style="color:var(--muted)">{{ $u->designation }}</small></td>
                    <td>{{ $u->email }}</td>
                    <td><span class="badge-role badge-view">View Only</span></td>
                    <td><button class="btn-select-user" onclick="autofill('{{ $u->email }}')">Quick Select</button></td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility() {
    const input = document.getElementById('password');
    const btn = document.getElementById('togglePwd');
    if (input.type === 'password') {
        input.type = 'text';
        btn.innerText = 'Hide';
    } else {
        input.type = 'password';
        btn.innerText = 'Show';
    }
}

function openDemoModal() {
    document.getElementById('demoModal').classList.add('open');
}

function closeDemoModal() {
    document.getElementById('demoModal').classList.remove('open');
}

function autofill(email) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = 'Pass@2026#Sec';
    closeDemoModal();
}

window.onclick = function(event) {
    const modal = document.getElementById('demoModal');
    if (event.target === modal) {
        closeDemoModal();
    }
};
</script>

</body>
</html>

