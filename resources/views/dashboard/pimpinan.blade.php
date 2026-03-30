<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP — Dashboard Pimpinan</title>
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
            --success:#10b981; --success-bg:#ecfdf5;
            --error:#ef4444;   --error-bg:#fef2f2;
            --warning:#f59e0b; --warning-bg:#fffbeb;
            --info:#3b82f6;    --info-bg:#eff6ff;
            --shadow:0 1px 3px rgba(0,0,0,.07);
            --shadow-md:0 4px 6px -1px rgba(0,0,0,.07);
        }
        html,body{height:100%;font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text);}
        .app{display:flex;height:100vh;overflow:hidden;}

        /* Sidebar */
        .sidebar{width:var(--sidebar-w);flex-shrink:0;background:var(--sidebar-bg);border-right:1px solid var(--border);display:flex;flex-direction:column;z-index:100;}
        .sidebar-brand{padding:20px;display:flex;align-items:center;gap:12px;border-bottom:1px solid var(--border);}
        .brand-logo{width:36px;height:36px;background:var(--accent);border-radius:10px;display:flex;align-items:center;justify-content:center;font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:13px;color:#fff;}
        .brand-name{font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:700;}
        .brand-sub{font-size:11px;color:var(--text-muted);}
        .sidebar-nav{flex:1;padding:12px 10px;overflow-y:auto;}
        .nav-item{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:8px;color:var(--text-sec);font-size:14px;font-weight:500;cursor:pointer;text-decoration:none;transition:all .15s;margin-bottom:2px;}
        .nav-item:hover{background:var(--bg);color:var(--text);}
        .nav-item.active{background:var(--accent-light);color:var(--accent);font-weight:600;}
        .nav-icon{font-size:16px;width:20px;text-align:center;flex-shrink:0;}
        .sidebar-footer{padding:12px 10px;border-top:1px solid var(--border);}
        .user-card{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:8px;}
        .user-avatar{width:36px;height:36px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;color:#fff;flex-shrink:0;}
        .user-name{font-size:13px;font-weight:600;}
        .user-role{font-size:11px;color:var(--text-muted);}
        .logout-btn{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:8px;color:var(--error);font-size:13px;font-weight:500;cursor:pointer;background:none;border:none;width:100%;margin-top:4px;transition:background .15s;}
        .logout-btn:hover{background:var(--error-bg);}

        /* Main */
        .main{flex:1;display:flex;flex-direction:column;overflow:hidden;}
        .topbar{height:var(--header-h);background:var(--card);border-bottom:1px solid var(--border);display:flex;align-items:center;padding:0 28px;gap:16px;}
        .topbar-title{font-family:'Plus Jakarta Sans',sans-serif;font-size:17px;font-weight:700;}
        .topbar-sub{font-size:13px;color:var(--text-muted);margin-top:1px;}
        .topbar-spacer{flex:1;}
        .btn-refresh{display:flex;align-items:center;gap:6px;padding:7px 14px;background:var(--bg);color:var(--text-sec);border:1px solid var(--border);border-radius:8px;font-family:'DM Sans',sans-serif;font-size:13px;font-weight:500;cursor:pointer;transition:all .15s;}
        .btn-refresh:hover{border-color:var(--accent);color:var(--accent);}
        .btn-refresh.spinning .spin{display:inline-block;animation:spin .6s linear infinite;}
        @keyframes spin{to{transform:rotate(360deg);}}
        .content{flex:1;overflow-y:auto;padding:24px 28px;}

        /* Stat Cards */
        .stat-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-bottom:24px;}
        .stat-card{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:18px;box-shadow:var(--shadow);transition:box-shadow .2s;position:relative;overflow:hidden;}
        .stat-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;}
        .stat-card.s-total::before  {background:var(--accent);}
        .stat-card.s-pending::before{background:var(--warning);}
        .stat-card.s-verif::before  {background:var(--info);}
        .stat-card.s-terima::before {background:var(--success);}
        .stat-card.s-tolak::before  {background:var(--error);}
        .stat-card:hover{box-shadow:var(--shadow-md);}
        .stat-top{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:10px;}
        .stat-label{font-size:12px;color:var(--text-sec);font-weight:500;}
        .stat-icon{width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:16px;}
        .stat-icon.blue  {background:var(--info-bg);}
        .stat-icon.green {background:var(--success-bg);}
        .stat-icon.red   {background:var(--error-bg);}
        .stat-icon.yellow{background:var(--warning-bg);}
        .stat-num{font-family:'Plus Jakarta Sans',sans-serif;font-size:26px;font-weight:800;}
        .stat-sub{font-size:11px;color:var(--text-muted);margin-top:3px;}

        /* Table Card */
        .table-card{background:var(--card);border:1px solid var(--border);border-radius:12px;box-shadow:var(--shadow);overflow:hidden;}
        .table-header{padding:18px 20px;border-bottom:1px solid var(--border);}
        .table-title{font-family:'Plus Jakarta Sans',sans-serif;font-size:15px;font-weight:700;}
        .table-sub{font-size:13px;color:var(--text-muted);margin-top:2px;}
        .table-controls{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-top:14px;}
        .filters{display:flex;gap:10px;flex-wrap:wrap;align-items:center;}
        .search-box{display:flex;align-items:center;gap:8px;background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:8px 12px;transition:border-color .2s;}
        .search-box:focus-within{border-color:var(--accent);}
        .search-box input{border:none;background:transparent;outline:none;font-family:'DM Sans',sans-serif;font-size:13px;color:var(--text);width:220px;}
        .search-box input::placeholder{color:var(--text-muted);}
        .filter-select{padding:8px 28px 8px 12px;border:1px solid var(--border);border-radius:8px;background:var(--bg);font-family:'DM Sans',sans-serif;font-size:13px;color:var(--text);outline:none;cursor:pointer;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 10px center;transition:border-color .2s;}
        .filter-select:focus{border-color:var(--accent);}
        .btn-export{display:flex;align-items:center;gap:8px;padding:8px 16px;background:var(--success);color:#fff;border:none;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;cursor:pointer;transition:all .2s;white-space:nowrap;}
        .btn-export:hover{background:#059669;}
        .btn-export:disabled{opacity:.6;cursor:not-allowed;}

        /* Table */
        .table-wrap{overflow-x:auto;}
        table{width:100%;border-collapse:collapse;}
        thead th{padding:11px 16px;text-align:left;font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;background:var(--bg);border-bottom:1px solid var(--border);white-space:nowrap;}
        tbody tr{border-bottom:1px solid var(--border);transition:background .1s;}
        tbody tr:last-child{border-bottom:none;}
        tbody tr:hover{background:#f8fafc;}
        tbody td{padding:12px 16px;font-size:13px;color:var(--text-sec);vertical-align:middle;}
        .td-name{font-weight:600;color:var(--text);font-size:14px;}

        /* Badges */
        .badge{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;white-space:nowrap;}
        .badge::before{content:'';width:5px;height:5px;border-radius:50%;flex-shrink:0;}
        .b-0{background:var(--warning-bg);color:#92400e;} .b-0::before{background:var(--warning);}
        .b-1{background:var(--info-bg);color:#1d4ed8;}    .b-1::before{background:var(--info);}
        .b-2{background:#f3e8ff;color:#7c3aed;}           .b-2::before{background:#8b5cf6;}
        .b-3{background:var(--success-bg);color:#065f46;} .b-3::before{background:var(--success);}
        .b-4{background:var(--error-bg);color:#991b1b;}   .b-4::before{background:var(--error);}
        .b-9{background:var(--bg);color:var(--text-muted);}.b-9::before{background:var(--text-muted);}

        /* Pagination */
        .table-footer{padding:14px 20px;display:flex;align-items:center;justify-content:space-between;border-top:1px solid var(--border);font-size:13px;color:var(--text-muted);flex-wrap:wrap;gap:10px;}
        .pagination{display:flex;gap:4px;}
        .page-btn{min-width:32px;height:32px;border-radius:6px;padding:0 6px;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:500;cursor:pointer;border:1px solid var(--border);background:#fff;color:var(--text-sec);transition:all .15s;}
        .page-btn:hover:not(:disabled){border-color:var(--accent);color:var(--accent);}
        .page-btn.active{background:var(--accent);color:#fff;border-color:var(--accent);}
        .page-btn:disabled{opacity:.4;cursor:not-allowed;}

        /* Modal */
        .modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.4);backdrop-filter:blur(4px);display:flex;align-items:center;justify-content:center;z-index:1000;opacity:0;visibility:hidden;transition:all .2s;}
        .modal-overlay.active{opacity:1;visibility:visible;}
        .modal{background:#fff;border-radius:16px;width:100%;max-width:560px;max-height:90vh;overflow:hidden;transform:scale(.95) translateY(10px);transition:transform .2s;box-shadow:0 20px 60px rgba(0,0,0,.15);}
        .modal-overlay.active .modal{transform:scale(1) translateY(0);}
        .modal-header{padding:20px 24px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;}
        .modal-title{font-family:'Plus Jakarta Sans',sans-serif;font-size:17px;font-weight:700;}
        .modal-close{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;background:var(--bg);border:none;cursor:pointer;color:var(--text-muted);font-size:16px;transition:background .15s;}
        .modal-close:hover{background:var(--border);}
        .modal-body{padding:24px;overflow-y:auto;max-height:calc(90vh - 70px);}
        .info-section{margin-bottom:20px;}
        .info-section-title{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--accent);margin-bottom:10px;padding-bottom:6px;border-bottom:1px solid var(--border);}
        .info-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
        .info-item.full{grid-column:1/-1;}
        .info-label{font-size:11px;color:var(--text-muted);margin-bottom:2px;}
        .info-value{font-size:13px;font-weight:600;color:var(--text);}

        /* Empty/Loading */
        .empty-state{text-align:center;padding:60px 20px;color:var(--text-muted);}
        .empty-icon{font-size:40px;margin-bottom:12px;opacity:.4;}
        .empty-title{font-size:15px;font-weight:600;color:var(--text-sec);margin-bottom:4px;}
        .loading-row td{text-align:center;padding:48px;color:var(--text-muted);}

        @media(max-width:1300px){.stat-grid{grid-template-columns:repeat(3,1fr);}}
        @media(max-width:900px) {.stat-grid{grid-template-columns:repeat(2,1fr);}}
        @media(max-width:768px) {.sidebar{display:none;}}
    </style>
</head>
<body>
<div class="app">

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo">SIP</div>
            <div>
                <div class="brand-name">SIP Sekolah</div>
                <div class="brand-sub">Sistem Pendaftaran</div>
            </div>
        </div>
        <nav class="sidebar-nav">
            <a href="#" class="nav-item active">
                <span class="nav-icon">📊</span> Dashboard
            </a>
        </nav>
        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar" id="sideAvatar">P</div>
                <div>
                    <div class="user-name" id="sideUserName">Pimpinan</div>
                    <div class="user-role">Pimpinan SIP</div>
                </div>
            </div>
            <button class="logout-btn" onclick="doLogout()">
                <span>⏻</span> Keluar
            </button>
        </div>
    </aside>

    <!-- Main -->
    <div class="main">
        <header class="topbar">
            <div>
                <div class="topbar-title">Dashboard Pendaftaran</div>
                <div class="topbar-sub">Pantau data pendaftar — hanya baca</div>
            </div>
            <div class="topbar-spacer"></div>
            <button class="btn-refresh" id="btnRefresh" onclick="loadData()">
                <span class="spin">↻</span> Refresh
            </button>
        </header>

        <main class="content">
            <!-- Stat Cards -->
            <div class="stat-grid">
                <div class="stat-card s-total">
                    <div class="stat-top"><div class="stat-label">Total Pendaftar</div><div class="stat-icon blue">👥</div></div>
                    <div class="stat-num" id="sTotal">—</div>
                    <div class="stat-sub">Semua status</div>
                </div>
                <div class="stat-card s-pending">
                    <div class="stat-top"><div class="stat-label">Menunggu</div><div class="stat-icon yellow">⏳</div></div>
                    <div class="stat-num" id="sPending">—</div>
                    <div class="stat-sub">Belum diproses</div>
                </div>
                <div class="stat-card s-verif">
                    <div class="stat-top"><div class="stat-label">Proses Seleksi</div><div class="stat-icon blue">🔍</div></div>
                    <div class="stat-num" id="sVerif">—</div>
                    <div class="stat-sub">Verifikasi + Seleksi</div>
                </div>
                <div class="stat-card s-terima">
                    <div class="stat-top"><div class="stat-label">Diterima</div><div class="stat-icon green">✅</div></div>
                    <div class="stat-num" id="sTerima">—</div>
                    <div class="stat-sub">Status diterima</div>
                </div>
                <div class="stat-card s-tolak">
                    <div class="stat-top"><div class="stat-label">Ditolak</div><div class="stat-icon red">❌</div></div>
                    <div class="stat-num" id="sTolak">—</div>
                    <div class="stat-sub">Status ditolak</div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-card">
                <div class="table-header">
                    <div class="table-title">Data Pendaftar</div>
                    <div class="table-sub" id="tableSubLabel">Memuat data...</div>
                    <div class="table-controls">
                        <div class="filters">
                            <div class="search-box">
                                <span style="color:var(--text-muted);font-size:14px">🔍</span>
                                <input type="text" id="searchInput"
                                    placeholder="Cari nama, NISN, atau asal sekolah..."
                                    oninput="applyFilters()">
                            </div>
                            <select class="filter-select" id="filterSekolah"   onchange="applyFilters()">
                                <option value="">Semua Sekolah</option>
                            </select>
                            <select class="filter-select" id="filterGelombang" onchange="applyFilters()">
                                <option value="">Semua Gelombang</option>
                            </select>
                            <select class="filter-select" id="filterStatus"    onchange="applyFilters()">
                                <option value="">Semua Status</option>
                                <option value="0">⏳ Menunggu</option>
                                <option value="1">🔍 Verifikasi</option>
                                <option value="2">📝 Seleksi</option>
                                <option value="3">✅ Diterima</option>
                                <option value="4">❌ Ditolak</option>
                            </select>
                        </div>
                        <button class="btn-export" id="btnExport" onclick="exportExcel()">
                            ⬇ Export Excel
                        </button>
                    </div>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Siswa</th>
                                <th>NISN</th>
                                <th>Asal Sekolah</th>
                                <th>Sekolah Tujuan</th>
                                <th>Gelombang / TA</th>
                                <th>Status</th>
                                <th>Tgl Update</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <tr class="loading-row"><td colspan="9">⏳ Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <span id="tableInfo">—</span>
                    <div class="pagination" id="pagination"></div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Detail Modal (READ ONLY) -->
<div class="modal-overlay" id="modalOverlay" onclick="if(event.target===this)closeModal()">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Detail Pendaftar</div>
            <button class="modal-close" onclick="closeModal()">✕</button>
        </div>
        <div class="modal-body" id="modalBody"></div>
    </div>
</div>

<script>
// ─── CONFIG ─────────────────────────────────────────
const API   = '/api';
const TOKEN = localStorage.getItem('token') || '';
const HDR   = { 'Authorization': `Bearer ${TOKEN}`, 'Accept': 'application/json' };
const PER_PAGE = 15;

// Status sesuai enum DB: '0','1','2','3','4','9'
const ST_LABEL = {
    '0':'Menunggu', '1':'Verifikasi', '2':'Seleksi',
    '3':'Diterima', '4':'Ditolak',   '9':'Lainnya'
};

let allRegs = [], filtered = [], currentPage = 1;

// ─── INIT ────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    if (!TOKEN) { window.location.href = '/login'; return; }
    loadUser();
    loadData();
});

async function loadUser() {
    try {
        const res = await fetch(`${API}/me`, { headers: HDR });
        if (!res.ok) { window.location.href = '/login'; return; }
        const d = await res.json();
        const u = d.user || d.data || d;
        if (u?.name) {
            document.getElementById('sideUserName').textContent = u.name;
            document.getElementById('sideAvatar').textContent =
                u.name.trim().split(' ').map(w=>w[0]).join('').substring(0,2).toUpperCase();
        }
    } catch(e) {}
}

// ─── LOAD DATA ───────────────────────────────────────
async function loadData() {
    const btn = document.getElementById('btnRefresh');
    btn.classList.add('spinning'); btn.disabled = true;
    document.getElementById('tableBody').innerHTML =
        '<tr class="loading-row"><td colspan="9">⏳ Memuat data...</td></tr>';

    try {
        // Ambil semua registrasi — limit besar, diorder desc
        const res = await fetch(`${API}/registration?limit=1000&order=id&orderType=desc`, { headers: HDR });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const json = await res.json();

        // Response dari StudentRegistrationController: { data: [...], count, limit }
        // Resource memetakan: student.nama, student.nisn, school.name, regForm.title, school_origin, status
        allRegs = json.data || [];

        updateStats();
        buildFilters();
        applyFilters();
    } catch(e) {
        console.error(e);
        document.getElementById('tableBody').innerHTML =
            `<tr class="loading-row"><td colspan="9" style="color:var(--error)">❌ Gagal memuat: ${e.message}</td></tr>`;
        document.getElementById('tableSubLabel').textContent = 'Gagal memuat data';
    } finally {
        btn.classList.remove('spinning'); btn.disabled = false;
    }
}

// ─── STATS ───────────────────────────────────────────
function updateStats() {
    const s = str => String(str ?? '');
    document.getElementById('sTotal').textContent  = allRegs.length;
    document.getElementById('sPending').textContent = allRegs.filter(r=>s(r.status)==='0').length;
    document.getElementById('sVerif').textContent  = allRegs.filter(r=>s(r.status)==='1'||s(r.status)==='2').length;
    document.getElementById('sTerima').textContent = allRegs.filter(r=>s(r.status)==='3').length;
    document.getElementById('sTolak').textContent  = allRegs.filter(r=>s(r.status)==='4').length;
}

// ─── BUILD FILTERS ────────────────────────────────────
function buildFilters() {
    const sekolahs   = new Set();
    const gelombangs = new Set();

    allRegs.forEach(r => {
        if (r.school?.name) sekolahs.add(r.school.name);
        if (r.regForm?.title) gelombangs.add(`${r.regForm.title} — TA ${r.regForm.ta}`);
    });

    fillSelect('filterSekolah',   sekolahs,   'Semua Sekolah');
    fillSelect('filterGelombang', gelombangs, 'Semua Gelombang');
}

function fillSelect(id, set, placeholder) {
    const el = document.getElementById(id);
    while (el.options.length > 1) el.remove(1);
    set.forEach(v => { const o = document.createElement('option'); o.value=v; o.textContent=v; el.appendChild(o); });
}

// ─── FILTERS + RENDER ─────────────────────────────────
function applyFilters() {
    const q   = document.getElementById('searchInput').value.toLowerCase().trim();
    const sch = document.getElementById('filterSekolah').value;
    const gel = document.getElementById('filterGelombang').value;
    const st  = document.getElementById('filterStatus').value;

    filtered = allRegs.filter(r => {
        const nama = (r.student?.nama || '').toLowerCase();
        const nisn = (r.student?.nisn || '').toLowerCase();
        const asal = (r.school_origin || '').toLowerCase();
        const rSch = r.school?.name || '';
        const rGel = r.regForm ? `${r.regForm.title} — TA ${r.regForm.ta}` : '';
        const rSt  = String(r.status ?? '');

        return (!q   || nama.includes(q)||nisn.includes(q)||asal.includes(q))
            && (!sch || rSch === sch)
            && (!gel || rGel === gel)
            && (!st  || rSt  === st);
    });

    currentPage = 1;
    renderTable();
}

// ─── RENDER TABLE ────────────────────────────────────
function renderTable() {
    const tbody = document.getElementById('tableBody');
    const start = (currentPage - 1) * PER_PAGE;
    const page  = filtered.slice(start, start + PER_PAGE);

    document.getElementById('tableSubLabel').textContent =
        `${filtered.length} pendaftar ditemukan (total ${allRegs.length})`;

    if (!page.length) {
        tbody.innerHTML = `<tr><td colspan="9"><div class="empty-state">
            <div class="empty-icon">📭</div>
            <div class="empty-title">Tidak ada data</div>
            <div>Coba ubah filter pencarian</div>
        </div></td></tr>`;
        document.getElementById('tableInfo').textContent = 'Tidak ada data';
        document.getElementById('pagination').innerHTML  = '';
        return;
    }

    tbody.innerHTML = page.map((r, i) => {
        const no   = start + i + 1;
        const nama = r.student?.nama  || '—';
        const nisn = r.student?.nisn  || '—';
        const asal = r.school_origin  || '—';
        const sch  = r.school?.name   || '—';
        const gel  = r.regForm
            ? `${esc(r.regForm.title)}<br><small style="color:var(--text-muted)">TA ${esc(r.regForm.ta)}</small>`
            : '—';
        const st   = String(r.status ?? '0');
        const tgl  = r.updated_at ? fmtDate(r.updated_at) : '—';

        return `<tr>
            <td style="color:var(--text-muted);font-size:12px">${no}</td>
            <td class="td-name">${esc(nama)}</td>
            <td style="font-family:monospace;font-size:12px">${esc(nisn)}</td>
            <td>${esc(asal)}</td>
            <td>${esc(sch)}</td>
            <td>${gel}</td>
            <td>${statusBadge(st)}</td>
            <td style="white-space:nowrap;font-size:12px">${tgl}</td>
            <td>
                <button onclick="showDetail(${r.id})"
                    style="background:none;border:none;color:var(--accent);font-size:12px;font-weight:600;
                           cursor:pointer;display:flex;align-items:center;gap:4px;padding:4px 8px;
                           border-radius:6px;transition:background .15s;"
                    onmouseover="this.style.background='var(--accent-light)'"
                    onmouseout="this.style.background='none'">
                    👁 Lihat
                </button>
            </td>
        </tr>`;
    }).join('');

    const total = Math.ceil(filtered.length / PER_PAGE);
    document.getElementById('tableInfo').textContent =
        `${start+1}–${Math.min(start+PER_PAGE, filtered.length)} dari ${filtered.length}`;
    renderPagination(total);
}

// ─── PAGINATION ───────────────────────────────────────
function renderPagination(total) {
    const el = document.getElementById('pagination');
    if (total <= 1) { el.innerHTML = ''; return; }

    let html = `<button class="page-btn" onclick="goPage(${currentPage-1})" ${currentPage===1?'disabled':''}>‹</button>`;
    for (let i = 1; i <= total; i++) {
        if (total > 7 && i > 2 && i < total-1 && Math.abs(i-currentPage) > 1) {
            if (i===3||i===total-2) html += `<span style="padding:0 4px;color:var(--text-muted)">…</span>`;
            continue;
        }
        html += `<button class="page-btn ${i===currentPage?'active':''}" onclick="goPage(${i})">${i}</button>`;
    }
    html += `<button class="page-btn" onclick="goPage(${currentPage+1})" ${currentPage===total?'disabled':''}>›</button>`;
    el.innerHTML = html;
}

function goPage(p) {
    const total = Math.ceil(filtered.length / PER_PAGE);
    if (p < 1 || p > total) return;
    currentPage = p;
    renderTable();
    document.querySelector('.content').scrollTo({ top: 0, behavior: 'smooth' });
}

// ─── DETAIL MODAL (READ ONLY) ─────────────────────────
async function showDetail(id) {
    document.getElementById('modalOverlay').classList.add('active');
    document.getElementById('modalBody').innerHTML =
        '<div style="text-align:center;padding:48px;color:var(--text-muted)">⏳ Memuat...</div>';

    // Gunakan data yang sudah di-cache di allRegs (sudah eager load)
    const r = allRegs.find(x => x.id === id);
    if (!r) {
        document.getElementById('modalBody').innerHTML =
            '<div style="text-align:center;padding:48px;color:var(--error)">❌ Data tidak ditemukan</div>';
        return;
    }

    const st    = String(r.status ?? '0');
    const siswa = r.student || {};
    const form  = r.regForm || {};
    const sch   = r.school  || {};

    document.getElementById('modalBody').innerHTML = `
        <!-- Header -->
        <div style="display:flex;align-items:center;gap:14px;background:#f8fafc;border-radius:10px;
            padding:14px 16px;margin-bottom:20px;">
            <div style="width:48px;height:48px;border-radius:50%;background:var(--accent);
                display:flex;align-items:center;justify-content:center;
                font-weight:700;font-size:18px;color:white;flex-shrink:0">
                ${esc((siswa.nama||'?').charAt(0).toUpperCase())}
            </div>
            <div style="flex:1">
                <div style="font-weight:700;font-size:16px">${esc(siswa.nama||'—')}</div>
                <div style="font-size:12px;color:var(--text-muted);margin-top:2px">
                    NISN: <span style="font-family:monospace">${esc(siswa.nisn||'—')}</span>
                </div>
            </div>
            <div>${statusBadge(st)}</div>
        </div>

        <!-- Data Siswa -->
        <div class="info-section">
            <div class="info-section-title">Data Siswa</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nama Lengkap</div>
                    <div class="info-value">${esc(siswa.nama||'—')}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">NISN</div>
                    <div class="info-value" style="font-family:monospace">${esc(siswa.nisn||'—')}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">${esc(siswa.email||'—')}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Asal Sekolah</div>
                    <div class="info-value">${esc(r.school_origin||'—')}</div>
                </div>
                ${siswa.alamat?`<div class="info-item full">
                    <div class="info-label">Alamat</div>
                    <div class="info-value">${esc(siswa.alamat)}</div>
                </div>`:''}
            </div>
        </div>

        <!-- Data Pendaftaran -->
        <div class="info-section">
            <div class="info-section-title">Data Pendaftaran</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Sekolah Tujuan</div>
                    <div class="info-value">${esc(sch.name||'—')}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Gelombang</div>
                    <div class="info-value">${esc(form.title||'—')}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tahun Ajaran</div>
                    <div class="info-value">${esc(form.ta||'—')}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Didaftarkan Oleh</div>
                    <div class="info-value">${regByLabel(r.registered_by)}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value">${statusBadge(st)}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Terakhir Diperbarui</div>
                    <div class="info-value">${r.updated_at?fmtDate(r.updated_at):'—'}</div>
                </div>
            </div>
        </div>

        <div style="padding:10px 14px;background:#f8fafc;border-radius:8px;
            font-size:12px;color:var(--text-muted);text-align:center;border:1px solid var(--border);">
            🔒 Tampilan hanya baca — perubahan status dilakukan oleh admin sekolah
        </div>
    `;
}

function closeModal() {
    document.getElementById('modalOverlay').classList.remove('active');
}

// ─── EXPORT EXCEL ─────────────────────────────────────
function exportExcel() {
    if (!filtered.length) { alert('Tidak ada data untuk diekspor.'); return; }
    const btn = document.getElementById('btnExport');
    btn.disabled = true; btn.textContent = '⏳ Mengekspor...';

    try {
        const rows = filtered.map((r, i) => ({
            'No'             : i + 1,
            'Nama Siswa'     : r.student?.nama  || '',
            'NISN'           : r.student?.nisn  || '',
            'Email'          : r.student?.email || '',
            'Asal Sekolah'   : r.school_origin  || '',
            'Sekolah Tujuan' : r.school?.name   || '',
            'Gelombang'      : r.regForm?.title || '',
            'Tahun Ajaran'   : r.regForm?.ta    || '',
            'Status'         : ST_LABEL[String(r.status)] || r.status || '',
            'Didaftarkan'    : regByLabel(r.registered_by),
            'Tgl Diperbarui' : r.updated_at ? fmtDate(r.updated_at) : '',
        }));

        const ws = XLSX.utils.json_to_sheet(rows);
        ws['!cols'] = Object.keys(rows[0]).map(k => ({ wch: Math.max(k.length + 2, 14) }));
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Pendaftar');
        XLSX.writeFile(wb, `data-pendaftar-${new Date().toISOString().slice(0,10)}.xlsx`);
    } finally {
        btn.disabled = false; btn.innerHTML = '⬇ Export Excel';
    }
}

// ─── HELPERS ──────────────────────────────────────────
function statusBadge(s) {
    const label = ST_LABEL[String(s)] || 'Lainnya';
    return `<span class="badge b-${s}">${label}</span>`;
}

function regByLabel(v) {
    return {'1':'Oleh Sekolah','2':'Oleh Orang Tua','9':'Mandiri (Online)'}[String(v)] || v || '—';
}

function fmtDate(str) {
    try {
        return new Date(str).toLocaleDateString('id-ID',
            { day:'numeric', month:'short', year:'numeric' });
    } catch { return str; }
}

function esc(s) {
    if (s == null) return '—';
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

function doLogout() {
    fetch(`${API}/logout`, { method:'POST', headers:HDR }).catch(()=>{});
    localStorage.removeItem('token');
    window.location.href = '/login';
}
</script>
</body>
</html>