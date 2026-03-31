<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Sekolah — Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:           #f0f4f8;
            --card:         #ffffff;
            --sidebar:      #ffffff;
            --green-700:    #15803d;
            --green-600:    #16a34a;
            --green-500:    #22c55e;
            --green-100:    #dcfce7;
            --green-50:     #f0fdf4;
            --green-glow:   rgba(34,197,94,0.15);
            --text:         #0f172a;
            --text-sec:     #475569;
            --text-muted:   #94a3b8;
            --border:       #e2e8f0;
            --border-med:   #cbd5e1;
            --success:      #10b981;
            --success-bg:   #ecfdf5;
            --error:        #ef4444;
            --error-bg:     #fef2f2;
            --warning:      #f59e0b;
            --warning-bg:   #fffbeb;
            --info:         #3b82f6;
            --info-bg:      #eff6ff;
            --shadow:       0 1px 3px rgba(0,0,0,0.07);
            --shadow-md:    0 4px 12px rgba(0,0,0,0.08);
        }

        html, body { height: 100%; font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); overflow: hidden; }
        .app { display: flex; height: 100vh; overflow: hidden; }

        /* ── Sidebar ── */
        .sidebar {
            width: 256px; flex-shrink: 0; background: var(--sidebar);
            border-right: 1px solid var(--border); display: flex; flex-direction: column;
            transition: width .25s cubic-bezier(.4,0,.2,1); z-index: 100; box-shadow: var(--shadow);
        }
        .sidebar.collapsed { width: 68px; }
        .sidebar-brand {
            padding: 18px 16px; display: flex; align-items: center; gap: 12px;
            border-bottom: 1px solid var(--border); min-height: 68px; overflow: hidden;
        }
        .brand-logo {
            width: 36px; height: 36px; flex-shrink: 0;
            background: linear-gradient(135deg, var(--green-700), var(--green-500));
            border-radius: 10px; display: flex; align-items: center; justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 12px; color: white;
            box-shadow: 0 4px 12px var(--green-glow);
        }
        .brand-text { overflow: hidden; white-space: nowrap; }
        .brand-name { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; font-weight: 700; color: var(--text); }
        .brand-sub  { font-size: 10px; color: var(--green-600); margin-top: 1px; font-weight: 600; }
        .school-pill { margin: 10px 10px 0; padding: 10px 12px; background: var(--green-50); border: 1px solid var(--green-100); border-radius: 10px; overflow: hidden; }
        .school-pill-name { font-size: 12px; font-weight: 700; color: var(--green-700); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .school-pill-type { font-size: 10px; color: var(--green-600); margin-top: 1px; }
        .sidebar-nav { flex: 1; padding: 12px 8px; overflow-y: auto; }
        .nav-label-group { font-size: 10px; font-weight: 700; letter-spacing: .1em; color: var(--text-muted); text-transform: uppercase; padding: 10px 10px 5px; }
        .nav-item {
            display: flex; align-items: center; gap: 10px; padding: 9px 10px; border-radius: 8px;
            color: var(--text-sec); font-size: 13px; font-weight: 500; cursor: pointer; text-decoration: none;
            transition: all .15s; margin-bottom: 1px; position: relative; white-space: nowrap; overflow: hidden;
        }
        .nav-item:hover  { background: var(--bg); color: var(--text); }
        .nav-item.active { background: var(--green-50); color: var(--green-700); font-weight: 600; }
        .nav-item.active::before {
            content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%);
            width: 3px; height: 18px; background: var(--green-600); border-radius: 0 3px 3px 0;
        }
        .nav-icon { font-size: 15px; flex-shrink: 0; width: 20px; text-align: center; }
        .nav-text { flex: 1; overflow: hidden; }
        .nav-badge { margin-left: auto; flex-shrink: 0; background: var(--error); color: white; font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 9px; }
        .sidebar-footer { padding: 10px 8px; border-top: 1px solid var(--border); }
        .logout-btn {
            display: flex; align-items: center; gap: 10px; padding: 9px 10px; border-radius: 8px;
            color: var(--text-sec); font-size: 13px; font-weight: 500; cursor: pointer; background: none;
            border: none; width: 100%; transition: all .2s; white-space: nowrap; overflow: hidden;
        }
        .logout-btn:hover { background: var(--error-bg); color: var(--error); }

        /* ── Main ── */
        .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; min-width: 0; }
        .topbar {
            height: 64px; background: var(--card); border-bottom: 1px solid var(--border);
            display: flex; align-items: center; padding: 0 24px; gap: 14px;
            box-shadow: var(--shadow); flex-shrink: 0;
        }
        .topbar-toggle { background: none; border: none; cursor: pointer; color: var(--text-sec); font-size: 18px; padding: 7px; border-radius: 8px; transition: all .2s; }
        .topbar-toggle:hover { background: var(--bg); color: var(--text); }
        .topbar-title { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 16px; font-weight: 700; color: var(--text); }
        .topbar-spacer { flex: 1; }
        .topbar-school { display: flex; align-items: center; gap: 8px; padding: 6px 12px; border-radius: 8px; background: var(--green-50); border: 1px solid var(--green-100); }
        .topbar-school-name { font-size: 12px; font-weight: 700; color: var(--green-700); }
        .user-btn { display: flex; align-items: center; gap: 9px; padding: 6px 10px; border-radius: 9px; cursor: pointer; transition: all .2s; border: 1px solid transparent; }
        .user-btn:hover { background: var(--bg); border-color: var(--border); }
        .user-avatar { width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0; background: linear-gradient(135deg, var(--green-700), var(--green-500)); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; color: white; }
        .user-name { font-size: 13px; font-weight: 600; color: var(--text); }
        .user-role { font-size: 10px; color: var(--text-muted); }

        .content { flex: 1; overflow-y: auto; padding: 22px 24px; background: var(--bg); }

        /* ── Page Header ── */
        .page-header { margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
        .page-title { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 20px; font-weight: 800; color: var(--text); }
        .breadcrumb { font-size: 12px; color: var(--text-muted); margin-top: 2px; }

        /* ── Stat Cards ── */
        .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px; }
        .stat-card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 18px; position: relative; overflow: hidden; box-shadow: var(--shadow); transition: all .2s; cursor: default; }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
        .stat-card.green::before { background: var(--green-600); }
        .stat-card.blue::before  { background: var(--info); }
        .stat-card.amber::before { background: var(--warning); }
        .stat-card.red::before   { background: var(--error); }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .stat-head { display: flex; align-items: flex-start; justify-content: space-between; }
        .stat-label { font-size: 12px; color: var(--text-sec); font-weight: 500; }
        .stat-num { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 26px; font-weight: 800; color: var(--text); margin-top: 4px; line-height: 1; }
        .stat-ico { width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 17px; }
        .stat-ico.green { background: var(--green-50); }
        .stat-ico.blue  { background: var(--info-bg); }
        .stat-ico.amber { background: var(--warning-bg); }
        .stat-ico.red   { background: var(--error-bg); }
        .stat-foot { margin-top: 14px; padding-top: 10px; border-top: 1px solid var(--border); font-size: 11px; font-weight: 600; display: flex; justify-content: space-between; cursor: pointer; transition: opacity .2s; }
        .stat-foot:hover { opacity: .7; }
        .stat-foot.green { color: var(--green-600); }
        .stat-foot.blue  { color: var(--info); }
        .stat-foot.amber { color: var(--warning); }
        .stat-foot.red   { color: var(--error); }

        /* ── Cards ── */
        .card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: var(--shadow); margin-bottom: 18px; }
        .card-header { padding: 14px 18px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .card-title { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; font-weight: 700; color: var(--text); flex: 1; }
        .card-body  { padding: 18px; }
        .card-controls { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px; }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        thead th { background: var(--bg); padding: 10px 14px; text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--text-muted); border-bottom: 1px solid var(--border); }
        tbody tr { border-bottom: 1px solid var(--border); transition: background .12s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: var(--bg); }
        tbody td { padding: 11px 14px; color: var(--text-sec); vertical-align: middle; }
        tbody td:first-child { color: var(--text); font-weight: 600; }

        /* ── Badges ── */
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 9px; border-radius: 20px; font-size: 11px; font-weight: 700; white-space: nowrap; }
        .badge-pending  { background: var(--warning-bg); color: #92400e; }
        .badge-review   { background: var(--info-bg);    color: #1d4ed8; }
        .badge-accepted { background: var(--success-bg); color: #065f46; }
        .badge-rejected { background: var(--error-bg);   color: #991b1b; }
        .badge-green    { background: var(--green-50);   color: var(--green-700); }

        /* ── Search box ── */
        .search-box { display: flex; align-items: center; gap: 8px; background: var(--bg); border: 1px solid var(--border); border-radius: 8px; padding: 7px 12px; min-width: 200px; }
        .search-box input { border: none; background: none; outline: none; font-size: 13px; font-family: 'DM Sans', sans-serif; color: var(--text); width: 100%; }
        .search-box input::placeholder { color: var(--text-muted); }

        /* ── Buttons ── */
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 8px; border: none; font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 600; cursor: pointer; transition: all .18s; white-space: nowrap; }
        .btn-primary { background: var(--green-600); color: white; }
        .btn-primary:hover { background: var(--green-700); }
        .btn-outline { background: white; color: var(--text-sec); border: 1px solid var(--border); }
        .btn-outline:hover { background: var(--bg); }
        .btn-sm { padding: 5px 10px; font-size: 12px; }
        .btn-danger { background: var(--error-bg); color: var(--error); border: 1px solid #fecaca; }
        .btn-danger:hover { background: var(--error); color: white; }

        /* ── Act buttons ── */
        .act-btn { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 6px; border: 1px solid var(--border); background: white; cursor: pointer; font-size: 13px; transition: all .15s; text-decoration: none; color: var(--text-sec); }
        .act-btn:hover { border-color: var(--green-600); background: var(--green-50); }
        .act-btn.danger:hover { border-color: var(--error); background: var(--error-bg); }

        /* ── Form ── */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .form-group { display: flex; flex-direction: column; gap: 5px; }
        .form-group.full { grid-column: 1/-1; }
        .form-label { font-size: 11px; font-weight: 700; color: var(--text-sec); text-transform: uppercase; letter-spacing: .06em; }
        .form-input, .form-select { padding: 9px 12px; background: var(--bg); border: 1px solid var(--border); border-radius: 8px; font-family: 'DM Sans', sans-serif; font-size: 13px; color: var(--text); outline: none; transition: all .18s; }
        .form-input:focus, .form-select:focus { border-color: var(--green-600); background: white; box-shadow: 0 0 0 3px var(--green-glow); }
        .form-select { appearance: none; cursor: pointer; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; padding-right: 30px; }

        /* ── Upload zone ── */
        .upload-zone { border: 2px dashed #cbd5e1; border-radius: 10px; padding: 20px; text-align: center; cursor: pointer; transition: all .2s; background: #f8fafc; }
        .upload-zone:hover, .upload-zone.drag-over { border-color: var(--green-600); background: var(--green-50); }

        /* ── Modal ── */
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.35); backdrop-filter: blur(3px); display: none; align-items: center; justify-content: center; z-index: 1000; }
        .modal-overlay.open { display: flex; animation: fadeIn .15s ease; }
        @keyframes fadeIn { from{opacity:0} to{opacity:1} }
        .modal { background: white; border-radius: 14px; width: 560px; max-width: 95vw; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.15); animation: slideUp .2s ease; }
        @keyframes slideUp { from{transform:translateY(20px);opacity:0} to{transform:translateY(0);opacity:1} }
        .modal-head { padding: 18px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .modal-title { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 15px; font-weight: 700; }
        .modal-close { background: none; border: none; cursor: pointer; font-size: 18px; color: var(--text-muted); padding: 4px; border-radius: 6px; transition: all .15s; }
        .modal-close:hover { background: var(--bg); color: var(--text); }
        .modal-body { padding: 20px; }
        .modal-foot { padding: 14px 20px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 8px; }

        /* ── Status options ── */
        .status-options { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; margin-top: 6px; }
        .status-opt { padding: 9px 8px; border-radius: 8px; border: 2px solid var(--border); text-align: center; font-size: 12px; font-weight: 600; cursor: pointer; transition: all .15s; color: var(--text-sec); }
        .status-opt:hover { border-color: var(--green-600); }
        .status-opt.selected { border-color: var(--green-600); background: var(--green-50); color: var(--green-700); }

        /* ── Proof card ── */
        .proof-item { border: 1px solid var(--border); border-radius: 9px; padding: 11px 13px; margin-bottom: 8px; background: white; }
        .proof-item.proof-pending  { border-left: 3px solid var(--warning); }
        .proof-item.proof-verified { border-left: 3px solid var(--success); }
        .proof-item.proof-rejected { border-left: 3px solid var(--error); }

        /* ── Misc ── */
        .empty { padding: 44px 20px; text-align: center; color: var(--text-muted); font-size: 13px; }
        .empty-ico { font-size: 36px; margin-bottom: 10px; }
        .skel { background: linear-gradient(90deg, #f0f4f8 25%, #e2e8f0 50%, #f0f4f8 75%); background-size: 200% 100%; animation: shimmer 1.2s infinite; border-radius: 6px; }
        @keyframes shimmer { to { background-position: -200% 0; } }
        .profile-hero { background: linear-gradient(135deg, #0a3d20, #15803d); border-radius: 12px; padding: 24px; color: white; display: flex; align-items: center; gap: 20px; margin-bottom: 18px; position: relative; overflow: hidden; }
        .profile-hero::before { content: ''; position: absolute; inset: 0; background: radial-gradient(circle at 80% 50%, rgba(255,255,255,0.06), transparent 60%); }
        .profile-logo { width: 64px; height: 64px; border-radius: 14px; flex-shrink: 0; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; font-size: 28px; border: 2px solid rgba(255,255,255,0.2); }
        .profile-name { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 20px; font-weight: 800; margin-bottom: 4px; }
        .profile-meta { font-size: 13px; opacity: .7; display: flex; gap: 16px; flex-wrap: wrap; }
        .info-row { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; }
        .info-item { padding: 14px; background: var(--bg); border-radius: 10px; border: 1px solid var(--border); }
        .info-item-label { font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: .06em; margin-bottom: 5px; }
        .info-item-val   { font-size: 14px; font-weight: 700; color: var(--text); }

        @keyframes fadeInUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        .anim { animation: fadeInUp .35s ease forwards; opacity: 0; }
        .anim:nth-child(1){animation-delay:.04s} .anim:nth-child(2){animation-delay:.08s} .anim:nth-child(3){animation-delay:.12s} .anim:nth-child(4){animation-delay:.16s}

        @keyframes spin { to { transform: rotate(360deg); } }

        @media (max-width: 1100px) { .stat-grid { grid-template-columns: repeat(2,1fr); } .two-col, .info-row { grid-template-columns: 1fr; } }
        @media (max-width: 768px) { .sidebar { position: fixed; height: 100%; left: -256px; transition: left .25s; } .sidebar.mobile-open { left: 0; } .topbar-school { display: none; } }
    </style>
</head>
<body>
<div class="app">

<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">SIP</div>
        <div class="brand-text">
            <div class="brand-name">Portal Sekolah</div>
            <div class="brand-sub">Admin Dashboard</div>
        </div>
    </div>
    <div class="school-pill" id="sideSchoolPill">
        <div class="school-pill-name" id="sideSchoolName">Memuat...</div>
        <div class="school-pill-type" id="sideSchoolType">—</div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-label-group">Menu Utama</div>
        <a class="nav-item active" data-page="dashboard" href="#"><span class="nav-icon">📊</span><span class="nav-text">Dashboard</span></a>
        <a class="nav-item" data-page="pendaftaran" href="#"><span class="nav-icon">📋</span><span class="nav-text">Pendaftaran Siswa</span><span class="nav-badge" id="badgePending">0</span></a>
        <a class="nav-item" data-page="siswa" href="#"><span class="nav-icon">🎓</span><span class="nav-text">Data Siswa</span></a>
        <a class="nav-item" data-page="pembayaran" href="#"><span class="nav-icon">💳</span><span class="nav-text">Bukti Pembayaran</span><span class="nav-badge" id="badgePayment" style="display:none;">!</span></a>
        <a class="nav-item" data-page="gelombang" href="#"><span class="nav-icon">📅</span><span class="nav-text">Gelombang / Formulir</span></a>
        <div class="nav-label-group">Profil Sekolah</div>
        <a class="nav-item" data-page="profil" href="#"><span class="nav-icon">🏫</span><span class="nav-text">Info Sekolah</span></a>
        <a class="nav-item" data-page="guru" href="#"><span class="nav-icon">👨‍🏫</span><span class="nav-text">Data Guru</span></a>
        <a class="nav-item" data-page="fasilitas" href="#"><span class="nav-icon">🏗️</span><span class="nav-text">Fasilitas</span></a>
        <a class="nav-item" data-page="ekskul" href="#"><span class="nav-icon">⚽</span><span class="nav-text">Ekstrakulikuler</span></a>
        <a class="nav-item" data-page="prestasi" href="#"><span class="nav-icon">🏆</span><span class="nav-text">Prestasi</span></a>
    </nav>
    <div class="sidebar-footer">
        <button class="logout-btn" onclick="doLogout()"><span class="nav-icon">⏻</span><span class="nav-text">Keluar</span></button>
    </div>
</aside>

<div class="main">
    <header class="topbar">
        <button class="topbar-toggle" onclick="toggleSidebar()">☰</button>
        <div class="topbar-title" id="topbarTitle">Dashboard</div>
        <div class="topbar-spacer"></div>
        <div class="topbar-school"><span>🏫</span><span class="topbar-school-name" id="topSchoolName">Memuat...</span></div>
        <div class="user-btn">
            <div class="user-avatar" id="userAvatar">A</div>
            <div><div class="user-name" id="userName">Admin</div><div class="user-role">Admin Sekolah</div></div>
        </div>
    </header>
    <div class="content" id="mainContent">
        <div style="padding:60px;text-align:center;color:var(--text-muted);"><div style="font-size:30px;margin-bottom:10px;">⏳</div>Memuat dashboard...</div>
    </div>
</div>
</div>

<!-- ── MODAL UPDATE STATUS ── -->
<div class="modal-overlay" id="modalStatus">
    <div class="modal">
        <div class="modal-head"><div class="modal-title">Update Status Pendaftaran</div><button class="modal-close" onclick="closeModal('modalStatus')">✕</button></div>
        <div class="modal-body">
            <div style="margin-bottom:14px;"><div class="form-label" style="margin-bottom:6px;">Nama Siswa</div><div style="font-size:15px;font-weight:700;" id="modalStudentName">—</div></div>
            <div class="form-label" style="margin-bottom:8px;">Pilih Status</div>
            <div class="status-options">
                <div class="status-opt" data-val="0" onclick="pickStatus(this)">⏳ Menunggu</div>
                <div class="status-opt" data-val="1" onclick="pickStatus(this)">🔍 Verifikasi</div>
                <div class="status-opt" data-val="2" onclick="pickStatus(this)">📝 Seleksi</div>
                <div class="status-opt" data-val="3" onclick="pickStatus(this)">✅ Diterima</div>
                <div class="status-opt" data-val="4" onclick="pickStatus(this)">❌ Ditolak</div>
                <div class="status-opt" data-val="9" onclick="pickStatus(this)">🔄 Lainnya</div>
            </div>
        </div>
        <div class="modal-foot">
            <button class="btn btn-outline" onclick="closeModal('modalStatus')">Batal</button>
            <button class="btn btn-primary" onclick="saveStatus()">Simpan Status</button>
        </div>
    </div>
</div>

<!-- ── MODAL GELOMBANG ── -->
<div class="modal-overlay" id="modalGelombang">
    <div class="modal">
        <div class="modal-head"><div class="modal-title" id="modalGelombangTitle">Tambah Gelombang</div><button class="modal-close" onclick="closeModal('modalGelombang')">✕</button></div>
        <div class="modal-body">
            <div class="form-grid">
                <div class="form-group"><label class="form-label">Judul Gelombang</label><input type="text" class="form-input" id="gTitle" placeholder="Gelombang 1"></div>
                <div class="form-group"><label class="form-label">Tahun Ajaran</label><input type="text" class="form-input" id="gTA" placeholder="2025/2026"></div>
                <div class="form-group"><label class="form-label">Kuota</label><input type="number" class="form-input" id="gKuota" placeholder="100"></div>
                <div class="form-group"><label class="form-label">Biaya Pendaftaran</label><input type="number" class="form-input" id="gBiaya" placeholder="0"></div>
                <div class="form-group full"><label class="form-label">Deskripsi</label><input type="text" class="form-input" id="gDesc" placeholder="Keterangan gelombang..."></div>
                <div class="form-group"><label class="form-label">Status</label><select class="form-select" id="gStatus"><option value="1">Aktif</option><option value="0">Tidak Aktif</option></select></div>
            </div>
        </div>
        <div class="modal-foot"><button class="btn btn-outline" onclick="closeModal('modalGelombang')">Batal</button><button class="btn btn-primary" onclick="saveGelombang()">Simpan</button></div>
    </div>
</div>

<!-- ── MODAL BUKTI PEMBAYARAN (dari tabel pendaftaran) ── -->
<div class="modal-overlay" id="modalPayment">
    <div class="modal" style="width:600px;">
        <div class="modal-head">
            <div class="modal-title" id="modalPayTitle">Bukti Pembayaran</div>
            <button class="modal-close" onclick="closeModal('modalPayment')">✕</button>
        </div>
        <div class="modal-body" style="max-height:72vh;overflow-y:auto;padding:0;">

            <!-- Tab bukti yang ada -->
            <div style="padding:16px 20px 0;">
                <div style="font-size:11px;font-weight:700;color:#2563eb;text-transform:uppercase;letter-spacing:.08em;margin-bottom:10px;">📋 Bukti yang Sudah Diunggah</div>
                <div id="modalProofList">
                    <div style="text-align:center;padding:16px;color:#94a3b8;font-size:12px;">
                        <div style="width:20px;height:20px;border:2px solid #e2e8f0;border-top-color:#3b82f6;border-radius:50%;animation:spin .6s linear infinite;margin:0 auto 8px;"></div>Memuat...
                    </div>
                </div>
            </div>

            <!-- Upload / Edit area -->
            <div id="modalUploadArea" style="border-top:1px solid var(--border);padding:16px 20px;">
                <div style="font-size:11px;font-weight:700;color:#2563eb;text-transform:uppercase;letter-spacing:.08em;margin-bottom:10px;" id="modalUploadLabel">
                    📤 Upload Bukti Offline (oleh Admin)
                </div>

                <!-- Mode info banner (ditampilkan saat edit mode) -->
                <div id="modalEditBanner" style="display:none;background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:10px 12px;font-size:12px;color:#92400e;margin-bottom:12px;">
                    ✏️ <strong>Mode Ganti File</strong> — File lama akan digantikan dengan file baru yang kamu upload.
                </div>

                <div class="upload-zone" id="modalUploadZone"
                     onclick="document.getElementById('modalFileInput').click()"
                     ondragover="event.preventDefault();this.classList.add('drag-over')"
                     ondragleave="this.classList.remove('drag-over')"
                     ondrop="handleModalDrop(event)">
                    <div style="font-size:28px;margin-bottom:4px;">📎</div>
                    <div style="font-size:12px;color:#64748b;font-weight:500;">Klik atau seret file bukti pembayaran</div>
                    <div style="font-size:11px;color:#94a3b8;margin-top:2px;">JPG, PNG, PDF — Maks. 5 MB</div>
                </div>
                <input type="file" id="modalFileInput" accept=".jpg,.jpeg,.png,.pdf" style="display:none" onchange="previewModalFile(this)">

                <div id="modalFilePreview" style="display:none;margin-top:10px;">
                    <div style="display:flex;align-items:center;gap:10px;background:#f1f5f9;border-radius:9px;padding:10px 12px;">
                        <span style="font-size:22px;" id="modalPreviewIcon">📄</span>
                        <div style="flex:1;min-width:0;">
                            <div style="font-weight:600;font-size:12px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" id="modalPreviewName">—</div>
                            <div style="font-size:11px;color:#94a3b8;" id="modalPreviewSize">—</div>
                        </div>
                        <button onclick="clearModalFile()" style="background:none;border:none;cursor:pointer;color:#94a3b8;font-size:16px;padding:2px;">✕</button>
                    </div>
                </div>

                <div style="margin-top:10px;">
                    <label style="font-size:12px;font-weight:600;color:#475569;display:block;margin-bottom:4px;">Catatan</label>
                    <input type="text" id="modalUploadNotes" placeholder="Misal: Bayar tunai tgl 29 Maret 2026" class="form-input" style="font-size:12px;">
                </div>
                <div id="modalUploadMsg" style="display:none;margin-top:10px;"></div>
            </div>
        </div>
        <div class="modal-foot">
            <button class="btn btn-outline" id="btnModalCancel" onclick="cancelModalEdit()">Tutup</button>
            <button class="btn btn-primary" id="btnModalUpload" onclick="doModalUploadPayment()">📤 Upload Bukti</button>
        </div>
    </div>
</div>

<script>
// ════════════════════════════════════════════
//  CONFIG & STATE
// ════════════════════════════════════════════
const API   = '/api';
const TOKEN = localStorage.getItem('token') || '';
const HDR   = { 'Authorization': `Bearer ${TOKEN}`, 'Accept': 'application/json', 'Content-Type': 'application/json' };

let S = {
    user: null, school: null,
    registrations: [], students: [], forms: [],
    teachers: [], facilities: [], extracurriculars: [], achievements: [],
    editRegId: null, editRegStatus: null, editFormId: null,
};

// State modal pembayaran
let _modal = {
    regId: null,
    regName: null,
    proofs: [],         // list proof dari server
    editProofId: null,  // proof yang sedang di-edit (null = upload baru)
    selectedFile: null,
};

// ════════════════════════════════════════════
//  INIT
// ════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', async () => {
    if (!TOKEN) { window.location.href = '/login-sekolah'; return; }
    await loadUser();
    initNav();
    renderDashboard();
    loadPendingPaymentBadge();
});

async function loadUser() {
    try {
        const res  = await fetch(`${API}/me`, { headers: HDR });
        if (!res.ok) { window.location.href = '/login-sekolah'; return; }
        const data = await res.json();
        S.user = data.user;
        if (S.user.type !== 'school_admin' && S.user.type !== 'school_head') {
            window.location.href = '/login-sekolah'; return;
        }
        const initials = (S.user.name || 'A').split(' ').map(w => w[0]).join('').substring(0,2).toUpperCase();
        document.getElementById('userAvatar').textContent = initials;
        document.getElementById('userName').textContent   = S.user.name || 'Admin';
        if (S.user.school_id) {
            const sr = await fetch(`${API}/schools/${S.user.school_id}`, { headers: HDR });
            if (sr.ok) { const sd = await sr.json(); S.school = sd.data || sd; updateSchoolUI(); }
        }
    } catch(e) { console.error(e); }
}

function updateSchoolUI() {
    if (!S.school) return;
    document.getElementById('sideSchoolName').textContent = S.school.name || '—';
    document.getElementById('sideSchoolType').textContent = S.school.type || '—';
    document.getElementById('topSchoolName').textContent  = S.school.name || '—';
    document.title = `${S.school.name || 'Sekolah'} — Admin Portal`;
}

// ════════════════════════════════════════════
//  NAVIGATION
// ════════════════════════════════════════════
function initNav() {
    document.querySelectorAll('.nav-item[data-page]').forEach(el => {
        el.addEventListener('click', e => { e.preventDefault(); setNav(el); loadPage(el.dataset.page); });
    });
}
function setNav(el) { document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active')); el.classList.add('active'); }
function navTo(page) { const el = document.querySelector(`.nav-item[data-page="${page}"]`); if (el) { setNav(el); loadPage(page); } }
function loadPage(page) {
    const titles = { dashboard:'Dashboard', pendaftaran:'Pendaftaran Siswa', siswa:'Data Siswa', gelombang:'Gelombang & Formulir', profil:'Profil Sekolah', guru:'Data Guru', fasilitas:'Fasilitas Sekolah', ekskul:'Ekstrakulikuler', prestasi:'Prestasi', pembayaran:'Bukti Pembayaran' };
    document.getElementById('topbarTitle').textContent = titles[page] || page;
    const map = { dashboard:renderDashboard, pendaftaran:renderPendaftaran, siswa:renderSiswa, gelombang:renderGelombang, profil:renderProfil, guru:renderGuru, fasilitas:renderFasilitas, ekskul:renderEkskul, prestasi:renderPrestasi, pembayaran:renderPembayaranAdmin };
    if (map[page]) map[page]();
}
function toggleSidebar() { document.getElementById('sidebar').classList.toggle('collapsed'); }

// ════════════════════════════════════════════
//  DASHBOARD
// ════════════════════════════════════════════
async function renderDashboard() {
    const el = document.getElementById('mainContent');
    el.innerHTML = `
    <div class="page-header anim">
        <div><div class="page-title">Dashboard</div><div class="breadcrumb">Selamat datang, ${S.user?.name || 'Admin'}</div></div>
    </div>
    <div class="stat-grid">
        <div class="stat-card green anim"><div class="stat-head"><div><div class="stat-label">Total Pendaftar</div><div class="stat-num" id="dTotal">—</div></div><div class="stat-ico green">📋</div></div><div class="stat-foot green" onclick="navTo('pendaftaran')"><span>Lihat semua</span><span>→</span></div></div>
        <div class="stat-card amber anim"><div class="stat-head"><div><div class="stat-label">Menunggu Verifikasi</div><div class="stat-num" id="dPending">—</div></div><div class="stat-ico amber">⏳</div></div><div class="stat-foot amber" onclick="navTo('pendaftaran')"><span>Proses sekarang</span><span>→</span></div></div>
        <div class="stat-card blue anim"><div class="stat-head"><div><div class="stat-label">Diterima</div><div class="stat-num" id="dDiterima">—</div></div><div class="stat-ico blue">✅</div></div><div class="stat-foot blue"><span>Pendaftar diterima</span><span>→</span></div></div>
        <div class="stat-card green anim"><div class="stat-head"><div><div class="stat-label">Gelombang Aktif</div><div class="stat-num" id="dGelombang">—</div></div><div class="stat-ico green">📅</div></div><div class="stat-foot green" onclick="navTo('gelombang')"><span>Kelola gelombang</span><span>→</span></div></div>
    </div>
    <div class="two-col">
        <div class="card">
            <div class="card-header"><div class="card-title">📋 Pendaftaran Terbaru</div><button class="btn btn-sm btn-outline" onclick="navTo('pendaftaran')">Lihat Semua</button></div>
            <div class="table-wrap"><table><thead><tr><th>Nama</th><th>Asal Sekolah</th><th>Status</th><th>Aksi</th></tr></thead><tbody id="dashRegTable"><tr><td colspan="4" class="empty"><div class="skel" style="height:14px;width:80%;margin:6px auto"></div></td></tr></tbody></table></div>
        </div>
        <div class="card">
            <div class="card-header"><div class="card-title">📅 Gelombang Aktif</div><button class="btn btn-sm btn-outline" onclick="navTo('gelombang')">Lihat Semua</button></div>
            <div class="card-body" id="dashFormCards">
                <div class="skel" style="height:60px;border-radius:8px;margin-bottom:8px;"></div>
                <div class="skel" style="height:60px;border-radius:8px;"></div>
            </div>
        </div>
    </div>`;
    await loadDashboardData();
}

async function loadDashboardData() {
    if (!S.school) return;
    try {
        const rr = await fetch(`${API}/registration?school_id=${S.school.id}`, { headers: HDR });
        if (rr.ok) { const rd = await rr.json(); S.registrations = rd.data || []; }
        const fr = await fetch(`/public/registration-form/sch/${S.school.id}`);
        if (fr.ok) { const fd = await fr.json(); const raw = fd.data; S.forms = Array.isArray(raw) ? raw : (raw ? [raw] : []); }

        const total   = S.registrations.length;
        const pending = S.registrations.filter(r => r.status === '0').length;
        const terima  = S.registrations.filter(r => r.status === '3').length;
        const aktif   = S.forms.filter(f => f.status == 1).length;

        document.getElementById('dTotal').textContent     = total;
        document.getElementById('dPending').textContent   = pending;
        document.getElementById('dDiterima').textContent  = terima;
        document.getElementById('dGelombang').textContent = aktif;
        document.getElementById('badgePending').textContent = pending;

        const tbody = document.getElementById('dashRegTable');
        const recent = [...S.registrations].slice(0, 5);
        if (!recent.length) {
            tbody.innerHTML = `<tr><td colspan="4" class="empty"><div class="empty-ico">📭</div>Belum ada pendaftaran</td></tr>`;
        } else {
            tbody.innerHTML = recent.map(r => `<tr>
                <td>${esc(r.student?.nama || r.student_id)}</td>
                <td style="color:var(--text-muted)">${esc(r.school_origin || '—')}</td>
                <td>${statusBadge(r.status)}</td>
                <td><button class="act-btn" title="Update Status" onclick="openStatus(${r.id},'${esc(r.student?.nama||'')}','${r.status}')">✏️</button></td>
            </tr>`).join('');
        }

        const fWrap = document.getElementById('dashFormCards');
        const activeForms = S.forms.filter(f => f.status == 1);
        if (!activeForms.length) {
            fWrap.innerHTML = `<div class="empty"><div class="empty-ico">📭</div>Tidak ada gelombang aktif</div>`;
        } else {
            fWrap.innerHTML = activeForms.map(f => `
            <div style="padding:12px;border:1px solid var(--green-100);border-radius:10px;background:var(--green-50);margin-bottom:8px;">
                <div style="font-weight:700;font-size:13px;color:var(--green-700)">${esc(f.title||'Gelombang')} · TA ${esc(f.ta||'—')}</div>
                <div style="font-size:12px;color:var(--text-muted);margin-top:3px;">Kuota: ${f.quota||'—'} &nbsp;·&nbsp; Biaya: Rp ${num(f.registration_fee)}</div>
            </div>`).join('');
        }
    } catch(e) { console.error('dashboard data:', e); }
}

// ════════════════════════════════════════════
//  PENDAFTARAN
// ════════════════════════════════════════════
async function renderPendaftaran() {
    const el = document.getElementById('mainContent');
    el.innerHTML = `
    <div class="page-header anim">
        <div><div class="page-title">Pendaftaran Siswa</div><div class="breadcrumb">Kelola dan verifikasi pendaftaran masuk</div></div>
        <div class="card-controls">
            <div class="search-box"><span style="color:var(--text-muted)">🔍</span><input type="text" placeholder="Cari nama / asal sekolah..." oninput="filterReg(this.value)"></div>
            <select class="form-select" id="filterStatus" onchange="filterReg()" style="width:160px;">
                <option value="">Semua Status</option>
                <option value="0">Menunggu</option>
                <option value="1">Verifikasi</option>
                <option value="2">Seleksi</option>
                <option value="3">Diterima</option>
                <option value="4">Ditolak</option>
            </select>
        </div>
    </div>
    <div class="card anim">
        <div class="card-header"><div class="card-title">Daftar Pendaftar</div><span id="regCount" style="font-size:12px;color:var(--text-muted)">Memuat...</span></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>#</th><th>Nama Siswa</th><th>NIK</th><th>NISN</th><th>Asal Sekolah</th><th>Gelombang</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody id="regTableBody"><tr><td colspan="8" class="empty">Memuat data...</td></tr></tbody>
            </table>
        </div>
    </div>`;
    if (!S.registrations.length && S.school) {
        const rr = await fetch(`${API}/registration`, { headers: HDR });
        if (rr.ok) { const rd = await rr.json(); S.registrations = rd.data || []; }
    }
    renderRegTable(S.registrations);
}

function renderRegTable(data) {
    const tbody = document.getElementById('regTableBody');
    if (!tbody) return;
    document.getElementById('regCount').textContent = `${data.length} pendaftar`;
    if (!data.length) { tbody.innerHTML = `<tr><td colspan="8" class="empty"><div class="empty-ico">📭</div>Tidak ada data</td></tr>`; return; }
    tbody.innerHTML = data.map((r, i) => `<tr>
        <td style="color:var(--text-muted);font-weight:400">${i+1}</td>
        <td>${esc(r.student?.nama || '—')}</td>
        <td style="font-family:monospace;font-size:12px">${esc(r.student?.nik || '—')}</td>
        <td style="font-family:monospace;font-size:12px">${esc(r.student?.nisn || '—')}</td>
        <td>${esc(r.school_origin || '—')}</td>
        <td><span class="badge badge-green">${esc(r.reg_form?.title || 'Form #' + r.registration_form_id)}</span></td>
        <td>${statusBadge(r.status)}</td>
        <td style="display:flex;gap:4px;">
            <button class="act-btn" title="Update Status" onclick="openStatus(${r.id},'${esc(r.student?.nama||'')}','${r.status}')">✏️</button>
            <button class="act-btn" title="Detail Siswa" onclick="showStudentDetail(${r.student_id})">👁</button>
            <button class="act-btn" title="Bukti Pembayaran" onclick="openPaymentModal(${r.id},'${esc(r.student?.nama||'')}')">💳</button>
        </td>
    </tr>`).join('');
}

function filterReg(q = '') {
    const status = document.getElementById('filterStatus')?.value || '';
    const query  = (q || document.querySelector('#mainContent .search-box input')?.value || '').toLowerCase();
    const filtered = S.registrations.filter(r => {
        const mq = !query || (r.student?.nama||'').toLowerCase().includes(query) || (r.school_origin||'').toLowerCase().includes(query);
        const ms = !status || r.status === status;
        return mq && ms;
    });
    renderRegTable(filtered);
}

// ════════════════════════════════════════════
//  SISWA
// ════════════════════════════════════════════
async function renderSiswa() {
    const el = document.getElementById('mainContent');
    el.innerHTML = `
    <div class="page-header anim">
        <div><div class="page-title">Data Siswa</div><div class="breadcrumb">Daftar calon siswa yang telah mendaftar</div></div>
        <div class="search-box"><span style="color:var(--text-muted)">🔍</span><input type="text" placeholder="Cari nama / NIK / NISN..." oninput="filterSiswa(this.value)"></div>
    </div>
    <div class="card anim">
        <div class="card-header"><div class="card-title">Daftar Calon Siswa</div><span id="siswaCount" style="font-size:12px;color:var(--text-muted)">Memuat...</span></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>#</th><th>Nama</th><th>NIK</th><th>NISN</th><th>Jenis Kelamin</th><th>Agama</th><th>Asal Sekolah</th><th>No. HP</th></tr></thead>
                <tbody id="siswaBody"><tr><td colspan="8" class="empty">Memuat data...</td></tr></tbody>
            </table>
        </div>
    </div>`;
    try {
        const res = await fetch(`${API}/student`, { headers: HDR });
        if (res.ok) { const d = await res.json(); S.students = d.data || []; }
        renderSiswaTable(S.students);
    } catch(e) { console.error(e); }
}

function renderSiswaTable(data) {
    const tbody = document.getElementById('siswaBody');
    if (!tbody) return;
    document.getElementById('siswaCount').textContent = `${data.length} siswa`;
    if (!data.length) { tbody.innerHTML = `<tr><td colspan="8" class="empty"><div class="empty-ico">🎓</div>Belum ada data siswa</td></tr>`; return; }
    tbody.innerHTML = data.map((s,i) => `<tr>
        <td style="color:var(--text-muted);font-weight:400">${i+1}</td>
        <td>${esc(s.nama)}</td>
        <td style="font-family:monospace;font-size:12px">${esc(s.nik||'—')}</td>
        <td style="font-family:monospace;font-size:12px">${esc(s.nisn||'—')}</td>
        <td>${esc(s.jk||'—')}</td>
        <td>${esc(s.agama||'—')}</td>
        <td>${esc(s.sekolah_asal||'—')}</td>
        <td style="font-family:monospace;font-size:12px">${esc(s.no_hp||'—')}</td>
    </tr>`).join('');
}
function filterSiswa(q) {
    const ql = q.toLowerCase();
    renderSiswaTable(S.students.filter(s => (s.nama||'').toLowerCase().includes(ql) || (s.nik||'').includes(ql) || (s.nisn||'').includes(ql)));
}

// ════════════════════════════════════════════
//  GELOMBANG
// ════════════════════════════════════════════
async function renderGelombang() {
    const el = document.getElementById('mainContent');
    el.innerHTML = `
    <div class="page-header anim">
        <div><div class="page-title">Gelombang & Formulir</div><div class="breadcrumb">Kelola gelombang penerimaan siswa baru</div></div>
        <button class="btn btn-primary" onclick="openModalGelombang()">+ Tambah Gelombang</button>
    </div>
    <div class="card anim">
        <div class="table-wrap">
            <table>
                <thead><tr><th>Judul</th><th>TA</th><th>Kuota</th><th>Biaya</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody id="gelombangBody"><tr><td colspan="6" class="empty">Memuat...</td></tr></tbody>
            </table>
        </div>
    </div>`;
    try {
        const res = await fetch(`/public/registration-form/sch/${S.school?.id}`);
        if (res.ok) { const d = await res.json(); const raw = d.data; S.forms = Array.isArray(raw) ? raw : (raw ? [raw] : []); }
        renderGelombangTable();
    } catch(e) { console.error(e); }
}

function renderGelombangTable() {
    const tbody = document.getElementById('gelombangBody');
    if (!tbody) return;
    if (!S.forms.length) { tbody.innerHTML = `<tr><td colspan="6" class="empty"><div class="empty-ico">📅</div>Belum ada gelombang</td></tr>`; return; }
    tbody.innerHTML = S.forms.map(f => `<tr>
        <td>${esc(f.title||'—')}</td>
        <td>${esc(f.ta||'—')}</td>
        <td>${f.quota||'—'}</td>
        <td>Rp ${num(f.registration_fee)}</td>
        <td><span class="badge ${f.status==1?'badge-accepted':'badge-rejected'}">${f.status==1?'Aktif':'Nonaktif'}</span></td>
        <td><button class="act-btn" onclick="editGelombang(${f.id})">✏️</button></td>
    </tr>`).join('');
}

function openModalGelombang(id = null) {
    S.editFormId = id;
    document.getElementById('modalGelombangTitle').textContent = id ? 'Edit Gelombang' : 'Tambah Gelombang';
    if (!id) { ['gTitle','gTA','gKuota','gBiaya','gDesc'].forEach(i => document.getElementById(i).value = ''); document.getElementById('gStatus').value = '1'; }
    openModal('modalGelombang');
}

async function editGelombang(id) {
    const f = S.forms.find(x => x.id === id);
    if (!f) return;
    S.editFormId = id;
    document.getElementById('modalGelombangTitle').textContent = 'Edit Gelombang';
    document.getElementById('gTitle').value  = f.title || '';
    document.getElementById('gTA').value     = f.ta || '';
    document.getElementById('gKuota').value  = f.quota || '';
    document.getElementById('gBiaya').value  = f.registration_fee || '';
    document.getElementById('gDesc').value   = f.description || '';
    document.getElementById('gStatus').value = f.status || '1';
    openModal('modalGelombang');
}

async function saveGelombang() {
    const body = { school_id: S.school?.id, title: document.getElementById('gTitle').value, ta: document.getElementById('gTA').value, quota: parseInt(document.getElementById('gKuota').value)||null, registration_fee: parseInt(document.getElementById('gBiaya').value)||0, description: document.getElementById('gDesc').value, status: document.getElementById('gStatus').value, registration_field: [] };
    try {
        const url    = S.editFormId ? `${API}/registration-form/${S.editFormId}` : `${API}/registration-form`;
        const method = S.editFormId ? 'PUT' : 'POST';
        const res    = await fetch(url, { method, headers: HDR, body: JSON.stringify(body) });
        if (res.ok) { closeModal('modalGelombang'); renderGelombang(); }
        else { const d = await res.json(); alert(d.message || 'Gagal menyimpan'); }
    } catch(e) { alert('Koneksi error'); }
}

// ════════════════════════════════════════════
//  PROFIL SEKOLAH
// ════════════════════════════════════════════
async function renderProfil() {
    const el = document.getElementById('mainContent');
    if (!S.school) { el.innerHTML = `<div class="empty"><div class="empty-ico">🏫</div>Data sekolah tidak ditemukan</div>`; return; }
    const s = S.school;
    el.innerHTML = `
    <div class="page-header anim"><div><div class="page-title">Profil Sekolah</div></div></div>
    <div class="profile-hero anim">
        <div class="profile-logo">🏫</div>
        <div style="flex:1;position:relative;z-index:1;">
            <div class="profile-name">${esc(s.name||'—')}</div>
            <div class="profile-meta"><span>🏷️ ${esc(s.type||'—')}</span><span>📍 ${esc(s.city_name||s.city?.name||'—')}</span><span>🔢 NPSN: ${esc(s.npsn||'—')}</span><span>👑 ${esc(s.accreditation||'—')}</span></div>
        </div>
        <div style="position:relative;z-index:1;flex-shrink:0;"><span class="badge" style="background:rgba(255,255,255,0.2);color:white;font-size:12px;padding:6px 14px;">${s.school_status||'Aktif'}</span></div>
    </div>
    <div class="info-row anim" style="margin-bottom:18px;">
        <div class="info-item"><div class="info-item-label">Kepala Sekolah</div><div class="info-item-val">${esc(s.headmaster||'—')}</div></div>
        <div class="info-item"><div class="info-item-label">Total Kelas</div><div class="info-item-val">${s.class||'—'}</div></div>
        <div class="info-item"><div class="info-item-label">Total Siswa</div><div class="info-item-val">${s.student||'—'}</div></div>
        <div class="info-item"><div class="info-item-label">Kurikulum</div><div class="info-item-val">${esc(s.curriculum||'—')}</div></div>
        <div class="info-item"><div class="info-item-label">Tahun Berdiri</div><div class="info-item-val">${s.established||'—'}</div></div>
        <div class="info-item"><div class="info-item-label">Telepon</div><div class="info-item-val">${esc(s.telephone||'—')}</div></div>
    </div>
    <div class="two-col anim">
        <div class="card">
            <div class="card-header"><div class="card-title">📍 Lokasi</div></div>
            <div class="card-body">
                <div style="margin-bottom:10px"><div style="font-size:11px;color:var(--text-muted);margin-bottom:3px">Alamat</div><div style="font-size:13px;font-weight:600">${esc(s.location||'—')}</div></div>
                <div class="form-grid">
                    <div><div style="font-size:11px;color:var(--text-muted);margin-bottom:2px">Provinsi</div><div style="font-size:13px;font-weight:600">${esc(s.province_name||'—')}</div></div>
                    <div><div style="font-size:11px;color:var(--text-muted);margin-bottom:2px">Kota/Kab</div><div style="font-size:13px;font-weight:600">${esc(s.city_name||s.city?.name||'—')}</div></div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><div class="card-title">📝 Visi & Misi</div></div>
            <div class="card-body">
                <div style="margin-bottom:12px"><div style="font-size:11px;color:var(--text-muted);margin-bottom:4px">VISI</div><div style="font-size:13px;line-height:1.6">${esc(s.vision||'—')}</div></div>
                <div><div style="font-size:11px;color:var(--text-muted);margin-bottom:4px">MISI</div><div style="font-size:13px;line-height:1.6">${esc(s.mission||'—')}</div></div>
            </div>
        </div>
    </div>`;
}

// ════════════════════════════════════════════
//  GURU / FASILITAS / EKSKUL / PRESTASI
// ════════════════════════════════════════════
async function renderGuru() {
    const el = document.getElementById('mainContent');
    el.innerHTML = `
    <div class="page-header anim"><div><div class="page-title">Data Guru</div></div><div class="search-box"><span style="color:var(--text-muted)">🔍</span><input type="text" placeholder="Cari nama guru..." oninput="filterGuru(this.value)"></div></div>
    <div class="card anim">
        <div class="card-header"><div class="card-title">Daftar Guru</div><span id="guruCount" style="font-size:12px;color:var(--text-muted)">Memuat...</span></div>
        <div class="table-wrap"><table><thead><tr><th>#</th><th>Nama</th><th>NIK</th><th>NIP</th><th>Jenis Kelamin</th><th>No. HP</th></tr></thead><tbody id="guruBody"><tr><td colspan="6" class="empty">Memuat...</td></tr></tbody></table></div>
    </div>`;
    try {
        const res = await fetch(`${API}/schools/teachers/${S.school?.id}`, { headers: HDR });
        if (res.ok) { const d = await res.json(); S.teachers = d.data || []; }
        renderGuruTable(S.teachers);
    } catch(e) { console.error(e); }
}
function renderGuruTable(data) {
    const tbody = document.getElementById('guruBody');
    if (!tbody) return;
    document.getElementById('guruCount').textContent = `${data.length} guru`;
    if (!data.length) { tbody.innerHTML = `<tr><td colspan="6" class="empty"><div class="empty-ico">👨‍🏫</div>Belum ada data guru</td></tr>`; return; }
    tbody.innerHTML = data.map((g,i) => `<tr><td style="color:var(--text-muted);font-weight:400">${i+1}</td><td>${esc(g.nama||'—')}</td><td style="font-family:monospace;font-size:12px">${esc(g.nik||'—')}</td><td style="font-family:monospace;font-size:12px">${esc(g.nip||'—')}</td><td>${esc(g.jk||'—')}</td><td style="font-family:monospace;font-size:12px">${esc(g.no_hp||'—')}</td></tr>`).join('');
}
function filterGuru(q) { renderGuruTable(S.teachers.filter(g => (g.nama||'').toLowerCase().includes(q.toLowerCase()))); }

async function renderFasilitas() { await renderSimpleList('fasilitas','Fasilitas Sekolah',`${API}/schools/facility/${S.school?.id}`,['nama','type','kondisi'],['Nama','Tipe','Kondisi'],'🏗️','fasilitas'); }
async function renderEkskul()    { await renderSimpleList('ekskul','Ekstrakulikuler',`${API}/schools/extracurricular/${S.school?.id}`,['name','type','description'],['Nama','Tipe','Deskripsi'],'⚽','ekskul'); }
async function renderPrestasi()  { await renderSimpleList('prestasi','Prestasi',`${API}/schools/achievement/${S.school?.id}`,['title','level','year'],['Judul','Tingkat','Tahun'],'🏆','prestasi'); }

async function renderSimpleList(key, title, url, cols, labels, ico, cacheKey) {
    const el = document.getElementById('mainContent');
    el.innerHTML = `
    <div class="page-header anim"><div><div class="page-title">${ico} ${title}</div></div></div>
    <div class="card anim">
        <div class="card-header"><div class="card-title">${title}</div><span id="${key}Count" style="font-size:12px;color:var(--text-muted)">Memuat...</span></div>
        <div class="table-wrap"><table><thead><tr><th>#</th>${labels.map(l=>`<th>${l}</th>`).join('')}</tr></thead><tbody id="${key}Body"><tr><td colspan="${cols.length+1}" class="empty">Memuat...</td></tr></tbody></table></div>
    </div>`;
    try {
        const res = await fetch(url, { headers: HDR });
        if (res.ok) { const d = await res.json(); S[cacheKey] = d.data || []; }
        const data = S[cacheKey] || [];
        document.getElementById(`${key}Count`).textContent = `${data.length} item`;
        const tbody = document.getElementById(`${key}Body`);
        if (!data.length) { tbody.innerHTML = `<tr><td colspan="${cols.length+1}" class="empty"><div class="empty-ico">${ico}</div>Belum ada data</td></tr>`; }
        else { tbody.innerHTML = data.map((item,i) => `<tr><td style="color:var(--text-muted);font-weight:400">${i+1}</td>${cols.map(c=>`<td>${esc(item[c]||'—')}</td>`).join('')}</tr>`).join(''); }
    } catch(e) { console.error(e); }
}

// ════════════════════════════════════════════
//  STATUS MODAL
// ════════════════════════════════════════════
function openStatus(regId, nama, currentStatus) {
    S.editRegId = regId; S.editRegStatus = currentStatus;
    document.getElementById('modalStudentName').textContent = nama || '—';
    document.querySelectorAll('.status-opt').forEach(o => o.classList.toggle('selected', o.dataset.val === String(currentStatus)));
    openModal('modalStatus');
}
function pickStatus(el) { document.querySelectorAll('.status-opt').forEach(o => o.classList.remove('selected')); el.classList.add('selected'); S.editRegStatus = el.dataset.val; }
async function saveStatus() {
    if (!S.editRegId || S.editRegStatus === null) return;
    try {
        const res = await fetch(`${API}/registration/${S.editRegId}`, { method:'PUT', headers:HDR, body:JSON.stringify({ status: S.editRegStatus }) });
        if (res.ok) {
            const reg = S.registrations.find(r => r.id === S.editRegId);
            if (reg) reg.status = S.editRegStatus;
            closeModal('modalStatus');
            if (document.getElementById('regTableBody')) renderRegTable(S.registrations);
            if (document.getElementById('dashRegTable')) loadDashboardData();
        } else { const d = await res.json(); alert(d.message || 'Gagal update status'); }
    } catch(e) { alert('Koneksi error'); }
}

// ════════════════════════════════════════════
//  PEMBAYARAN — HALAMAN UTAMA ADMIN
// ════════════════════════════════════════════
async function renderPembayaranAdmin() {
    const el = document.getElementById('mainContent');
    el.innerHTML = `
    <div class="page-header anim">
        <div><div class="page-title">Bukti Pembayaran</div><div class="breadcrumb">Verifikasi bukti pembayaran & upload untuk pembayaran offline</div></div>
    </div>
    <div class="card anim">
        <div class="card-header">
            <div class="card-title">Daftar Bukti Pembayaran</div>
            <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                <select id="filterPayStatus" class="form-select" style="width:160px;" onchange="loadAllProofs()">
                    <option value="">Semua Status</option>
                    <option value="pending">⏳ Menunggu</option>
                    <option value="verified">✅ Terverifikasi</option>
                    <option value="rejected">❌ Ditolak</option>
                </select>
            </div>
        </div>
        <div id="allProofsWrap" style="padding:8px 0;">
            <div style="text-align:center;padding:32px;color:var(--text-muted);">
                <div style="width:24px;height:24px;border:2px solid #e2e8f0;border-top-color:#3b82f6;border-radius:50%;animation:spin .6s linear infinite;margin:0 auto 10px;"></div>
                Memuat data...
            </div>
        </div>
    </div>`;
    loadAllProofs();
}

async function loadAllProofs() {
    const wrap   = document.getElementById('allProofsWrap');
    const status = document.getElementById('filterPayStatus')?.value || '';
    if (!wrap) return;

    try {
        let url = `${API}/payment-proof`;
        if (status) url += `?status=${status}`;
        const res  = await fetch(url, { headers: HDR });
        const data = await res.json();
        const list = data.data || [];

        if (!list.length) {
            wrap.innerHTML = `<div style="text-align:center;padding:32px;color:var(--text-muted);font-size:13px;"><div style="font-size:32px;margin-bottom:8px;">📭</div>Belum ada bukti pembayaran.</div>`;
            return;
        }

        const stMap = {
            pending:  { bg:'#fef3c7', color:'#92400e', icon:'⏳', label:'Menunggu' },
            verified: { bg:'#ecfdf5', color:'#065f46', icon:'✅', label:'Terverifikasi' },
            rejected: { bg:'#fef2f2', color:'#dc2626', icon:'❌', label:'Ditolak' },
        };

        wrap.innerHTML = `<div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead><tr style="background:var(--bg);border-bottom:1px solid var(--border);">
                <th style="padding:10px 14px;text-align:left;font-weight:600;color:var(--text-muted);">#</th>
                <th style="padding:10px 14px;text-align:left;font-weight:600;color:var(--text-muted);">Nama Siswa</th>
                <th style="padding:10px 14px;text-align:left;font-weight:600;color:var(--text-muted);">File</th>
                <th style="padding:10px 14px;text-align:left;font-weight:600;color:var(--text-muted);">Catatan</th>
                <th style="padding:10px 14px;text-align:left;font-weight:600;color:var(--text-muted);">Diupload</th>
                <th style="padding:10px 14px;text-align:left;font-weight:600;color:var(--text-muted);">Status</th>
                <th style="padding:10px 14px;text-align:left;font-weight:600;color:var(--text-muted);">Aksi</th>
            </tr></thead>
            <tbody>
            ${list.map((p, i) => {
                const st     = stMap[p.status] || stMap.pending;
                const reg    = S.registrations.find(r => r.id === p.student_registration_id);
                const namaS  = reg?.student?.nama || `Reg #${p.student_registration_id}`;
                const tgl    = p.created_at ? new Date(p.created_at).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) : '—';
                const byLbl  = p.uploaded_by === 'admin' ? '👤 Admin' : '🎓 Siswa';
                const canEdit = p.status !== 'verified'; // admin bisa edit selama belum verified
                return `<tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:10px 14px;color:var(--text-muted);">${i+1}</td>
                    <td style="padding:10px 14px;font-weight:600;">${esc(namaS)}</td>
                    <td style="padding:10px 14px;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        <a href="${esc(p.file_url)}" target="_blank" style="color:#2563eb;text-decoration:none;font-weight:500;">📎 ${esc(p.file_name)}</a>
                    </td>
                    <td style="padding:10px 14px;color:var(--text-muted);">${esc(p.notes||'—')}</td>
                    <td style="padding:10px 14px;">${byLbl}<br><span style="font-size:11px;color:var(--text-muted);">${tgl}</span></td>
                    <td style="padding:10px 14px;">
                        <span style="background:${st.bg};color:${st.color};border-radius:20px;padding:3px 10px;font-size:11px;font-weight:700;">${st.icon} ${st.label}</span>
                    </td>
                    <td style="padding:10px 14px;">
                        <div style="display:flex;gap:4px;flex-wrap:wrap;">
                            ${p.status !== 'verified' ? `<button class="act-btn" title="Verifikasi" onclick="verifyProof(${p.id},'verified')">✅</button>` : ''}
                            ${p.status !== 'rejected' ? `<button class="act-btn" title="Tolak"      onclick="verifyProof(${p.id},'rejected')">❌</button>` : ''}
                            ${canEdit ? `<button class="act-btn" title="Ganti File" onclick="openReplaceProofPage(${p.id},${p.student_registration_id},'${esc(namaS)}')">✏️</button>` : ''}
                            <button class="act-btn danger" title="Hapus" onclick="deleteProof(${p.id})">🗑️</button>
                        </div>
                    </td>
                </tr>`;
            }).join('')}
            </tbody>
        </table></div>`;
    } catch(e) {
        wrap.innerHTML = `<div style="text-align:center;padding:20px;color:#dc2626;font-size:13px;">⚠️ Gagal memuat data pembayaran.</div>`;
    }
}

// Inline replace form di halaman pembayaran admin (bukan modal)
function openReplaceProofPage(proofId, regId, namaS) {
    // Cari baris & insert form di bawahnya — gunakan modal payment saja untuk konsistensi
    openPaymentModal(regId, namaS, proofId);
}

async function verifyProof(id, status) {
    try {
        const res = await fetch(`${API}/payment-proof/${id}`, { method:'PUT', headers:HDR, body:JSON.stringify({ status }) });
        if (!res.ok) throw new Error();
        loadAllProofs();
        loadPendingPaymentBadge();
    } catch(e) { alert('Gagal update status bukti pembayaran.'); }
}

async function deleteProof(id) {
    if (!confirm('Hapus bukti pembayaran ini?')) return;
    try {
        const res = await fetch(`${API}/payment-proof/${id}`, { method:'DELETE', headers:HDR });
        if (!res.ok) throw new Error();
        loadAllProofs();
        loadPendingPaymentBadge();
    } catch(e) { alert('Gagal menghapus bukti pembayaran.'); }
}

// ════════════════════════════════════════════
//  MODAL BUKTI PEMBAYARAN — UNIFIED
//  openPaymentModal(regId, nama)             → upload baru
//  openPaymentModal(regId, nama, proofId)    → ganti file proof tertentu
// ════════════════════════════════════════════
function openPaymentModal(regId, nama, editProofId = null) {
    _modal.regId       = regId;
    _modal.regName     = nama;
    _modal.editProofId = editProofId;
    _modal.selectedFile = null;

    // Reset file input & preview
    document.getElementById('modalFileInput').value      = '';
    document.getElementById('modalFilePreview').style.display = 'none';
    document.getElementById('modalUploadMsg').style.display   = 'none';
    document.getElementById('modalUploadNotes').value    = '';

    // Judul modal
    document.getElementById('modalPayTitle').textContent =
        editProofId ? `Ganti File Bukti — ${nama}` : `Bukti Pembayaran — ${nama}`;

    // Label & banner upload area
    const isEdit = !!editProofId;
    document.getElementById('modalUploadLabel').textContent =
        isEdit ? '✏️ Ganti File Bukti Pembayaran' : '📤 Upload Bukti Offline (oleh Admin)';
    document.getElementById('modalEditBanner').style.display  = isEdit ? '' : 'none';

    // Tombol footer
    document.getElementById('btnModalCancel').textContent  = isEdit ? 'Batal' : 'Tutup';
    document.getElementById('btnModalUpload').textContent  = isEdit ? '🔄 Ganti File' : '📤 Upload Bukti';

    // Jika mode edit langsung tampilkan upload area, jika tidak sembunyikan dulu
    // (loadProofsInModal akan otomatis tampilkan jika belum ada proof)
    _setUploadAreaVisibility(isEdit);

    // Load list proof
    loadProofsInModal(regId);
    openModal('modalPayment');
}

async function loadProofsInModal(regId) {
    const wrap = document.getElementById('modalProofList');
    wrap.innerHTML = `<div style="text-align:center;padding:16px;color:#94a3b8;font-size:12px;"><div style="width:18px;height:18px;border:2px solid #e2e8f0;border-top-color:#3b82f6;border-radius:50%;animation:spin .6s linear infinite;margin:0 auto 8px;"></div>Memuat...</div>`;
    try {
        const res  = await fetch(`${API}/payment-proof?registration_id=${regId}`, { headers: HDR });
        const data = await res.json();
        _modal.proofs = data.data || [];

        const stMap = {
            pending:  ['#fef3c7','#92400e','⏳','Menunggu'],
            verified: ['#ecfdf5','#065f46','✅','Terverifikasi'],
            rejected: ['#fef2f2','#dc2626','❌','Ditolak'],
        };

        if (!_modal.proofs.length) {
            wrap.innerHTML = `<div style="text-align:center;padding:14px;color:#94a3b8;font-size:12px;">Belum ada bukti diunggah.</div>`;
            // Tidak ada proof → tampilkan upload zone
            _setUploadAreaVisibility(true);
            return;
        }

        wrap.innerHTML = _modal.proofs.map(p => {
            const [bg, color, icon, lbl] = stMap[p.status] || stMap.pending;
            const isActive = p.id === _modal.editProofId;
            const canEdit  = p.status !== 'verified';
            return `<div class="proof-item proof-${p.status}" style="${isActive ? 'border:2px solid #f59e0b;background:#fffbeb;' : ''}">
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                    <div style="flex:1;min-width:0;">
                        <a href="${esc(p.file_url)}" target="_blank" style="font-weight:600;font-size:12px;color:#2563eb;text-decoration:none;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;display:block;">
                            📎 ${esc(p.file_name)}
                        </a>
                        ${p.notes ? `<div style="font-size:11px;color:#64748b;margin-top:2px;">📝 ${esc(p.notes)}</div>` : ''}
                        <div style="font-size:10px;color:#94a3b8;margin-top:2px;">${p.uploaded_by === 'admin' ? '👤 Admin' : '🎓 Siswa'}</div>
                    </div>
                    <span style="background:${bg};color:${color};border-radius:20px;padding:3px 10px;font-size:11px;font-weight:700;flex-shrink:0;">${icon} ${lbl}</span>
                    <div style="display:flex;gap:4px;flex-shrink:0;">
                        ${p.status !== 'verified' ? `<button class="act-btn" title="Verifikasi" onclick="verifyProofModal(${p.id},'verified',${regId})">✅</button>` : ''}
                        ${p.status !== 'rejected' ? `<button class="act-btn" title="Tolak"      onclick="verifyProofModal(${p.id},'rejected',${regId})">❌</button>` : ''}
                        ${canEdit ? `<button class="act-btn" title="Ganti File" onclick="setModalEditMode(${p.id})" style="${isActive?'border-color:#f59e0b;background:#fef3c7;':''}">✏️</button>` : ''}
                    </div>
                </div>
                ${isActive ? `<div style="margin-top:6px;font-size:11px;color:#92400e;font-weight:600;">▲ Sedang mengganti file ini</div>` : ''}
            </div>`;
        }).join('');

        // Ada proof & bukan sedang mode edit → sembunyikan upload zone
        if (!_modal.editProofId) {
            _setUploadAreaVisibility(false);
        }
    } catch(e) {
        wrap.innerHTML = `<div style="color:#dc2626;font-size:12px;padding:8px;">⚠️ Gagal memuat.</div>`;
    }
}

// Helper: tampilkan / sembunyikan area upload
function _setUploadAreaVisibility(show) {
    const area = document.getElementById('modalUploadArea');
    const btn  = document.getElementById('btnModalUpload');
    if (!area) return;
    if (show) {
        area.style.display = '';
        if (btn) btn.style.display = '';
    } else {
        area.style.display = 'none';
        if (btn) btn.style.display = 'none';
    }
}

// Set mode ganti dari dalam list proof di modal
function setModalEditMode(proofId) {
    _modal.editProofId  = proofId;
    _modal.selectedFile = null;

    document.getElementById('modalFileInput').value = '';
    document.getElementById('modalFilePreview').style.display = 'none';
    document.getElementById('modalUploadMsg').style.display   = 'none';

    document.getElementById('modalPayTitle').textContent  = `Ganti File Bukti — ${_modal.regName}`;
    document.getElementById('modalUploadLabel').textContent = '✏️ Ganti File Bukti Pembayaran';
    document.getElementById('modalEditBanner').style.display  = '';
    document.getElementById('btnModalCancel').textContent  = 'Batal';
    document.getElementById('btnModalUpload').textContent  = '🔄 Ganti File';

    // Tampilkan area upload saat mode edit
    _setUploadAreaVisibility(true);

    // Scroll ke area upload
    document.getElementById('modalUploadArea').scrollIntoView({ behavior: 'smooth', block: 'nearest' });

    // Re-render list agar highlight aktif
    loadProofsInModal(_modal.regId);
}

function cancelModalEdit() {
    if (_modal.editProofId) {
        // Kembali ke mode normal (bukan upload baru, tapi sembunyikan upload area karena proof masih ada)
        _modal.editProofId  = null;
        _modal.selectedFile = null;
        document.getElementById('modalFileInput').value = '';
        document.getElementById('modalFilePreview').style.display = 'none';
        document.getElementById('modalUploadMsg').style.display   = 'none';
        document.getElementById('modalPayTitle').textContent  = `Bukti Pembayaran — ${_modal.regName}`;
        document.getElementById('modalUploadLabel').textContent = '📤 Upload Bukti Offline (oleh Admin)';
        document.getElementById('modalEditBanner').style.display  = 'none';
        document.getElementById('btnModalCancel').textContent  = 'Tutup';
        // Sembunyikan upload area karena proof masih ada
        _setUploadAreaVisibility(false);
        loadProofsInModal(_modal.regId);
    } else {
        closeModal('modalPayment');
    }
}

async function verifyProofModal(id, status, regId) {
    try {
        const res = await fetch(`${API}/payment-proof/${id}`, { method:'PUT', headers:HDR, body:JSON.stringify({ status }) });
        if (!res.ok) throw new Error();
        loadProofsInModal(regId);
        loadPendingPaymentBadge();
    } catch(e) { alert('Gagal update status.'); }
}

function previewModalFile(input) {
    const file = input.files[0];
    if (!file) return;
    _modal.selectedFile = file;
    const isImg = file.type.startsWith('image/');
    document.getElementById('modalPreviewIcon').textContent = isImg ? '🖼️' : '📄';
    document.getElementById('modalPreviewName').textContent = file.name;
    document.getElementById('modalPreviewSize').textContent = (file.size/1024/1024).toFixed(2) + ' MB';
    document.getElementById('modalFilePreview').style.display = '';
}

function handleModalDrop(event) {
    event.preventDefault();
    document.getElementById('modalUploadZone').classList.remove('drag-over');
    const file = event.dataTransfer.files[0];
    if (!file) return;
    const dt = new DataTransfer(); dt.items.add(file);
    const input = document.getElementById('modalFileInput');
    input.files = dt.files;
    previewModalFile(input);
}

function clearModalFile() {
    _modal.selectedFile = null;
    document.getElementById('modalFileInput').value = '';
    document.getElementById('modalFilePreview').style.display = 'none';
}

async function doModalUploadPayment() {
    if (!_modal.selectedFile || !_modal.regId) { alert('Pilih file terlebih dahulu.'); return; }

    const btn = document.getElementById('btnModalUpload');
    const msg = document.getElementById('modalUploadMsg');
    btn.disabled = true;
    btn.textContent = '⏳ Mengunggah...';
    msg.style.display = 'none';

    try {
        const isEdit = !!_modal.editProofId;

        // Jika edit: hapus proof lama dulu
        if (isEdit) {
            const delRes = await fetch(`${API}/payment-proof/${_modal.editProofId}`, { method:'DELETE', headers:HDR });
            if (!delRes.ok) {
                const dd = await delRes.json().catch(()=>({}));
                throw new Error(dd.message || 'Gagal menghapus bukti lama');
            }
        }

        // Upload file baru
        const form = new FormData();
        form.append('student_registration_id', _modal.regId);
        form.append('file', _modal.selectedFile);
        const notes = document.getElementById('modalUploadNotes').value;
        if (notes) form.append('notes', notes);

        const res = await fetch(`${API}/payment-proof`, {
            method: 'POST',
            headers: { 'Authorization': `Bearer ${TOKEN}`, 'Accept': 'application/json' },
            body: form,
        });
        const d = await res.json();
        if (!res.ok) throw new Error(d.message || 'Gagal upload');

        // Sukses
        msg.innerHTML = `<div style="background:#ecfdf5;border:1px solid #a7f3d0;border-radius:8px;padding:10px 12px;font-size:12px;color:#065f46;font-weight:600;">
            ${isEdit ? '✅ File berhasil diganti.' : '✅ Bukti berhasil diunggah.'}
        </div>`;
        msg.style.display = '';

        // Reset ke mode normal
        _modal.editProofId  = null;
        _modal.selectedFile = null;
        document.getElementById('modalFileInput').value = '';
        document.getElementById('modalFilePreview').style.display = 'none';
        document.getElementById('modalUploadNotes').value = '';
        document.getElementById('modalEditBanner').style.display = 'none';
        document.getElementById('modalPayTitle').textContent  = `Bukti Pembayaran — ${_modal.regName}`;
        document.getElementById('modalUploadLabel').textContent = '📤 Upload Bukti Offline (oleh Admin)';
        document.getElementById('btnModalCancel').textContent = 'Tutup';
        btn.textContent = '📤 Upload Bukti';

        // Setelah upload sukses → sembunyikan upload area (bukti sudah ada)
        _setUploadAreaVisibility(false);

        loadProofsInModal(_modal.regId);
        loadPendingPaymentBadge();

        // Refresh halaman pembayaran jika sedang terbuka
        if (document.getElementById('allProofsWrap')) loadAllProofs();

    } catch(e) {
        msg.innerHTML = `<div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:10px 12px;font-size:12px;color:#dc2626;">⚠️ ${e.message}</div>`;
        msg.style.display = '';
    } finally {
        if (btn.disabled) { btn.disabled = false; }
    }
}

async function loadPendingPaymentBadge() {
    try {
        const res  = await fetch(`${API}/payment-proof?status=pending`, { headers: HDR });
        const data = await res.json();
        const cnt  = (data.data || []).length;
        const badge = document.getElementById('badgePayment');
        if (badge) { badge.style.display = cnt > 0 ? '' : 'none'; badge.textContent = cnt; }
    } catch(e) {}
}

// ════════════════════════════════════════════
//  HELPERS
// ════════════════════════════════════════════
function openModal(id)  { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

function statusBadge(s) {
    const map = { '0':['badge-pending','⏳ Menunggu'], '1':['badge-review','🔍 Verifikasi'], '2':['badge-review','📝 Seleksi'], '3':['badge-accepted','✅ Diterima'], '4':['badge-rejected','❌ Ditolak'], '9':['badge-pending','🔄 Lainnya'] };
    const [cls, label] = map[String(s)] || ['badge-pending','—'];
    return `<span class="badge ${cls}">${label}</span>`;
}

function num(n) { return n ? Number(n).toLocaleString('id-ID') : '0'; }
function esc(s) {
    if (s == null) return '';
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

async function doLogout() {
    try { await fetch(`${API}/logout`, { method:'POST', headers:HDR }); } catch(e) {}
    localStorage.removeItem('token');
    window.location.href = '/login-sekolah';
}

document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); });
});
</script>
</body>
</html>