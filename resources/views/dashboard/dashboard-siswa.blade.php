<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP — Dashboard Siswa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:       #f0f4f8;
            --card:     #ffffff;
            --sidebar:  #ffffff;
            --accent:   #2563eb;
            --accent-l: #eff6ff;
            --accent-h: #1d4ed8;
            --text:     #0f172a;
            --text-s:   #475569;
            --text-m:   #94a3b8;
            --border:   #e2e8f0;
            --success:  #10b981; --success-bg: #ecfdf5;
            --error:    #ef4444; --error-bg:   #fef2f2;
            --warning:  #f59e0b; --warning-bg: #fffbeb;
            --info:     #3b82f6; --info-bg:    #eff6ff;
            --shadow:   0 1px 3px rgba(0,0,0,.07);
            --shadow-md:0 4px 12px rgba(0,0,0,.08);
        }

        html, body { height: 100%; font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); }
        .app { display: flex; height: 100vh; overflow: hidden; }

        /* ── Sidebar ── */
        .sidebar {
            width: 240px; flex-shrink: 0;
            background: var(--sidebar); border-right: 1px solid var(--border);
            display: flex; flex-direction: column; z-index: 100;
            transition: width .25s;
        }
        .sidebar.collapsed { width: 64px; }

        .sidebar-brand {
            padding: 18px 16px; display: flex; align-items: center; gap: 12px;
            border-bottom: 1px solid var(--border); min-height: 66px; overflow: hidden;
        }
        .brand-logo {
            width: 34px; height: 34px; flex-shrink: 0; border-radius: 9px;
            background: linear-gradient(135deg, var(--accent-h), var(--accent));
            display: flex; align-items: center; justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 12px; color: white;
        }
        .brand-text { overflow: hidden; white-space: nowrap; }
        .brand-name { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; font-weight: 700; }
        .brand-sub  { font-size: 10px; color: var(--text-m); margin-top: 1px; }

        .user-pill {
            margin: 10px 10px 0;
            padding: 10px 12px;
            background: var(--accent-l); border: 1px solid #bfdbfe;
            border-radius: 10px; overflow: hidden;
        }
        .user-pill-name { font-size: 12px; font-weight: 700; color: var(--accent-h); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-pill-sub  { font-size: 10px; color: var(--accent); margin-top: 1px; }

        .sidebar-nav { flex: 1; padding: 12px 8px; overflow-y: auto; }
        .nav-group { font-size: 10px; font-weight: 700; letter-spacing: .1em; color: var(--text-m); text-transform: uppercase; padding: 10px 10px 5px; }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 10px; border-radius: 8px;
            color: var(--text-s); font-size: 13px; font-weight: 500;
            cursor: pointer; text-decoration: none;
            transition: all .15s; margin-bottom: 1px;
            position: relative; white-space: nowrap; overflow: hidden;
        }
        .nav-item:hover { background: var(--bg); color: var(--text); }
        .nav-item.active { background: var(--accent-l); color: var(--accent); font-weight: 600; }
        .nav-item.active::before {
            content: ''; position: absolute; left: 0; top: 50%;
            transform: translateY(-50%); width: 3px; height: 18px;
            background: var(--accent); border-radius: 0 3px 3px 0;
        }
        .nav-icon { font-size: 15px; flex-shrink: 0; width: 20px; text-align: center; }
        .nav-text  { flex: 1; overflow: hidden; }

        .sidebar-footer { padding: 10px 8px; border-top: 1px solid var(--border); }
        .logout-btn {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 10px; border-radius: 8px;
            color: var(--text-s); font-size: 13px; font-weight: 500;
            cursor: pointer; background: none; border: none; width: 100%;
            transition: all .2s;
        }
        .logout-btn:hover { background: var(--error-bg); color: var(--error); }

        /* ── Main ── */
        .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; min-width: 0; }

        .topbar {
            height: 64px; background: var(--card); border-bottom: 1px solid var(--border);
            display: flex; align-items: center; padding: 0 24px; gap: 14px;
            box-shadow: var(--shadow); flex-shrink: 0;
        }
        .topbar-toggle { background: none; border: none; cursor: pointer; color: var(--text-s); font-size: 18px; padding: 7px; border-radius: 8px; transition: all .2s; }
        .topbar-toggle:hover { background: var(--bg); color: var(--text); }
        .topbar-title { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 16px; font-weight: 700; }
        .topbar-spacer { flex: 1; }

        .content { flex: 1; overflow-y: auto; padding: 22px 24px; }

        .page-header { margin-bottom: 20px; }
        .page-title  { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 20px; font-weight: 800; }
        .breadcrumb  { font-size: 12px; color: var(--text-m); margin-top: 2px; }

        /* ── Status Hero Card ── */
        .status-hero {
            border-radius: 14px; padding: 24px 28px;
            display: flex; align-items: center; gap: 20px;
            margin-bottom: 20px; position: relative; overflow: hidden;
            box-shadow: var(--shadow-md);
        }
        .status-hero.s-0 { background: linear-gradient(135deg, #78350f, #b45309); }
        .status-hero.s-1 { background: linear-gradient(135deg, #1e3a5f, #1d4ed8); }
        .status-hero.s-2 { background: linear-gradient(135deg, #4c1d95, #7c3aed); }
        .status-hero.s-3 { background: linear-gradient(135deg, #064e3b, #059669); }
        .status-hero.s-4 { background: linear-gradient(135deg, #7f1d1d, #dc2626); }
        .status-hero.s-x { background: linear-gradient(135deg, #1e293b, #334155); }
        .status-hero::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(circle at 80% 50%, rgba(255,255,255,.06), transparent 60%);
        }
        .hero-icon  { font-size: 44px; flex-shrink: 0; position: relative; z-index: 1; }
        .hero-body  { flex: 1; position: relative; z-index: 1; }
        .hero-label { font-size: 12px; color: rgba(255,255,255,.6); text-transform: uppercase; letter-spacing: .08em; margin-bottom: 4px; }
        .hero-status{ font-family: 'Plus Jakarta Sans', sans-serif; font-size: 24px; font-weight: 800; color: white; margin-bottom: 4px; }
        .hero-desc  { font-size: 13px; color: rgba(255,255,255,.65); line-height: 1.5; }
        .hero-badge {
            position: relative; z-index: 1;
            padding: 8px 18px; border-radius: 20px; flex-shrink: 0;
            background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.2);
            font-size: 13px; font-weight: 700; color: white;
        }

        /* ── Card ── */
        .card {
            background: var(--card); border: 1px solid var(--border);
            border-radius: 12px; box-shadow: var(--shadow); margin-bottom: 16px; overflow: hidden;
        }
        .card-head {
            padding: 14px 18px; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 12px;
        }
        .card-title { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; font-weight: 700; flex: 1; }
        .card-body  { padding: 18px; }

        /* ── Info grid ── */
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .info-item.full { grid-column: 1 / -1; }
        .info-label { font-size: 11px; color: var(--text-m); text-transform: uppercase; letter-spacing: .06em; margin-bottom: 3px; }
        .info-value { font-size: 14px; font-weight: 600; }

        /* ── Progress steps ── */
        .progress-wrap { padding: 20px 18px; }
        .progress-steps { display: flex; align-items: center; position: relative; }
        .progress-line  {
            position: absolute; top: 18px; left: 18px; right: 18px; height: 2px;
            background: var(--border); z-index: 0;
        }
        .progress-fill {
            position: absolute; top: 18px; left: 18px; height: 2px;
            background: var(--accent); z-index: 1; transition: width .5s;
        }
        .step-item { flex: 1; display: flex; flex-direction: column; align-items: center; position: relative; z-index: 2; }
        .step-circle {
            width: 36px; height: 36px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 700; border: 2px solid var(--border);
            background: var(--card); color: var(--text-m); margin-bottom: 8px;
            transition: all .3s;
        }
        .step-circle.done    { background: var(--accent); border-color: var(--accent); color: white; }
        .step-circle.active  { background: white; border-color: var(--accent); color: var(--accent); box-shadow: 0 0 0 4px rgba(37,99,235,.1); }
        .step-circle.reject  { background: var(--error); border-color: var(--error); color: white; }
        .step-name  { font-size: 11px; font-weight: 600; text-align: center; color: var(--text-m); }
        .step-name.done   { color: var(--accent); }
        .step-name.active { color: var(--text); }
        .step-name.reject { color: var(--error); }

        /* ── Sekolah card ── */
        .school-banner {
            background: linear-gradient(135deg, #1e3a5f, #2563eb);
            padding: 18px 20px; display: flex; align-items: center; gap: 14px;
            position: relative; overflow: hidden;
        }
        .school-banner::before { content: ''; position: absolute; inset: 0; background: radial-gradient(circle at 80% 50%, rgba(255,255,255,.05), transparent 60%); }
        .school-logo {
            width: 50px; height: 50px; border-radius: 12px; flex-shrink: 0;
            background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.2);
            display: flex; align-items: center; justify-content: center; font-size: 22px;
            position: relative; z-index: 1;
        }
        .school-name { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 15px; font-weight: 800; color: white; position: relative; z-index: 1; }
        .school-type { font-size: 12px; color: rgba(255,255,255,.6); position: relative; z-index: 1; margin-top: 2px; }

        /* ── Form edit ── */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .form-group { display: flex; flex-direction: column; gap: 4px; }
        .form-group.full { grid-column: 1/-1; }
        .form-label { font-size: 11px; font-weight: 700; color: var(--text-s); text-transform: uppercase; letter-spacing: .06em; }
        .form-input, .form-select {
            padding: 9px 12px; background: var(--bg); border: 1px solid var(--border);
            border-radius: 8px; font-family: 'DM Sans', sans-serif;
            font-size: 13px; color: var(--text); outline: none; transition: all .18s;
        }
        .form-input:focus, .form-select:focus {
            border-color: var(--accent); background: white;
            box-shadow: 0 0 0 3px rgba(37,99,235,.08);
        }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; border-radius: 8px; border: none;
            font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 600;
            cursor: pointer; transition: all .18s;
        }
        .btn-primary { background: var(--accent); color: white; }
        .btn-primary:hover { background: var(--accent-h); }
        .btn-outline { background: white; color: var(--text-s); border: 1px solid var(--border); }
        .btn-outline:hover { background: var(--bg); }
        .btn-warn { background: var(--warning-bg); color: #92400e; border: 1px solid #fde68a; }
        .btn-warn:hover { background: var(--warning); color: white; }
        .btn-danger { background: var(--error-bg); color: var(--error); border: 1px solid #fecaca; }
        .btn-danger:hover { background: var(--error); color: white; }

        /* ── Toast ── */
        .toast {
            position: fixed; bottom: 24px; right: 24px;
            padding: 12px 18px; border-radius: 10px;
            background: var(--text); color: white;
            font-size: 13px; font-weight: 600;
            box-shadow: var(--shadow-md); z-index: 9999;
            transform: translateY(80px); opacity: 0;
            transition: all .3s cubic-bezier(.4,0,.2,1);
        }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast.success { background: var(--success); }
        .toast.error   { background: var(--error); }

        /* ── Empty ── */
        .empty { padding: 48px 20px; text-align: center; color: var(--text-m); font-size: 13px; }
        .empty-ico { font-size: 36px; margin-bottom: 10px; }

        /* ── Loading ── */
        .skel { background: linear-gradient(90deg,#f0f4f8 25%,#e2e8f0 50%,#f0f4f8 75%); background-size: 200% 100%; animation: shimmer 1.2s infinite; border-radius: 6px; }
        @keyframes shimmer { to { background-position: -200% 0; } }

        /* ── Upload zone ── */
        .upload-zone {
            border: 2px dashed #cbd5e1; border-radius: 12px;
            padding: 28px; text-align: center; cursor: pointer;
            transition: all .2s; background: #f8fafc;
        }
        .upload-zone:hover, .upload-zone.drag-over {
            border-color: var(--accent); background: var(--accent-l);
        }
        .upload-zone.disabled {
            opacity: .5; cursor: not-allowed; pointer-events: none;
        }

        /* ── Proof card ── */
        .proof-card {
            border: 1px solid var(--border); border-radius: 10px;
            padding: 14px 16px; background: white;
            display: flex; align-items: flex-start; gap: 12px; flex-wrap: wrap;
        }
        .proof-card.proof-pending  { border-left: 3px solid var(--warning); }
        .proof-card.proof-verified { border-left: 3px solid var(--success); }
        .proof-card.proof-rejected { border-left: 3px solid var(--error); }

        @media (max-width: 900px) {
            .info-grid, .form-grid { grid-template-columns: 1fr; }
            .info-item.full, .form-group.full { grid-column: 1; }
        }
        @media (max-width: 768px) {
            .sidebar { position: fixed; height: 100%; left: -240px; transition: left .25s; }
            .sidebar.mobile-open { left: 0; }
        }
    </style>
</head>
<body>
<div class="app">

<!-- ── Sidebar ── -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">SIP</div>
        <div class="brand-text">
            <div class="brand-name">Portal Siswa</div>
            <div class="brand-sub">Pendaftaran Online</div>
        </div>
    </div>

    <div class="user-pill">
        <div class="user-pill-name" id="pillName">Memuat...</div>
        <div class="user-pill-sub">Calon Siswa</div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-group">Menu</div>
        <a class="nav-item active" data-page="dashboard" href="#">
            <span class="nav-icon">🏠</span>
            <span class="nav-text">Dashboard</span>
        </a>
        <a class="nav-item" data-page="pendaftaran" href="#">
            <span class="nav-icon">📋</span>
            <span class="nav-text">Status Pendaftaran</span>
        </a>
        <a class="nav-item" data-page="sekolah" href="#">
            <span class="nav-icon">🏫</span>
            <span class="nav-text">Info Sekolah</span>
        </a>
        <a class="nav-item" data-page="pembayaran" href="#">
            <span class="nav-icon">💳</span>
            <span class="nav-text">Bukti Pembayaran</span>
        </a>
        <div class="nav-group">Akun</div>
        <a class="nav-item" data-page="profil" href="#">
            <span class="nav-icon">👤</span>
            <span class="nav-text">Profil Saya</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <button class="logout-btn" onclick="doLogout()">
            <span class="nav-icon">⏻</span>
            <span class="nav-text">Keluar</span>
        </button>
    </div>
</aside>

<!-- ── Main ── -->
<div class="main">
    <header class="topbar">
        <button class="topbar-toggle" onclick="toggleSidebar()">☰</button>
        <div class="topbar-title" id="topbarTitle">Dashboard</div>
        <div class="topbar-spacer"></div>
        <div style="display:flex;align-items:center;gap:10px;padding:6px 10px;border-radius:9px;cursor:pointer;"
             onclick="navTo('profil')">
            <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,var(--accent-h),var(--accent));display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;color:white" id="topAvatar">S</div>
            <div>
                <div style="font-size:13px;font-weight:600" id="topName">Siswa</div>
                <div style="font-size:10px;color:var(--text-m)">Calon Siswa</div>
            </div>
        </div>
    </header>

    <div class="content" id="mainContent">
        <div style="padding:60px;text-align:center;color:var(--text-m)">
            <div class="skel" style="height:120px;border-radius:14px;margin-bottom:16px;"></div>
            <div class="skel" style="height:80px;border-radius:12px;margin-bottom:12px;"></div>
            <div class="skel" style="height:80px;border-radius:12px;"></div>
        </div>
    </div>
</div>
</div>

<!-- Toast -->
<div class="toast" id="toast"></div>

<script>
// ─── CONFIG ───────────────────────────────────────────
const API   = '/api';
const TOKEN = localStorage.getItem('token') || '';
const HDR   = { 'Authorization': `Bearer ${TOKEN}`, 'Accept': 'application/json', 'Content-Type': 'application/json' };

const ST = {
    '0': { label: 'Menunggu Verifikasi', desc: 'Pendaftaranmu sedang menunggu ditinjau oleh admin sekolah.',     icon: '⏳', cls: 's-0', badge: 'Menunggu' },
    '1': { label: 'Dalam Proses Verifikasi', desc: 'Dokumenmu sedang diverifikasi oleh admin sekolah.',          icon: '🔍', cls: 's-1', badge: 'Verifikasi' },
    '2': { label: 'Tahap Seleksi',      desc: 'Kamu telah lolos verifikasi dan sedang dalam tahap seleksi.',     icon: '📝', cls: 's-2', badge: 'Seleksi' },
    '3': { label: 'DITERIMA! 🎉',       desc: 'Selamat! Kamu diterima di sekolah ini. Segera hubungi sekolah.',  icon: '✅', cls: 's-3', badge: 'Diterima' },
    '4': { label: 'Tidak Diterima',     desc: 'Maaf, pendaftaranmu tidak dapat diterima pada tahap ini.',        icon: '❌', cls: 's-4', badge: 'Ditolak' },
    '9': { label: 'Status Lainnya',     desc: 'Status pendaftaranmu sedang diproses lebih lanjut.',              icon: '🔄', cls: 's-x', badge: 'Diproses' },
};

let S = { user: null, member: null, registrations: [], student: null };

// ─── INIT ─────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', async () => {
    if (!TOKEN) { window.location.href = '/login-siswa'; return; }
    await loadUser();
    initNav();
    renderDashboard();
});

async function loadUser() {
    try {
        const res = await fetch(`${API}/me`, { headers: HDR });
        if (!res.ok) { window.location.href = '/login-siswa'; return; }
        const d = await res.json();
        S.user = d.user;

        if (S.user.role !== 'member') { window.location.href = '/login-siswa'; return; }

        const name = S.user.name || 'Siswa';
        document.getElementById('pillName').textContent = name;
        document.getElementById('topName').textContent  = name;
        document.getElementById('topAvatar').textContent =
            name.trim().split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase();
        localStorage.setItem('user_name', name);

        await loadRegistrations();
    } catch(e) { console.error(e); }
}

async function loadRegistrations() {
    try {
        const res = await fetch(`${API}/registration?limit=100`, { headers: HDR });
        if (res.ok) {
            const d = await res.json();
            S.registrations = d.data || [];
        }
    } catch(e) { console.error(e); }
}

// ─── NAVIGATION ───────────────────────────────────────
function initNav() {
    document.querySelectorAll('.nav-item[data-page]').forEach(el => {
        el.addEventListener('click', e => {
            e.preventDefault();
            setNav(el);
            loadPage(el.dataset.page);
        });
    });
}

function setNav(el) {
    document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
    el.classList.add('active');
}

function navTo(page) {
    const el = document.querySelector(`.nav-item[data-page="${page}"]`);
    if (el) { setNav(el); loadPage(page); }
}

function loadPage(page) {
    const titles = { dashboard: 'Dashboard', pendaftaran: 'Status Pendaftaran', sekolah: 'Info Sekolah', pembayaran: 'Bukti Pembayaran', profil: 'Profil Saya' };
    document.getElementById('topbarTitle').textContent = titles[page] || page;
    const map = { dashboard: renderDashboard, pendaftaran: renderPendaftaran, sekolah: renderSekolah, pembayaran: renderPembayaran, profil: renderProfil };
    if (map[page]) map[page]();
}

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('collapsed');
}

// ─── DASHBOARD ────────────────────────────────────────
function renderDashboard() {
    const el = document.getElementById('mainContent');
    const reg = S.registrations[0];
    const st  = reg ? String(reg.status ?? '0') : null;
    const info = st ? (ST[st] || ST['9']) : null;

    el.innerHTML = `
    <div class="page-header">
        <div class="page-title">Halo, ${esc(S.user?.name?.split(' ')[0] || 'Siswa')} 👋</div>
        <div class="breadcrumb">Pantau progress pendaftaranmu di sini</div>
    </div>

    ${reg ? `
    <div class="status-hero ${info.cls}">
        <div class="hero-icon">${info.icon}</div>
        <div class="hero-body">
            <div class="hero-label">Status Pendaftaranmu</div>
            <div class="hero-status">${info.label}</div>
            <div class="hero-desc">${info.desc}</div>
        </div>
        <div class="hero-badge">${info.badge}</div>
    </div>

    <div class="card" style="margin-bottom:16px;">
        <div class="card-head"><div class="card-title">📊 Alur Pendaftaran</div></div>
        ${buildProgressSteps(st)}
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
        <div class="card" style="margin-bottom:0;">
            <div class="card-head"><div class="card-title">📋 Detail Pendaftaran</div></div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Sekolah Tujuan</div>
                        <div class="info-value">${esc(reg.school?.name || '—')}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Gelombang</div>
                        <div class="info-value">${esc(reg.regForm?.title || '—')}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tahun Ajaran</div>
                        <div class="info-value">${esc(reg.regForm?.ta || '—')}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Asal Sekolah</div>
                        <div class="info-value">${esc(reg.school_origin || '—')}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Terakhir Update</div>
                        <div class="info-value">${reg.updated_at ? fmtDate(reg.updated_at) : '—'}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">ID Pendaftaran</div>
                        <div class="info-value" style="font-family:monospace;font-size:13px">#${reg.id}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom:0;">
            <div class="card-head"><div class="card-title">👤 Data Siswa</div></div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Nama Lengkap</div>
                        <div class="info-value">${esc(reg.student?.nama || S.user?.name || '—')}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">NISN</div>
                        <div class="info-value" style="font-family:monospace">${esc(reg.student?.nisn || '—')}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value" style="font-size:12px">${esc(reg.student?.email || S.user?.email || '—')}</div>
                    </div>
                    <div class="info-item full">
                        <div class="info-label">Alamat</div>
                        <div class="info-value" style="font-size:13px">${esc(reg.student?.alamat || '—')}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    ${st === '3' ? `
    <div style="background:linear-gradient(135deg,#064e3b,#059669);border-radius:12px;padding:20px 24px;display:flex;align-items:center;gap:16px;color:white;">
        <div style="font-size:36px">🎉</div>
        <div>
            <div style="font-family:'Plus Jakarta Sans',sans-serif;font-size:16px;font-weight:800;margin-bottom:4px">Selamat! Kamu diterima!</div>
            <div style="font-size:13px;color:rgba(255,255,255,.7)">Segera hubungi ${esc(reg.school?.name || 'sekolah')} untuk proses selanjutnya.</div>
        </div>
        <button class="btn btn-outline" style="margin-left:auto;background:rgba(255,255,255,.15);color:white;border-color:rgba(255,255,255,.2);" onclick="navTo('sekolah')">Info Sekolah</button>
    </div>` : ''}
    ` : `
    <div class="card">
        <div class="empty">
            <div class="empty-ico">📭</div>
            <div style="font-size:15px;font-weight:600;margin-bottom:8px;color:var(--text-s)">Belum ada pendaftaran</div>
            <div style="margin-bottom:16px">Kamu belum mendaftarkan diri ke sekolah manapun.</div>
            <a href="/daftar-siswa" class="btn btn-primary" style="text-decoration:none">+ Daftar Sekarang</a>
        </div>
    </div>
    `}`;
}

// ─── PENDAFTARAN ──────────────────────────────────────
function renderPendaftaran() {
    const el = document.getElementById('mainContent');

    if (!S.registrations.length) {
        el.innerHTML = `
        <div class="page-header"><div class="page-title">Status Pendaftaran</div></div>
        <div class="card"><div class="empty">
            <div class="empty-ico">📋</div>
            <div style="font-size:15px;font-weight:600;margin-bottom:8px;color:var(--text-s)">Belum ada pendaftaran</div>
            <a href="/daftar-siswa" class="btn btn-primary" style="text-decoration:none;margin-top:8px;">Daftar Sekarang</a>
        </div></div>`;
        return;
    }

    el.innerHTML = `
    <div class="page-header">
        <div class="page-title">Status Pendaftaran</div>
        <div class="breadcrumb">${S.registrations.length} pendaftaran tercatat</div>
    </div>
    ${S.registrations.map((reg) => {
        const st   = String(reg.status ?? '0');
        const info = ST[st] || ST['9'];
        return `
        <div class="card">
            <div class="status-hero ${info.cls}" style="border-radius:0;margin-bottom:0;">
                <div class="hero-icon" style="font-size:32px">${info.icon}</div>
                <div class="hero-body">
                    <div class="hero-label">Pendaftaran #${reg.id}</div>
                    <div class="hero-status" style="font-size:18px">${info.label}</div>
                </div>
                <div class="hero-badge">${info.badge}</div>
            </div>
            ${buildProgressSteps(st)}
            <div class="card-body" style="border-top:1px solid var(--border)">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Sekolah Tujuan</div>
                        <div class="info-value">${esc(reg.school?.name || '—')}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Gelombang / TA</div>
                        <div class="info-value">${esc(reg.regForm?.title || '—')} ${esc(reg.regForm?.ta ? '· '+reg.regForm.ta : '')}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Asal Sekolah</div>
                        <div class="info-value">${esc(reg.school_origin || '—')}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Diperbarui</div>
                        <div class="info-value">${reg.updated_at ? fmtDate(reg.updated_at) : '—'}</div>
                    </div>
                </div>
            </div>
        </div>`;
    }).join('')}`;
}

// ─── SEKOLAH ──────────────────────────────────────────
async function renderSekolah() {
    const el = document.getElementById('mainContent');
    el.innerHTML = `
    <div class="page-header"><div class="page-title">Info Sekolah</div></div>
    <div class="card"><div class="card-body"><div class="skel" style="height:60px;border-radius:8px;margin-bottom:12px;"></div><div class="skel" style="height:40px;border-radius:8px;"></div></div></div>`;

    const reg = S.registrations[0];
    if (!reg?.school_id) {
        el.innerHTML = `<div class="page-header"><div class="page-title">Info Sekolah</div></div>
        <div class="card"><div class="empty"><div class="empty-ico">🏫</div><div>Belum ada sekolah tujuan</div></div></div>`;
        return;
    }

    try {
        const res = await fetch(`/public/schools/${reg.school_id}`);
        if (!res.ok) throw new Error();
        const d = await res.json();
        const s = d.data || d;

        el.innerHTML = `
        <div class="page-header"><div class="page-title">Info Sekolah</div></div>
        <div class="card">
            <div class="school-banner">
                <div class="school-logo">🏫</div>
                <div style="position:relative;z-index:1">
                    <div class="school-name">${esc(s.name || '—')}</div>
                    <div class="school-type">${esc(s.type || '')} · ${esc(s.city?.name || s.city_name || '—')}</div>
                </div>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item"><div class="info-label">NPSN</div><div class="info-value" style="font-family:monospace">${esc(s.npsn || '—')}</div></div>
                    <div class="info-item"><div class="info-label">Akreditasi</div><div class="info-value">${esc(s.accreditation || '—')}</div></div>
                    <div class="info-item"><div class="info-label">Kepala Sekolah</div><div class="info-value">${esc(s.headmaster || '—')}</div></div>
                    <div class="info-item"><div class="info-label">Telepon</div><div class="info-value">${esc(s.telephone || '—')}</div></div>
                    <div class="info-item"><div class="info-label">Website</div><div class="info-value">${s.web ? `<a href="${esc(s.web)}" target="_blank" style="color:var(--accent)">${esc(s.web)}</a>` : '—'}</div></div>
                    <div class="info-item"><div class="info-label">Instagram</div><div class="info-value">${esc(s.instagram || '—')}</div></div>
                    <div class="info-item full"><div class="info-label">Alamat</div><div class="info-value">${esc(s.location || '—')}</div></div>
                    ${s.vision ? `<div class="info-item full"><div class="info-label">Visi</div><div class="info-value" style="font-weight:400;line-height:1.6;font-size:13px">${esc(s.vision)}</div></div>` : ''}
                </div>
            </div>
        </div>`;
    } catch(e) {
        el.innerHTML += `<div class="card"><div class="empty"><div class="empty-ico">⚠️</div><div>Gagal memuat info sekolah</div></div></div>`;
    }
}

// ─── PEMBAYARAN (FIXED) ───────────────────────────────
// State untuk halaman pembayaran
let _payState = {
    regId: null,
    existingProofs: [],   // semua proof dari server
    activeProof: null,    // proof yang sedang aktif (pending/verified)
    editMode: false,      // true = sedang ganti file
    selectedFile: null,
};

async function renderPembayaran() {
    const el = document.getElementById('mainContent');
    const reg = S.registrations[0];

    if (!reg) {
        el.innerHTML = `
        <div class="page-header"><div class="page-title">Bukti Pembayaran</div></div>
        <div class="card"><div class="empty">
            <div class="empty-ico">💳</div>
            <div style="font-size:15px;font-weight:600;margin-bottom:8px;">Belum ada pendaftaran</div>
            <p style="font-size:13px;color:var(--text-m)">Upload bukti pembayaran tersedia setelah Anda mendaftar ke sekolah.</p>
        </div></div>`;
        return;
    }

    // Reset state
    _payState = { regId: reg.id, existingProofs: [], activeProof: null, editMode: false, selectedFile: null };

    el.innerHTML = `
    <div class="page-header">
        <div class="page-title">Bukti Pembayaran</div>
        <div class="breadcrumb">Upload dan pantau verifikasi bukti pembayaran pendaftaran ke <strong>${esc(reg.school?.name || 'sekolah tujuan')}</strong></div>
    </div>

    <!-- Info Banner -->
    <div style="background:var(--info-bg);border:1px solid #bfdbfe;border-radius:10px;padding:12px 16px;font-size:13px;color:#1d4ed8;margin-bottom:16px;display:flex;align-items:center;gap:10px;">
        <span style="font-size:18px;flex-shrink:0;">ℹ️</span>
        <span>Kamu hanya dapat mengirimkan <strong>satu bukti pembayaran</strong>. Jika ditolak atau ingin mengganti, gunakan tombol <strong>Ganti File</strong>.</span>
    </div>

    <!-- Bukti saat ini -->
    <div class="card">
        <div class="card-head">
            <div class="card-title">📎 Bukti Pembayaran Kamu</div>
            <div id="proofStatusBadge"></div>
        </div>
        <div id="proofSection" style="padding:0;">
            <!-- Dirender oleh JS -->
            <div style="padding:32px;text-align:center;">
                <div style="width:24px;height:24px;border:2px solid #e2e8f0;border-top-color:#3b82f6;border-radius:50%;animation:spin .6s linear infinite;margin:0 auto 10px;"></div>
                <div style="color:var(--text-m);font-size:13px;">Memuat...</div>
            </div>
        </div>
    </div>

    <style>@keyframes spin{to{transform:rotate(360deg)}}</style>`;

    // Load proofs
    await refreshProofSection(reg.id);
}

async function refreshProofSection(regId) {
    try {
        const res  = await fetch(`${API}/payment-proof?registration_id=${regId}`, { headers: HDR });
        const data = await res.json();
        _payState.existingProofs = data.data || [];
    } catch(e) {
        _payState.existingProofs = [];
    }

    // Tentukan proof aktif: verified > pending > rejected (ambil terbaru)
    const proofs = _payState.existingProofs;
    const verified = proofs.find(p => p.status === 'verified');
    const pending  = proofs.find(p => p.status === 'pending');
    const rejected = proofs.filter(p => p.status === 'rejected');

    _payState.activeProof = verified || pending || (rejected[0] || null);

    renderProofSection();
}

function renderProofSection() {
    const section = document.getElementById('proofSection');
    const badge   = document.getElementById('proofStatusBadge');
    if (!section) return;

    const ap = _payState.activeProof;

    // ── Belum ada proof sama sekali ──
    if (!ap) {
        if (badge) badge.innerHTML = `<span style="background:#f1f5f9;color:var(--text-m);border-radius:20px;padding:4px 12px;font-size:11px;font-weight:700;">Belum ada bukti</span>`;
        section.innerHTML = renderUploadForm(false);
        return;
    }

    // ── Ada proof ──
    const statusMap = {
        pending:  { bg:'#fef3c7', color:'#92400e', icon:'⏳', label:'Menunggu Verifikasi', desc:'Buktimu sedang direview oleh admin sekolah. Mohon tunggu.' },
        verified: { bg:'#ecfdf5', color:'#065f46', icon:'✅', label:'Terverifikasi',        desc:'Bukti pembayaranmu sudah diverifikasi oleh admin sekolah.' },
        rejected: { bg:'#fef2f2', color:'#dc2626', icon:'❌', label:'Ditolak',              desc:'Buktimu ditolak. Silakan upload ulang dengan file yang benar.' },
    };
    const st = statusMap[ap.status] || statusMap.pending;
    const date = ap.created_at ? new Date(ap.created_at).toLocaleDateString('id-ID',{day:'2-digit',month:'long',year:'numeric'}) : '—';
    const byLabel = ap.uploaded_by === 'admin' ? '👤 Diupload oleh Admin Sekolah' : '🎓 Diupload olehmu';
    const canEdit = ap.status === 'pending' || ap.status === 'rejected';

    if (badge) badge.innerHTML = `<span style="background:${st.bg};color:${st.color};border-radius:20px;padding:4px 12px;font-size:11px;font-weight:700;">${st.icon} ${st.label}</span>`;

    section.innerHTML = `
    <!-- Proof card -->
    <div style="padding:16px;">
        <div class="proof-card proof-${ap.status}">
            <div style="width:44px;height:44px;border-radius:10px;background:${st.bg};display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;">${ap.file_name?.match(/\.(jpg|jpeg|png)$/i) ? '🖼️' : '📄'}</div>
            <div style="flex:1;min-width:0;">
                <a href="${esc(ap.file_url)}" target="_blank"
                   style="font-weight:700;font-size:14px;color:var(--accent);text-decoration:none;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                    ${esc(ap.file_name)}
                </a>
                <div style="font-size:12px;color:var(--text-m);margin-top:3px;">${byLabel} · ${date}</div>
                ${ap.notes ? `<div style="font-size:12px;color:var(--text-s);margin-top:4px;">📝 ${esc(ap.notes)}</div>` : ''}
            </div>
            <div style="flex-shrink:0;display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                <a href="${esc(ap.file_url)}" target="_blank"
                   style="display:inline-flex;align-items:center;gap:5px;padding:7px 13px;background:#f1f5f9;border:1px solid var(--border);border-radius:8px;font-size:12px;font-weight:600;color:var(--text-s);text-decoration:none;">
                    👁 Lihat File
                </a>
                ${canEdit ? `
                <button onclick="toggleEditMode()" id="btnToggleEdit"
                    style="display:inline-flex;align-items:center;gap:5px;padding:7px 13px;background:var(--warning-bg);border:1px solid #fde68a;border-radius:8px;font-size:12px;font-weight:600;color:#92400e;cursor:pointer;border:1px solid #fde68a;">
                    ✏️ Ganti File
                </button>` : ''}
            </div>
        </div>

        <!-- Status info banner -->
        <div style="margin-top:12px;background:${st.bg};border-radius:9px;padding:11px 14px;font-size:13px;color:${st.color};display:flex;align-items:center;gap:8px;">
            <span style="font-size:16px;">${st.icon}</span>
            <span>${st.desc}</span>
        </div>

        ${ap.status === 'rejected' && ap.verifier_name ? `
        <div style="margin-top:8px;background:var(--error-bg);border-radius:8px;padding:10px 13px;font-size:12px;color:var(--error);">
            Ditolak oleh ${esc(ap.verifier_name)}
        </div>` : ''}
    </div>

    <!-- Edit/Ganti file form (tersembunyi default) -->
    <div id="editUploadWrap" style="display:${_payState.editMode ? '' : 'none'};border-top:1px solid var(--border);padding:16px;">
        <div style="font-size:13px;font-weight:700;color:var(--text-s);margin-bottom:12px;display:flex;align-items:center;gap:6px;">
            ✏️ Ganti Bukti Pembayaran
            <span style="font-size:11px;font-weight:400;color:var(--text-m)">— File lama akan digantikan dengan file baru</span>
        </div>
        ${renderUploadForm(true)}
    </div>

    <!-- Riwayat lainnya (jika ada lebih dari 1) -->
    ${_payState.existingProofs.length > 1 ? renderProofHistory() : ''}`;
}

function renderUploadForm(isEdit) {
    return `
    <div id="uploadArea">
        <div class="upload-zone" id="uploadZone"
             onclick="document.getElementById('payFileInput').click()"
             ondragover="event.preventDefault();this.classList.add('drag-over')"
             ondragleave="this.classList.remove('drag-over')"
             ondrop="handlePayDrop(event)">
            <div style="font-size:36px;margin-bottom:8px;">📎</div>
            <div style="font-weight:600;color:#475569;margin-bottom:4px;">${isEdit ? 'Pilih file pengganti' : 'Klik atau seret file ke sini'}</div>
            <div style="font-size:12px;color:#94a3b8;">JPG, PNG, PDF — Maks. 5 MB</div>
        </div>
        <input type="file" id="payFileInput" accept=".jpg,.jpeg,.png,.pdf" style="display:none" onchange="previewPayFile(this)">

        <div id="payFilePreview" style="display:none;margin-top:12px;">
            <div style="display:flex;align-items:center;gap:12px;background:#f1f5f9;border-radius:10px;padding:12px 14px;">
                <div style="font-size:28px;" id="payPreviewIcon">📄</div>
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:600;font-size:13px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" id="payPreviewName">—</div>
                    <div style="font-size:11px;color:#94a3b8;" id="payPreviewSize">—</div>
                </div>
                <button onclick="clearPayFile()" style="background:none;border:none;cursor:pointer;color:#94a3b8;font-size:18px;padding:4px;">✕</button>
            </div>
            <div style="margin-top:10px;">
                <label style="font-size:12px;font-weight:600;color:#475569;display:block;margin-bottom:4px;">Catatan (opsional)</label>
                <input type="text" id="payUploadNotes" placeholder="Misal: Transfer BCA tgl 28 Maret 2026" class="form-input" style="font-size:13px;">
            </div>
            <div style="display:flex;gap:8px;margin-top:12px;">
                <button onclick="doSubmitPayment()" id="btnSubmitPay"
                    style="flex:1;padding:11px;background:var(--accent);color:white;border:none;border-radius:9px;font-size:14px;font-weight:600;cursor:pointer;transition:all .2s;">
                    ${isEdit ? '🔄 Ganti Bukti Pembayaran' : '📤 Kirim Bukti Pembayaran'}
                </button>
                ${isEdit ? `<button onclick="cancelEditMode()" style="padding:11px 16px;background:var(--bg);color:var(--text-s);border:1px solid var(--border);border-radius:9px;font-size:13px;font-weight:600;cursor:pointer;">Batal</button>` : ''}
            </div>
        </div>
        <div id="payUploadMsg" style="display:none;margin-top:12px;"></div>
    </div>`;
}

function renderProofHistory() {
    const others = _payState.existingProofs.slice(1);
    const statusMap = {
        pending:  ['#fef3c7','#92400e','⏳'],
        verified: ['#ecfdf5','#065f46','✅'],
        rejected: ['#fef2f2','#dc2626','❌'],
    };
    return `
    <div style="border-top:1px solid var(--border);padding:12px 16px;">
        <div style="font-size:11px;font-weight:700;color:var(--text-m);text-transform:uppercase;letter-spacing:.08em;margin-bottom:10px;">Riwayat Upload Sebelumnya</div>
        ${others.map(p => {
            const [bg,color,icon] = statusMap[p.status] || statusMap.pending;
            const date = p.created_at ? new Date(p.created_at).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) : '—';
            return `<div style="display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid var(--border);">
                <a href="${esc(p.file_url)}" target="_blank" style="flex:1;font-size:12px;color:var(--accent);font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;text-decoration:none;">📎 ${esc(p.file_name)}</a>
                <span style="font-size:11px;color:var(--text-m);">${date}</span>
                <span style="background:${bg};color:${color};border-radius:12px;padding:2px 8px;font-size:10px;font-weight:700;">${icon}</span>
            </div>`;
        }).join('')}
    </div>`;
}

function toggleEditMode() {
    _payState.editMode = true;
    _payState.selectedFile = null;
    const wrap = document.getElementById('editUploadWrap');
    if (wrap) {
        wrap.style.display = '';
        wrap.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
    const btn = document.getElementById('btnToggleEdit');
    if (btn) btn.style.display = 'none';
}

function cancelEditMode() {
    _payState.editMode = false;
    _payState.selectedFile = null;
    // Re-render section tanpa reload dari server
    renderProofSection();
}

function previewPayFile(input) {
    const file = input.files[0];
    if (!file) return;
    _payState.selectedFile = file;
    const isImg = file.type.startsWith('image/');
    document.getElementById('payPreviewIcon').textContent = isImg ? '🖼️' : '📄';
    document.getElementById('payPreviewName').textContent = file.name;
    document.getElementById('payPreviewSize').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
    document.getElementById('payFilePreview').style.display = '';
}

function handlePayDrop(event) {
    event.preventDefault();
    const zone = document.getElementById('uploadZone');
    if (zone) zone.classList.remove('drag-over');
    const file = event.dataTransfer.files[0];
    if (!file) return;
    const dt = new DataTransfer();
    dt.items.add(file);
    const input = document.getElementById('payFileInput');
    input.files = dt.files;
    previewPayFile(input);
}

function clearPayFile() {
    _payState.selectedFile = null;
    const inp = document.getElementById('payFileInput');
    if (inp) inp.value = '';
    const prev = document.getElementById('payFilePreview');
    if (prev) prev.style.display = 'none';
}

async function doSubmitPayment() {
    if (!_payState.selectedFile || !_payState.regId) return;

    const btn = document.getElementById('btnSubmitPay');
    const msg = document.getElementById('payUploadMsg');
    if (btn) { btn.disabled = true; btn.textContent = '⏳ Mengunggah...'; }
    if (msg) msg.style.display = 'none';

    try {
        const isEdit = !!_payState.activeProof;

        // Jika edit: hapus proof lama (hanya jika bukan verified)
        if (isEdit && _payState.activeProof.status !== 'verified') {
            const delRes = await fetch(`${API}/payment-proof/${_payState.activeProof.id}`, {
                method: 'DELETE',
                headers: HDR,
            });
            if (!delRes.ok) {
                const delData = await delRes.json().catch(() => ({}));
                throw new Error(delData.message || 'Gagal menghapus bukti lama');
            }
        }

        // Upload file baru
        const form = new FormData();
        form.append('student_registration_id', _payState.regId);
        form.append('file', _payState.selectedFile);
        const notes = document.getElementById('payUploadNotes')?.value;
        if (notes) form.append('notes', notes);

        const res = await fetch(`${API}/payment-proof`, {
            method: 'POST',
            headers: { 'Authorization': `Bearer ${TOKEN}`, 'Accept': 'application/json' },
            body: form,
        });
        const d = await res.json();
        if (!res.ok) throw new Error(d.message || 'Gagal upload');

        showToast(isEdit ? 'Bukti berhasil diganti! ✓' : 'Bukti pembayaran berhasil dikirim! ✓', 'success');
        _payState.editMode = false;
        _payState.selectedFile = null;
        await refreshProofSection(_payState.regId);

    } catch(e) {
        if (msg) {
            msg.innerHTML = `<div style="background:var(--error-bg);border:1px solid #fecaca;border-radius:9px;padding:12px 14px;font-size:13px;color:var(--error);">⚠️ ${e.message}</div>`;
            msg.style.display = '';
        }
        showToast(e.message, 'error');
    } finally {
        if (btn) { btn.disabled = false; btn.textContent = _payState.activeProof ? '🔄 Ganti Bukti Pembayaran' : '📤 Kirim Bukti Pembayaran'; }
    }
}

// ─── PROFIL ───────────────────────────────────────────
function renderProfil() {
    const el   = document.getElementById('mainContent');
    const reg  = S.registrations[0];
    const siswa = reg?.student || {};

    el.innerHTML = `
    <div class="page-header"><div class="page-title">Profil Saya</div></div>

    <div class="card">
        <div class="card-head">
            <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,var(--accent-h),var(--accent));display:flex;align-items:center;justify-content:center;font-weight:800;font-size:16px;color:white;flex-shrink:0">${esc((S.user?.name||'S').charAt(0).toUpperCase())}</div>
            <div>
                <div class="card-title">${esc(S.user?.name || '—')}</div>
                <div style="font-size:12px;color:var(--text-m)">${esc(S.user?.email || '—')}</div>
            </div>
        </div>
        <div class="card-body">
            <div class="info-grid">
                <div class="info-item"><div class="info-label">Nama Lengkap</div><div class="info-value">${esc(siswa.nama || S.user?.name || '—')}</div></div>
                <div class="info-item"><div class="info-label">NISN</div><div class="info-value" style="font-family:monospace">${esc(siswa.nisn || '—')}</div></div>
                <div class="info-item"><div class="info-label">Email</div><div class="info-value">${esc(siswa.email || S.user?.email || '—')}</div></div>
                <div class="info-item full"><div class="info-label">Alamat</div><div class="info-value">${esc(siswa.alamat || '—')}</div></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-head"><div class="card-title">🔒 Ganti Password</div></div>
        <div class="card-body">
            <div style="background:var(--warning-bg);border:1px solid #fde68a;border-radius:8px;padding:10px 14px;font-size:12px;color:#92400e;margin-bottom:14px;">
                ⚠️ Password default kamu adalah <strong>123456</strong>. Segera ganti demi keamanan akun!
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <input type="password" class="form-input" id="newPass" placeholder="Min. 8 karakter">
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-input" id="confPass" placeholder="Ulangi password baru">
                </div>
            </div>
            <div style="margin-top:14px">
                <button class="btn btn-primary" onclick="gantiPassword()">Simpan Password Baru</button>
            </div>
        </div>
    </div>`;
}

// ─── HELPER: Progress Steps ───────────────────────────
function buildProgressSteps(st) {
    const steps = [
        { key: '0', label: 'Mendaftar' },
        { key: '1', label: 'Verifikasi' },
        { key: '2', label: 'Seleksi' },
        { key: '3', label: 'Diterima' },
    ];

    const isDitolak = st === '4';
    const order = ['0','1','2','3'];
    const currentIdx = isDitolak ? 3 : order.indexOf(st);
    const pct = isDitolak ? 100 : (currentIdx / (order.length - 1)) * 100;

    const stepsHtml = steps.map((step, i) => {
        let cls = '';
        if (isDitolak && i === 3) cls = 'reject';
        else if (i < currentIdx) cls = 'done';
        else if (i === currentIdx) cls = 'active';

        const icon = cls === 'done' ? '✓' : cls === 'reject' ? '✕' : (i + 1);
        return `<div class="step-item">
            <div class="step-circle ${cls}">${icon}</div>
            <div class="step-name ${cls}">${isDitolak && i===3 ? 'Ditolak' : step.label}</div>
        </div>`;
    }).join('');

    return `<div class="progress-wrap">
        <div class="progress-steps">
            <div class="progress-line"></div>
            <div class="progress-fill" style="width:calc(${pct}% - 36px)"></div>
            ${stepsHtml}
        </div>
    </div>`;
}

// ─── GANTI PASSWORD ───────────────────────────────────
async function gantiPassword() {
    const np = document.getElementById('newPass').value;
    const cp = document.getElementById('confPass').value;
    if (!np || np.length < 8) { showToast('Password minimal 8 karakter', 'error'); return; }
    if (np !== cp) { showToast('Konfirmasi password tidak cocok', 'error'); return; }

    try {
        const res = await fetch(`${API}/users/change-password`, {
            method: 'POST', headers: HDR,
            body: JSON.stringify({ password: np, password_confirmation: cp })
        });
        if (res.ok) {
            showToast('Password berhasil diubah! ✓', 'success');
            document.getElementById('newPass').value = '';
            document.getElementById('confPass').value = '';
        } else {
            const d = await res.json();
            showToast(d.message || 'Gagal mengubah password', 'error');
        }
    } catch(e) { showToast('Koneksi error', 'error'); }
}

// ─── HELPERS ──────────────────────────────────────────
function showToast(msg, type = 'default') {
    const t = document.getElementById('toast');
    t.textContent = msg; t.className = `toast ${type} show`;
    setTimeout(() => t.classList.remove('show'), 3200);
}

function fmtDate(str) {
    try { return new Date(str).toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric' }); }
    catch { return str; }
}

function esc(s) {
    if (s == null) return '—';
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

async function doLogout() {
    try { await fetch(`${API}/logout`, { method: 'POST', headers: HDR }); } catch(e) {}
    localStorage.removeItem('token');
    window.location.href = '/login-siswa';
}
</script>
</body>
</html>