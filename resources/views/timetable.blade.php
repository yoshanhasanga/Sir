<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SLTES Orientation 2026 – Central Timetable Planner</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --a: #0f5257;
            --a-hover: #0b3d41;
            --g: #d9a21b;
            --bg: #eef2f1;
            --ink: #1b2a2f;
            --ln: #c8d3d1;
            --card-bg: #ffffff;
            --success: #28a745;
            --warning: #f39c12;
            --danger: #c0392b;
            --info: #2980b9;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font: 14px/1.45 'Inter', "Segoe UI", system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--ink);
        }
        header {
            background: var(--a);
            color: #fff;
            padding: 12px 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
            border-bottom: 4px solid var(--g);
            box-shadow: 0 2px 5px rgba(0,0,0,0.08);
        }
        .header-title { display: flex; flex-direction: column; gap: 2px; }
        h1 { font-size: 17px; margin: 0; font-weight: 700; letter-spacing: -0.2px; }
        h1 small { display: block; font-weight: 400; opacity: 0.85; font-size: 11.5px; margin-top: 1px; }
        
        .header-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 4px;
            flex-wrap: wrap;
        }

        .role-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .role-approver { background: #fee2e2; color: #991b1b; }
        .role-admin { background: #fef3c7; color: #92400e; }
        .role-view { background: #e0f2fe; color: #075985; }

        .user-menu {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(0,0,0,0.2);
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
        }
        .user-menu b { color: #fff; }
        .btn-logout {
            background: rgba(255,255,255,0.15);
            color: #fff;
            border: none;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            cursor: pointer;
        }
        .btn-logout:hover { background: #c0392b; }

        .btn-approvals {
            position: relative;
            background: #d9a21b;
            color: #1a1a1a;
            border: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-approvals:hover { background: #e6b02e; }
        .approval-badge {
            background: #c0392b;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 10px;
        }

        nav, .hb, .bar { display: flex; flex-wrap: wrap; gap: 6px; align-items: center; }
        main { padding: 16px 20px; max-width: 1550px; margin: auto; }
        
        button {
            font: inherit;
            cursor: pointer;
            border: 1px solid var(--ln);
            background: #fff;
            color: var(--ink);
            border-radius: 5px;
            padding: 6px 12px;
            font-weight: 500;
            transition: all 0.15s ease;
        }
        button:hover { background: #f8faf9; border-color: #adbdb9; }
        button:disabled { opacity: 0.55; cursor: not-allowed; }
        button:focus-visible, select:focus-visible, input:focus-visible, textarea:focus-visible {
            outline: 2px solid var(--g);
            outline-offset: 1px;
        }
        nav button.on, .bar button.on {
            background: var(--g);
            border-color: var(--g);
            font-weight: 600;
            color: #1a1a1a;
        }
        .pri {
            background: var(--a);
            color: #fff;
            border-color: var(--a);
        }
        .pri:hover {
            background: var(--a-hover);
            border-color: var(--a-hover);
        }
        .bar { margin-bottom: 10px; }
        .sp { flex: 1; }
        
        .card {
            background: var(--card-bg);
            border: 1px solid var(--ln);
            border-radius: 6px;
            padding: 14px 16px;
            margin-bottom: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        }
        .card h2 { font-size: 15px; margin: 0 0 10px; color: var(--a); }
        .card label { margin-right: 18px; font-weight: 500; }
        .hint { color: #5a6b6f; font-size: 12px; margin: 6px 0 0; line-height: 1.4; }

        .banner-readonly {
            background: #e0f2fe;
            border: 1px solid #bae6fd;
            color: #0369a1;
            padding: 10px 16px;
            border-radius: 6px;
            margin-bottom: 14px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        table.t { border-collapse: collapse; width: 100%; background: #fff; border-radius: 4px; overflow: hidden; }
        .t th, .t td { border: 1px solid var(--ln); padding: 5px 8px; vertical-align: top; text-align: left; }
        .t th { background: #e2ebe9; font-weight: 600; font-size: 12.5px; }
        .t td input:not([type]), .t td input[type=text] {
            width: 100%;
            padding: 4px 6px;
            border: 1px solid var(--ln);
            border-radius: 4px;
        }
        input:disabled, select:disabled, textarea:disabled {
            background: #f1f5f4 !important;
            color: #555 !important;
            cursor: not-allowed;
        }
        
        textarea {
            width: 100%;
            min-height: 80px;
            font: inherit;
            border: 1px solid var(--ln);
            border-radius: 4px;
            padding: 8px;
        }
        
        .chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #e2ebe9;
            border-radius: 12px;
            padding: 2px 6px 2px 10px;
            margin: 0 4px 4px 0;
            font-size: 12px;
            font-weight: 500;
        }
        .chip button {
            border: 0;
            background: none;
            padding: 0 4px;
            color: #a33;
            font-weight: bold;
            font-size: 14px;
            line-height: 1;
        }
        .chip button:hover { color: #d00; }
        
        .tt { min-width: 950px; table-layout: fixed; }
        .tt .tm { width: 130px; font-weight: 600; }
        .tt td { padding: 4px; position: relative; }
        .tt select, .tt input {
            width: 100%;
            font-size: 12px;
            margin-bottom: 3px;
            border: 1px solid var(--ln);
            border-radius: 3px;
            padding: 3px 4px;
            background: #fff;
        }
        .fx th, .fx td { background: #f6f8f7; color: #4a5b5f; font-size: 12px; font-weight: 400; }
        td.cf { outline: 2px solid var(--danger); outline-offset: -2px; background: #fff5f5 !important; }
        .w { color: var(--danger); font-size: 11px; font-weight: 600; margin-top: 2px; }
        td.offd { background: #e0e0e0; color: #777; text-align: center; font-size: 12px; vertical-align: middle; }
        .off { font-weight: 400; font-size: 11px; cursor: pointer; }
        th small { font-weight: 400; opacity: 0.8; }
        .lc { font-size: 11px; text-align: center; padding: 3px; }
        .ok { background: #d8efdc !important; }
        .ov { background: #fbe9b7 !important; }
        input[type=number] { width: 80px; padding: 4px 6px; border: 1px solid var(--ln); border-radius: 4px; }
        .held { font-size: 11px; display: flex; gap: 4px; align-items: center; margin-top: 2px; cursor: pointer; }
        .tt .held input { width: auto; margin: 0; }
        
        .pg { height: 7px; background: #e2ebe9; border-radius: 4px; overflow: hidden; margin-top: 4px; }
        .pg i { display: block; height: 100%; background: var(--a); transition: width 0.3s ease; }

        /* Modern Floating Toast Notifications */
        #toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }
        .toast {
            pointer-events: auto;
            min-width: 280px;
            max-width: 420px;
            background: #fff;
            border-radius: 8px;
            padding: 12px 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.18), 0 0 0 1px rgba(0,0,0,0.06);
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 13px;
            line-height: 1.4;
            animation: toastIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }
        .toast.fade-out {
            opacity: 0;
            transform: translateX(40px);
        }
        @keyframes toastIn {
            from { opacity: 0; transform: translateY(-12px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .toast-icon { flex-shrink: 0; width: 18px; height: 18px; margin-top: 1px; }
        .toast-content { flex: 1; }
        .toast-title { font-weight: 600; font-size: 13px; margin-bottom: 2px; }
        .toast-msg { color: #5a6b6f; font-size: 12px; }
        .toast-close {
            background: none; border: none; color: #94a3b8; font-size: 16px; cursor: pointer; padding: 0; line-height: 1;
        }
        .toast-close:hover { color: #334155; }
        .toast.success { border-left: 4px solid #16a34a; }
        .toast.success .toast-icon { color: #16a34a; }
        .toast.error { border-left: 4px solid #dc2626; }
        .toast.error .toast-icon { color: #dc2626; }
        .toast.warning { border-left: 4px solid #d97706; }
        .toast.warning .toast-icon { color: #d97706; }
        .toast.info { border-left: 4px solid #0284c7; }
        .toast.info .toast-icon { color: #0284c7; }

        /* Approvals & Confirm Modals */
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
            max-width: 800px;
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
        .modal-header h2 { font-size: 16px; font-weight: 700; margin: 0; }
        .modal-close {
            background: none; border: none; color: #fff; font-size: 22px; cursor: pointer;
        }
        .modal-body {
            padding: 20px;
            overflow-y: auto;
            flex: 1;
        }
        .req-card {
            border: 1px solid var(--ln);
            border-radius: 8px;
            padding: 14px;
            margin-bottom: 12px;
            background: #fff;
        }
        .req-card.pending { border-left: 4px solid var(--warning); background: #fffdfa; }
        .req-card.approved { border-left: 4px solid var(--success); }
        .req-card.rejected { border-left: 4px solid var(--danger); opacity: 0.75; }
        .req-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 6px; }
        .req-title { font-weight: 700; font-size: 14px; color: var(--ink); }
        .req-meta { font-size: 11.5px; color: #5a6b6f; margin-bottom: 8px; }
        .req-actions { display: flex; gap: 8px; margin-top: 10px; }
        .btn-approve { background: #16a34a; color: #fff; border: none; padding: 6px 14px; border-radius: 4px; font-size: 12px; font-weight: 600; cursor: pointer; }
        .btn-approve:hover { background: #15803d; }
        .btn-reject { background: #dc2626; color: #fff; border: none; padding: 6px 14px; border-radius: 4px; font-size: 12px; font-weight: 600; cursor: pointer; }
        .btn-reject:hover { background: #b91c1c; }

        /* Custom Confirm Dialog Modal */
        #confirmModal .modal-card {
            max-width: 480px;
        }
        .confirm-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        .btn-cancel {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }
        .btn-proceed-danger {
            background: #dc2626;
            color: #fff;
            border: none;
            font-weight: 600;
        }
        .btn-proceed-danger:hover { background: #b91c1c; }

        /* Next-Level Ultra-Modern Footer */
        .site-footer {
            margin-top: 50px;
            background: linear-gradient(180deg, #0d262b 0%, #061518 100%);
            color: #d1dedb;
            border-top: 3px solid var(--g);
            padding: 34px 24px 22px;
            font-size: 13px;
            box-shadow: 0 -8px 25px rgba(0, 0, 0, 0.2);
        }
        .footer-inner {
            max-width: 1550px;
            margin: auto;
            display: flex;
            flex-wrap: wrap;
            gap: 26px;
            align-items: center;
            justify-content: space-between;
        }
        .footer-brand {
            display: flex;
            flex-direction: column;
            gap: 5px;
            max-width: 550px;
        }
        .footer-title {
            font-weight: 700;
            font-size: 15.5px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 8px;
            letter-spacing: -0.2px;
        }
        .footer-sub {
            color: #8da4a0;
            font-size: 12px;
            line-height: 1.5;
        }
        .footer-security-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 4px 11px;
            border-radius: 20px;
            font-size: 11px;
            color: #b8ccc8;
            margin-top: 6px;
            width: fit-content;
        }
        .footer-security-pill .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 6px #22c55e;
        }

        /* NeoCraftix Spotlight Card */
        .neocraftix-card {
            display: inline-flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 6px;
        }
        .neocraftix-label {
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: 0.9px;
            font-weight: 600;
            color: #8da4a0;
        }
        .neocraftix-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 18px;
            background: linear-gradient(135deg, rgba(15, 82, 87, 0.9) 0%, rgba(20, 50, 55, 0.95) 100%);
            border: 1.5px solid rgba(217, 162, 27, 0.55);
            border-radius: 30px;
            color: #fff;
            text-decoration: none;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.35), inset 0 0 12px rgba(217, 162, 27, 0.15);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }
        .neocraftix-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.25), transparent);
            transition: left 0.6s ease;
        }
        .neocraftix-badge:hover::before {
            left: 100%;
        }
        .neocraftix-badge:hover {
            transform: translateY(-2px);
            border-color: var(--g);
            box-shadow: 0 8px 25px rgba(217, 162, 27, 0.45), inset 0 0 16px rgba(217, 162, 27, 0.3);
            color: #fff;
        }
        .neocraftix-icon {
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, #d9a21b 0%, #f59e0b 100%);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0b2228;
            box-shadow: 0 2px 8px rgba(217, 162, 27, 0.4);
            font-weight: 800;
            font-size: 13.5px;
        }
        .neocraftix-name {
            font-size: 14.5px;
            font-weight: 700;
            letter-spacing: 0.3px;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .neocraftix-name span {
            color: var(--g);
        }
        .neocraftix-arrow {
            width: 14px;
            height: 14px;
            transition: transform 0.2s ease;
            opacity: 0.85;
            color: var(--g);
        }
        .neocraftix-badge:hover .neocraftix-arrow {
            transform: translate(2px, -2px);
            opacity: 1;
        }
        .footer-bottom {
            max-width: 1550px;
            margin: 22px auto 0;
            padding-top: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            font-size: 11.5px;
            color: #728a86;
        }
        
        @media print {
            header, .noprint, .banner-readonly, #toast-container, .site-footer { display: none !important; }
            main { padding: 0; max-width: 100%; }
            .card { border: none; padding: 0; }
            .tt select, .tt input { border: 0; appearance: none; -webkit-appearance: none; background: transparent; }
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
</head>
<body>

<!-- Toast Notification Container -->
<div id="toast-container"></div>

<header>
    <div class="header-title">
        <h1>SLTES Orientation Programme 2026 – Central Timetable Planner<small>Ministry of Education, Sri Lanka • Six-month 7-Centre Timetable System</small></h1>
        <div class="header-meta">
            @if ($currentUser)
                @if ($currentUser->role === 'moe_approver')
                    <span class="role-pill role-approver">MoE Chief Authorizer (Dual-Approval Granted)</span>
                @elseif ($currentUser->role === 'moe_admin')
                    <span class="role-pill role-admin">MoE Editor / Planner</span>
                @else
                    <span class="role-pill role-view">View-Only Access ({{ $currentUser->isCenterManager() ? 'Center Manager' : 'Topic Leader' }})</span>
                @endif

                <div class="user-menu">
                    <span>Officer: <b>{{ $currentUser->name }}</b></span>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-logout">Sign Out</button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <nav>
        <button data-v="setup">1 Setup</button>
        <button data-v="asg">2 Lecturers by topic</button>
        <button data-v="tt">3 Timetable</button>
        <button data-v="prog">4 Centre progress</button>
        <button data-v="lec">5 Lecturer view</button>
        <button data-v="sum">6 Hours summary</button>
    </nav>

    <div class="hb">
        <button class="btn-approvals" onclick="openApprovalsModal()">
            <span>MoE Approvals</span>
            <span class="approval-badge" id="approvalBadge">{{ $initialState['pending_approvals_count'] ?? 0 }}</span>
        </button>
        <button data-act="exp">Export to Excel</button>
        @if ($currentUser && ($currentUser->can_edit || $currentUser->can_approve))
            <button data-act="imp">Import from Excel</button>
            <input type="file" id="imp" hidden accept=".xlsx,.xls,.csv">
        @endif
    </div>
</header>

<main>
    @if ($currentUser && $currentUser->isReadOnly())
        <div class="banner-readonly">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            <div>
                <b>View-Only Mode Active:</b> You are signed in as <b>{{ $currentUser->name }}</b> ({{ $currentUser->designation }}).
                As per Ministry of Education security policy, Centre Managers and Topic Leaders have full timetable viewing and reporting privileges. Direct deletions and modifications require Ministry authorization.
            </div>
        </div>
    @endif

    <div id="app"></div>
</main>

<!-- Next-Level NeoCraftix Footer -->
<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <div class="footer-title">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--g)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <span>SLTES Orientation 2026 – Ministry of Education</span>
            </div>
            <div class="footer-sub">
                Official centralized academic timetable planning & multi-centre logistical management portal developed for the Sri Lanka Teacher Educators Service (SLTES).
            </div>
            <div class="footer-security-pill">
                <span class="dot"></span>
                <span>256-Bit SSL Encrypted • Role-Based Access Control • MoE Dual-Approval Protected</span>
            </div>
        </div>

        <div class="neocraftix-card">
            <span class="neocraftix-label">Designed & Engineered By</span>
            <a href="https://neocraftix.com/" target="_blank" rel="noopener noreferrer" class="neocraftix-badge" title="Visit NeoCraftix Official Website">
                <div class="neocraftix-icon">N</div>
                <div class="neocraftix-name">
                    Neo<span>Craftix</span>
                </div>
                <svg class="neocraftix-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
        </div>
    </div>

    <div class="footer-bottom">
        <div>
            © 2026 Ministry of Education, Sri Lanka. All Rights Reserved. Official Government Information System.
        </div>
        <div>
            Enterprise Technology Architecture & Engineering by <a href="https://neocraftix.com/" target="_blank" rel="noopener noreferrer" style="color:var(--g); font-weight:700; text-decoration:none;">NeoCraftix</a>
        </div>
    </div>
</footer>

<!-- Approvals Modal -->
<div class="modal" id="approvalsModal">
    <div class="modal-card">
        <div class="modal-header">
            <h2>Ministry of Education – Deletion & Authorization Requests</h2>
            <button class="modal-close" onclick="closeApprovalsModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p style="font-size:12.5px; color:#5a6b6f; margin-bottom:14px;">
                As mandated by the Ministry of Education, all timetable modifications, slot removals, and data clearances require designated MoE Chief Approver authorization before executing.
            </p>
            <div id="approvalsList">
                <p style="color:#777;">Loading requests...</p>
            </div>
        </div>
    </div>
</div>

<!-- Custom Confirmation Modal -->
<div class="modal" id="confirmModal">
    <div class="modal-card">
        <div class="modal-header" id="confirmHeader">
            <h2 id="confirmTitle">Confirm Action</h2>
            <button class="modal-close" onclick="closeConfirmModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p id="confirmMessage" style="font-size:13.5px; line-height:1.5; color:#334155; margin-bottom:14px;"></p>
            <div class="confirm-actions">
                <button type="button" class="btn-cancel" onclick="closeConfirmModal()">Cancel</button>
                <button type="button" id="confirmProceedBtn" class="btn-proceed-danger">Proceed</button>
            </div>
        </div>
    </div>
</div>

<script>
// Initial state injected from Laravel
const INITIAL_DATA = @json($initialState);
const USER = INITIAL_DATA.user;
const IS_READONLY = USER ? USER.is_read_only : false;
const CAN_APPROVE = USER ? USER.can_approve : false;
const CAN_EDIT = USER ? USER.can_edit : false;

const T = INITIAL_DATA.topics.map(t => [t.title, t.required_hours, t.id]);
const NC = 7;
const SL = [
    ["09:00–10:30", "Session 01", 1.5],
    ["11:00–12:30", "Session 02", 1.5],
    ["13:30–16:30", "Session 03", 3.0],
    ["17:30–20:30", "Night Session", 3.0]
];
const FX = {
    0: [["08:00–09:00", "Morning Assembly / Reflection and Thought of the Day"]],
    1: [["10:30–11:00", "Morning Tea Break"]],
    2: [["12:30–13:30", "Lunch"]],
    3: [["16:30–17:30", "Evening Refreshment and Break"]]
};

const $ = s => document.querySelector(s);
const e = s => String(s ?? '').replace(/[&<>"]/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));
const CSRF = $('meta[name="csrf-token"]').getAttribute('content');

// Toast Notification Engine
function showToast(title, message = '', type = 'info', duration = 4000) {
    const container = $('#toast-container');
    if (!container) return;

    const icons = {
        success: '<svg class="toast-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
        error: '<svg class="toast-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>',
        warning: '<svg class="toast-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
        info: '<svg class="toast-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
    };

    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        ${icons[type] || icons.info}
        <div class="toast-content">
            <div class="toast-title">${e(title)}</div>
            ${message ? `<div class="toast-msg">${e(message)}</div>` : ''}
        </div>
        <button class="toast-close" onclick="this.parentElement.remove()">&times;</button>
    `;

    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('fade-out');
        setTimeout(() => toast.remove(), 250);
    }, duration);
}

// Confirmation Modal Helper
let onConfirmCallback = null;
function showConfirm(title, message, isDanger, onConfirm) {
    $('#confirmTitle').innerText = title;
    $('#confirmMessage').innerHTML = message;
    const btn = $('#confirmProceedBtn');
    btn.className = isDanger ? 'btn-proceed-danger' : 'pri';
    btn.innerText = isDanger ? 'Confirm Deletion' : 'Submit Request';
    onConfirmCallback = onConfirm;
    $('#confirmModal').classList.add('open');
}

function closeConfirmModal() {
    $('#confirmModal').classList.remove('open');
    onConfirmCallback = null;
}

$('#confirmProceedBtn').addEventListener('click', () => {
    if (onConfirmCallback) {
        const cb = onConfirmCallback;
        closeConfirmModal();
        cb();
    }
});

// Debounce helper to prevent input lag
const debounceTimers = {};
function debounce(key, fn, delay = 350) {
    clearTimeout(debounceTimers[key]);
    debounceTimers[key] = setTimeout(fn, delay);
}

// Convert server state to client state
function buildClientState(data) {
    const s = {
        start: data.settings.start_date || '2026-11-02',
        weeks: parseInt(data.settings.weeks) || 20,
        sat: !!data.settings.include_saturday,
        cur: { c: 0, w: 0 },
        view: 'setup',
        off: {},
        s: {},
        nid: 1,
        L: [],
        C: [],
        A: [],
        P: []
    };

    s.C = data.centres.map(c => ({
        id: c.id,
        code: c.code,
        n: c.name,
        m: c.manager_name || '',
        p: c.contact || ''
    }));

    s.L = data.lecturers.map(l => ({ id: l.id, n: l.name }));
    s.nid = s.L.reduce((max, l) => Math.max(max, l.id), 0) + 1;

    s.A = Array(NC).fill(null).map(() => Array(T.length).fill(null).map(() => []));
    data.assignments.forEach(a => {
        const ci = s.C.findIndex(c => c.id === a.centre_id);
        const ti = T.findIndex(t => t[2] === a.topic_id);
        if (ci >= 0 && ti >= 0 && !s.A[ci][ti].includes(a.lecturer_id)) {
            s.A[ci][ti].push(a.lecturer_id);
        }
    });

    data.slots.forEach(sl => {
        const ci = s.C.findIndex(c => c.id === sl.centre_id);
        let ti = -1;
        if (sl.is_other_topic) {
            ti = 9;
        } else if (sl.topic_id) {
            ti = T.findIndex(t => t[2] === sl.topic_id);
        }
        if (ci >= 0) {
            const key = [ci, sl.week, sl.day, sl.slot_index].join('|');
            s.s[key] = {
                t: ti,
                l: sl.lecturer_id ? sl.lecturer_id : -1,
                u: sl.unit_note || '',
                h: !!sl.is_held
            };
        }
    });

    data.days_off.forEach(d => {
        s.off[d.week + '-' + d.day] = true;
    });

    s.P = Array(NC).fill(null).map(() => Array(T.length).fill(null).map(() => ({ h: 0, r: '' })));
    data.progress.forEach(p => {
        const ci = s.C.findIndex(c => c.id === p.centre_id);
        const ti = T.findIndex(t => t[2] === p.topic_id);
        if (ci >= 0 && ti >= 0) {
            s.P[ci][ti] = {
                h: parseFloat(p.manual_hours) || 0,
                r: p.remarks || ''
            };
        }
    });

    return s;
}

let S = buildClientState(INITIAL_DATA);

// Seamless API Post with Sanitized Error Handling
async function apiPost(endpoint, body) {
    try {
        const res = await fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json'
            },
            body: JSON.stringify(body || {})
        });

        const data = await res.json().catch(() => ({}));

        if (!res.ok) {
            const cleanMsg = data.message || 'දත්ත සුරැකීමට නොහැකි විය. කරුණාකර නැවත උත්සාහ කරන්න.';
            showToast('System Notice', cleanMsg, 'error');
            return null;
        }

        return data;
    } catch (err) {
        showToast('Connection Warning', 'Server connection momentarily unavailable. Data remains protected.', 'warning');
        return null;
    }
}

// Helpers
const nd = () => S.sat ? 6 : 5;
const k = (c, w, d, i) => [c, w, d, i].join('|');
const ln = id => (S.L.find(x => x.id === id) || { n: '?' }).n;
const act = () => S.L.filter(l => l.n.trim()).map(l => l.id);
const dt = (w, d) => {
    const x = new Date(S.start + 'T00:00:00');
    x.setDate(x.getDate() + w * 7 + d);
    return x;
};
const fd = (x, wd) => x.toLocaleDateString('en-GB', wd ? { weekday: 'short', day: '2-digit', month: 'short' } : { day: '2-digit', month: 'short', year: 'numeric' });
const col = t => (t >= 0 && t < 9) ? `background:hsl(${t * 40} 55% 93%)` : '';
const pool = (c, t) => (t >= 0 && t < 9) ? S.A[c][t].filter(id => act().includes(id)) : [];

function lecBusy(w, d, i, l, ex) {
    for (let c = 0; c < NC; c++) {
        if (c !== ex && S.s[k(c, w, d, i)]?.l === l) return S.C[c].n;
    }
    return '';
}

const heldH = (c, t) => {
    let h = 0;
    for (const K in S.s) {
        const o = S.s[K];
        if (o.h && o.t === t) {
            const p = K.split('|').map(Number);
            if (p[0] === c && p[3] < SL.length) h += SL[p[3]][2];
        }
    }
    return h;
};

const cov = (c, t) => heldH(c, t) + (+S.P[c][t].h || 0);

// Rendering views
function R() {
    S.weeks = Math.max(1, Math.min(30, S.weeks));
    S.cur.w = Math.min(S.cur.w, S.weeks - 1);
    document.querySelectorAll('nav button').forEach(b => b.classList.toggle('on', b.dataset.v === S.view));
    $('#app').innerHTML = { setup: vSetup, asg: vAsg, tt: vTT, prog: vProg, lec: vLec, sum: vSum }[S.view]();
}

const cbar = () => `<div class="bar noprint">${S.C.map((x, i) => `<button data-go="c" data-i="${i}" class="${i === S.cur.c ? 'on' : ''}">${e(x.n)}</button>`).join('')}</div>`;

function wbar() {
    const w = S.cur.w;
    return `<button data-go="w" data-i="${w - 1}">◀</button><select id="wsel">${[...Array(S.weeks).keys()].map(i => `<option value="${i}" ${i === w ? 'selected' : ''}>Week ${i + 1} (${fd(dt(i, 0))})</option>`).join('')}</select><button data-go="w" data-i="${w + 1}">▶</button>`;
}

function vSetup() {
    const dis = IS_READONLY ? 'disabled' : '';
    return `<section class="card"><h2>Programme Settings</h2>
    <label>Start date (Monday) <input type="date" id="st" value="${S.start}" ${dis}></label>
    <label>Weeks <input type="number" id="wk" min="1" max="30" value="${S.weeks}" style="width:64px" ${dis}></label>
    <label><input type="checkbox" id="sat" ${S.sat ? 'checked' : ''} ${dis}> Include Saturday</label>
    <p class="hint">The course is 511 lecture hours. Sessions 01–03 give 6 lecture hours a day (about 17 weeks at 5 days a week); the 3-hour night session is optional. All configuration changes are saved automatically.</p></section>

    <section class="card"><h2>Training Centres and Centre Managers</h2>
    <table class="t"><tr><th>#<th>Centre Name<th>Centre Manager<th>Contact (Phone/Email)</tr>
    ${S.C.map((c, i) => `<tr><td>${i + 1}<td><input data-c="${i}" data-f="n" value="${e(c.n)}" ${dis}><td><input data-c="${i}" data-f="m" value="${e(c.m)}" placeholder="Manager name" ${dis}><td><input data-c="${i}" data-f="p" value="${e(c.p)}" placeholder="Phone / email" ${dis}></tr>`).join('')}
    </table></section>

    <section class="card"><h2>Lecturer Directory (${S.L.length} Registered Lecturers)</h2>
    ${!IS_READONLY ? `
    <textarea id="bulk" placeholder="Type or paste names, one per line"></textarea>
    <div class="bar" style="margin-top:6px"><button class="pri" data-act="addl">+ Add lecturers</button></div>
    ` : ''}
    ${S.L.length ? `<table class="t"><tr><th>Name${!IS_READONLY ? '<th style="width:120px">Action' : ''}</tr>${S.L.map(l => `<tr><td><input data-l="${l.id}" data-f="n" value="${e(l.n)}" ${dis}>${!IS_READONLY ? `<td><button data-rm="${l.id}" style="color:var(--danger)">Remove</button>` : ''}</tr>`).join('')}</table>` : '<p class="hint">No lecturers yet.</p>'}</section>`;
}

function vAsg() {
    const c = S.cur.c, all = act(), dis = IS_READONLY ? 'disabled' : '';
    return cbar() + `<div class="card"><b>${e(S.C[c].n)}</b> | Manager: ${e(S.C[c].m || 'not set')}<p class="hint">Lecturers assigned for each topic in this centre. Changes are saved automatically.</p></div>
    <table class="t"><tr><th>Topic<th style="width:70px">Hours<th>Lecturers assigned for this centre</tr>
    ${T.map((x, t) => {
        const ids = S.A[c][t];
        return `<tr><td>T${t + 1} ${e(x[0])}<td>${x[1]} h<td>${ids.map(id => `<span class="chip">${e(ln(id))}${!IS_READONLY ? `<button data-un="${c}|${t}|${id}" title="Remove">×</button>` : ''}</span>`).join('')}
        ${!IS_READONLY ? `<select data-as="${c}|${t}"><option value="">+ add lecturer</option>${all.filter(id => !ids.includes(id)).map(id => `<option value="${id}">${e(ln(id))}</option>`).join('')}<option value="-9">+ New lecturer…</option></select>` : ''}</tr>`;
    }).join('')}</table>
    ${!IS_READONLY ? `<div class="bar" style="margin-top:10px"><button class="pri" data-act="cpall">Copy this centre’s lecturers to all centres</button></div>` : ''}`;
}

function cell(c, w, d, i) {
    const K = k(c, w, d, i), o = S.s[K] || { t: -1, l: -1, u: '', h: false }, cf = o.l >= 0 ? lecBusy(w, d, i, o.l, c) : '';
    let ids = o.t === 9 ? act() : pool(c, o.t);
    if (o.l >= 0 && !ids.includes(o.l)) ids = [...ids, o.l];
    const ph = o.t < 0 ? '– select topic –' : ids.length ? '– lecturer –' : '– none assigned –';
    const dis = IS_READONLY ? 'disabled' : '';

    return `<td class="${cf ? 'cf' : ''}" style="${col(o.t)}">
        <select data-k="${K}" data-f="t" ${dis}>
            <option value="-1">– topic –</option>
            ${T.map((x, j) => `<option value="${j}" ${o.t === j ? 'selected' : ''}>T${j + 1} ${e(x[0])}</option>`).join('')}
            <option value="9" ${o.t === 9 ? 'selected' : ''}>Assessment / Other</option>
        </select>
        <select data-k="${K}" data-f="l" ${dis}>
            <option value="-1">${ph}</option>
            ${ids.map(j => `<option value="${j}" ${o.l === j ? 'selected' : ''}>${e(ln(j))}</option>`).join('')}
        </select>
        <input data-k="${K}" data-f="u" placeholder="Unit / note" value="${e(o.u)}" ${dis}>
        <label class="held"><input type="checkbox" data-k="${K}" data-f="h" ${o.h ? 'checked' : ''} ${dis}> Session held</label>
        ${cf ? `<div class="w">⚠ Double-booked: Lecturer also at ${e(cf)}</div>` : ''}
    </td>`;
}

function vTT() {
    const { c, w } = S.cur, C = S.C[c], n = nd(), D = [...Array(n).keys()], dis = IS_READONLY ? 'disabled' : '';
    let h = cbar() + `<div class="bar noprint">${wbar()}<span class="sp"></span>
    ${!IS_READONLY ? `
    <button class="pri" data-act="auto">⚡ Auto-fill empty slots</button>
    <button data-act="clrw" style="color:var(--danger)">Clear this week</button>
    <button data-act="clrc" style="color:var(--danger)">Clear this centre</button>
    ` : ''}
    <button onclick="print()">Print</button></div>
    <div class="card"><b>${e(C.n)}</b> | Centre manager: ${e(C.m || 'not set')}${C.p ? ' | ' + e(C.p) : ''} | Week ${w + 1}: ${fd(dt(w, 0))} to ${fd(dt(w, n - 1))}</div>
    <div style="overflow:auto"><table class="t tt">
    <tr><th class="tm">Time${D.map(d => `<th>${fd(dt(w, d), 1)}<br><label class="noprint off"><input type="checkbox" data-off="${w}-${d}" ${S.off[w + '-' + d] ? 'checked' : ''} ${dis}> day off</label>`).join('')}</tr>`;

    for (let i = 0; i < SL.length; i++) {
        (FX[i] || []).forEach(f => h += `<tr class="fx"><th class="tm">${f[0]}<td colspan="${n}">${f[1]}</tr>`);
        h += `<tr><th class="tm">${SL[i][0]}<br><small>${SL[i][1]}</small>${D.map(d => S.off[w + '-' + d] ? '<td class="offd">Day off' : cell(c, w, d, i)).join('')}</tr>`;
    }
    return h + '</table></div>';
}

function vProg() {
    const c = S.cur.c, C = S.C[c], sch = T.map(() => 0), f = x => +x.toFixed(2), dis = IS_READONLY ? 'disabled' : '';
    for (const K in S.s) {
        const o = S.s[K], p = K.split('|').map(Number);
        if (p[0] === c && o.t >= 0 && o.t < 9 && p[3] < SL.length) sch[o.t] += SL[p[3]][2];
    }
    const sum = fn => f(T.reduce((a, x, t) => a + fn(x, t), 0));
    return cbar() + `<div class="card"><b>${e(C.n)}</b> | Centre manager: ${e(C.m || 'not set')}<p class="hint">Session progress status automatically computed from scheduled timetable sessions and completed training hours.</p></div>
    <div class="bar noprint"><button onclick="print()">Print</button></div>
    <table class="t"><tr><th>Topic<th>Required h<th>Scheduled h<th>Held (timetable) h<th>Extra hours (manual)<th>Covered h<th>Remaining h<th>Status<th>Remarks</tr>
    ${T.map((x, t) => {
        const cv = cov(c, t), st = cv <= 0 ? 'Not started' : cv >= x[1] ? 'Completed' : 'In progress';
        return `<tr><td>T${t + 1} ${e(x[0])}<td>${x[1]}<td>${f(sch[t])}<td>${f(heldH(c, t))}<td><input type="number" min="0" step="0.25" data-p="${c}|${t}" data-f="h" value="${S.P[c][t].h || 0}" ${dis}><td>${f(cv)}<div class="pg"><i style="width:${Math.min(100, (cv / x[1]) * 100)}%"></i></div><td>${f(Math.max(0, x[1] - cv))}<td class="${st === 'Completed' ? 'ok' : st === 'In progress' ? 'ov' : ''}">${st}<td><input data-p="${c}|${t}" data-f="r" value="${e(S.P[c][t].r || '')}" placeholder="Remarks" ${dis}></tr>`;
    }).join('')}
    <tr><th>Total<th>${sum(x => x[1])}<th>${sum((x, t) => sch[t])}<th>${sum((x, t) => heldH(c, t))}<th><th>${sum((x, t) => cov(c, t))}<th>${sum((x, t) => Math.max(0, x[1] - cov(c, t)))}<th><th></tr></table>`;
}

function vLec() {
    const w = S.cur.w, D = [...Array(nd()).keys()];
    return `<div class="bar noprint">${wbar()}<button onclick="print()">Print</button></div>
    <div style="overflow:auto"><table class="t">
    <tr><th>Lecturer${D.map(d => `<th colspan="4">${fd(dt(w, d), 1)}`).join('')}</tr>
    <tr><th>${D.map(() => '<th class="lc">S1<th class="lc">S2<th class="lc">S3<th class="lc">Night').join('')}</tr>` +
    act().map(l => `<tr><th>${e(ln(l))}${D.map(d => [0, 1, 2, 3].map(i => {
        for (let c = 0; c < NC; c++) {
            const o = S.s[k(c, w, d, i)];
            if (o && o.l === l) return `<td class="lc" style="${col(o.t)}" title="${e(S.C[c].n)}">${'ABCDEFG'[c]}${o.t >= 0 && o.t < 9 ? ' T' + (o.t + 1) : ''}`;
        }
        return '<td class="lc">';
    }).join('')).join('')}</tr>`).join('') +
    `</table></div><p class="hint">A–G are the seven centres, T is the topic, S1–S3 are the day sessions and Night is the night session.</p>`;
}

function vSum() {
    const sc = S.C.map(() => T.map(() => 0)), ld = {};
    for (const K in S.s) {
        const [c, w, d, i] = K.split('|').map(Number), o = S.s[K];
        if (i >= SL.length || w >= S.weeks || d >= nd()) continue;
        const h = SL[i][2];
        if (o.t >= 0 && o.t < 9) sc[c][o.t] += h;
        if (o.l >= 0) ld[o.l] = (ld[o.l] || 0) + h;
    }
    const f = x => +x.toFixed(2);
    return `<section class="card"><h2>Hours scheduled per topic and centre</h2><table class="t"><tr><th>Topic<th>Required${S.C.map(c => `<th>${e(c.n)}`).join('')}</tr>
    ${T.map((x, t) => `<tr><td>T${t + 1} ${e(x[0])}<td>${x[1]}${S.C.map((c, ci) => `<td class="${sc[ci][t] >= x[1] ? (sc[ci][t] > x[1] + 1.5 ? 'ov' : 'ok') : ''}">${f(sc[ci][t])}`).join('')}</tr>`).join('')}</table>
    <p class="hint">Green: topic hours covered. Amber: more than required.</p></section>
    
    <section class="card"><h2>Hours covered (held sessions + manager entries)</h2><table class="t"><tr><th>Topic<th>Required${S.C.map(c => `<th>${e(c.n)}`).join('')}</tr>
    ${T.map((x, t) => `<tr><td>T${t + 1} ${e(x[0])}<td>${x[1]}${S.C.map((c, ci) => `<td class="${cov(ci, t) >= x[1] ? 'ok' : ''}">${f(cov(ci, t))}`).join('')}</tr>`).join('')}</table></section>

    <section class="card"><h2>Lecturer teaching hours</h2><table class="t"><tr><th>Lecturer<th>Hours</tr>
    ${act().map(l => `<tr><td>${e(ln(l))}<td>${f(ld[l] || 0)}</tr>`).join('')}</table></section>`;
}

// Automated timetable scheduling algorithm
async function auto() {
    if (IS_READONLY) return showToast('Permission Denied', 'View-only accounts cannot modify the schedule.', 'warning');
    const rem = S.C.map(() => T.map(x => x[1])), load = {}, lt = Array(NC).fill(-1), ll = Array(NC).fill(-1);
    for (const K in S.s) {
        const [c, w, d, i] = K.split('|').map(Number), o = S.s[K];
        if (o.t >= 0 && o.t < 9 && i < SL.length) rem[c][o.t] -= SL[i][2];
        if (o.l >= 0) load[o.l] = (load[o.l] || 0) + SL[i][2];
    }
    const modifiedSlots = [];
    for (let w = 0; w < S.weeks; w++) {
        for (let d = 0; d < nd(); d++) {
            if (S.off[w + '-' + d]) continue;
            for (let i = 0; i < 3; i++) {
                for (let c = 0; c < NC; c++) {
                    const K = k(c, w, d, i);
                    if (S.s[K] && S.s[K].t >= 0) continue;
                    const ord = [...Array(9).keys()].map(j => (j + c * 2) % 9);
                    const cand = (lt[c] >= 0 && rem[c][lt[c]] > 0.01) ? [lt[c], ...ord] : ord;
                    for (const t of cand) {
                        if (rem[c][t] <= 0.01) continue;
                        const p = pool(c, t).filter(l => !lecBusy(w, d, i, l, c));
                        if (!p.length) continue;
                        const l = (t === lt[c] && p.includes(ll[c])) ? ll[c] : p.sort((a, b) => (load[a] || 0) - (load[b] || 0))[0];
                        S.s[K] = { t, l, u: S.s[K]?.u || '', h: S.s[K]?.h || false };
                        rem[c][t] -= SL[i][2];
                        load[l] = (load[l] || 0) + SL[i][2];
                        lt[c] = t;
                        ll[c] = l;
                        modifiedSlots.push({
                            centre_id: S.C[c].id,
                            week: w,
                            day: d,
                            slot_index: i,
                            topic_id: T[t][2],
                            is_other_topic: false,
                            lecturer_id: l,
                            unit_note: S.s[K].u || '',
                            is_held: false
                        });
                        break;
                    }
                }
            }
        }
    }

    if (modifiedSlots.length > 0) {
        await apiPost('/api/timetable/slots-bulk', { slots: modifiedSlots });
    }

    const left = rem.reduce((a, r) => a + r.reduce((x, y) => x + Math.max(0, y), 0), 0);
    R();
    showToast('Auto-Fill Complete', left > 0.01 ? `${+left.toFixed(2)} lecture hours remain unscheduled.` : 'All required hours scheduled successfully!', 'success');
}

// Export to Excel
function expX() {
    if (!window.XLSX) return showToast('Error', 'Excel library could not be loaded.', 'error');
    const wb = XLSX.utils.book_new(), add = (n, r) => XLSX.utils.book_append_sheet(wb, XLSX.utils.json_to_sheet(r), n);
    add('Settings', [{ Setting: 'Start date', Value: S.start }, { Setting: 'Weeks', Value: S.weeks }, { Setting: 'Include Saturday', Value: S.sat ? 'Yes' : 'No' }]);
    add('Centres', S.C.map((c, i) => ({ Centre: i + 1, Name: c.n, Manager: c.m, Contact: c.p })));
    add('Lecturers', S.L.map(l => ({ Name: l.n })));
    add('Assignments', S.C.flatMap((c, ci) => T.map((x, t) => ({ Centre: c.n, 'Topic No': t + 1, Topic: x[0], Hours: x[1], Lecturers: S.A[ci][t].map(ln).join('; ') }))));
    add('Timetable', Object.keys(S.s).map(K => K.split('|').map(Number)).sort((a, b) => a[0] - b[0] || a[1] - b[1] || a[2] - b[2] || a[3] - b[3]).map(([c, w, d, i]) => ({ o: S.s[k(c, w, d, i)], c, w, d, i })).filter(x => x.o.t >= 0 || x.o.l >= 0 || x.o.u || x.o.h).map(({ o, c, w, d, i }) => ({ Centre: S.C[c].n, Week: w + 1, 'Day No': d + 1, Date: fd(dt(w, d), 1), 'Slot No': i + 1, Time: SL[i][0], 'Topic No': o.t < 0 ? '' : o.t === 9 ? 'Other' : o.t + 1, Topic: o.t < 0 ? '' : o.t === 9 ? 'Assessment / Other' : T[o.t][0], Lecturer: o.l < 0 ? '' : ln(o.l), Note: o.u, Held: o.h ? 'Yes' : '' })));
    add('DaysOff', Object.keys(S.off).filter(x => S.off[x]).map(x => { const [w, d] = x.split('-').map(Number); return { Week: w + 1, 'Day No': d + 1 }; }));
    add('Progress', S.C.flatMap((c, ci) => T.map((x, t) => { const cv = cov(ci, t); return { Centre: c.n, 'Topic No': t + 1, Topic: x[0], Required: x[1], 'Held hours': heldH(ci, t), 'Manual hours': +S.P[ci][t].h || 0, Covered: cv, Status: cv <= 0 ? 'Not started' : cv >= x[1] ? 'Completed' : 'In progress', Remarks: S.P[ci][t].r || '' }; })));
    XLSX.writeFile(wb, 'sltes2026_timetable.xlsx');
    showToast('Exported', 'Excel spreadsheet downloaded.', 'success');
}

// Deletion / Sensitive Operations Interceptor (Always passes valid object payload)
function requestOrExecuteDeletion(actionType, title, description, payload) {
    if (IS_READONLY) {
        showToast('Access Denied', 'Your account role is View-Only. Deletions are restricted.', 'error');
        return;
    }

    const safePayload = (payload && typeof payload === 'object') ? payload : {};

    if (CAN_APPROVE) {
        showConfirm(
            'Ministry Authorization Confirmation',
            `Are you sure you want to permanently execute: <b>${e(title)}</b>?`,
            true,
            async () => {
                const res = await apiPost('/api/approvals', {
                    action_type: actionType,
                    title: title,
                    description: description,
                    payload: safePayload
                });
                if (res && res.success) {
                    showToast('Approved & Executed', 'Action approved and updated successfully.', 'success');
                    setTimeout(() => location.reload(), 600);
                }
            }
        );
    } else {
        showConfirm(
            'Ministry of Education Authorization Required',
            `This deletion or clearing action requires permission from the 2 Ministry of Education Approvers.<br><br>Submit authorization request for: <b>${e(title)}</b>?`,
            false,
            async () => {
                const res = await apiPost('/api/approvals', {
                    action_type: actionType,
                    title: title,
                    description: description,
                    payload: safePayload
                });
                if (res && res.success) {
                    showToast('Request Submitted', 'Waiting for authorization by Ministry of Education Approvers.', 'warning');
                    refreshApprovalCount();
                }
            }
        );
    }
}

// Approvals Modal Logic
async function openApprovalsModal() {
    $('#approvalsModal').classList.add('open');
    loadApprovals();
}

function closeApprovalsModal() {
    $('#approvalsModal').classList.remove('open');
}

async function loadApprovals() {
    const list = $('#approvalsList');
    list.innerHTML = '<p style="color:#777;">Loading authorization requests...</p>';
    try {
        const res = await fetch('/api/approvals', {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
        });
        const data = await res.json();
        if (data.pending_count !== undefined) {
            $('#approvalBadge').innerText = data.pending_count;
        }

        if (!data.requests || !data.requests.length) {
            list.innerHTML = '<p style="color:#5a6b6f; font-style:italic;">No authorization requests found.</p>';
            return;
        }

        list.innerHTML = data.requests.map(r => {
            const isPending = r.status === 'pending';
            return `<div class="req-card ${r.status}">
                <div class="req-header">
                    <span class="req-title">${e(r.title)}</span>
                    <span class="role-pill ${isPending ? 'role-admin' : (r.status === 'approved' ? 'role-approver' : 'role-view')}">${e(r.status.toUpperCase())}</span>
                </div>
                <div class="req-meta">
                    Requested by: <b>${e(r.requester?.name || 'Officer')}</b> (${e(r.requester?.designation || r.requester?.role)}) • ${new Date(r.created_at).toLocaleString()}
                    ${r.approver ? `<br>Decided by: <b>${e(r.approver.name)}</b> (${e(r.approver.designation)}) on ${new Date(r.decided_at).toLocaleString()}` : ''}
                    ${r.decision_notes ? `<br>Notes: <i>${e(r.decision_notes)}</i>` : ''}
                </div>
                <div>${e(r.description)}</div>
                ${isPending && CAN_APPROVE ? `
                <div class="req-actions">
                    <button class="btn-approve" onclick="decideRequest(${r.id}, 'approved')">✓ Authorize & Execute</button>
                    <button class="btn-reject" onclick="decideRequest(${r.id}, 'rejected')">✗ Reject</button>
                </div>
                ` : (isPending ? '<div style="margin-top:8px; font-size:12px; color:#d97706; font-weight:600;">⏳ Waiting for Ministry of Education Chief Approver decision.</div>' : '')}
            </div>`;
        }).join('');
    } catch (err) {
        list.innerHTML = '<p style="color:red;">Failed to load requests.</p>';
    }
}

async function decideRequest(id, decision) {
    const notes = decision === 'rejected' ? (prompt('Enter reason for rejection (optional):') || '') : '';
    const res = await apiPost(`/api/approvals/${id}/decide`, {
        decision: decision,
        notes: notes
    });
    if (res && res.success) {
        showToast(decision === 'approved' ? 'Authorization Granted' : 'Request Rejected', res.message, decision === 'approved' ? 'success' : 'info');
        loadApprovals();
        if (decision === 'approved') {
            setTimeout(() => location.reload(), 700);
        }
    }
}

async function refreshApprovalCount() {
    try {
        const res = await fetch('/api/approvals', {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
        });
        const data = await res.json();
        if (data.pending_count !== undefined) {
            $('#approvalBadge').innerText = data.pending_count;
        }
    } catch (e) {}
}

// Live Input Event Listeners with Debounce
document.addEventListener('input', ev => {
    if (IS_READONLY) return;
    const t = ev.target, d = t.dataset;

    if (d.c !== undefined && d.f) {
        S.C[d.c][d.f] = t.value;
        debounce(`centre-${d.c}`, () => {
            apiPost(`/api/centres/${S.C[d.c].id}`, {
                name: S.C[d.c].n,
                manager_name: S.C[d.c].m,
                contact: S.C[d.c].p
            });
        }, 400);
        return;
    }

    if (d.l !== undefined && d.f) {
        const item = S.L.find(x => x.id === +d.l);
        if (item) {
            item.n = t.value;
            debounce(`lecturer-${item.id}`, () => {
                fetch(`/api/lecturers/${item.id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify({ name: item.n })
                });
            }, 400);
        }
        return;
    }

    if (d.k && d.f === 'u') {
        const o = S.s[d.k] || (S.s[d.k] = { t: -1, l: -1, u: '', h: false });
        o.u = t.value;
        const [c, w, dd, i] = d.k.split('|').map(Number);
        debounce(`slot-note-${d.k}`, () => {
            const isOther = o.t === 9;
            const topicId = (o.t >= 0 && o.t < 9) ? T[o.t][2] : null;
            apiPost('/api/timetable/slot', {
                centre_id: S.C[c].id,
                week: w,
                day: dd,
                slot_index: i,
                topic_id: topicId,
                is_other_topic: isOther,
                lecturer_id: o.l > 0 ? o.l : null,
                unit_note: o.u || '',
                is_held: !!o.h
            });
        }, 400);
        return;
    }

    if (d.p && d.f === 'r') {
        const [c, tp] = d.p.split('|').map(Number);
        S.P[c][tp].r = t.value;
        debounce(`progress-r-${c}-${tp}`, () => {
            apiPost('/api/progress', {
                centre_id: S.C[c].id,
                topic_id: T[tp][2],
                manual_hours: S.P[c][tp].h,
                remarks: S.P[c][tp].r
            });
        }, 400);
        return;
    }
});

document.addEventListener('change', async ev => {
    if (IS_READONLY) return;
    const t = ev.target, d = t.dataset;

    if (t.id === 'st' || t.id === 'wk' || t.id === 'sat') {
        if (t.id === 'st') S.start = t.value;
        if (t.id === 'wk') S.weeks = +t.value || 20;
        if (t.id === 'sat') S.sat = t.checked;
        await apiPost('/api/settings', {
            start_date: S.start,
            weeks: S.weeks,
            include_saturday: S.sat
        });
        R();
        return;
    }

    if (d.p && d.f === 'h') {
        const [c, tp] = d.p.split('|').map(Number);
        S.P[c][tp].h = Math.max(0, +t.value || 0);
        await apiPost('/api/progress', {
            centre_id: S.C[c].id,
            topic_id: T[tp][2],
            manual_hours: S.P[c][tp].h,
            remarks: S.P[c][tp].r
        });
        R();
        return;
    }

    if (d.as) {
        if (!t.value) return;
        const [c, tp] = d.as.split('|').map(Number);
        let id = +t.value;
        if (id === -9) {
            const n = (prompt('New lecturer name:') || '').trim();
            if (!n) return R();
            const res = await apiPost('/api/lecturers', { names: [n] });
            if (res && res.lecturers && res.lecturers[0]) {
                const newL = res.lecturers[0];
                id = newL.id;
                S.L.push({ id: newL.id, n: newL.name });
            } else {
                return R();
            }
        }
        if (!S.A[c][tp].includes(id)) {
            S.A[c][tp].push(id);
            await apiPost('/api/assignment', {
                centre_id: S.C[c].id,
                topic_id: T[tp][2],
                lecturer_id: id,
                action: 'add'
            });
        }
        R();
        return;
    }

    if (d.k && d.f && d.f !== 'u') {
        const o = S.s[d.k] || (S.s[d.k] = { t: -1, l: -1, u: '', h: false });
        o[d.f] = d.f === 'h' ? t.checked : +t.value;
        const [c, w, dd, i] = d.k.split('|').map(Number);
        if (d.f === 't' && o.l < 0) {
            const p = pool(c, o.t).filter(l => !lecBusy(w, dd, i, l, c));
            if (p.length) o.l = p[0];
        }

        const isOther = o.t === 9;
        const topicId = (o.t >= 0 && o.t < 9) ? T[o.t][2] : null;
        await apiPost('/api/timetable/slot', {
            centre_id: S.C[c].id,
            week: w,
            day: dd,
            slot_index: i,
            topic_id: topicId,
            is_other_topic: isOther,
            lecturer_id: o.l > 0 ? o.l : null,
            unit_note: o.u || '',
            is_held: !!o.h
        });
        R();
        return;
    }

    if (t.id === 'wsel') {
        S.cur.w = +t.value;
        R();
        return;
    }

    if (d.off) {
        S.off[d.off] = t.checked;
        const [w, day] = d.off.split('-').map(Number);
        await apiPost('/api/timetable/day-off', {
            week: w,
            day: day,
            is_off: t.checked
        });
        R();
        return;
    }
});

document.addEventListener('click', async ev => {
    const b = ev.target.closest('button');
    if (!b) return;
    const d = b.dataset, cc = S.cur;

    if (d.v) {
        S.view = d.v;
        R();
    } else if (d.go === 'c') {
        cc.c = +d.i;
        R();
    } else if (d.go === 'w') {
        cc.w = Math.max(0, Math.min(S.weeks - 1, +d.i));
        R();
    } else if (d.act === 'addl') {
        if (IS_READONLY) return showToast('Permission Denied', 'View-only accounts cannot add lecturers.', 'warning');
        const n = $('#bulk').value.split('\n').map(x => x.trim()).filter(Boolean);
        if (!n.length) return;
        const res = await apiPost('/api/lecturers', { names: n });
        if (res && res.lecturers) {
            res.lecturers.forEach(l => S.L.push({ id: l.id, n: l.name }));
            showToast('Lecturers Added', `${res.lecturers.length} lecturer(s) added successfully.`, 'success');
        }
        $('#bulk').value = '';
        R();
    } else if (d.rm) {
        const id = +d.rm;
        const name = ln(id);
        requestOrExecuteDeletion(
            'delete_lecturer',
            `Delete Lecturer: ${name}`,
            `Permanently removes lecturer "${name}" from the system and all assigned sessions.`,
            { lecturer_id: id }
        );
    } else if (d.un) {
        if (IS_READONLY) return;
        const [c, t, id] = d.un.split('|').map(Number);
        S.A[c][t] = S.A[c][t].filter(x => x !== id);
        await apiPost('/api/assignment', {
            centre_id: S.C[c].id,
            topic_id: T[t][2],
            lecturer_id: id,
            action: 'remove'
        });
        R();
    } else if (d.act === 'cpall') {
        if (IS_READONLY) return;
        showConfirm(
            'Copy Lecturer Assignments',
            'Are you sure you want to replace the assigned lecturers of all other centres with this centre’s list?',
            false,
            async () => {
                await apiPost('/api/assignments/copy', { source_centre_id: S.C[cc.c].id });
                S.A = S.A.map(() => S.A[cc.c].map(a => [...a]));
                R();
                showToast('Copied', 'Lecturers copied to all centres.', 'success');
            }
        );
    } else if (d.act === 'auto') {
        await auto();
    } else if (d.act === 'clrw') {
        const centreName = S.C[cc.c].n;
        const weekNum = cc.w + 1;
        requestOrExecuteDeletion(
            'clear_week',
            `Clear Timetable: Week ${weekNum} at ${centreName}`,
            `Deletes all scheduled sessions for Week ${weekNum} at ${centreName}.`,
            { centre_id: S.C[cc.c].id, week: cc.w }
        );
    } else if (d.act === 'clrc') {
        const centreName = S.C[cc.c].n;
        requestOrExecuteDeletion(
            'clear_centre',
            `Clear Entire Timetable for ${centreName}`,
            `Clears all scheduled weeks and sessions for ${centreName}.`,
            { centre_id: S.C[cc.c].id }
        );
    } else if (d.act === 'exp') {
        expX();
    } else if (d.act === 'imp') {
        $('#imp').click();
    }
});

// Periodic approval count refresh
setInterval(refreshApprovalCount, 20000);

// Initial Render
R();
</script>
</body>
</html>
