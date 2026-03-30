<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP — Admin Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg:            #f0f4f8;
            --bg-sidebar:    #ffffff;
            --bg-card:       #ffffff;
            --bg-card-hover: #f8fafc;
            --bg-input:      #f0f4f8;
            --accent:        #2563eb;
            --accent-hover:  #1d4ed8;
            --accent-muted:  #eff6ff;
            --accent-glow:   rgba(37, 99, 235, 0.2);
            --text-primary:  #0f172a;
            --text-secondary:#475569;
            --text-muted:    #94a3b8;
            --border:        #e2e8f0;
            --border-light:  #cbd5e1;
            --success:       #10b981;
            --success-bg:    #ecfdf5;
            --error:         #ef4444;
            --error-bg:      #fef2f2;
            --warning:       #f59e0b;
            --warning-bg:    #fffbeb;
            --info:          #3b82f6;
            --info-bg:       #eff6ff;
            --shadow:        0 1px 3px rgba(0,0,0,0.07), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md:     0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -1px rgba(0,0,0,0.05);
            --shadow-lg:     0 10px 15px -3px rgba(0,0,0,0.07), 0 4px 6px -2px rgba(0,0,0,0.04);
        }
        html, body { height: 100%; font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text-primary); overflow: hidden; }
        .app { display: flex; height: 100vh; overflow: hidden; }

        /* ── Sidebar ── */
        .sidebar { width: 260px; flex-shrink: 0; background: var(--bg-sidebar); border-right: 1px solid var(--border); display: flex; flex-direction: column; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); z-index: 100; box-shadow: var(--shadow); }
        .sidebar.collapsed { width: 72px; }
        .sidebar-brand { padding: 20px 18px; display: flex; align-items: center; gap: 14px; border-bottom: 1px solid var(--border); min-height: 72px; }
        .brand-logo { width: 38px; height: 38px; flex-shrink: 0; background: var(--accent); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 13px; color: #fff; box-shadow: 0 4px 12px var(--accent-glow); }
        .brand-text { overflow: hidden; white-space: nowrap; }
        .brand-name { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 15px; font-weight: 700; color: var(--text-primary); }
        .brand-sub  { font-size: 11px; color: var(--text-muted); margin-top: 2px; }
        .sidebar-nav { flex: 1; padding: 16px 10px; overflow-y: auto; }
        .nav-section-label { font-size: 10px; font-weight: 700; letter-spacing: 0.12em; color: var(--text-muted); text-transform: uppercase; padding: 12px 12px 6px; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px; color: var(--text-secondary); font-size: 14px; font-weight: 500; cursor: pointer; text-decoration: none; transition: all 0.15s ease; margin-bottom: 2px; position: relative; }
        .nav-item:hover { background: var(--bg); color: var(--text-primary); }
        .nav-item.active { background: var(--accent-muted); color: var(--accent); font-weight: 600; }
        .nav-item.active::before { content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%); width: 3px; height: 20px; background: var(--accent); border-radius: 0 3px 3px 0; }
        .nav-icon { font-size: 16px; flex-shrink: 0; width: 20px; text-align: center; }
        .nav-label { overflow: hidden; flex: 1; }
        .nav-badge { margin-left: auto; background: var(--error); color: white; font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 10px; }
        .nav-item.active .nav-badge { background: var(--accent); color: white; }
        .nav-arrow { font-size: 10px; transition: transform 0.2s; color: var(--text-muted); }
        .nav-item.expanded .nav-arrow { transform: rotate(90deg); }
        .sub-menu { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; padding-left: 8px; }
        .sub-menu.open { max-height: 400px; }
        .sub-menu .nav-item { padding-left: 40px; font-size: 13px; }
        .sidebar-footer { padding: 14px 10px; border-top: 1px solid var(--border); }
        .logout-btn { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px; color: var(--text-secondary); font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s; background: none; border: none; width: 100%; }
        .logout-btn:hover { background: var(--error-bg); color: var(--error); }

        /* ── Main ── */
        .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        .topbar { height: 72px; background: var(--bg-sidebar); border-bottom: 1px solid var(--border); display: flex; align-items: center; padding: 0 28px; gap: 16px; box-shadow: var(--shadow); }
        .topbar-toggle { background: none; border: none; cursor: pointer; color: var(--text-secondary); font-size: 20px; padding: 8px; border-radius: 8px; transition: all 0.2s; }
        .topbar-toggle:hover { background: var(--bg); color: var(--text-primary); }
        .topbar-title { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 17px; font-weight: 700; color: var(--text-primary); }
        .topbar-spacer { flex: 1; }
        .user-menu { display: flex; align-items: center; gap: 10px; padding: 8px 12px; border-radius: 10px; cursor: pointer; transition: all 0.2s; border: 1px solid transparent; }
        .user-menu:hover { background: var(--bg); border-color: var(--border); }
        .user-avatar { width: 36px; height: 36px; border-radius: 10px; background: var(--accent); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; color: white; flex-shrink: 0; }
        .user-info { line-height: 1.3; }
        .user-name { font-size: 13px; font-weight: 600; color: var(--text-primary); }
        .user-role { font-size: 11px; color: var(--text-muted); }

        /* ── Content ── */
        .content { flex: 1; overflow-y: auto; padding: 24px 28px; background: var(--bg); }
        .page-header { margin-bottom: 24px; }
        .page-title  { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 22px; font-weight: 800; color: var(--text-primary); }
        .breadcrumb  { font-size: 13px; color: var(--text-muted); margin-top: 3px; }

        /* ── Stat Cards ── */
        .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
        .stat-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 20px; position: relative; overflow: hidden; animation: fadeInUp 0.5s ease forwards; opacity: 0; transition: all 0.25s ease; box-shadow: var(--shadow); }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
        .stat-card.accent::before  { background: var(--accent); }
        .stat-card.info::before    { background: var(--info); }
        .stat-card.success::before { background: var(--success); }
        .stat-card.error::before   { background: var(--error); }
        .stat-card:hover { transform: translateY(-2px); border-color: var(--border-light); box-shadow: var(--shadow-md); }
        .stat-card:nth-child(1) { animation-delay: 0.05s; }
        .stat-card:nth-child(2) { animation-delay: 0.1s; }
        .stat-card:nth-child(3) { animation-delay: 0.15s; }
        .stat-card:nth-child(4) { animation-delay: 0.2s; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        .stat-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 12px; }
        .stat-label { font-size: 13px; color: var(--text-secondary); font-weight: 500; }
        .stat-num   { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: var(--text-primary); margin-top: 4px; }
        .stat-icon  { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; }
        .stat-icon.accent  { background: var(--accent-muted); }
        .stat-icon.info    { background: var(--info-bg); }
        .stat-icon.success { background: var(--success-bg); }
        .stat-icon.error   { background: var(--error-bg); }
        .stat-footer { margin-top: 16px; padding-top: 12px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; font-size: 12px; font-weight: 600; cursor: pointer; transition: opacity 0.2s; }
        .stat-footer.accent  { color: var(--accent); }
        .stat-footer.info    { color: var(--info); }
        .stat-footer.success { color: var(--success); }
        .stat-footer.error   { color: var(--error); }
        .stat-footer:hover { opacity: 0.75; }

        /* ── Card ── */
        .card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; animation: fadeInUp 0.5s ease 0.25s forwards; opacity: 0; margin-bottom: 20px; box-shadow: var(--shadow); }
        .card-header { padding: 18px 22px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border); flex-wrap: wrap; gap: 10px; }
        .card-title  { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 15px; font-weight: 700; color: var(--text-primary); }
        .card-controls { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .search-box { display: flex; align-items: center; gap: 8px; background: var(--bg-input); border: 1px solid var(--border); border-radius: 8px; padding: 8px 12px; transition: border-color 0.2s; }
        .search-box:focus-within { border-color: var(--accent); }
        .search-box input { border: none; background: transparent; font-family: 'DM Sans', sans-serif; font-size: 13px; color: var(--text-primary); outline: none; width: 180px; }
        .search-box input::placeholder { color: var(--text-muted); }
        .card-body { padding: 0; }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead th { padding: 12px 18px; text-align: left; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.08em; background: var(--bg); border-bottom: 1px solid var(--border); white-space: nowrap; }
        tbody tr { border-bottom: 1px solid var(--border); transition: background 0.1s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #f8fafc; }
        tbody td { padding: 14px 18px; font-size: 13px; color: var(--text-secondary); }
        .td-name { font-weight: 600; color: var(--text-primary); font-size: 14px; }

        /* ── Badges ── */
        .badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge::before { content: ''; width: 5px; height: 5px; border-radius: 50%; }
        .badge.accent  { background: var(--accent-muted); color: var(--accent); }
        .badge.accent::before  { background: var(--accent); }
        .badge.success { background: var(--success-bg); color: var(--success); }
        .badge.success::before { background: var(--success); }
        .badge.warning { background: var(--warning-bg); color: var(--warning); }
        .badge.warning::before { background: var(--warning); }
        .badge.error   { background: var(--error-bg); color: var(--error); }
        .badge.error::before   { background: var(--error); }
        .badge.info    { background: var(--info-bg); color: var(--info); }
        .badge.info::before    { background: var(--info); }

        /* ── Buttons ── */
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s; border: 1px solid transparent; text-decoration: none; font-family: 'DM Sans', sans-serif; }
        .btn-primary { background: var(--accent); color: white; }
        .btn-primary:hover { background: var(--accent-hover); }
        .btn-ghost { background: transparent; color: var(--accent); border-color: var(--accent); }
        .btn-ghost:hover { background: var(--accent-muted); }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        .action-group { display: flex; gap: 6px; }
        .btn-edit   { background: transparent; color: var(--info); border: 1px solid var(--info); border-radius: 7px; padding: 5px 10px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.15s; display: inline-flex; align-items: center; gap: 4px; font-family: 'DM Sans', sans-serif; }
        .btn-edit:hover   { background: var(--info-bg); }
        .btn-delete { background: transparent; color: var(--error); border: 1px solid var(--error); border-radius: 7px; padding: 5px 10px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.15s; display: inline-flex; align-items: center; gap: 4px; font-family: 'DM Sans', sans-serif; }
        .btn-delete:hover { background: var(--error-bg); }

        /* ── Form Card ── */
        .form-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; animation: fadeInUp 0.5s ease forwards; opacity: 0; box-shadow: var(--shadow); }
        .form-header { padding: 18px 22px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .form-body { padding: 22px; }
        .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group.full { grid-column: 1 / -1; }
        .form-label { font-size: 12px; font-weight: 600; color: var(--text-secondary); }
        .form-section-label { font-size: 11px; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: .08em; margin-bottom: 12px; }
        .form-input, .form-select, .form-textarea { padding: 10px 14px; background: var(--bg-input); border: 1px solid var(--border); border-radius: 8px; font-family: 'DM Sans', sans-serif; font-size: 13px; color: var(--text-primary); outline: none; transition: all 0.2s; }
        .form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--accent); background: white; box-shadow: 0 0 0 3px rgba(37,99,235,0.08); }
        .form-input::placeholder, .form-textarea::placeholder { color: var(--text-muted); }
        .form-select { cursor: pointer; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; padding-right: 36px; }
        .form-footer { padding: 16px 22px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: flex-end; gap: 10px; }

        /* ── Filter Select ── */
        .filter-select { padding: 8px 32px 8px 12px; border: 1px solid var(--border); border-radius: 8px; background: var(--bg-input); font-family: 'DM Sans', sans-serif; font-size: 13px; color: var(--text-primary); outline: none; cursor: pointer; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; transition: border-color 0.2s; }
        .filter-select:focus { border-color: var(--accent); }

        /* ── Modal ── */
        .modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.45); backdrop-filter: blur(6px); display: flex; align-items: center; justify-content: center; z-index: 1000; opacity: 0; visibility: hidden; transition: all 0.25s ease; }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; width: 100%; max-width: 520px; max-height: 90vh; overflow: hidden; transform: scale(0.95) translateY(10px); transition: transform 0.25s ease; box-shadow: 0 20px 60px rgba(0,0,0,0.12); }
        .modal-overlay.active .modal { transform: scale(1) translateY(0); }
        .modal-header { padding: 18px 22px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .modal-title  { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 16px; font-weight: 700; color: var(--text-primary); }
        .modal-close  { background: var(--bg); border: none; color: var(--text-muted); font-size: 16px; cursor: pointer; padding: 6px; border-radius: 6px; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; transition: background 0.15s; }
        .modal-close:hover { background: var(--border); }
        .modal-body   { padding: 22px; overflow-y: auto; max-height: 65vh; }
        .modal-footer { padding: 16px 22px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: flex-end; gap: 10px; }

        /* ── Alert ── */
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; display: flex; flex-direction: column; gap: 6px; font-size: 13px; }
        .alert-error { background: var(--error-bg); border: 1px solid #fecaca; color: #b91c1c; }
        .alert-error ul { margin-left: 18px; }

        /* ── Toast ── */
        .toast-container { position: fixed; top: 20px; right: 20px; z-index: 2000; display: flex; flex-direction: column; gap: 10px; }
        .toast { background: var(--bg-card); border: 1px solid var(--border); border-radius: 10px; padding: 14px 18px; display: flex; align-items: center; gap: 10px; min-width: 280px; box-shadow: var(--shadow-lg); animation: slideIn 0.3s ease; font-size: 13px; }
        .toast.success { border-left: 4px solid var(--success); }
        .toast.error   { border-left: 4px solid var(--error); }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

        /* ── Credential Box ── */
        .credential-box { background: var(--accent-muted); border: 1px solid #bfdbfe; border-radius: 10px; padding: 18px; margin-top: 14px; }
        .credential-title { font-size: 12px; font-weight: 700; color: var(--accent); margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }
        .credential-item { display: flex; align-items: center; justify-content: space-between; padding: 9px 0; border-bottom: 1px solid #bfdbfe; }
        .credential-item:last-child { border-bottom: none; }
        .credential-label { font-size: 12px; color: var(--text-muted); }
        .credential-value { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; font-weight: 600; color: var(--text-primary); background: white; padding: 4px 10px; border-radius: 6px; }

        /* ── Empty State ── */
        .empty-state { padding: 50px 20px; text-align: center; }
        .empty-icon  { font-size: 40px; margin-bottom: 12px; opacity: 0.4; }
        .empty-title { font-size: 15px; font-weight: 600; color: var(--text-primary); margin-bottom: 6px; }

        @media (max-width: 1200px) { .stat-grid { grid-template-columns: repeat(2, 1fr); } .form-grid { grid-template-columns: 1fr; } }
        @media (max-width: 900px)  { .sidebar { position: fixed; left: -260px; top: 0; bottom: 0; transition: left 0.3s; } .sidebar.open { left: 0; } .content { padding: 16px; } }
        .hidden { display: none !important; }
    </style>
</head>
<body>

<div class="app">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo">SIP</div>
            <div class="brand-text">
                <div class="brand-name">Admin Portal</div>
                <div class="brand-sub">Sistem Informasi Pendidikan</div>
            </div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section-label">Menu Utama</div>
            <a href="#" class="nav-item active" data-page="dashboard"><span class="nav-icon">◈</span><span class="nav-label">Dashboard</span></a>
            <a href="#" class="nav-item" data-page="master-data" onclick="toggleSubMenu(this)"><span class="nav-icon">▣</span><span class="nav-label">Master Data</span><span class="nav-arrow">▸</span></a>
            <div class="sub-menu" id="sub-master-data">
                <a href="#" class="nav-item" data-page="daftar-sekolah"><span class="nav-label">Daftar Sekolah</span></a>
                <a href="#" class="nav-item" data-page="daftar-admin-sekolah"><span class="nav-label">Daftar Admin Sekolah</span></a>
            </div>
            <a href="#" class="nav-item" data-page="pendaftaran"><span class="nav-icon">◎</span><span class="nav-label">Pendaftaran</span><span class="nav-badge" id="pendingBadge">0</span></a>
            <div class="nav-section-label">Wilayah</div>
            <a href="#" class="nav-item" data-page="provinsi"><span class="nav-icon">◇</span><span class="nav-label">Provinsi</span></a>
            <a href="#" class="nav-item" data-page="kota"><span class="nav-icon">◈</span><span class="nav-label">Kota/Kabupaten</span></a>
            <a href="#" class="nav-item" data-page="kecamatan"><span class="nav-icon">◉</span><span class="nav-label">Kecamatan</span></a>
            <div class="nav-section-label">Pengaturan</div>
            <a href="#" class="nav-item" data-page="jenjang-sekolah"><span class="nav-icon">◆</span><span class="nav-label">Jenjang Sekolah</span></a>
        </nav>
        <div class="sidebar-footer">
            <button class="logout-btn" onclick="handleLogout()"><span class="nav-icon">⏻</span><span class="nav-label">Keluar</span></button>
        </div>
    </aside>

    <div class="main">
        <header class="topbar">
            <button class="topbar-toggle" onclick="toggleSidebar()">☰</button>
            <span class="topbar-title" id="topbarTitle">Dashboard</span>
            <div class="topbar-spacer"></div>
            <div class="user-menu">
                <div class="user-avatar" id="userAvatar">AS</div>
                <div class="user-info">
                    <div class="user-name" id="userName">Admin</div>
                    <div class="user-role">Super Admin</div>
                </div>
            </div>
        </header>
        <main class="content" id="mainContent"></main>
    </div>
</div>

<div class="toast-container" id="toastContainer"></div>
<div class="modal-overlay" id="modalOverlay" onclick="if(event.target === this) closeModal()">
    <div class="modal" id="modal">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Title</h3>
            <button class="modal-close" onclick="closeModal()">✕</button>
        </div>
        <div class="modal-body" id="modalBody"></div>
        <div class="modal-footer" id="modalFooter"></div>
    </div>
</div>

<script>
    const API_BASE = '/api';
    const TOKEN = localStorage.getItem('token') || '';
    const headers = { 'Authorization': `Bearer ${TOKEN}`, 'Accept': 'application/json', 'Content-Type': 'application/json' };

    let state = { currentPage: 'dashboard', provinces: [], cities: [], districts: [], schoolLevels: [], schools: [], users: [], registrations: [], schoolsForSelect: [] };

    document.addEventListener('DOMContentLoaded', () => { loadUserInfo(); initNavigation(); loadPage('dashboard'); });

    function initNavigation() {
        document.querySelectorAll('.nav-item[data-page]').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                if (!item.classList.contains('expanded')) { setActiveNav(item); loadPage(item.dataset.page); }
            });
        });
    }

    function setActiveNav(activeItem) { document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active')); activeItem.classList.add('active'); }
    function toggleSubMenu(item) { item.classList.toggle('expanded'); const subMenu = item.nextElementSibling; if (subMenu) subMenu.classList.toggle('open'); }
    function toggleSidebar() { document.getElementById('sidebar').classList.toggle('open'); }

    function loadPage(page) {
        state.currentPage = page;
        const titles = { 'dashboard': 'Dashboard', 'daftar-sekolah': 'Daftar Sekolah', 'daftar-admin-sekolah': 'Daftar Admin Sekolah', 'pendaftaran': 'Pendaftaran Siswa', 'provinsi': 'Provinsi', 'kota': 'Kota/Kabupaten', 'kecamatan': 'Kecamatan', 'jenjang-sekolah': 'Jenjang Sekolah' };
        document.getElementById('topbarTitle').textContent = titles[page] || 'Dashboard';
        const renderMap = { 'dashboard': renderDashboard, 'daftar-sekolah': renderDaftarSekolah, 'daftar-admin-sekolah': renderDaftarAdminSekolah, 'pendaftaran': renderPendaftaran, 'provinsi': renderProvinsi, 'kota': renderKota, 'kecamatan': renderKecamatan, 'jenjang-sekolah': renderJenjangSekolah };
        if (renderMap[page]) renderMap[page]();
    }

    // ================================================================
    // === DASHBOARD ===
    // ================================================================
    async function renderDashboard() {
        const el = document.getElementById('mainContent');
        el.innerHTML = `
        <div class="page-header"><h1 class="page-title">Dashboard</h1></div>
        <div class="stat-grid">
            <div class="stat-card accent">
                <div class="stat-header"><div><div class="stat-label">Total Pendaftar</div><div class="stat-num" id="statTotal">—</div></div><div class="stat-icon accent">◉</div></div>
                <div class="stat-footer accent"><span>Lihat semua</span><span>→</span></div>
            </div>
            <div class="stat-card info">
                <div class="stat-header"><div><div class="stat-label">Sekolah</div><div class="stat-num" id="statSekolah">—</div></div><div class="stat-icon info">🏫</div></div>
                <div class="stat-footer info"><span>Kelola sekolah</span><span>→</span></div>
            </div>
            <div class="stat-card success">
                <div class="stat-header"><div><div class="stat-label">Diterima</div><div class="stat-num" id="statDiterima">—</div></div><div class="stat-icon success">✓</div></div>
                <div class="stat-footer success"><span>Pendaftar diterima</span><span>→</span></div>
            </div>
            <div class="stat-card error">
                <div class="stat-header"><div><div class="stat-label">Pending</div><div class="stat-num" id="statPending">—</div></div><div class="stat-icon error">⏳</div></div>
                <div class="stat-footer error"><span>Perlu verifikasi</span><span>→</span></div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">Data Pendaftar Terbaru</div>
                <div class="card-controls">
                    <div class="search-box"><span style="color:var(--text-muted)">🔍</span><input type="text" placeholder="Cari pendaftar..." oninput="filterDashboardTable(this.value)"></div>
                </div>
            </div>
            <div class="card-body table-wrap">
                <table>
                    <thead><tr><th>Nama</th><th>Sekolah</th><th>Status</th><th>Tanggal</th></tr></thead>
                    <tbody id="tableBody"><tr><td colspan="4" style="text-align:center;padding:40px;color:var(--text-muted)">Memuat data...</td></tr></tbody>
                </table>
            </div>
        </div>`;
        await loadDashboardData();
    }

    let dashboardRegs = [];
    async function loadDashboardData() {
        try {
            const [regRes, schRes] = await Promise.all([
                fetch(`${API_BASE}/registration?per_page=100`, { headers }),
                fetch(`${API_BASE}/schools?per_page=50`, { headers })
            ]);
            const regData = await regRes.json(); const schData = await schRes.json();
            dashboardRegs = regData.data || regData || [];
            const schs = schData.data || schData || [];
            state.registrations = dashboardRegs; state.schools = schs;

            animateNumber('statTotal',    dashboardRegs.length);
            animateNumber('statSekolah',  schs.length);
            animateNumber('statDiterima', dashboardRegs.filter(r => r.status === 'diterima' || r.status === 'lulus').length);
            animateNumber('statPending',  dashboardRegs.filter(r => r.status === 'pending').length);

            const pending = dashboardRegs.filter(r => r.status === 'pending').length;
            document.getElementById('pendingBadge').textContent = pending || '0';
            renderRegistrationTable(dashboardRegs.slice(0, 15));
        } catch (e) { console.error("Dashboard load error", e); }
    }

    function filterDashboardTable(q) {
        const lower = q.toLowerCase();
        const filtered = dashboardRegs.filter(r => (r.name||'').toLowerCase().includes(lower) || (r.school?.name||'').toLowerCase().includes(lower));
        renderRegistrationTable(filtered.slice(0, 15));
    }

    function renderRegistrationTable(data) {
        const tbody = document.getElementById('tableBody');
        if (!data || !data.length) { tbody.innerHTML = '<tr><td colspan="4"><div class="empty-state"><div class="empty-icon">📭</div><div class="empty-title">Tidak ada data</div></div></td></tr>'; return; }
        tbody.innerHTML = data.map(r => `<tr>
            <td class="td-name">${escHtml(r.name)}</td>
            <td>${escHtml(r.school?.name || '—')}</td>
            <td>${renderBadge(r.status)}</td>
            <td style="font-size:12px;color:var(--text-muted)">${r.created_at ? formatDate(r.created_at) : '—'}</td>
        </tr>`).join('');
    }

    // ================================================================
    // === PROVINSI ===
    // ================================================================
    async function renderProvinsi() {
        document.getElementById('mainContent').innerHTML = `
            <div class="page-header"><h1 class="page-title">Provinsi</h1></div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Daftar Provinsi</div>
                    <button class="btn btn-primary" onclick="showAddProvinsiModal()">+ Tambah</button>
                </div>
                <div class="card-body table-wrap">
                    <table>
                        <thead><tr><th>Nama</th><th>Kode</th><th style="width:140px">Aksi</th></tr></thead>
                        <tbody id="provTableBody"><tr><td colspan="3" style="text-align:center;padding:32px;color:var(--text-muted)">Memuat...</td></tr></tbody>
                    </table>
                </div>
            </div>`;
        await loadProvinsiData();
    }

    async function loadProvinsiData() {
        try {
            const res = await fetch(`${API_BASE}/provinces`, { headers });
            const data = await res.json();
            const items = (data.data && data.data.data) || data.data || data || [];
            state.provinces = items;
            const tbody = document.getElementById('provTableBody');
            if (!tbody) return;
            if (!items.length) { tbody.innerHTML = '<tr><td colspan="3" style="text-align:center;padding:32px;color:var(--text-muted)">Tidak ada data</td></tr>'; return; }
            tbody.innerHTML = items.map(p => {
                const dn = p.prov_name || p.name || '—';
                const dc = p.prov_code || '—';
                const id = p.prov_id || p.id || '';
                return `<tr>
                    <td class="td-name">${escHtml(dn)}</td>
                    <td>${escHtml(dc)}</td>
                    <td><div class="action-group">
                        <button class="btn-edit"   onclick="showEditProvinsiModal(${id}, '${escAttr(dn)}', '${escAttr(dc !== '—' ? dc : '')}')">✎ Edit</button>
                        <button class="btn-delete" onclick="deleteProvinsi(${id})">✕ Hapus</button>
                    </div></td>
                </tr>`;
            }).join('');
        } catch(e) {
            const tbody = document.getElementById('provTableBody');
            if (tbody) tbody.innerHTML = '<tr><td colspan="3" style="text-align:center;padding:32px;color:var(--error)">Gagal memuat data</td></tr>';
        }
    }

    function showAddProvinsiModal() {
        openModal('Tambah Provinsi',
            `<div id="modalErrorBox" class="alert alert-error hidden"></div>
            <div class="form-group"><label class="form-label">Nama Provinsi *</label><input type="text" class="form-input" id="inputProvName" placeholder="Nama lengkap"></div>
            <div class="form-group" style="margin-top:14px;"><label class="form-label">Kode Provinsi</label><input type="text" class="form-input" id="inputProvCode" placeholder="Kode (opsional)"></div>`,
            `<button class="btn btn-ghost" onclick="closeModal()">Batal</button><button class="btn btn-primary" onclick="saveProvinsi()">Simpan</button>`
        );
    }

    function showEditProvinsiModal(id, name, code) {
        openModal('Edit Provinsi',
            `<div id="modalErrorBox" class="alert alert-error hidden"></div>
            <div class="form-group"><label class="form-label">Nama Provinsi *</label><input type="text" class="form-input" id="inputProvName" value="${escAttr(name)}"></div>
            <div class="form-group" style="margin-top:14px;"><label class="form-label">Kode Provinsi</label><input type="text" class="form-input" id="inputProvCode" value="${escAttr(code)}"></div>`,
            `<button class="btn btn-ghost" onclick="closeModal()">Batal</button><button class="btn btn-primary" onclick="updateProvinsi(${id})">Simpan Perubahan</button>`
        );
    }

    async function saveProvinsi() {
        const name = document.getElementById('inputProvName').value.trim();
        const code = document.getElementById('inputProvCode').value.trim();
        const eb   = document.getElementById('modalErrorBox'); eb.classList.add('hidden');
        if (!name) { eb.innerHTML = 'Nama provinsi wajib diisi'; eb.classList.remove('hidden'); return; }
        try {
            const res = await fetch(`${API_BASE}/provinces`, { method: 'POST', headers, body: JSON.stringify({ name, prov_name: name, prov_code: code || null }) });
            if (res.ok) { showToast("Berhasil menambah provinsi", "success"); closeModal(); loadProvinsiData(); }
            else { showError(eb, await res.json()); }
        } catch(e) { eb.innerHTML = 'Koneksi error'; eb.classList.remove('hidden'); }
    }

    async function updateProvinsi(id) {
        const name = document.getElementById('inputProvName').value.trim();
        const code = document.getElementById('inputProvCode').value.trim();
        const eb   = document.getElementById('modalErrorBox'); eb.classList.add('hidden');
        if (!name) { eb.innerHTML = 'Nama provinsi wajib diisi'; eb.classList.remove('hidden'); return; }
        try {
            const res = await fetch(`${API_BASE}/provinces/${id}`, { method: 'PUT', headers, body: JSON.stringify({ name, prov_name: name, prov_code: code || null }) });
            if (res.ok) { showToast("Provinsi berhasil diperbarui", "success"); closeModal(); loadProvinsiData(); }
            else { showError(eb, await res.json()); }
        } catch(e) { eb.innerHTML = 'Koneksi error'; eb.classList.remove('hidden'); }
    }

    // ================================================================
    // === KOTA ===
    // ================================================================
    async function renderKota() {
        await loadMasterData();
        document.getElementById('mainContent').innerHTML = `
            <div class="page-header"><h1 class="page-title">Kota/Kabupaten</h1></div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Daftar Kota</div>
                    <button class="btn btn-primary" onclick="showKotaModal()">+ Tambah</button>
                </div>
                <div class="card-body table-wrap">
                    <table>
                        <thead><tr><th>Nama</th><th>Provinsi</th><th>Kode</th><th style="width:140px">Aksi</th></tr></thead>
                        <tbody id="kotaTableBody"><tr><td colspan="4" style="text-align:center;padding:32px;color:var(--text-muted)">Memuat...</td></tr></tbody>
                    </table>
                </div>
            </div>`;
        await loadKotaData();
    }

    async function loadKotaData() {
        try {
            const res = await fetch(`${API_BASE}/cities`, { headers });
            const data = await res.json();
            const items = data.data || data || [];
            const tbody = document.getElementById('kotaTableBody');
            if (!tbody) return;
            if (!items.length) { tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;padding:32px;color:var(--text-muted)">Tidak ada data</td></tr>'; return; }
            tbody.innerHTML = items.map(c => {
                const cid   = c.id || c.city_id || '';
                const cname = c.name || c.city_name || '—';
                const pname = c.province?.name || c.province?.prov_name || '—';
                const pid   = c.province?.id || c.province?.prov_id || c.province_id || '';
                const code  = c.city_code || '';
                return `<tr>
                    <td class="td-name">${escHtml(cname)}</td>
                    <td>${escHtml(pname)}</td>
                    <td>${escHtml(code || '—')}</td>
                    <td><div class="action-group">
                        <button class="btn-edit"   onclick="showEditKotaModal(${cid}, '${escAttr(cname)}', ${pid}, '${escAttr(code)}')">✎ Edit</button>
                        <button class="btn-delete" onclick="deleteKota(${cid})">✕ Hapus</button>
                    </div></td>
                </tr>`;
            }).join('');
        } catch(e) { console.error(e); }
    }

    async function showKotaModal() {
        if (!state.provinces.length) await loadMasterData();
        openModal('Tambah Kota',
            `<div id="modalErrorBox" class="alert alert-error hidden"></div>
            <div class="form-group"><label class="form-label">Provinsi *</label><select class="form-select" id="inputKotaProv"><option value="">-- Pilih Provinsi --</option>${buildProvOptions()}</select></div>
            <div class="form-group" style="margin-top:14px;"><label class="form-label">Nama Kota *</label><input type="text" class="form-input" id="inputKotaName" placeholder="Nama Kota"></div>
            <div class="form-group" style="margin-top:14px;"><label class="form-label">Kode Kota</label><input type="text" class="form-input" id="inputKotaCode" placeholder="Kode (opsional)"></div>`,
            `<button class="btn btn-ghost" onclick="closeModal()">Batal</button><button class="btn btn-primary" onclick="saveKota()">Simpan</button>`
        );
    }

    async function showEditKotaModal(id, name, provId, code) {
        if (!state.provinces.length) await loadMasterData();
        openModal('Edit Kota',
            `<div id="modalErrorBox" class="alert alert-error hidden"></div>
            <div class="form-group"><label class="form-label">Provinsi *</label><select class="form-select" id="inputKotaProv"><option value="">-- Pilih Provinsi --</option>${buildProvOptions(provId)}</select></div>
            <div class="form-group" style="margin-top:14px;"><label class="form-label">Nama Kota *</label><input type="text" class="form-input" id="inputKotaName" value="${escAttr(name)}"></div>
            <div class="form-group" style="margin-top:14px;"><label class="form-label">Kode Kota</label><input type="text" class="form-input" id="inputKotaCode" value="${escAttr(code)}"></div>`,
            `<button class="btn btn-ghost" onclick="closeModal()">Batal</button><button class="btn btn-primary" onclick="updateKota(${id})">Simpan Perubahan</button>`
        );
    }

    function buildProvOptions(selectedId) {
        return state.provinces.map(p => {
            const pid = p.id ?? p.prov_id ?? '';
            const pname = p.name || p.prov_name || '—';
            return `<option value="${pid}" ${selectedId && String(pid) === String(selectedId) ? 'selected' : ''}>${escHtml(pname)}</option>`;
        }).join('');
    }

    async function saveKota() {
        const sel = document.getElementById('inputKotaProv');
        const provId = sel ? sel.value : '';
        const name   = document.getElementById('inputKotaName').value.trim();
        const code   = document.getElementById('inputKotaCode').value.trim();
        const eb     = document.getElementById('modalErrorBox'); eb.classList.add('hidden');
        if (!provId) { eb.innerHTML = 'Provinsi wajib dipilih'; eb.classList.remove('hidden'); return; }
        if (!name)   { eb.innerHTML = 'Nama kota wajib diisi';  eb.classList.remove('hidden'); return; }
        try {
            const res = await fetch(`${API_BASE}/cities`, { method: 'POST', headers, body: JSON.stringify({ province_id: parseInt(provId,10), name, city_code: code || null }) });
            if (res.ok) { showToast("Kota berhasil ditambah", "success"); closeModal(); loadKotaData(); }
            else { showError(eb, await res.json()); }
        } catch(e) { eb.innerHTML = 'Koneksi error'; eb.classList.remove('hidden'); }
    }

    async function updateKota(id) {
        const provId = document.getElementById('inputKotaProv').value;
        const name   = document.getElementById('inputKotaName').value.trim();
        const code   = document.getElementById('inputKotaCode').value.trim();
        const eb     = document.getElementById('modalErrorBox'); eb.classList.add('hidden');
        if (!provId) { eb.innerHTML = 'Provinsi wajib dipilih'; eb.classList.remove('hidden'); return; }
        if (!name)   { eb.innerHTML = 'Nama kota wajib diisi';  eb.classList.remove('hidden'); return; }
        try {
            const res = await fetch(`${API_BASE}/cities/${id}`, { method: 'PUT', headers, body: JSON.stringify({ province_id: parseInt(provId,10), name, city_code: code || null }) });
            if (res.ok) { showToast("Kota berhasil diperbarui", "success"); closeModal(); loadKotaData(); }
            else { showError(eb, await res.json()); }
        } catch(e) { eb.innerHTML = 'Koneksi error'; eb.classList.remove('hidden'); }
    }

    // ================================================================
    // === KECAMATAN ===
    // ================================================================
    async function renderKecamatan() {
        await loadMasterData(); await loadCitiesData();
        document.getElementById('mainContent').innerHTML = `
            <div class="page-header"><h1 class="page-title">Kecamatan</h1></div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Daftar Kecamatan</div>
                    <button class="btn btn-primary" onclick="showKecamatanModal()">+ Tambah</button>
                </div>
                <div class="card-body table-wrap">
                    <table>
                        <thead><tr><th>Nama</th><th>Kota</th><th>Kode</th><th style="width:140px">Aksi</th></tr></thead>
                        <tbody id="kecTableBody"><tr><td colspan="4" style="text-align:center;padding:32px;color:var(--text-muted)">Memuat...</td></tr></tbody>
                    </table>
                </div>
            </div>`;
        await loadKecamatanData();
    }

    async function loadKecamatanData() {
        try {
            const res = await fetch(`${API_BASE}/districts`, { headers });
            const data = await res.json();
            const items = data.data || data || [];
            const tbody = document.getElementById('kecTableBody');
            if (!tbody) return;
            if (!items.length) { tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;padding:32px;color:var(--text-muted)">Tidak ada data</td></tr>'; return; }
            tbody.innerHTML = items.map(d => {
                const cname = d.city?.name || d.city?.city_name || '—';
                const cid   = d.city?.id || d.city?.city_id || d.city_id || '';
                const kcode = d.district_code || '';
                return `<tr>
                    <td class="td-name">${escHtml(d.name)}</td>
                    <td>${escHtml(cname)}</td>
                    <td>${escHtml(kcode || '—')}</td>
                    <td><div class="action-group">
                        <button class="btn-edit"   onclick="showEditKecamatanModal(${d.id}, '${escAttr(d.name)}', ${cid}, '${escAttr(String(kcode))}')">✎ Edit</button>
                        <button class="btn-delete" onclick="deleteKecamatan(${d.id})">✕ Hapus</button>
                    </div></td>
                </tr>`;
            }).join('');
        } catch(e) { console.error(e); }
    }

    function buildCityOptions(selectedId) {
        return state.cities.map(c => {
            const id = c.id || c.city_id || '';
            const name = c.name || c.city_name || '—';
            return `<option value="${id}" ${selectedId && String(id) === String(selectedId) ? 'selected' : ''}>${escHtml(name)}</option>`;
        }).join('');
    }

    function showKecamatanModal() {
        openModal('Tambah Kecamatan',
            `<div id="modalErrorBox" class="alert alert-error hidden"></div>
            <div class="form-group"><label class="form-label">Kota/Kabupaten *</label><select class="form-select" id="inputKecKota"><option value="">-- Pilih Kota --</option>${buildCityOptions()}</select></div>
            <div class="form-group" style="margin-top:14px;"><label class="form-label">Nama Kecamatan *</label><input type="text" class="form-input" id="inputKecName" placeholder="Nama Kecamatan"></div>
            <div class="form-group" style="margin-top:14px;"><label class="form-label">Kode Kecamatan</label><input type="text" class="form-input" id="inputKecCode" placeholder="Kode (opsional)"></div>`,
            `<button class="btn btn-ghost" onclick="closeModal()">Batal</button><button class="btn btn-primary" onclick="saveKecamatan()">Simpan</button>`
        );
    }

    function showEditKecamatanModal(id, name, cityId, code) {
        openModal('Edit Kecamatan',
            `<div id="modalErrorBox" class="alert alert-error hidden"></div>
            <div class="form-group"><label class="form-label">Kota/Kabupaten *</label><select class="form-select" id="inputKecKota"><option value="">-- Pilih Kota --</option>${buildCityOptions(cityId)}</select></div>
            <div class="form-group" style="margin-top:14px;"><label class="form-label">Nama Kecamatan *</label><input type="text" class="form-input" id="inputKecName" value="${escAttr(name)}"></div>
            <div class="form-group" style="margin-top:14px;"><label class="form-label">Kode Kecamatan</label><input type="text" class="form-input" id="inputKecCode" value="${escAttr(code)}"></div>`,
            `<button class="btn btn-ghost" onclick="closeModal()">Batal</button><button class="btn btn-primary" onclick="updateKecamatan(${id})">Simpan Perubahan</button>`
        );
    }

    async function saveKecamatan() {
        const cityId = document.getElementById('inputKecKota').value;
        const name   = document.getElementById('inputKecName').value.trim();
        const code   = document.getElementById('inputKecCode').value.trim();
        const eb     = document.getElementById('modalErrorBox'); eb.classList.add('hidden');
        if (!cityId) { eb.innerHTML = 'Kota wajib dipilih';          eb.classList.remove('hidden'); return; }
        if (!name)   { eb.innerHTML = 'Nama kecamatan wajib diisi';  eb.classList.remove('hidden'); return; }
        try {
            const res = await fetch(`${API_BASE}/districts`, { method: 'POST', headers, body: JSON.stringify({ city_id: parseInt(cityId,10), name, district_code: code ? parseInt(code,10) : null }) });
            if (res.ok) { showToast("Kecamatan berhasil ditambah", "success"); closeModal(); loadKecamatanData(); }
            else { showError(eb, await res.json()); }
        } catch(e) { eb.innerHTML = 'Koneksi error'; eb.classList.remove('hidden'); }
    }

    async function updateKecamatan(id) {
        const cityId = document.getElementById('inputKecKota').value;
        const name   = document.getElementById('inputKecName').value.trim();
        const code   = document.getElementById('inputKecCode').value.trim();
        const eb     = document.getElementById('modalErrorBox'); eb.classList.add('hidden');
        if (!cityId) { eb.innerHTML = 'Kota wajib dipilih';          eb.classList.remove('hidden'); return; }
        if (!name)   { eb.innerHTML = 'Nama kecamatan wajib diisi';  eb.classList.remove('hidden'); return; }
        try {
            const res = await fetch(`${API_BASE}/districts/${id}`, { method: 'PUT', headers, body: JSON.stringify({ city_id: parseInt(cityId,10), name, district_code: code ? parseInt(code,10) : null }) });
            if (res.ok) { showToast("Kecamatan berhasil diperbarui", "success"); closeModal(); loadKecamatanData(); }
            else { showError(eb, await res.json()); }
        } catch(e) { eb.innerHTML = 'Koneksi error'; eb.classList.remove('hidden'); }
    }

    // ================================================================
    // === SEKOLAH ===
    // ================================================================
    async function renderDaftarSekolah() {
        await loadMasterData();
        document.getElementById('mainContent').innerHTML = `
            <div class="page-header"><h1 class="page-title">Daftar Sekolah</h1></div>
            <div class="form-card" style="margin-bottom:20px;">
                <div class="form-header">
                    <h3 class="card-title">Tambah Sekolah Baru</h3>
                    <button class="btn btn-ghost btn-sm" onclick="toggleSchoolForm()" id="btnToggleForm">▲ Sembunyikan</button>
                </div>
                <div id="schoolFormWrap">
                <div class="form-body">
                    <div id="formErrorBox" class="alert alert-error hidden"></div>
                    <div class="form-section-label">Informasi Dasar</div>
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Nama Sekolah *</label><input type="text" class="form-input" id="schoolName" placeholder="Nama lengkap sekolah"></div>
                        <div class="form-group"><label class="form-label">NPSN *</label><input type="text" class="form-input" id="schoolNpsn" placeholder="Nomor Pokok Sekolah Nasional"></div>
                        <div class="form-group"><label class="form-label">Jenjang *</label>
                            <select class="form-select" id="schoolLevel"><option value="">-- Pilih Jenjang --</option>${state.schoolLevels.map(l => `<option value="${l.id}">${escHtml(l.name)}</option>`).join('')}</select>
                        </div>
                        <div class="form-group"><label class="form-label">Tipe Sekolah *</label>
                            <select class="form-select" id="schoolType"><option value="">-- Pilih Tipe --</option><option value="Negeri">Negeri</option><option value="Swasta">Swasta</option></select>
                        </div>
                        <div class="form-group"><label class="form-label">Akreditasi</label>
                            <select class="form-select" id="schoolAccreditation"><option value="">-- Pilih --</option><option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="TT">TT</option></select>
                        </div>
                        <div class="form-group"><label class="form-label">Tahun Berdiri</label><input type="text" class="form-input" id="schoolEstablished" placeholder="Contoh: 1985"></div>
                        <div class="form-group"><label class="form-label">Kepala Sekolah</label><input type="text" class="form-input" id="schoolHeadmaster" placeholder="Nama kepala sekolah"></div>
                        <div class="form-group"><label class="form-label">Jumlah Kelas</label><input type="text" class="form-input" id="schoolClass" placeholder="Contoh: 18"></div>
                        <div class="form-group"><label class="form-label">Kurikulum</label><input type="text" class="form-input" id="schoolCurriculum" placeholder="Contoh: Kurikulum Merdeka"></div>
                        <div class="form-group"><label class="form-label">Jumlah Siswa</label><input type="text" class="form-input" id="schoolStudent" placeholder="Contoh: 500"></div>
                    </div>
                    <div class="form-section-label" style="margin-top:20px;">Lokasi</div>
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Provinsi *</label>
                            <select class="form-select" id="schoolProv" onchange="loadCitiesByProvince(this.value)"><option value="">-- Pilih Provinsi --</option>${state.provinces.map(p => `<option value="${p.id}">${escHtml(p.name || p.prov_name)}</option>`).join('')}</select>
                        </div>
                        <div class="form-group"><label class="form-label">Kota *</label><select class="form-select" id="schoolCity"><option value="">-- Pilih Kota --</option></select></div>
                        <div class="form-group full"><label class="form-label">Alamat Lengkap</label><input type="text" class="form-input" id="schoolLocation" placeholder="Alamat lengkap sekolah"></div>
                        <div class="form-group"><label class="form-label">Latitude</label><input type="text" class="form-input" id="schoolLat" placeholder="-5.4294"></div>
                        <div class="form-group"><label class="form-label">Longitude</label><input type="text" class="form-input" id="schoolLng" placeholder="105.2614"></div>
                        <div class="form-group full"><label class="form-label">Link Google Maps</label><input type="text" class="form-input" id="schoolLinkLocation" placeholder="https://maps.google.com/..."></div>
                    </div>
                    <div class="form-section-label" style="margin-top:20px;">Kontak & Media Sosial</div>
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Telepon</label><input type="text" class="form-input" id="schoolTelephone" placeholder="Nomor telepon"></div>
                        <div class="form-group"><label class="form-label">Website</label><input type="text" class="form-input" id="schoolWeb" placeholder="https://sekolah.sch.id"></div>
                        <div class="form-group"><label class="form-label">Instagram</label><input type="text" class="form-input" id="schoolInstagram" placeholder="@username"></div>
                        <div class="form-group"><label class="form-label">Facebook</label><input type="text" class="form-input" id="schoolFacebook" placeholder="URL atau nama halaman"></div>
                        <div class="form-group"><label class="form-label">TikTok</label><input type="text" class="form-input" id="schoolTiktok" placeholder="@username"></div>
                        <div class="form-group"><label class="form-label">Twitter / X</label><input type="text" class="form-input" id="schoolTwitter" placeholder="@username"></div>
                        <div class="form-group"><label class="form-label">LinkedIn</label><input type="text" class="form-input" id="schoolLinkedin" placeholder="URL LinkedIn"></div>
                    </div>
                    <div class="form-section-label" style="margin-top:20px;">Profil Sekolah</div>
                    <div class="form-grid">
                        <div class="form-group full"><label class="form-label">Motto</label><input type="text" class="form-input" id="schoolMotto" placeholder="Motto sekolah"></div>
                        <div class="form-group full"><label class="form-label">Visi</label><textarea class="form-textarea" id="schoolVision" rows="3" placeholder="Visi sekolah"></textarea></div>
                        <div class="form-group full"><label class="form-label">Misi</label><textarea class="form-textarea" id="schoolMission" rows="3" placeholder="Misi sekolah"></textarea></div>
                    </div>
                </div>
                <div class="form-footer">
                    <button class="btn btn-ghost" onclick="resetSchoolForm()">Reset</button>
                    <button class="btn btn-primary" onclick="submitSchool()">Simpan Sekolah</button>
                </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Data Sekolah</div>
                    <div class="search-box"><span style="color:var(--text-muted)">🔍</span><input type="text" id="searchSekolah" placeholder="Cari nama sekolah..." oninput="filterSekolah(this.value)"></div>
                </div>
                <div class="card-body table-wrap">
                    <table>
                        <thead><tr><th>Nama</th><th>NPSN</th><th>Tipe</th><th>Akreditasi</th><th>Kota</th><th>Admin</th><th style="width:140px">Aksi</th></tr></thead>
                        <tbody id="sekolahTableBody"><tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted)">Memuat...</td></tr></tbody>
                    </table>
                </div>
            </div>`;
        await loadSekolahData();
    }

    function toggleSchoolForm() {
        const wrap = document.getElementById('schoolFormWrap');
        const btn  = document.getElementById('btnToggleForm');
        const h    = wrap.style.display === 'none';
        wrap.style.display = h ? '' : 'none';
        btn.textContent    = h ? '▲ Sembunyikan' : '▼ Tampilkan Form';
    }

    function resetSchoolForm() {
        ['schoolName','schoolNpsn','schoolLevel','schoolType','schoolAccreditation','schoolEstablished','schoolHeadmaster','schoolClass','schoolCurriculum','schoolStudent','schoolProv','schoolCity','schoolLocation','schoolLat','schoolLng','schoolLinkLocation','schoolTelephone','schoolWeb','schoolInstagram','schoolFacebook','schoolTiktok','schoolTwitter','schoolLinkedin','schoolMotto','schoolVision','schoolMission'].forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
        document.getElementById('formErrorBox').classList.add('hidden');
    }

    let allSchools = [];
    function filterSekolah(q) {
        const lower = q.toLowerCase();
        renderSekolahRows(allSchools.filter(s => (s.name||'').toLowerCase().includes(lower) || (s.npsn||'').toLowerCase().includes(lower)));
    }

    async function loadSekolahData() {
        try {
            const res = await fetch(`${API_BASE}/schools?per_page=100`, { headers });
            const data = await res.json();
            allSchools = data.data || data || [];
            state.schools = allSchools;
            renderSekolahRows(allSchools);
        } catch(e) { console.error(e); }
    }

        function renderSekolahRows(list) {
        const tbody = document.getElementById('sekolahTableBody');
        if (!tbody) return;
        if (!list.length) { tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted)">Tidak ada data</td></tr>'; return; }
        tbody.innerHTML = list.map(s => {
            const cityName  = s.city?.name || s.city?.city_name || '—';
            const accBadge  = s.accreditation ? `<span class="badge ${s.accreditation==='A'?'success':s.accreditation==='B'?'info':'warning'}">${escHtml(s.accreditation)}</span>` : '—';
            const typeBadge = s.type ? `<span class="badge ${s.type==='Negeri'?'info':'warning'}">${escHtml(s.type)}</span>` : '—';
            
            // UBAH DISINI: Tombol selalu tampil, tidak ada cek s.user
            return `<tr>
                <td class="td-name">${escHtml(s.name)}</td>
                <td style="font-size:12px;color:var(--text-muted)">${escHtml(s.npsn||'—')}</td>
                <td>${typeBadge}</td>
                <td>${accBadge}</td>
                <td>${escHtml(cityName)}</td>
                <td>
                    <button class="btn btn-ghost btn-sm" onclick="generateAdmin(${s.id})">Buat Akun</button>
                    ${s.user ? '<span class="badge success" style="margin-left:5px;">Aktif</span>' : ''}
                </td>
                <td><div class="action-group">
                    <button class="btn-edit"   onclick="showEditSekolahModal(${s.id})">✎ Edit</button>
                    <button class="btn-delete" onclick="deleteSchool(${s.id})">✕ Hapus</button>
                </div></td>
            </tr>`;
        }).join('');
    }

    async function submitSchool() {
        const name = document.getElementById('schoolName').value.trim();
        const npsn = document.getElementById('schoolNpsn').value.trim();
        const levelId = document.getElementById('schoolLevel').value;
        const type = document.getElementById('schoolType').value;
        const provId = document.getElementById('schoolProv').value;
        const cityId = document.getElementById('schoolCity').value;
        const eb = document.getElementById('formErrorBox'); eb.classList.add('hidden');
        if (!name || !npsn || !levelId || !type || !provId || !cityId) { eb.innerHTML = 'Field Nama, NPSN, Jenjang, Tipe, Provinsi, dan Kota wajib diisi'; eb.classList.remove('hidden'); return; }
        try {
            const res = await fetch(`${API_BASE}/schools`, { method: 'POST', headers, body: JSON.stringify(buildSchoolPayload()) });
            const result = await res.json();
            if (res.ok) { showToast("Sekolah berhasil disimpan", "success"); resetSchoolForm(); loadSekolahData(); }
            else { showError(eb, result); }
        } catch(e) { eb.innerHTML = 'Koneksi error'; eb.classList.remove('hidden'); }
    }

    function buildSchoolPayload() {
        const g = (id) => { const el = document.getElementById(id); return el ? el.value.trim() : ''; };
        const lat = parseFloat(g('schoolLat')), lng = parseFloat(g('schoolLng'));
        return { name: g('schoolName'), npsn: g('schoolNpsn'), level: parseInt(g('schoolLevel'),10)||null, type: g('schoolType')||null, accreditation: g('schoolAccreditation')||null, established: g('schoolEstablished')||null, headmaster: g('schoolHeadmaster')||null, class: g('schoolClass')||null, curriculum: g('schoolCurriculum')||null, student: g('schoolStudent')||null, province_id: parseInt(g('schoolProv'),10)||null, city_id: parseInt(g('schoolCity'),10)||null, location: g('schoolLocation')||null, latitude: isNaN(lat)?null:lat, longitude: isNaN(lng)?null:lng, link_location: g('schoolLinkLocation')||null, telephone: g('schoolTelephone')||null, web: g('schoolWeb')||null, instagram: g('schoolInstagram')||null, facebook: g('schoolFacebook')||null, tiktok: g('schoolTiktok')||null, twitter: g('schoolTwitter')||null, linkedin: g('schoolLinkedin')||null, motto: g('schoolMotto')||null, vision: g('schoolVision')||null, mission: g('schoolMission')||null };
    }

    async function showEditSekolahModal(id) {
        let school;
        try { const res = await fetch(`${API_BASE}/schools/${id}`, { headers }); const d = await res.json(); school = d.data || d; }
        catch(e) { showToast('Gagal memuat data sekolah','error'); return; }
        if (!state.provinces.length) await loadMasterData();
        const provOpts = state.provinces.map(p => `<option value="${p.id}" ${String(p.id)===String(school.province_id)?'selected':''}>${escHtml(p.name)}</option>`).join('');
        let cityOpts = '<option value="">-- Pilih Kota --</option>';
        if (school.province_id) {
            try {
                const cr = await fetch(`${API_BASE}/cities?prov=${school.province_id}`, { headers });
                const cd = await cr.json();
                const cities = cd.data || cd || [];
                cityOpts = '<option value="">-- Pilih Kota --</option>' + cities.map(c => { const cid = c.id||c.city_id||''; const cname = c.name||c.city_name||'—'; return `<option value="${cid}" ${String(cid)===String(school.city_id)?'selected':''}>${escHtml(cname)}</option>`; }).join('');
            } catch(e) {}
        }
        const accOpts  = ['','A','B','C','TT'].map(v => `<option value="${v}" ${school.accreditation===v?'selected':''}>${v||'-- Pilih --'}</option>`).join('');
        const typeOpts = ['','Negeri','Swasta'].map(v => `<option value="${v}" ${school.type===v?'selected':''}>${v||'-- Pilih --'}</option>`).join('');
        openModal('Edit Sekolah', `
            <div id="modalErrorBox" class="alert alert-error hidden"></div>
            <div class="form-section-label">Informasi Dasar</div>
            <div class="form-group" style="margin-bottom:12px;"><label class="form-label">Nama Sekolah *</label><input type="text" class="form-input" id="eSchoolName" value="${escAttr(school.name||'')}"></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                <div class="form-group"><label class="form-label">NPSN</label><input type="text" class="form-input" id="eSchoolNpsn" value="${escAttr(school.npsn||'')}"></div>
                <div class="form-group"><label class="form-label">Jenjang</label><select class="form-select" id="eSchoolLevel"><option value="">-- Pilih --</option>${state.schoolLevels.map(l => `<option value="${l.id}" ${String(l.id)===String(school.level)?'selected':''}>${escHtml(l.name)}</option>`).join('')}</select></div>
                <div class="form-group"><label class="form-label">Tipe</label><select class="form-select" id="eSchoolType">${typeOpts}</select></div>
                <div class="form-group"><label class="form-label">Akreditasi</label><select class="form-select" id="eSchoolAccreditation">${accOpts}</select></div>
                <div class="form-group"><label class="form-label">Tahun Berdiri</label><input type="text" class="form-input" id="eSchoolEstablished" value="${escAttr(school.established||'')}"></div>
                <div class="form-group"><label class="form-label">Kepala Sekolah</label><input type="text" class="form-input" id="eSchoolHeadmaster" value="${escAttr(school.headmaster||'')}"></div>
                <div class="form-group"><label class="form-label">Jumlah Kelas</label><input type="text" class="form-input" id="eSchoolClass" value="${escAttr(school.class||'')}"></div>
                <div class="form-group"><label class="form-label">Kurikulum</label><input type="text" class="form-input" id="eSchoolCurriculum" value="${escAttr(school.curriculum||'')}"></div>
                <div class="form-group"><label class="form-label">Jumlah Siswa</label><input type="text" class="form-input" id="eSchoolStudent" value="${escAttr(school.student||'')}"></div>
            </div>
            <div class="form-section-label" style="margin:14px 0 10px;">Lokasi</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                <div class="form-group"><label class="form-label">Provinsi</label><select class="form-select" id="eSchoolProv" onchange="loadCitiesForEdit(this.value)"><option value="">-- Pilih --</option>${provOpts}</select></div>
                <div class="form-group"><label class="form-label">Kota</label><select class="form-select" id="eSchoolCity">${cityOpts}</select></div>
                <div class="form-group" style="grid-column:1/-1"><label class="form-label">Alamat</label><input type="text" class="form-input" id="eSchoolLocation" value="${escAttr(school.location||'')}"></div>
                <div class="form-group"><label class="form-label">Latitude</label><input type="text" class="form-input" id="eSchoolLat" value="${escAttr(String(school.latitude||''))}"></div>
                <div class="form-group"><label class="form-label">Longitude</label><input type="text" class="form-input" id="eSchoolLng" value="${escAttr(String(school.longitude||''))}"></div>
                <div class="form-group" style="grid-column:1/-1"><label class="form-label">Link Maps</label><input type="text" class="form-input" id="eSchoolLinkLocation" value="${escAttr(school.link_location||'')}"></div>
            </div>
            <div class="form-section-label" style="margin:14px 0 10px;">Kontak & Sosmed</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                <div class="form-group"><label class="form-label">Telepon</label><input type="text" class="form-input" id="eSchoolTelephone" value="${escAttr(school.telephone||'')}"></div>
                <div class="form-group"><label class="form-label">Website</label><input type="text" class="form-input" id="eSchoolWeb" value="${escAttr(school.web||'')}"></div>
                <div class="form-group"><label class="form-label">Instagram</label><input type="text" class="form-input" id="eSchoolInstagram" value="${escAttr(school.instagram||'')}"></div>
                <div class="form-group"><label class="form-label">Facebook</label><input type="text" class="form-input" id="eSchoolFacebook" value="${escAttr(school.facebook||'')}"></div>
                <div class="form-group"><label class="form-label">TikTok</label><input type="text" class="form-input" id="eSchoolTiktok" value="${escAttr(school.tiktok||'')}"></div>
                <div class="form-group"><label class="form-label">Twitter</label><input type="text" class="form-input" id="eSchoolTwitter" value="${escAttr(school.twitter||'')}"></div>
                <div class="form-group" style="grid-column:1/-1"><label class="form-label">LinkedIn</label><input type="text" class="form-input" id="eSchoolLinkedin" value="${escAttr(school.linkedin||'')}"></div>
            </div>
            <div class="form-section-label" style="margin:14px 0 10px;">Profil</div>
            <div class="form-group" style="margin-bottom:12px;"><label class="form-label">Motto</label><input type="text" class="form-input" id="eSchoolMotto" value="${escAttr(school.motto||'')}"></div>
            <div class="form-group" style="margin-bottom:12px;"><label class="form-label">Visi</label><textarea class="form-textarea" id="eSchoolVision" rows="2">${escHtml(school.vision||'')}</textarea></div>
            <div class="form-group"><label class="form-label">Misi</label><textarea class="form-textarea" id="eSchoolMission" rows="2">${escHtml(school.mission||'')}</textarea></div>`,
            `<button class="btn btn-ghost" onclick="closeModal()">Batal</button><button class="btn btn-primary" onclick="updateSchool(${id})">Simpan Perubahan</button>`
        );
    }

    async function loadCitiesForEdit(provId) {
        const sel = document.getElementById('eSchoolCity');
        if (!sel) return;
        if (!provId) { sel.innerHTML = '<option value="">-- Pilih Kota --</option>'; return; }
        try {
            const res = await fetch(`${API_BASE}/cities?prov=${parseInt(provId,10)}`, { headers });
            const d = await res.json();
            sel.innerHTML = '<option value="">-- Pilih Kota --</option>' + (d.data||d||[]).map(c => `<option value="${c.id||c.city_id||''}">${escHtml(c.name||c.city_name||'—')}</option>`).join('');
        } catch(e) {}
    }

    async function updateSchool(id) {
        const g = (eid) => { const el = document.getElementById(eid); return el ? el.value.trim() : ''; };
        const name = g('eSchoolName');
        const eb = document.getElementById('modalErrorBox'); eb.classList.add('hidden');
        if (!name) { eb.innerHTML = 'Nama sekolah wajib diisi'; eb.classList.remove('hidden'); return; }
        const lat = parseFloat(g('eSchoolLat')), lng = parseFloat(g('eSchoolLng'));
        const payload = { name, npsn: g('eSchoolNpsn')||null, level: parseInt(g('eSchoolLevel'),10)||null, type: g('eSchoolType')||null, accreditation: g('eSchoolAccreditation')||null, established: g('eSchoolEstablished')||null, headmaster: g('eSchoolHeadmaster')||null, class: g('eSchoolClass')||null, curriculum: g('eSchoolCurriculum')||null, student: g('eSchoolStudent')||null, province_id: parseInt(g('eSchoolProv'),10)||null, city_id: parseInt(g('eSchoolCity'),10)||null, location: g('eSchoolLocation')||null, latitude: isNaN(lat)?null:lat, longitude: isNaN(lng)?null:lng, link_location: g('eSchoolLinkLocation')||null, telephone: g('eSchoolTelephone')||null, web: g('eSchoolWeb')||null, instagram: g('eSchoolInstagram')||null, facebook: g('eSchoolFacebook')||null, tiktok: g('eSchoolTiktok')||null, twitter: g('eSchoolTwitter')||null, linkedin: g('eSchoolLinkedin')||null, motto: g('eSchoolMotto')||null, vision: g('eSchoolVision')||null, mission: g('eSchoolMission')||null };
        try {
            const res = await fetch(`${API_BASE}/schools/${id}`, { method: 'PUT', headers, body: JSON.stringify(payload) });
            if (res.ok) { showToast('Sekolah berhasil diperbarui','success'); closeModal(); loadSekolahData(); }
            else { showError(eb, await res.json()); }
        } catch(e) { eb.innerHTML = 'Koneksi error'; eb.classList.remove('hidden'); }
    }

    async function loadCitiesByProvince(provId) {
        const sel = document.getElementById('schoolCity') || document.getElementById('selCity');
        if (!sel) return;
        if (!provId) { sel.innerHTML = '<option value="">-- Pilih Kota --</option>'; return; }
        try {
            const res = await fetch(`${API_BASE}/cities?prov=${parseInt(provId,10)}`, { headers });
            const data = await res.json();
            sel.innerHTML = '<option value="">-- Pilih Kota --</option>' + (data.data||data||[]).map(c => `<option value="${c.id||c.city_id||''}">${escHtml(c.name||c.city_name||'—')}</option>`).join('');
        } catch(e) {}
    }

        function generateAdmin(schoolId) {
    const school = allSchools.find(s => s.id == schoolId);
    const schoolName = school ? school.name : 'Sekolah';
    openModal('Buat Akun untuk Sekolah',
        `<div id="modalErrorBox" class="alert alert-error hidden"></div>
        <div style="background:var(--accent-muted);border:1px solid #bfdbfe;border-radius:8px;padding:10px 14px;margin-bottom:18px;font-size:13px;color:var(--text-secondary);">🏫 <strong style="color:var(--text-primary)">${escHtml(schoolName)}</strong></div>
        <div class="form-group" style="margin-bottom:12px;"><label class="form-label">Nama Lengkap *</label><input type="text" class="form-input" id="genName" placeholder="Nama lengkap pengguna"></div>
        <div class="form-group" style="margin-bottom:12px;"><label class="form-label">Email *</label><input type="email" class="form-input" id="genEmail" placeholder="email@contoh.com"></div>
        <div class="form-group" style="margin-bottom:12px;"><label class="form-label">Password *</label>
            <div style="position:relative;"><input type="password" class="form-input" id="genPassword" placeholder="Minimal 8 karakter" style="padding-right:44px;">
            <button onclick="togglePassVis('genPassword',this)" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:15px;">👁</button></div>
        </div>
        <div class="form-group" style="margin-bottom:12px;"><label class="form-label">Tipe Akun *</label>
            <select class="form-select" id="genType" onchange="syncRoleFromType(this.value, 'genRole')">
                <option value="">-- Pilih Tipe --</option>
                <option value="sip">SIP (Pengelola Sistem)</option>
                <option value="school_admin">Admin Sekolah</option>
                <option value="school_head">Kepala Sekolah</option>
            </select>
            <span style="font-size:11px;color:var(--text-muted);margin-top:4px;">Menentukan jabatan akun dalam sistem</span>
        </div>
        <div class="form-group"><label class="form-label">Role *</label>
            <select class="form-select" id="genRole">
                <option value="">-- Otomatis dari Tipe --</option>
                <option value="superadmin">Super Admin</option>
                <option value="admin">Admin Sekolah</option>
            </select>
            <span style="font-size:11px;color:var(--text-muted);margin-top:4px;">Akan terisi otomatis saat memilih Tipe</span>
        </div>`,
        `<button class="btn btn-ghost" onclick="closeModal()">Batal</button><button class="btn btn-primary" onclick="confirmCreateUser(${schoolId})">Buat Akun</button>`
    );
}

    function togglePassVis(inputId, btn) {
        const inp = document.getElementById(inputId);
        if (inp.type === 'password') { inp.type = 'text'; btn.textContent = '🙈'; }
        else { inp.type = 'password'; btn.textContent = '👁'; }
    }

    function toggleTypeField(role) {
        const wrap = document.getElementById('typeFieldWrap');
        if (!wrap) return;
        
        // Selalu tampilkan field Type
        wrap.style.display = '';
        
        const typeSelect = document.getElementById('genType');
        
        // Atur default value berdasarkan role, tapi JANGAN paksa user tidak bisa mengubahnya
        if (role === 'superadmin') {
            if(typeSelect) typeSelect.value = 'sip';
        } else if (role === 'admin') {
            if(typeSelect) typeSelect.value = 'school_admin';
        }
    }

    // Fungsi ini untuk memaksa Role berubah saat Type diubah manual
        function syncRoleFromType(typeVal, roleId) {
        const roleEl = document.getElementById(roleId);
        if (!roleEl) return;

        // Logika Ketentuan:
        // school_head atau sip -> superadmin
        // school_admin -> admin
        
        if (typeVal === 'school_head' || typeVal === 'sip') {
            roleEl.value = 'superadmin';
        } else if (typeVal === 'school_admin') {
            roleEl.value = 'admin';
        }
    }
        async function confirmCreateUser(schoolId) {
        const name = document.getElementById('genName').value.trim();
        const email = document.getElementById('genEmail').value.trim();
        const password = document.getElementById('genPassword').value;
        // Ambil nilai awal
        let role = document.getElementById('genRole').value;
        const type = document.getElementById('genType')?.value || '';

        const eb = document.getElementById('modalErrorBox'); eb.classList.add('hidden');
        
        // Validasi dasar
        if (!name)  { eb.innerHTML = 'Nama wajib diisi'; eb.classList.remove('hidden'); return; }
        if (!email) { eb.innerHTML = 'Email wajib diisi'; eb.classList.remove('hidden'); return; }
        if (!password || password.length < 8) { eb.innerHTML = 'Password minimal 8 karakter'; eb.classList.remove('hidden'); return; }
        
        // === LOGIKA UTAMA PERBAIKAN ===
        // Paksa Role berdasarkan Type (Ini yang sebelumnya kurang kuat)
        if (type === 'school_head' || type === 'sip') {
            role = 'superadmin'; 
            document.getElementById('genRole').value = 'superadmin'; // Update UI juga
        } else if (type === 'school_admin') {
            role = 'admin';
            document.getElementById('genRole').value = 'admin';
        }

        if (!role)  { eb.innerHTML = 'Role wajib dipilih'; eb.classList.remove('hidden'); return; }
        if ((role === 'superadmin' || role === 'admin') && !type) { eb.innerHTML = 'Tipe akun wajib dipilih'; eb.classList.remove('hidden'); return; }

        const payload = { name, email, password, role };
        if (type) payload.type = type;
        
        const sid = schoolId ? parseInt(schoolId,10) : null;
        if (sid && !isNaN(sid)) payload.school_id = sid;
        
        try {
            const res = await fetch(`${API_BASE}/users`, { method: 'POST', headers, body: JSON.stringify(payload) });
            const result = await res.json();
            if (res.ok) {
                document.getElementById('modalBody').innerHTML = `... (kode sukses tetap sama) ...`;
                document.getElementById('modalFooter').innerHTML = `<button class="btn btn-primary" onclick="closeModal()">Selesai</button>`;
                showToast("Akun berhasil dibuat", "success");
                loadSekolahData(); loadAdminSekolahData();
            } else { showError(eb, result); }
        } catch(e) { eb.innerHTML = 'Koneksi error'; eb.classList.remove('hidden'); }
    }

    // ================================================================
    // === JENJANG SEKOLAH ===
    // ================================================================
    async function renderJenjangSekolah() {
        document.getElementById('mainContent').innerHTML = `
            <div class="page-header"><h1 class="page-title">Jenjang Sekolah</h1></div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Daftar Jenjang</div>
                    <button class="btn btn-primary" onclick="showJenjangModal()">+ Tambah</button>
                </div>
                <div class="card-body table-wrap">
                    <table>
                        <thead><tr><th>Nama</th><th>Prefix</th><th style="width:140px">Aksi</th></tr></thead>
                        <tbody id="jenjangTableBody"><tr><td colspan="3" style="text-align:center;padding:32px;color:var(--text-muted)">Memuat...</td></tr></tbody>
                    </table>
                </div>
            </div>`;
        await loadJenjangTable();
    }

    async function loadJenjangTable() {
        try {
            const res = await fetch(`${API_BASE}/school-levels`, { headers });
            const d = await res.json();
            const items = d.data || d || [];
            const tbody = document.getElementById('jenjangTableBody');
            if (!tbody) return;
            if (!items.length) { tbody.innerHTML = '<tr><td colspan="3" style="text-align:center;padding:32px;color:var(--text-muted)">Tidak ada data</td></tr>'; return; }
            tbody.innerHTML = items.map(l => `<tr>
                <td class="td-name">${escHtml(l.name)}</td>
                <td>${escHtml(l.prefix||'—')}</td>
                <td><div class="action-group">
                    <button class="btn-edit"   onclick="showEditJenjangModal(${l.id}, '${escAttr(l.name)}', '${escAttr(l.prefix||'')}')">✎ Edit</button>
                    <button class="btn-delete" onclick="deleteJenjang(${l.id})">✕ Hapus</button>
                </div></td>
            </tr>`).join('');
        } catch(e) { console.error(e); }
    }

    function showJenjangModal() {
        openModal('Tambah Jenjang',
            `<div id="modalErrorBox" class="alert alert-error hidden"></div>
            <div class="form-group"><label class="form-label">Nama Jenjang *</label><input type="text" class="form-input" id="inputJenjangName" placeholder="contoh: SMA, SMP, SD"></div>
            <div class="form-group" style="margin-top:14px;"><label class="form-label">Prefix</label><input type="text" class="form-input" id="inputJenjangPrefix" placeholder="opsional"></div>`,
            `<button class="btn btn-ghost" onclick="closeModal()">Batal</button><button class="btn btn-primary" onclick="saveJenjang()">Simpan</button>`
        );
    }

    function showEditJenjangModal(id, name, prefix) {
        openModal('Edit Jenjang',
            `<div id="modalErrorBox" class="alert alert-error hidden"></div>
            <div class="form-group"><label class="form-label">Nama Jenjang *</label><input type="text" class="form-input" id="inputJenjangName" value="${escAttr(name)}"></div>
            <div class="form-group" style="margin-top:14px;"><label class="form-label">Prefix</label><input type="text" class="form-input" id="inputJenjangPrefix" value="${escAttr(prefix)}"></div>`,
            `<button class="btn btn-ghost" onclick="closeModal()">Batal</button><button class="btn btn-primary" onclick="updateJenjang(${id})">Simpan Perubahan</button>`
        );
    }

    async function saveJenjang() {
        const n = document.getElementById('inputJenjangName').value.trim();
        const p = document.getElementById('inputJenjangPrefix').value.trim();
        const eb = document.getElementById('modalErrorBox'); eb.classList.add('hidden');
        if (!n) { eb.innerHTML = 'Nama jenjang wajib diisi'; eb.classList.remove('hidden'); return; }
        try {
            const res = await fetch(`${API_BASE}/school-levels`, { method: 'POST', headers, body: JSON.stringify({ name: n, prefix: p||null }) });
            if (res.ok) { showToast('Jenjang berhasil ditambah','success'); closeModal(); loadJenjangTable(); }
            else { showError(eb, await res.json()); }
        } catch(e) { eb.innerHTML = 'Koneksi error'; eb.classList.remove('hidden'); }
    }

    async function updateJenjang(id) {
        const n = document.getElementById('inputJenjangName').value.trim();
        const p = document.getElementById('inputJenjangPrefix').value.trim();
        const eb = document.getElementById('modalErrorBox'); eb.classList.add('hidden');
        if (!n) { eb.innerHTML = 'Nama jenjang wajib diisi'; eb.classList.remove('hidden'); return; }
        try {
            const res = await fetch(`${API_BASE}/school-levels/${id}`, { method: 'PUT', headers, body: JSON.stringify({ name: n, prefix: p||null }) });
            if (res.ok) { showToast('Jenjang berhasil diperbarui','success'); closeModal(); loadJenjangTable(); }
            else { showError(eb, await res.json()); }
        } catch(e) { eb.innerHTML = 'Koneksi error'; eb.classList.remove('hidden'); }
    }

    // ================================================================
    // === PENDAFTARAN ===
    // ================================================================
    async function renderPendaftaran() {
        document.getElementById('mainContent').innerHTML = `
            <div class="page-header"><h1 class="page-title">Pendaftaran Siswa</h1></div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Data Pendaftar</div>
                    <div class="search-box"><span style="color:var(--text-muted)">🔍</span><input type="text" placeholder="Cari pendaftar..." oninput="filterPendaftaran(this.value)"></div>
                </div>
                <div class="card-body table-wrap">
                    <table>
                        <thead><tr><th>Nama</th><th>Sekolah</th><th>Status</th><th>Tanggal</th></tr></thead>
                        <tbody id="pendaftaranBody"><tr><td colspan="4" style="text-align:center;padding:40px;color:var(--text-muted)">Memuat...</td></tr></tbody>
                    </table>
                </div>
            </div>`;
        await loadPendaftaranData();
    }

    let allPendaftaran = [];
    function filterPendaftaran(q) {
        const l = q.toLowerCase();
        renderPendaftaranRows(allPendaftaran.filter(r => (r.name||'').toLowerCase().includes(l) || (r.school?.name||'').toLowerCase().includes(l)));
    }

    async function loadPendaftaranData() {
        try {
            const res = await fetch(`${API_BASE}/registration`, { headers });
            const d = await res.json();
            allPendaftaran = d.data || d || [];
            renderPendaftaranRows(allPendaftaran);
        } catch(e) { console.error(e); }
    }

    function renderPendaftaranRows(items) {
        const tbody = document.getElementById('pendaftaranBody');
        if (!tbody) return;
        if (!items.length) { tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;padding:40px;color:var(--text-muted)">Tidak ada data pendaftaran</td></tr>'; return; }
        tbody.innerHTML = items.map(r => `<tr>
            <td class="td-name">${escHtml(r.name)}</td>
            <td>${escHtml(r.school?.name||'—')}</td>
            <td>${renderBadge(r.status)}</td>
            <td style="font-size:12px;color:var(--text-muted)">${r.created_at ? formatDate(r.created_at) : '—'}</td>
        </tr>`).join('');
    }

    // ================================================================
    // === DAFTAR ADMIN / AKUN ===
    // ================================================================
    async function renderDaftarAdminSekolah() {
        await loadSekolahListForUserForm();
        document.getElementById('mainContent').innerHTML = `
            <div class="page-header"><h1 class="page-title">Manajemen Akun</h1></div>
            <div class="form-card" style="margin-bottom:20px;">
                <div class="form-header">
                    <h3 class="card-title">Tambah Akun Baru</h3>
                    <button class="btn btn-ghost btn-sm" onclick="toggleUserForm()" id="btnToggleUserForm">▲ Sembunyikan</button>
                </div>
                <div id="userFormWrap">
                <div class="form-body">
                    <div id="userFormErrorBox" class="alert alert-error hidden"></div>
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Nama Lengkap *</label><input type="text" class="form-input" id="uName" placeholder="Nama lengkap"></div>
                        <div class="form-group"><label class="form-label">Email *</label><input type="email" class="form-input" id="uEmail" placeholder="email@contoh.com"></div>
                        <div class="form-group"><label class="form-label">Password *</label>
                            <div style="position:relative;"><input type="password" class="form-input" id="uPassword" placeholder="Minimal 8 karakter" style="padding-right:44px;">
                            <button onclick="togglePassVis('uPassword',this)" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:15px;">👁</button></div>
                        </div>
                        <div class="form-group"><label class="form-label">Role *</label>
                            <select class="form-select" id="uRole" onchange="handleRoleChange(this.value,'uType','uSchoolGroup')"><option value="">-- Pilih Role --</option><option value="superadmin">Super Admin</option><option value="admin">Admin</option><option value="member">Member</option></select>
                        </div>
                        <div class="form-group" id="uTypeGroup" style="display:none;"><label class="form-label">Tipe Akun</label>
                            <select class="form-select" id="uType" onchange="handleTypeChange(this.value, 'uSchoolGroup'); syncRoleFromType(this.value, 'uRole');"><option value="">-- Pilih Tipe --</option><option value="sip">SIP</option><option value="school_admin">Admin Sekolah</option><option value="school_head">Kepala Sekolah</option></select>
                            <span style="font-size:11px;color:var(--text-muted);margin-top:4px;">Jabatan akun dalam sistem</span>
                        </div>
                        <div class="form-group" id="uSchoolGroup" style="display:none;"><label class="form-label">Sekolah</label>
                            <select class="form-select" id="uSchool"><option value="">-- Pilih Sekolah (opsional) --</option>${(state.schoolsForSelect||[]).map(s => `<option value="${s.id}">${escHtml(s.name)}</option>`).join('')}</select>
                        </div>
                    </div>
                </div>
                <div class="form-footer">
                    <button class="btn btn-ghost" onclick="resetUserForm()">Reset</button>
                    <button class="btn btn-primary" onclick="submitCreateUser()">Buat Akun</button>
                </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Daftar Akun</div>
                    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                        <select class="filter-select" id="filterRole" onchange="filterUsers()"><option value="">Semua Role</option><option value="superadmin">Super Admin</option><option value="admin">Admin</option><option value="member">Member</option></select>
                        <div class="search-box"><span style="color:var(--text-muted)">🔍</span><input type="text" id="searchUser" placeholder="Cari nama / email..." oninput="filterUsers()"></div>
                    </div>
                </div>
                <div class="card-body table-wrap">
                    <table>
                        <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Tipe</th><th>Sekolah</th><th style="width:140px">Aksi</th></tr></thead>
                        <tbody id="adminBody"><tr><td colspan="6" style="text-align:center;padding:40px;color:var(--text-muted)">Memuat...</td></tr></tbody>
                    </table>
                </div>
            </div>`;
        await loadAdminSekolahData();
    }

    async function loadSekolahListForUserForm() {
        try {
            const res = await fetch(`${API_BASE}/schools?per_page=200`, { headers });
            const d = await res.json();
            state.schoolsForSelect = (d.data||d||[]).map(s => ({ id: s.id, name: s.name }));
        } catch(e) { state.schoolsForSelect = []; }
    }

    function handleTypeChange(type, schoolGroupId) {
        const sg = document.getElementById(schoolGroupId);
        if (!sg) return;

        // Jika Type adalah Kepala Sekolah atau Admin Sekolah, tampilkan pilihan Sekolah
        if (type === 'school_head' || type === 'school_admin') {
            sg.style.display = '';
        } else {
            // Jika SIP (superadmin global), sembunyikan pilihan Sekolah
            sg.style.display = 'none';
        }
    }

    function handleRoleChange(role, typeSelectId, schoolGroupId) {
        const tg = document.getElementById('uTypeGroup');
        const sg = document.getElementById(schoolGroupId);
        
        // Tampilkan Type field untuk superadmin & admin
        if (role === 'superadmin' || role === 'admin') {
            if (tg) tg.style.display = '';
        } else {
            if (tg) tg.style.display = 'none';
        }

        const typeSelect = document.getElementById(typeSelectId);
        
        // Set default Type
        if (role === 'superadmin') {
            if(typeSelect) typeSelect.value = 'sip';
        } else if (role === 'admin') {
            if(typeSelect) typeSelect.value = 'school_admin';
        }
        
        // Trigger perubahan Type untuk mengatur visibilitas Sekolah
        if(typeSelect) handleTypeChange(typeSelect.value, schoolGroupId);
    }

    function toggleUserForm() {
        const wrap = document.getElementById('userFormWrap');
        const btn  = document.getElementById('btnToggleUserForm');
        const h = wrap.style.display === 'none';
        wrap.style.display = h ? '' : 'none';
        btn.textContent = h ? '▲ Sembunyikan' : '▼ Tampilkan Form';
    }

    function resetUserForm() {
        ['uName','uEmail','uPassword','uRole','uType','uSchool'].forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
        document.getElementById('uTypeGroup').style.display  = 'none';
        document.getElementById('uSchoolGroup').style.display = 'none';
        document.getElementById('userFormErrorBox').classList.add('hidden');
    }

    let allUsers = [];
    function filterUsers() {
        const q = (document.getElementById('searchUser')?.value||'').toLowerCase();
        const role = document.getElementById('filterRole')?.value||'';
        renderUserRows(allUsers.filter(u => {
            const mq = !q || (u.name||'').toLowerCase().includes(q) || (u.email||'').toLowerCase().includes(q);
            const mr = !role || u.role === role;
            return mq && mr;
        }));
    }

    async function loadAdminSekolahData() {
        try {
            const res = await fetch(`${API_BASE}/users`, { headers });
            const d = await res.json();
            allUsers = d.data || d || [];
            renderUserRows(allUsers);
        } catch(e) { console.error(e); }
    }

    function renderUserRows(list) {
        const tbody = document.getElementById('adminBody');
        if (!tbody) return;
        if (!list.length) { tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:40px;color:var(--text-muted)">Tidak ada data</td></tr>'; return; }
        const rc = { superadmin: 'accent', admin: 'info', member: 'success' };
        const tl = { sip: 'SIP', school_admin: 'Admin Sekolah', school_head: 'Kepala Sekolah' };
        tbody.innerHTML = list.map(u => `<tr>
            <td class="td-name">${escHtml(u.name)}</td>
            <td style="font-size:13px;color:var(--text-muted)">${escHtml(u.email||'—')}</td>
            <td><span class="badge ${rc[u.role]||'warning'}">${escHtml(u.role||'—')}</span></td>
            <td style="font-size:13px">${escHtml(u.type ? (tl[u.type]||u.type) : '—')}</td>
            <td style="font-size:13px">${escHtml(u.school?.name||'—')}</td>
            <td><div class="action-group">
                <button class="btn-edit"   onclick="showEditUserModal(${u.id})">✎ Edit</button>
                <button class="btn-delete" onclick="deleteUser(${u.id})">✕ Hapus</button>
            </div></td>
        </tr>`).join('');
    }

    async function submitCreateUser() {
        const name = document.getElementById('uName').value.trim();
        const email = document.getElementById('uEmail').value.trim();
        const password = document.getElementById('uPassword').value;
        const role = document.getElementById('uRole').value;
        const type = document.getElementById('uType')?.value||'';
        const schoolId = document.getElementById('uSchool')?.value||null;
        const eb = document.getElementById('userFormErrorBox'); eb.classList.add('hidden');
        if (!name)  { eb.innerHTML = 'Nama wajib diisi'; eb.classList.remove('hidden'); return; }
        if (!email) { eb.innerHTML = 'Email wajib diisi'; eb.classList.remove('hidden'); return; }
        if (!password || password.length < 8) { eb.innerHTML = 'Password minimal 8 karakter'; eb.classList.remove('hidden'); return; }
        if (!role)  { eb.innerHTML = 'Role wajib dipilih'; eb.classList.remove('hidden'); return; }
        if ((role === 'superadmin' || role === 'admin') && !type) { eb.innerHTML = 'Tipe akun wajib dipilih'; eb.classList.remove('hidden'); return; }
        const payload = { name, email, password, role };
        if (type) payload.type = type;
        const sid = schoolId ? parseInt(schoolId,10) : null;
        if (sid && !isNaN(sid)) payload.school_id = sid;
        try {
            const res = await fetch(`${API_BASE}/users`, { method: 'POST', headers, body: JSON.stringify(payload) });
            const result = await res.json();
            if (res.ok) { showToast('Akun berhasil dibuat','success'); resetUserForm(); loadAdminSekolahData(); }
            else { showError(eb, result); }
        } catch(e) { eb.innerHTML = 'Koneksi error'; eb.classList.remove('hidden'); }
    }

    async function showEditUserModal(id) {
        let user;
        try { const res = await fetch(`${API_BASE}/users/${id}`, { headers }); const d = await res.json(); user = d.data || d; }
        catch(e) { showToast('Gagal memuat data akun','error'); return; }
        if (!state.schoolsForSelect) await loadSekolahListForUserForm();
        const schoolOpts = (state.schoolsForSelect||[]).map(s => `<option value="${s.id}" ${String(s.id)===String(user.school_id)?'selected':''}>${escHtml(s.name)}</option>`).join('');
        const rc = { superadmin: 'accent', admin: 'info', member: 'success' };
        openModal('Edit Akun', `
            <div id="modalErrorBox" class="alert alert-error hidden"></div>
            <div style="display:flex;align-items:center;gap:12px;background:var(--bg);border-radius:10px;padding:12px 14px;margin-bottom:18px;">
                <div style="width:40px;height:40px;border-radius:10px;background:var(--accent-muted);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;color:var(--accent);">${escHtml((user.name||'?').substring(0,2).toUpperCase())}</div>
                <div><div style="font-weight:600;font-size:14px;color:var(--text-primary)">${escHtml(user.name)}</div>
                <div style="font-size:12px;color:var(--text-muted)">${escHtml(user.email||'—')} · <span class="badge ${rc[user.role]||'warning'}" style="font-size:10px;padding:2px 8px">${escHtml(user.role)}</span></div></div>
            </div>
            <div class="form-group" style="margin-bottom:12px;"><label class="form-label">Nama Lengkap *</label><input type="text" class="form-input" id="euName" value="${escAttr(user.name||'')}"></div>
            <div class="form-group" style="margin-bottom:12px;"><label class="form-label">Email *</label><input type="email" class="form-input" id="euEmail" value="${escAttr(user.email||'')}"></div>
            <div class="form-group" style="margin-bottom:12px;"><label class="form-label">Password Baru <span style="font-weight:400;color:var(--text-muted)">(kosongkan jika tidak diubah)</span></label>
                <div style="position:relative;"><input type="password" class="form-input" id="euPassword" placeholder="Isi untuk ganti password" style="padding-right:44px;">
                <button onclick="togglePassVis('euPassword',this)" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:15px;">👁</button></div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                <div class="form-group"><label class="form-label">Role *</label>
                    <select class="form-select" id="euRole"><option value="superadmin" ${user.role==='superadmin'?'selected':''}>Super Admin</option><option value="admin" ${user.role==='admin'?'selected':''}>Admin</option><option value="member" ${user.role==='member'?'selected':''}>Member</option></select>
                </div>
                <div class="form-group"><label class="form-label">Tipe Akun</label>
                    <select class="form-select" id="uType" onchange="handleTypeChange(this.value, 'uSchoolGroup')">
                    <option value="">-- Pilih Tipe --</option>
                    <option value="sip">SIP</option>
                    <option value="school_admin">Admin Sekolah</option>
                    <option value="school_head">Kepala Sekolah</option>
                </select>
                </div>
            </div>
            <div class="form-group"><label class="form-label">Sekolah</label>
                <select class="form-select" id="euSchool"><option value="">-- Tidak terhubung sekolah --</option>${schoolOpts}</select>
            </div>`,
            `<button class="btn btn-ghost" onclick="closeModal()">Batal</button><button class="btn btn-primary" onclick="updateUser(${id})">Simpan Perubahan</button>`
        );
    }

    async function updateUser(id) {
    const name = document.getElementById('euName').value.trim();
    const email = document.getElementById('euEmail').value.trim();
    const password = document.getElementById('euPassword').value;
    const role = document.getElementById('euRole').value;
    const type = document.getElementById('uType')?.value || '';   // ← fix: euType tidak ada, harusnya uType
    const schoolId = document.getElementById('euSchool').value;
    const eb = document.getElementById('modalErrorBox'); eb.classList.add('hidden');
    if (!name)  { eb.innerHTML = 'Nama wajib diisi'; eb.classList.remove('hidden'); return; }
    if (!email) { eb.innerHTML = 'Email wajib diisi'; eb.classList.remove('hidden'); return; }
    if (password && password.length < 8) { eb.innerHTML = 'Password minimal 8 karakter'; eb.classList.remove('hidden'); return; }
    const payload = { name, email, role, type: type||null, school_id: schoolId ? parseInt(schoolId,10) : null };
    if (password) payload.password = password;
    try {
        const res = await fetch(`${API_BASE}/users/${id}`, { method: 'PUT', headers, body: JSON.stringify(payload) });
        const result = await res.json();
        if (res.ok) { showToast('Akun berhasil diperbarui','success'); closeModal(); loadAdminSekolahData(); }
        else { showError(eb, result); }
    } catch(e) { eb.innerHTML = 'Koneksi error'; eb.classList.remove('hidden'); }
}

    async function deleteUser(id) {
        if (!confirm('Hapus akun ini? Tindakan ini tidak dapat dibatalkan.')) return;
        try {
            const res = await fetch(`${API_BASE}/users/${id}`, { method: 'DELETE', headers });
            if (res.ok) { showToast('Akun berhasil dihapus','success'); loadAdminSekolahData(); }
            else { showToast('Gagal menghapus akun','error'); }
        } catch(e) { showToast('Koneksi error','error'); }
    }

    // ================================================================
    // === HELPERS ===
    // ================================================================
    async function loadMasterData() {
        try {
            const [pRes, lRes] = await Promise.all([fetch(`${API_BASE}/provinces`, { headers }), fetch(`${API_BASE}/school-levels`, { headers })]);
            const pj = await pRes.json(); const lj = await lRes.json();
            const raw = (pj.data && pj.data.data) || pj.data || pj || [];
            state.provinces = raw.map(p => ({ ...p, id: p.id??p.prov_id??p.province_id??null, name: p.name||p.prov_name||'—' })).filter(p => p.id !== null);
            state.schoolLevels = (lj.data && lj.data.data) || lj.data || lj || [];
        } catch(e) { console.error('[loadMasterData]', e); }
    }

    async function loadCitiesData() {
        try {
            const res = await fetch(`${API_BASE}/cities`, { headers });
            const d = await res.json();
            state.cities = (d.data||d||[]).map(c => ({ id: c.id||c.city_id, name: c.name||c.city_name||'—' }));
        } catch(e) { console.error(e); }
    }

    function showError(box, result) {
        let msg = '<strong>Gagal:</strong><ul>';
        if (result.errors) { for (const f in result.errors) result.errors[f].forEach(m => msg += `<li>${escHtml(m)}</li>`); }
        else if (result.message) { msg += `<li>${escHtml(result.message)}</li>`; }
        else { msg += '<li>Terjadi kesalahan, coba lagi</li>'; }
        msg += '</ul>'; box.innerHTML = msg; box.classList.remove('hidden');
    }

    function renderBadge(type, label) {
        const map = { 'diterima':'success', 'lulus':'success', 'ditolak':'error', 'pending':'warning', 'success':'success', 'warning':'warning', 'error':'error', 'info':'info' };
        const cls = map[(type||'').toLowerCase()] || 'warning';
        return `<span class="badge ${cls}">${label||type||'—'}</span>`;
    }

    function formatDate(str) {
        try { return new Date(str).toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric' }); }
        catch { return str; }
    }

    function escHtml(str) {
        if (str == null) return '—';
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function escAttr(str) {
        if (str == null) return '';
        return String(str).replace(/\\/g,'\\\\').replace(/'/g,"\\'").replace(/"/g,'&quot;');
    }

    function animateNumber(id, target) {
        const el = document.getElementById(id); if (!el) return;
        let c = 0; const s = Math.max(1, Math.ceil(target/20));
        const t = setInterval(() => { c = Math.min(c+s, target); el.textContent = c; if (c >= target) clearInterval(t); }, 30);
    }

    async function loadUserInfo() {
        try {
            const res = await fetch(`${API_BASE}/me`, { headers });
            const d = await res.json();
            const u = d.user || d.data || d;
            if (u && u.name) { document.getElementById('userName').textContent = u.name; document.getElementById('userAvatar').textContent = u.name.substring(0,2).toUpperCase(); }
        } catch(e) {}
    }

    function openModal(title, body, footer) {
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalBody').innerHTML = body;
        document.getElementById('modalFooter').innerHTML = footer;
        document.getElementById('modalOverlay').classList.add('active');
    }

    function closeModal() { document.getElementById('modalOverlay').classList.remove('active'); }

    function showToast(msg, type = 'info') {
        const c = document.getElementById('toastContainer');
        const t = document.createElement('div');
        t.className = `toast ${type}`;
        t.innerHTML = `<span style="font-size:16px">${type==='error'?'✕':'✓'}</span><span style="font-size:13px;color:var(--text-primary)">${escHtml(msg)}</span>`;
        c.appendChild(t);
        setTimeout(() => { t.style.opacity = 0; t.style.transition = 'opacity 0.3s'; setTimeout(() => t.remove(), 300); }, 4000);
    }

    async function handleLogout() {
        try { await fetch(`${API_BASE}/logout`, { method: 'POST', headers }); } catch(e) {}
        localStorage.removeItem('token'); window.location.href = '/login';
    }

    async function deleteSchool(id)    { if (!confirm('Hapus sekolah ini?'))   return; await fetch(`${API_BASE}/schools/${id}`,       { method:'DELETE', headers }); showToast('Sekolah dihapus','success');   loadSekolahData(); }
    async function deleteProvinsi(id)  { if (!confirm('Hapus provinsi ini?'))  return; await fetch(`${API_BASE}/provinces/${id}`,     { method:'DELETE', headers }); showToast('Provinsi dihapus','success');  loadProvinsiData(); }
    async function deleteKota(id)      { if (!confirm('Hapus kota ini?'))      return; await fetch(`${API_BASE}/cities/${id}`,        { method:'DELETE', headers }); showToast('Kota dihapus','success');      loadKotaData(); }
    async function deleteKecamatan(id) { if (!confirm('Hapus kecamatan ini?')) return; await fetch(`${API_BASE}/districts/${id}`,     { method:'DELETE', headers }); showToast('Kecamatan dihapus','success'); loadKecamatanData(); }
    async function deleteJenjang(id)   { if (!confirm('Hapus jenjang ini?'))   return; await fetch(`${API_BASE}/school-levels/${id}`, { method:'DELETE', headers }); showToast('Jenjang dihapus','success');   loadJenjangTable(); }
</script>
</body>
</html>