<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP — Dashboard Pimpinan Sekolah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg:#f0f4f8; --sidebar-bg:#fff; --sidebar-w:240px; --header-h:64px;
            --accent:#2563eb; --accent-light:#eff6ff; --accent-hover:#1d4ed8;
            --text:#0f172a; --text-sec:#475569; --text-muted:#94a3b8;
            --border:#e2e8f0; --card:#fff;
            --green:#10b981; --green-bg:#ecfdf5;
            --amber:#f59e0b; --amber-bg:#fffbeb;
            --red:#ef4444;   --red-bg:#fef2f2;
            --blue:#3b82f6;  --blue-bg:#eff6ff;
            --shadow:0 1px 3px rgba(0,0,0,.07);
        }
        html,body{height:100%;font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text);}
        .app{display:flex;height:100vh;overflow:hidden;}

        /* ── Sidebar ── */
        .sidebar{width:var(--sidebar-w);flex-shrink:0;background:var(--sidebar-bg);border-right:1px solid var(--border);display:flex;flex-direction:column;z-index:100;}
        .sidebar-brand{padding:20px;display:flex;align-items:center;gap:12px;border-bottom:1px solid var(--border);}
        .brand-logo{width:36px;height:36px;background:var(--accent);border-radius:10px;display:flex;align-items:center;justify-content:center;font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:13px;color:#fff;flex-shrink:0;}
        .brand-name{font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:700;}
        .brand-sub{font-size:11px;color:var(--text-muted);}
        .sidebar-nav{flex:1;padding:12px 10px;overflow-y:auto;}
        .nav-label{font-size:10px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em;padding:8px 12px 4px;}
        .nav-item{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:8px;color:var(--text-sec);font-size:13px;font-weight:500;cursor:pointer;text-decoration:none;transition:all .15s;margin-bottom:2px;}
        .nav-item:hover{background:var(--bg);color:var(--text);}
        .nav-item.active{background:var(--accent-light);color:var(--accent);font-weight:600;}
        .nav-icon{font-size:15px;width:20px;text-align:center;flex-shrink:0;}
        .sidebar-footer{padding:12px 10px;border-top:1px solid var(--border);}
        .user-card{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:8px;}
        .user-avatar{width:34px;height:34px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;color:#fff;flex-shrink:0;}
        .user-name{font-size:13px;font-weight:600;line-height:1.2;}
        .user-role{font-size:11px;color:var(--text-muted);}
        .logout-btn{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:8px;color:var(--red);font-size:13px;font-weight:500;cursor:pointer;background:none;border:none;width:100%;margin-top:4px;transition:background .15s;}
        .logout-btn:hover{background:var(--red-bg);}

        /* ── Main ── */
        .main{flex:1;display:flex;flex-direction:column;overflow:hidden;}
        .topbar{height:var(--header-h);background:var(--card);border-bottom:1px solid var(--border);display:flex;align-items:center;padding:0 28px;gap:16px;}
        .topbar-title{font-family:'Plus Jakarta Sans',sans-serif;font-size:17px;font-weight:700;flex:1;}
        .school-badge{background:var(--accent-light);color:var(--accent);border-radius:20px;padding:5px 14px;font-size:12px;font-weight:700;display:flex;align-items:center;gap:6px;}
        .content{flex:1;overflow-y:auto;padding:24px 28px;}

        /* ── Stat Cards ── */
        .stat-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-bottom:20px;}
        .stat-card{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:16px 18px;display:flex;flex-direction:column;gap:8px;box-shadow:var(--shadow);}
        .stat-label{font-size:12px;color:var(--text-muted);font-weight:500;}
        .stat-num{font-family:'Plus Jakarta Sans',sans-serif;font-size:26px;font-weight:800;}
        .stat-num.green{color:var(--green);}
        .stat-num.amber{color:var(--amber);}
        .stat-num.red{color:var(--red);}
        .stat-num.blue{color:var(--blue);}

        /* ── Toolbar ── */
        .toolbar{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:14px 18px;display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:16px;box-shadow:var(--shadow);}
        .search-box{flex:1;min-width:200px;display:flex;align-items:center;gap:8px;background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:8px 12px;}
        .search-box input{background:none;border:none;outline:none;font-size:13px;font-family:'DM Sans',sans-serif;width:100%;color:var(--text);}
        select.filter{padding:8px 12px;border:1px solid var(--border);border-radius:8px;background:var(--bg);font-size:13px;font-family:'DM Sans',sans-serif;color:var(--text);outline:none;cursor:pointer;}
        .btn{padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;border:none;transition:all .15s;display:flex;align-items:center;gap:6px;white-space:nowrap;}
        .btn-primary{background:var(--accent);color:#fff;}
        .btn-primary:hover{background:var(--accent-hover);}
        .btn-outline{background:#fff;color:var(--text-sec);border:1px solid var(--border);}
        .btn-outline:hover{background:var(--bg);}
        .btn-green{background:var(--green);color:#fff;}
        .btn-green:hover{background:#059669;}
        .btn:disabled{opacity:.55;cursor:not-allowed;}

        /* ── Table ── */
        .table-card{background:var(--card);border:1px solid var(--border);border-radius:12px;box-shadow:var(--shadow);overflow:hidden;}
        .table-header{padding:14px 18px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;}
        .table-title{font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:700;}
        .table-sub{font-size:12px;color:var(--text-muted);}
        .table-wrap{overflow-x:auto;}
        table{width:100%;border-collapse:collapse;font-size:13px;}
        thead th{padding:10px 14px;text-align:left;font-weight:600;font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;background:#f8fafc;border-bottom:1px solid var(--border);white-space:nowrap;}
        tbody tr{border-bottom:1px solid var(--border);transition:background .1s;}
        tbody tr:last-child{border-bottom:none;}
        tbody tr:hover{background:#f8fafc;}
        td{padding:11px 14px;vertical-align:middle;}
        .td-name{font-weight:600;color:var(--text);}
        .td-mono{font-family:monospace;font-size:12px;color:var(--text-sec);}
        .td-muted{color:var(--text-muted);font-size:12px;}
        .td-detail{font-size:12px;}

        /* ── Badges ── */
        .badge{display:inline-flex;align-items:center;border-radius:20px;padding:3px 10px;font-size:11px;font-weight:700;}
        .b-0{background:#f1f5f9;color:#64748b;}
        .b-1,.b-2{background:var(--blue-bg);color:var(--blue);}
        .b-3{background:var(--green-bg);color:var(--green);}
        .b-4{background:var(--red-bg);color:var(--red);}
        .b-9{background:var(--amber-bg);color:var(--amber);}

        /* ── Pagination ── */
        .pagination{padding:12px 18px;display:flex;align-items:center;justify-content:space-between;border-top:1px solid var(--border);font-size:12px;color:var(--text-muted);gap:10px;flex-wrap:wrap;}
        .pag-btns{display:flex;gap:6px;}
        .pag-btn{padding:5px 12px;border:1px solid var(--border);border-radius:6px;background:#fff;cursor:pointer;font-size:12px;font-weight:500;transition:all .15s;}
        .pag-btn:hover:not(:disabled){background:var(--accent-light);color:var(--accent);border-color:var(--accent);}
        .pag-btn.active{background:var(--accent);color:#fff;border-color:var(--accent);}
        .pag-btn:disabled{opacity:.4;cursor:not-allowed;}

        /* ── Modal ── */
        .modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:500;display:none;align-items:center;justify-content:center;padding:20px;}
        .modal-overlay.open{display:flex;animation:fadeIn .15s ease;}
        @keyframes fadeIn{from{opacity:0}to{opacity:1}}
        .modal{background:var(--card);border-radius:14px;width:100%;max-width:680px;max-height:88vh;display:flex;flex-direction:column;box-shadow:0 20px 60px rgba(0,0,0,.2);}
        .modal-head{padding:18px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-shrink:0;}
        .modal-title{font-family:'Plus Jakarta Sans',sans-serif;font-size:15px;font-weight:700;}
        .modal-close{background:none;border:none;cursor:pointer;font-size:18px;color:var(--text-muted);padding:4px;border-radius:6px;}
        .modal-body{padding:20px;overflow-y:auto;flex:1;}
        .info-section{margin-bottom:20px;}
        .info-section-title{font-size:11px;font-weight:700;color:var(--accent);text-transform:uppercase;letter-spacing:.08em;margin-bottom:10px;padding-bottom:6px;border-bottom:1px solid var(--border);}
        .info-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
        .info-item{display:flex;flex-direction:column;gap:3px;}
        .info-item.full{grid-column:1/-1;}
        .info-label{font-size:11px;color:var(--text-muted);font-weight:500;}
        .info-value{font-size:13px;font-weight:600;color:var(--text);}

        /* ── Skel ── */
        .skel{background:linear-gradient(90deg,#f1f5f9 25%,#e2e8f0 50%,#f1f5f9 75%);background-size:200% 100%;animation:shimmer 1.2s infinite;border-radius:6px;}
        @keyframes shimmer{0%{background-position:200%}100%{background-position:-200%}}
        @keyframes spin{to{transform:rotate(360deg)}}
        .spinning{animation:spin .7s linear infinite;pointer-events:none;}

        /* ── Readonly notice ── */
        .readonly-notice{background:var(--amber-bg);border:1px solid #fde68a;border-radius:9px;padding:10px 14px;font-size:12px;color:#92400e;display:flex;align-items:center;gap:8px;margin-bottom:18px;}

        @media(max-width:900px){
            .stat-grid{grid-template-columns:repeat(3,1fr);}
        }
        @media(max-width:640px){
            .sidebar{display:none;}
            .stat-grid{grid-template-columns:repeat(2,1fr);}
        }
    </style>
</head>
<body>
<div class="app">

<!-- ── Sidebar ── -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">SIP</div>
        <div>
            <div class="brand-name">Portal Pimpinan</div>
            <div class="brand-sub" id="schoolNameSide">Memuat...</div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-label">Menu</div>
        <a class="nav-item active" data-page="dashboard" href="#">
            <span class="nav-icon">📊</span> Dashboard
        </a>
        <a class="nav-item" data-page="siswa" href="#">
            <span class="nav-icon">🎓</span> Data Pendaftar
        </a>
        <a class="nav-item" data-page="diterima" href="#">
            <span class="nav-icon">✅</span> Siswa Diterima
        </a>
        <a class="nav-item" data-page="pembayaran" href="#">
            <span class="nav-icon">💳</span> Bukti Pembayaran
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar" id="sideAvatar">P</div>
            <div>
                <div class="user-name" id="sideUserName">Memuat...</div>
                <div class="user-role">Pimpinan Sekolah</div>
            </div>
        </div>
        <button class="logout-btn" onclick="doLogout()">⏻ Keluar</button>
    </div>
</aside>

<!-- ── Main ── -->
<div class="main">
    <div class="topbar">
        <div class="topbar-title" id="topbarTitle">Dashboard</div>
        <div class="school-badge" id="schoolBadge">🏫 <span id="schoolNameBadge">Memuat...</span></div>
    </div>
    <div class="content" id="mainContent">
        <!-- diisi JS -->
        <div style="display:flex;align-items:center;justify-content:center;height:200px;color:var(--text-muted);font-size:13px;">
            <div style="text-align:center;">
                <div style="width:28px;height:28px;border:3px solid var(--border);border-top-color:var(--accent);border-radius:50%;animation:spin .6s linear infinite;margin:0 auto 12px;"></div>
                Memuat data...
            </div>
        </div>
    </div>
</div>

</div><!-- /.app -->

<!-- ── Modal Detail Siswa ── -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal">
        <div class="modal-head">
            <div class="modal-title" id="modalTitle">Detail Pendaftar</div>
            <button class="modal-close" onclick="closeModal()">✕</button>
        </div>
        <div class="modal-body" id="modalBody"></div>
    </div>
</div>

<script>
// ── CONFIG ──────────────────────────────────────────────
const API      = '/api';
const TOKEN    = localStorage.getItem('token') || '';
const HDR      = { 'Authorization': `Bearer ${TOKEN}`, 'Accept': 'application/json' };
const PER_PAGE = 20;

const ST_LABEL = {
    '0':'Menunggu','1':'Verifikasi','2':'Seleksi',
    '3':'Diterima','4':'Ditolak','9':'Lainnya'
};

// State
let S = {
    user: null, school: null,
    allRegs: [], filtered: [],
    currentPage: 1, currentPage2: 1,
    proofs: [],
};

// ── INIT ────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    if (!TOKEN) { window.location.href = '/login-sekolah'; return; }
    initNav();
    loadInit();
});

function initNav() {
    document.querySelectorAll('.nav-item[data-page]').forEach(el => {
        el.addEventListener('click', e => {
            e.preventDefault();
            document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
            el.classList.add('active');
            const titles = { dashboard:'Dashboard', siswa:'Data Pendaftar', diterima:'Siswa Diterima', pembayaran:'Bukti Pembayaran' };
            document.getElementById('topbarTitle').textContent = titles[el.dataset.page] || el.dataset.page;
            ({ dashboard: renderDashboard, siswa: renderSiswa, diterima: renderDiterima, pembayaran: renderPembayaran }[el.dataset.page] || renderDashboard)();
        });
    });
}

async function loadInit() {
    try {
        // Load user
        const ur = await fetch(`${API}/me`, { headers: HDR });
        if (!ur.ok) { window.location.href = '/login-sekolah'; return; }
        const ud = await ur.json();
        S.user = ud.user || ud.data || ud;

        // Update sidebar
        const name = S.user?.name || 'Pimpinan';
        document.getElementById('sideUserName').textContent = name;
        document.getElementById('sideAvatar').textContent =
            name.trim().split(' ').map(w => w[0]).join('').substring(0,2).toUpperCase();

        // Load sekolah
        if (S.user?.school_id) {
            const sr = await fetch(`/public/schools/${S.user.school_id}`);
            if (sr.ok) {
                const sd = await sr.json();
                S.school = sd.data || sd;
                const sname = S.school?.name || 'Sekolah';
                document.getElementById('schoolNameSide').textContent  = sname;
                document.getElementById('schoolNameBadge').textContent = sname;
            }
        }

        // Load semua registrasi sekolah ini
        const rr = await fetch(`${API}/registration?limit=2000&order=id&orderType=desc`, { headers: HDR });
        if (rr.ok) {
            const rd = await rr.json();
            S.allRegs = rd.data || [];
        }

        renderDashboard();
    } catch(e) {
        console.error(e);
        document.getElementById('mainContent').innerHTML =
            `<div style="color:var(--red);padding:20px;font-size:13px;">⚠️ Gagal memuat data: ${e.message}</div>`;
    }
}

// ════════════════════════════════════════════
//  DASHBOARD
// ════════════════════════════════════════════
function renderDashboard() {
    const el = document.getElementById('mainContent');
    const r  = S.allRegs;
    const sv = s => String(s ?? '');

    const total    = r.length;
    const pending  = r.filter(x => sv(x.status) === '0').length;
    const proses   = r.filter(x => ['1','2'].includes(sv(x.status))).length;
    const diterima = r.filter(x => sv(x.status) === '3').length;
    const ditolak  = r.filter(x => sv(x.status) === '4').length;

    el.innerHTML = `
    <div class="readonly-notice">
        👁 Mode Pimpinan — hanya baca. Perubahan status dilakukan oleh Admin Sekolah.
    </div>

    <!-- Stat cards -->
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-label">Total Pendaftar</div>
            <div class="stat-num">${total}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Menunggu</div>
            <div class="stat-num amber">${pending}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Diproses</div>
            <div class="stat-num blue">${proses}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Diterima</div>
            <div class="stat-num green">${diterima}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Ditolak</div>
            <div class="stat-num red">${ditolak}</div>
        </div>
    </div>

    <!-- Pendaftar terbaru -->
    <div class="table-card">
        <div class="table-header">
            <div>
                <div class="table-title">📋 Pendaftar Terbaru</div>
                <div class="table-sub">10 pendaftaran terakhir</div>
            </div>
            <button class="btn btn-outline" onclick="navTo('siswa')">Lihat Semua →</button>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr>
                    <th>#</th><th>Nama Siswa</th><th>NISN</th>
                    <th>Asal Sekolah</th><th>Gelombang</th><th>Status</th><th>Aksi</th>
                </tr></thead>
                <tbody>
                ${r.slice(0,10).map((reg, i) => `
                <tr>
                    <td class="td-muted">${i+1}</td>
                    <td class="td-name">${esc(reg.student?.nama || '—')}</td>
                    <td class="td-mono">${esc(reg.student?.nisn || '—')}</td>
                    <td class="td-detail">${esc(reg.school_origin || '—')}</td>
                    <td>${reg.regForm ? `<span class="badge b-1">${esc(reg.regForm.title)} · ${esc(reg.regForm.ta)}</span>` : '—'}</td>
                    <td>${statusBadge(reg.status)}</td>
                    <td><button class="btn btn-outline" style="padding:5px 10px;font-size:11px;" onclick="openDetail(${reg.id})">👁 Detail</button></td>
                </tr>`).join('') || '<tr><td colspan="7" style="text-align:center;padding:28px;color:var(--text-muted);">Belum ada data</td></tr>'}
                </tbody>
            </table>
        </div>
    </div>`;
}

// ════════════════════════════════════════════
//  SEMUA PENDAFTAR
// ════════════════════════════════════════════
function renderSiswa() {
    S.filtered = [...S.allRegs];
    S.currentPage = 1;
    _renderSiswaPage();
}

function _renderSiswaPage() {
    const el = document.getElementById('mainContent');

    // Build filter options
    const sekolahs   = [...new Set(S.allRegs.map(r => r.school?.name).filter(Boolean))];
    const gelombangs = [...new Set(S.allRegs.map(r => r.regForm?.title).filter(Boolean))];

    el.innerHTML = `
    <div class="toolbar">
        <div class="search-box">
            🔍<input type="text" id="searchInput" placeholder="Cari nama / NIK / NISN..." oninput="applyFilters()">
        </div>
        <select class="filter" id="fGelombang" onchange="applyFilters()">
            <option value="">Semua Gelombang</option>
            ${gelombangs.map(g => `<option>${esc(g)}</option>`).join('')}
        </select>
        <select class="filter" id="fStatus" onchange="applyFilters()">
            <option value="">Semua Status</option>
            <option value="0">⏳ Menunggu</option>
            <option value="1">🔍 Verifikasi</option>
            <option value="2">📝 Seleksi</option>
            <option value="3">✅ Diterima</option>
            <option value="4">❌ Ditolak</option>
        </select>
        <button class="btn btn-outline" onclick="loadInit().then(renderSiswa)" title="Refresh">🔄</button>
        <button class="btn btn-green" id="btnExport" onclick="exportExcel('semua')">⬇ Export Excel</button>
    </div>

    <div class="table-card">
        <div class="table-header">
            <div>
                <div class="table-title">Data Semua Pendaftar</div>
                <div class="table-sub" id="tableSubLabel">Memuat...</div>
            </div>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr>
                    <th>#</th><th>Nama Siswa</th><th>NIK</th><th>NISN</th>
                    <th>Jenis Kelamin</th><th>Agama</th><th>Asal Sekolah</th>
                    <th>Gelombang</th><th>TA</th><th>Status</th><th>Aksi</th>
                </tr></thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>
        <div class="pagination" id="paginationWrap"></div>
    </div>`;

    applyFilters();
}

function applyFilters() {
    const q   = (document.getElementById('searchInput')?.value || '').toLowerCase();
    const gel = document.getElementById('fGelombang')?.value || '';
    const st  = document.getElementById('fStatus')?.value || '';

    S.filtered = S.allRegs.filter(r => {
        const mq  = !q  || (r.student?.nama||'').toLowerCase().includes(q)
                        || (r.student?.nik||'').includes(q)
                        || (r.student?.nisn||'').includes(q)
                        || (r.school_origin||'').toLowerCase().includes(q);
        const mg  = !gel || (r.regForm?.title||'') === gel;
        const ms  = !st  || String(r.status) === st;
        return mq && mg && ms;
    });

    S.currentPage = 1;
    renderTable();
}

function renderTable() {
    const tbody  = document.getElementById('tableBody');
    const subLbl = document.getElementById('tableSubLabel');
    if (!tbody) return;

    const total  = S.filtered.length;
    const pages  = Math.max(1, Math.ceil(total / PER_PAGE));
    S.currentPage = Math.min(S.currentPage, pages);
    const start  = (S.currentPage - 1) * PER_PAGE;
    const slice  = S.filtered.slice(start, start + PER_PAGE);

    if (subLbl) subLbl.textContent = `${total} pendaftar${S.filtered.length !== S.allRegs.length ? ' (difilter)' : ''}`;

    if (!slice.length) {
        tbody.innerHTML = `<tr><td colspan="11" style="text-align:center;padding:32px;color:var(--text-muted);">Tidak ada data.</td></tr>`;
        renderPagination('paginationWrap', total, 'currentPage');
        return;
    }

    tbody.innerHTML = slice.map((r, i) => `
    <tr>
        <td class="td-muted">${start + i + 1}</td>
        <td class="td-name">${esc(r.student?.nama || '—')}</td>
        <td class="td-mono">${esc(r.student?.nik  || '—')}</td>
        <td class="td-mono">${esc(r.student?.nisn || '—')}</td>
        <td class="td-detail">${esc(r.student?.jk || '—')}</td>
        <td class="td-detail">${esc(r.student?.agama || '—')}</td>
        <td class="td-detail">${esc(r.school_origin || '—')}</td>
        <td>${r.regForm ? `<span class="badge b-1">${esc(r.regForm.title||'')}</span>` : '—'}</td>
        <td class="td-muted">${esc(r.regForm?.ta || '—')}</td>
        <td>${statusBadge(r.status)}</td>
        <td><button class="btn btn-outline" style="padding:5px 10px;font-size:11px;" onclick="openDetail(${r.id})">👁 Detail</button></td>
    </tr>`).join('');

    renderPagination('paginationWrap', total, 'currentPage');
}

// ════════════════════════════════════════════
//  SISWA DITERIMA
// ════════════════════════════════════════════
function renderDiterima() {
    const el = document.getElementById('mainContent');
    const diterima = S.allRegs.filter(r => String(r.status) === '3');
    S.currentPage2 = 1;
    const gelombangs = [...new Set(diterima.map(r => r.regForm?.title).filter(Boolean))];

    el.innerHTML = `
    <div class="toolbar">
        <div class="search-box">
            🔍<input type="text" id="searchInput2" placeholder="Cari nama / NISN..." oninput="applyFiltersDiterima()">
        </div>
        <select class="filter" id="fGel2" onchange="applyFiltersDiterima()">
            <option value="">Semua Gelombang</option>
            ${gelombangs.map(g => `<option>${esc(g)}</option>`).join('')}
        </select>
        <button class="btn btn-green" onclick="exportExcel('diterima')">⬇ Export Excel — Diterima</button>
    </div>

    <div class="table-card">
        <div class="table-header">
            <div>
                <div class="table-title">✅ Siswa Diterima</div>
                <div class="table-sub" id="subDiterima">${diterima.length} siswa diterima</div>
            </div>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr>
                    <th>#</th><th>Nama</th><th>NIK</th><th>NISN</th>
                    <th>Tgl Lahir</th><th>Jenis Kelamin</th><th>Agama</th>
                    <th>No. HP</th><th>Asal Sekolah</th><th>Gelombang</th><th>TA</th><th>Aksi</th>
                </tr></thead>
                <tbody id="tableBodyDiterima"></tbody>
            </table>
        </div>
        <div class="pagination" id="pagDiterima"></div>
    </div>`;

    applyFiltersDiterima();
}

let filteredDiterima = [];
function applyFiltersDiterima() {
    const q   = (document.getElementById('searchInput2')?.value || '').toLowerCase();
    const gel = document.getElementById('fGel2')?.value || '';
    filteredDiterima = S.allRegs.filter(r => String(r.status) === '3').filter(r => {
        const mq = !q || (r.student?.nama||'').toLowerCase().includes(q) || (r.student?.nisn||'').includes(q);
        const mg = !gel || (r.regForm?.title||'') === gel;
        return mq && mg;
    });
    S.currentPage2 = 1;
    renderDiterimaTable();
}

function renderDiterimaTable() {
    const tbody = document.getElementById('tableBodyDiterima');
    if (!tbody) return;
    const total = filteredDiterima.length;
    const pages = Math.max(1, Math.ceil(total / PER_PAGE));
    S.currentPage2 = Math.min(S.currentPage2, pages);
    const start = (S.currentPage2 - 1) * PER_PAGE;
    const slice = filteredDiterima.slice(start, start + PER_PAGE);

    const sub = document.getElementById('subDiterima');
    if (sub) sub.textContent = `${total} siswa diterima`;

    tbody.innerHTML = slice.map((r, i) => `
    <tr>
        <td class="td-muted">${start + i + 1}</td>
        <td class="td-name">${esc(r.student?.nama || '—')}</td>
        <td class="td-mono">${esc(r.student?.nik  || '—')}</td>
        <td class="td-mono">${esc(r.student?.nisn || '—')}</td>
        <td class="td-detail">${esc(r.student?.tanggal_lahir || '—')}</td>
        <td class="td-detail">${esc(r.student?.jk    || '—')}</td>
        <td class="td-detail">${esc(r.student?.agama || '—')}</td>
        <td class="td-mono">${esc(r.student?.no_hp || '—')}</td>
        <td class="td-detail">${esc(r.school_origin || '—')}</td>
        <td>${r.regForm ? `<span class="badge b-3">${esc(r.regForm.title||'')}</span>` : '—'}</td>
        <td class="td-muted">${esc(r.regForm?.ta || '—')}</td>
        <td><button class="btn btn-outline" style="padding:5px 10px;font-size:11px;" onclick="openDetail(${r.id})">👁 Detail</button></td>
    </tr>`).join('') || `<tr><td colspan="12" style="text-align:center;padding:32px;color:var(--text-muted);">Tidak ada data.</td></tr>`;

    renderPagination('pagDiterima', total, 'currentPage2');
}

// ════════════════════════════════════════════
//  BUKTI PEMBAYARAN (read-only)
// ════════════════════════════════════════════
async function renderPembayaran() {
    const el = document.getElementById('mainContent');
    el.innerHTML = `
    <div class="readonly-notice">👁 Hanya baca — verifikasi dilakukan oleh Admin Sekolah.</div>
    <div class="table-card">
        <div class="table-header">
            <div class="table-title">💳 Bukti Pembayaran</div>
        </div>
        <div id="proofWrap" style="padding:16px;">
            <div style="text-align:center;padding:24px;color:var(--text-muted);">
                <div style="width:24px;height:24px;border:2px solid var(--border);border-top-color:var(--accent);border-radius:50%;animation:spin .6s linear infinite;margin:0 auto 10px;"></div>
                Memuat...
            </div>
        </div>
    </div>`;

    try {
        const res  = await fetch(`${API}/payment-proof`, { headers: HDR });
        const data = await res.json();
        const list = data.data || [];
        const wrap = document.getElementById('proofWrap');

        if (!list.length) {
            wrap.innerHTML = `<div style="text-align:center;padding:32px;color:var(--text-muted);">📭 Belum ada bukti pembayaran.</div>`;
            return;
        }

        const stMap = {
            pending:  ['#fef3c7','#92400e','⏳','Menunggu'],
            verified: ['#ecfdf5','#065f46','✅','Terverifikasi'],
            rejected: ['#fef2f2','#dc2626','❌','Ditolak'],
        };

        wrap.innerHTML = `<div style="overflow-x:auto;"><table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead><tr style="background:#f8fafc;border-bottom:1px solid var(--border);">
                <th style="padding:10px 14px;text-align:left;font-size:11px;color:var(--text-muted);font-weight:600;">#</th>
                <th style="padding:10px 14px;text-align:left;font-size:11px;color:var(--text-muted);font-weight:600;">Nama Siswa</th>
                <th style="padding:10px 14px;text-align:left;font-size:11px;color:var(--text-muted);font-weight:600;">File</th>
                <th style="padding:10px 14px;text-align:left;font-size:11px;color:var(--text-muted);font-weight:600;">Catatan</th>
                <th style="padding:10px 14px;text-align:left;font-size:11px;color:var(--text-muted);font-weight:600;">Diupload</th>
                <th style="padding:10px 14px;text-align:left;font-size:11px;color:var(--text-muted);font-weight:600;">Status</th>
            </tr></thead>
            <tbody>
            ${list.map((p, i) => {
                const [bg, color, icon, lbl] = stMap[p.status] || stMap.pending;
                const reg = S.allRegs.find(r => r.id === p.student_registration_id);
                const nama = reg?.student?.nama || `Reg #${p.student_registration_id}`;
                const tgl  = p.created_at ? new Date(p.created_at).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) : '—';
                return `<tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:10px 14px;color:var(--text-muted);">${i+1}</td>
                    <td style="padding:10px 14px;font-weight:600;">${esc(nama)}</td>
                    <td style="padding:10px 14px;">
                        <a href="${esc(p.file_url)}" target="_blank" style="color:var(--accent);text-decoration:none;font-size:12px;">📎 ${esc(p.file_name)}</a>
                    </td>
                    <td style="padding:10px 14px;font-size:12px;color:var(--text-muted);">${esc(p.notes || '—')}</td>
                    <td style="padding:10px 14px;font-size:12px;">${p.uploaded_by === 'admin' ? '👤 Admin' : '🎓 Siswa'}<br><span style="color:var(--text-muted);">${tgl}</span></td>
                    <td style="padding:10px 14px;"><span style="background:${bg};color:${color};border-radius:20px;padding:3px 10px;font-size:11px;font-weight:700;">${icon} ${lbl}</span></td>
                </tr>`;
            }).join('')}
            </tbody>
        </table></div>`;
    } catch(e) {
        document.getElementById('proofWrap').innerHTML = `<div style="color:var(--red);padding:16px;font-size:13px;">⚠️ Gagal memuat.</div>`;
    }
}

// ════════════════════════════════════════════
//  DETAIL MODAL
// ════════════════════════════════════════════
function openDetail(regId) {
    const r = S.allRegs.find(x => x.id === regId);
    if (!r) return;
    const s   = r.student  || {};
    const sch = r.school   || {};
    const f   = r.regForm  || {};

    document.getElementById('modalTitle').textContent = `Detail — ${s.nama || 'Siswa'}`;
    document.getElementById('modalBody').innerHTML = `
    <!-- Data Diri -->
    <div class="info-section">
        <div class="info-section-title">Data Diri Siswa</div>
        <div class="info-grid">
            <div class="info-item"><div class="info-label">Nama Lengkap</div><div class="info-value">${esc(s.nama)}</div></div>
            <div class="info-item"><div class="info-label">NISN</div><div class="info-value" style="font-family:monospace">${esc(s.nisn)}</div></div>
            <div class="info-item"><div class="info-label">NIK</div><div class="info-value" style="font-family:monospace">${esc(s.nik)}</div></div>
            <div class="info-item"><div class="info-label">Tempat, Tgl Lahir</div><div class="info-value">${esc(s.tempat_lahir)}, ${esc(s.tanggal_lahir)}</div></div>
            <div class="info-item"><div class="info-label">Jenis Kelamin</div><div class="info-value">${esc(s.jk)}</div></div>
            <div class="info-item"><div class="info-label">Agama</div><div class="info-value">${esc(s.agama)}</div></div>
            <div class="info-item"><div class="info-label">No. HP</div><div class="info-value">${esc(s.no_hp)}</div></div>
            <div class="info-item"><div class="info-label">Email</div><div class="info-value">${esc(s.email)}</div></div>
            <div class="info-item"><div class="info-label">Kewarganegaraan</div><div class="info-value">${esc(s.kewarganegaraan)}</div></div>
            <div class="info-item"><div class="info-label">Jarak ke Sekolah</div><div class="info-value">${esc(s.jarak_rumah_kesekolah ? s.jarak_rumah_kesekolah + ' km' : '—')}</div></div>
            <div class="info-item full"><div class="info-label">Alamat</div><div class="info-value">${esc([s.alamat,s.kelurahan,s.kecamatan,s.kabupaten,s.provinsi].filter(Boolean).join(', ') || '—')}</div></div>
        </div>
    </div>
    <!-- Orang Tua -->
    <div class="info-section">
        <div class="info-section-title">Data Orang Tua / Wali</div>
        <div class="info-grid">
            <div class="info-item"><div class="info-label">Nama Ayah</div><div class="info-value">${esc(s.nama_ayah)}</div></div>
            <div class="info-item"><div class="info-label">Pekerjaan Ayah</div><div class="info-value">${esc(s.pekerjaan_ayah)}</div></div>
            <div class="info-item"><div class="info-label">Nama Ibu</div><div class="info-value">${esc(s.nama_ibu)}</div></div>
            <div class="info-item"><div class="info-label">Pekerjaan Ibu</div><div class="info-value">${esc(s.pekerjaan_ibu)}</div></div>
            ${s.nama_wali ? `<div class="info-item"><div class="info-label">Nama Wali</div><div class="info-value">${esc(s.nama_wali)}</div></div><div class="info-item"><div class="info-label">Pekerjaan Wali</div><div class="info-value">${esc(s.pekerjaan_wali)}</div></div>` : ''}
        </div>
    </div>
    <!-- Pendaftaran -->
    <div class="info-section">
        <div class="info-section-title">Data Pendaftaran</div>
        <div class="info-grid">
            <div class="info-item"><div class="info-label">Sekolah Tujuan</div><div class="info-value">${esc(sch.name)}</div></div>
            <div class="info-item"><div class="info-label">Asal Sekolah</div><div class="info-value">${esc(r.school_origin)}</div></div>
            <div class="info-item"><div class="info-label">Gelombang</div><div class="info-value">${esc(f.title)}</div></div>
            <div class="info-item"><div class="info-label">Tahun Ajaran</div><div class="info-value">${esc(f.ta)}</div></div>
            <div class="info-item"><div class="info-label">Status</div><div class="info-value">${statusBadge(r.status)}</div></div>
            <div class="info-item"><div class="info-label">Diperbarui</div><div class="info-value">${r.updated_at ? fmtDate(r.updated_at) : '—'}</div></div>
        </div>
    </div>`;

    document.getElementById('modalOverlay').classList.add('open');
}

function closeModal() {
    document.getElementById('modalOverlay').classList.remove('open');
}

// ════════════════════════════════════════════
//  EXPORT EXCEL — LENGKAP
// ════════════════════════════════════════════
function exportExcel(mode = 'semua') {
    const source = mode === 'diterima' ? filteredDiterima : S.filtered;
    if (!source.length) { alert('Tidak ada data untuk diekspor.'); return; }

    const btn = document.getElementById('btnExport');
    if (btn) { btn.disabled = true; btn.textContent = '⏳ Mengekspor...'; }

    try {
        const schoolName = S.school?.name || 'Sekolah';
        const rows = source.map((r, i) => {
            const s = r.student  || {};
            const f = r.regForm  || {};
            const c = r.school   || {};
            return {
                // ── Identitas ──
                'No'                    : i + 1,
                'Nama Lengkap'          : s.nama            || '',
                'NIK'                   : s.nik             || '',
                'NISN'                  : s.nisn            || '',
                'Tempat Lahir'          : s.tempat_lahir    || '',
                'Tanggal Lahir'         : s.tanggal_lahir   || '',
                'Jenis Kelamin'         : s.jk              || '',
                'Agama'                 : s.agama           || '',
                'Kewarganegaraan'       : s.kewarganegaraan || '',
                // ── Kontak ──
                'No. HP'                : s.no_hp           || '',
                'No. Telepon'           : s.no_telepon      || '',
                'Email'                 : s.email           || '',
                // ── Alamat ──
                'Alamat'                : s.alamat          || '',
                'RT'                    : s.rt              || '',
                'RW'                    : s.rw              || '',
                'Dusun'                 : s.dusun           || '',
                'Kelurahan'             : s.kelurahan       || '',
                'Kecamatan'             : s.kecamatan       || '',
                'Kabupaten/Kota'        : s.kabupaten       || '',
                'Provinsi'              : s.provinsi        || '',
                'Kode Pos'              : s.kode_pos        || '',
                'Jenis Tinggal'         : s.jenis_tinggal   || '',
                'Alat Transportasi'     : s.alat_transportasi || '',
                // ── Fisik ──
                'Berat Badan (kg)'      : s.berat_badan     || '',
                'Tinggi Badan (cm)'     : s.tinggi_badan    || '',
                'Kebutuhan Khusus'      : s.kebutuhan_khusus|| '',
                'Anak Ke'               : s.anak_ke         || '',
                'Jml Saudara Kandung'   : s.jml_saudara_kandung || '',
                'Jarak Rumah ke Sekolah (km)': s.jarak_rumah_kesekolah || '',
                // ── Keluarga ──
                'No. KK'                : s.no_kk           || '',
                'NIK Ayah'              : s.nik_ayah        || '',
                'Nama Ayah'             : s.nama_ayah       || '',
                'Tahun Lahir Ayah'      : s.tahun_lahir_ayah || '',
                'Pendidikan Ayah'       : s.jenjang_pendidikan_ayah || '',
                'Pekerjaan Ayah'        : s.pekerjaan_ayah  || '',
                'Penghasilan Ayah'      : s.penghasilan_ayah|| '',
                'NIK Ibu'               : s.nik_ibu         || '',
                'Nama Ibu'              : s.nama_ibu        || '',
                'Tahun Lahir Ibu'       : s.tahun_lahir_ibu || '',
                'Pendidikan Ibu'        : s.jenjang_pendidikan_ibu || '',
                'Pekerjaan Ibu'         : s.pekerjaan_ibu   || '',
                'Penghasilan Ibu'       : s.penghasilan_ibu || '',
                'NIK Wali'              : s.nik_wali        || '',
                'Nama Wali'             : s.nama_wali       || '',
                'Tahun Lahir Wali'      : s.tahun_lahir_wali|| '',
                'Pendidikan Wali'       : s.jenjang_pendidikan_wali || '',
                'Pekerjaan Wali'        : s.pekerjaan_wali  || '',
                'Penghasilan Wali'      : s.penghasilan_wali|| '',
                // ── Pendaftaran ──
                'Sekolah Tujuan'        : c.name            || '',
                'Asal Sekolah'          : r.school_origin   || '',
                'Gelombang'             : f.title           || '',
                'Tahun Ajaran'          : f.ta              || '',
                'Status Pendaftaran'    : ST_LABEL[String(r.status)] || r.status || '',
                'Didaftarkan Oleh'      : regByLabel(r.registered_by),
                'Tgl Pendaftaran'       : r.updated_at ? fmtDate(r.updated_at) : '',
            };
        });

        // Buat workbook dengan 2 sheet
        const wb = XLSX.utils.book_new();

        // Sheet 1: Data lengkap
        const ws1 = XLSX.utils.json_to_sheet(rows);
        ws1['!cols'] = Object.keys(rows[0]).map(k => ({ wch: Math.max(k.length + 2, 14) }));
        XLSX.utils.book_append_sheet(wb, ws1, mode === 'diterima' ? 'Siswa Diterima' : 'Semua Pendaftar');

        // Sheet 2: Ringkasan statistik
        const stats = [
            ['LAPORAN PENDAFTARAN SISWA'],
            ['Sekolah', schoolName],
            ['Tanggal Export', new Date().toLocaleDateString('id-ID', {day:'numeric',month:'long',year:'numeric'})],
            [''],
            ['STATUS', 'JUMLAH'],
            ['Total Pendaftar',    S.allRegs.length],
            ['Menunggu',           S.allRegs.filter(r => String(r.status) === '0').length],
            ['Verifikasi/Seleksi', S.allRegs.filter(r => ['1','2'].includes(String(r.status))).length],
            ['Diterima',           S.allRegs.filter(r => String(r.status) === '3').length],
            ['Ditolak',            S.allRegs.filter(r => String(r.status) === '4').length],
        ];
        const ws2 = XLSX.utils.aoa_to_sheet(stats);
        ws2['!cols'] = [{ wch: 26 }, { wch: 14 }];
        XLSX.utils.book_append_sheet(wb, ws2, 'Ringkasan');

        const filename = `${mode === 'diterima' ? 'siswa-diterima' : 'data-pendaftar'}-${schoolName.toLowerCase().replace(/\s+/g,'-')}-${new Date().toISOString().slice(0,10)}.xlsx`;
        XLSX.writeFile(wb, filename);
    } finally {
        if (btn) { btn.disabled = false; btn.innerHTML = '⬇ Export Excel'; }
    }
}

// ════════════════════════════════════════════
//  PAGINATION
// ════════════════════════════════════════════
function renderPagination(wrapperId, total, pageKey) {
    const wrap  = document.getElementById(wrapperId);
    if (!wrap) return;
    const pages = Math.max(1, Math.ceil(total / PER_PAGE));
    const cur   = S[pageKey];
    const start = (cur - 1) * PER_PAGE + 1;
    const end   = Math.min(cur * PER_PAGE, total);

    // Build page numbers (max 5 visible)
    let pagNums = [];
    for (let i = Math.max(1, cur - 2); i <= Math.min(pages, cur + 2); i++) pagNums.push(i);

    wrap.innerHTML = `
    <span>${total ? `${start}–${end} dari ${total}` : 'Tidak ada data'}</span>
    <div class="pag-btns">
        <button class="pag-btn" onclick="changePage('${pageKey}','${wrapperId}',${cur - 1})" ${cur <= 1 ? 'disabled' : ''}>‹</button>
        ${pagNums.map(p => `<button class="pag-btn${p === cur ? ' active' : ''}" onclick="changePage('${pageKey}','${wrapperId}',${p})">${p}</button>`).join('')}
        <button class="pag-btn" onclick="changePage('${pageKey}','${wrapperId}',${cur + 1})" ${cur >= pages ? 'disabled' : ''}>›</button>
    </div>`;
}

function changePage(pageKey, wrapperId, n) {
    S[pageKey] = n;
    if (pageKey === 'currentPage')  renderTable();
    if (pageKey === 'currentPage2') renderDiterimaTable();
}

// ════════════════════════════════════════════
//  HELPERS
// ════════════════════════════════════════════
function navTo(page) {
    const el = document.querySelector(`.nav-item[data-page="${page}"]`);
    if (el) el.click();
}

function statusBadge(s) {
    return `<span class="badge b-${s}">${ST_LABEL[String(s)] || 'Lainnya'}</span>`;
}

function regByLabel(v) {
    return {'1':'Oleh Sekolah','2':'Oleh Orang Tua','9':'Mandiri (Online)'}[String(v)] || String(v) || '—';
}

function fmtDate(str) {
    try { return new Date(str).toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric' }); }
    catch { return str; }
}

function esc(s) {
    if (s == null) return '—';
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

function doLogout() {
    fetch(`${API}/logout`, { method:'POST', headers:HDR }).catch(()=>{});
    localStorage.removeItem('token');
    window.location.href = '/login-sekolah';
}

// Close modal on backdrop click
document.getElementById('modalOverlay').addEventListener('click', e => {
    if (e.target === document.getElementById('modalOverlay')) closeModal();
});
</script>
</body>
</html>