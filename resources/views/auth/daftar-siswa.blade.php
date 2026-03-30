<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pendaftaran Siswa Baru</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg:           #f0f4f8;
            --card:         #ffffff;
            --accent:       #2563eb;
            --accent-light: #eff6ff;
            --accent-hover: #1d4ed8;
            --text:         #0f172a;
            --text-sec:     #475569;
            --text-muted:   #94a3b8;
            --border:       #e2e8f0;
            --success:      #10b981;
            --success-bg:   #ecfdf5;
            --error:        #ef4444;
            --error-bg:     #fef2f2;
            --shadow:       0 1px 3px rgba(0,0,0,0.06);
            --shadow-md:    0 4px 16px rgba(0,0,0,0.09);
        }
        html { scroll-behavior: smooth; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }

        /* ── Header ── */
        .page-header {
            background: var(--card); border-bottom: 1px solid var(--border);
            position: sticky; top: 0; z-index: 100; box-shadow: var(--shadow);
        }
        .header-inner { max-width: 900px; margin: 0 auto; padding: 14px 24px; display: flex; align-items: center; gap: 14px; }
        .header-logo {
            width: 42px; height: 42px; border-radius: 11px; background: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 12px; color: white; flex-shrink: 0;
        }
        .header-school-name { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 15px; font-weight: 700; line-height: 1.2; }
        .header-sub { font-size: 12px; color: var(--text-muted); margin-top: 1px; }

        /* ── Hero ── */
        .hero {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 60%, #3b82f6 100%);
            padding: 36px 24px 30px; color: white; text-align: center; position: relative; overflow: hidden;
        }
        .hero::before {
            content: ''; position: absolute; inset: 0; opacity: 0.05;
            background-image: radial-gradient(circle at 20% 50%, white 1px, transparent 1px), radial-gradient(circle at 80% 20%, white 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .hero-content { position: relative; z-index: 1; }
        .hero-title { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 24px; font-weight: 800; margin-bottom: 6px; }
        .hero-sub   { font-size: 14px; opacity: 0.85; }
        .hero-pills { display: flex; gap: 10px; justify-content: center; margin-top: 14px; flex-wrap: wrap; }
        .hero-pill  {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25);
            border-radius: 20px; padding: 5px 14px; font-size: 12px; font-weight: 600;
        }

        /* ── School selected banner ── */
        .school-banner {
            background: var(--success-bg); border-bottom: 2px solid #a7f3d0;
            padding: 9px 24px; text-align: center;
            font-size: 13px; font-weight: 600; color: #065f46; display: none;
        }

        /* ── Steps ── */
        .steps-wrap { background: var(--card); border-bottom: 1px solid var(--border); padding: 15px 24px; }
        .steps      { max-width: 900px; margin: 0 auto; display: flex; align-items: center; }
        .step       { display: flex; align-items: center; gap: 8px; }
        .step-circle {
            width: 30px; height: 30px; border-radius: 50%; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; border: 2px solid var(--border);
            background: white; color: var(--text-muted); transition: all .25s;
        }
        .step-circle.active { background: var(--accent); border-color: var(--accent); color: white; box-shadow: 0 0 0 4px rgba(37,99,235,.15); }
        .step-circle.done   { background: var(--success); border-color: var(--success); color: white; }
        .step-label { font-size: 12px; font-weight: 600; color: var(--text-muted); white-space: nowrap; transition: color .25s; }
        .step-label.active { color: var(--accent); }
        .step-label.done   { color: var(--success); }
        .step-line  { flex: 1; height: 2px; background: var(--border); margin: 0 8px; min-width: 16px; transition: background .25s; }
        .step-line.done { background: var(--success); }

        /* ── Main ── */
        .main { max-width: 900px; margin: 0 auto; padding: 24px 20px 60px; }

        /* ── Section Card ── */
        .section { background: var(--card); border: 1px solid var(--border); border-radius: 14px; margin-bottom: 18px; box-shadow: var(--shadow); overflow: hidden; }
        .section-header {
            padding: 14px 20px; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 12px;
            background: linear-gradient(to right, var(--accent-light), white);
        }
        .section-num {
            width: 26px; height: 26px; border-radius: 7px; background: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700; color: white; flex-shrink: 0;
        }
        .section-title { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; font-weight: 700; }
        .section-body  { padding: 20px; }

        /* ── Sub titles ── */
        .sub-title {
            font-size: 10px; font-weight: 700; color: var(--accent); text-transform: uppercase;
            letter-spacing: .1em; margin: 18px 0 12px; padding-bottom: 6px;
            border-bottom: 1px solid var(--border);
        }
        .sub-title:first-child { margin-top: 0; }

        /* ── Form Grid ── */
        .fg2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .fg3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }
        .cf  { grid-column: 1 / -1; }

        .form-group { display: flex; flex-direction: column; gap: 5px; }
        .form-label { font-size: 12px; font-weight: 600; color: var(--text-sec); }
        .form-label .req { color: var(--error); margin-left: 2px; }

        .form-input, .form-select {
            padding: 9px 13px; background: var(--bg); border: 1px solid var(--border);
            border-radius: 8px; font-family: 'DM Sans', sans-serif; font-size: 13px;
            color: var(--text); outline: none; transition: all .18s; width: 100%;
        }
        .form-input:focus, .form-select:focus {
            border-color: var(--accent); background: white;
            box-shadow: 0 0 0 3px rgba(37,99,235,.08);
        }
        .form-input::placeholder { color: var(--text-muted); }
        .form-input.err, .form-select.err { border-color: var(--error); background: #fff8f8; }
        .form-select {
            appearance: none; cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 11px center; background-size: 13px; padding-right: 34px;
        }
        .form-select:disabled { opacity: 0.45; cursor: not-allowed; }
        .form-hint  { font-size: 11px; color: var(--text-muted); }
        .field-err  { font-size: 11px; color: var(--error); display: none; }
        .field-err.show { display: block; }

        /* ── Select with spinner ── */
        .sel-wrap { position: relative; }
        .sel-spin {
            position: absolute; right: 32px; top: 50%; transform: translateY(-50%);
            width: 14px; height: 14px; border: 2px solid var(--border);
            border-top-color: var(--accent); border-radius: 50%;
            animation: spin .6s linear infinite; display: none; pointer-events: none;
        }
        .sel-wrap.loading .sel-spin { display: block; }
        @keyframes spin { to { transform: translateY(-50%) rotate(360deg); } }

        /* ── Info Box ── */
        .info-box {
            background: var(--accent-light); border: 1px solid #bfdbfe;
            border-radius: 10px; padding: 12px 16px;
            display: flex; gap: 10px; align-items: flex-start;
            font-size: 13px; color: var(--text-sec); line-height: 1.5;
        }
        .info-icon { font-size: 17px; flex-shrink: 0; margin-top: 1px; }
        code.pw { background: #dbeafe; color: var(--accent); padding: 1px 6px; border-radius: 4px; font-family: monospace; font-size: 13px; font-weight: 700; }

        /* ── Nav Bar ── */
        .nav-bar {
            background: var(--card); border: 1px solid var(--border); border-radius: 14px;
            padding: 15px 20px; display: flex; justify-content: space-between; align-items: center;
            box-shadow: var(--shadow-md);
        }
        .nav-info { font-size: 12px; color: var(--text-muted); }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 22px; border-radius: 8px; border: none;
            font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 600;
            cursor: pointer; transition: all .18s;
        }
        .btn-primary { background: var(--accent); color: white; }
        .btn-primary:hover:not(:disabled) { background: var(--accent-hover); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37,99,235,.3); }
        .btn-primary:disabled { opacity: .55; cursor: not-allowed; }
        .btn-outline { background: white; color: var(--text-sec); border: 1px solid var(--border); }
        .btn-outline:hover { background: var(--bg); }
        .btn-success { background: var(--success); color: white; }
        .btn-success:hover:not(:disabled) { filter: brightness(1.08); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(16,185,129,.3); }
        .btn-success:disabled { opacity: .55; cursor: not-allowed; }
        .btn-spin { position: relative; pointer-events: none; padding-right: 40px; }
        .btn-spin::after {
            content: ''; position: absolute; right: 12px; top: 50%;
            transform: translateY(-50%); width: 14px; height: 14px;
            border: 2px solid rgba(255,255,255,.35); border-top-color: white;
            border-radius: 50%; animation: spin .6s linear infinite;
        }

        /* ── Pages ── */
        .form-page { display: none; }
        .form-page.active { display: block; animation: fadeUp .3s ease; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* ── Alert ── */
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 14px; font-size: 13px; }
        .alert-error { background: var(--error-bg); border: 1px solid #fecaca; color: #b91c1c; }
        .alert-error ul { margin-left: 18px; margin-top: 4px; }

        /* ── School Cards ── */
        .school-cards-wrap { max-height: 440px; overflow-y: auto; padding-right: 2px; }
        .school-card {
            border: 2px solid var(--border); border-radius: 10px; padding: 14px 16px;
            display: flex; align-items: center; gap: 12px;
            cursor: pointer; transition: all .18s; margin-bottom: 8px; background: white;
        }
        .school-card:hover    { border-color: #93c5fd; background: var(--accent-light); }
        .school-card.selected { border-color: var(--accent); background: var(--accent-light); box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
        .school-logo { width: 42px; height: 42px; border-radius: 9px; background: var(--accent); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 12px; color: white; flex-shrink: 0; }
        .school-name { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; font-weight: 700; }
        .school-info { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
        .check-ring { width: 22px; height: 22px; border-radius: 50%; border: 2px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 11px; transition: all .18s; flex-shrink: 0; margin-left: auto; }
        .school-card.selected .check-ring { background: var(--accent); border-color: var(--accent); color: white; }

        /* ── Review ── */
        .review-grid { display: grid; grid-template-columns: 1fr 1fr; }
        .rv-item { padding: 9px 14px; border-bottom: 1px solid var(--border); border-right: 1px solid var(--border); }
        .rv-item:nth-child(2n) { border-right: none; }
        .rv-label { font-size: 11px; color: var(--text-muted); margin-bottom: 2px; }
        .rv-val   { font-size: 13px; font-weight: 600; }
        .rv-school { background: var(--accent-light); border: 1px solid #bfdbfe; border-radius: 10px; padding: 14px 16px; display: flex; gap: 12px; align-items: center; margin-bottom: 14px; }
        .rv-school-ico  { width: 40px; height: 40px; border-radius: 9px; background: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
        .rv-school-name { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; font-weight: 700; color: var(--accent); }
        .rv-school-form { font-size: 12px; color: var(--text-muted); }

        /* ── Success ── */
        .success-wrap {
            background: var(--card); border: 1px solid var(--border); border-radius: 16px;
            padding: 44px 28px; text-align: center; box-shadow: var(--shadow-md);
        }
        .succ-icon  { font-size: 60px; margin-bottom: 14px; }
        .succ-title { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 24px; font-weight: 800; margin-bottom: 6px; }
        .succ-sub   { font-size: 14px; color: var(--text-sec); line-height: 1.6; }
        .cred-box   { background: var(--accent-light); border: 1px solid #bfdbfe; border-radius: 12px; padding: 20px; margin: 20px auto; max-width: 400px; text-align: left; }
        .cred-title { font-size: 11px; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: .08em; margin-bottom: 12px; }
        .cred-row   { display: flex; justify-content: space-between; align-items: center; padding: 9px 0; border-bottom: 1px solid #bfdbfe; }
        .cred-row:last-child { border-bottom: none; }
        .cred-lbl   { font-size: 12px; color: var(--text-muted); }
        .cred-val   { font-size: 13px; font-weight: 700; background: white; padding: 3px 10px; border-radius: 6px; font-family: monospace; }
        .warn-note  { font-size: 13px; color: var(--error); font-weight: 600; margin-top: 10px; }
        .mute-note  { font-size: 12px; color: var(--text-muted); margin-top: 6px; }

        /* ── Loading skeleton ── */
        .skel { background: linear-gradient(90deg, #f0f4f8 25%, #e2e8f0 50%, #f0f4f8 75%); background-size: 200% 100%; animation: shimmer 1.2s infinite; border-radius: 8px; }
        @keyframes shimmer { to { background-position: -200% 0; } }

        @media (max-width: 640px) {
            .fg2, .fg3, .review-grid { grid-template-columns: 1fr; }
            .cf { grid-column: 1; }
            .step-label { display: none; }
            .hero-title { font-size: 20px; }
            .rv-item:nth-child(2n) { border-right: none; }
            .rv-item { border-right: none; }
        }
    </style>
</head>
<body>

<!-- Header -->
<header class="page-header">
    <div class="header-inner">
        <div class="header-logo" id="hLogo">SIP</div>
        <div>
            <div class="header-school-name" id="hName">Sistem Informasi Pendidikan</div>
            <div class="header-sub">Form Pendaftaran Siswa Baru</div>
        </div>
    </div>
</header>

<!-- Hero -->
<div class="hero">
    <div class="hero-content">
        <div class="hero-title" id="heroTitle">Pendaftaran Siswa Baru</div>
        <div class="hero-sub">Isi formulir dengan lengkap dan benar sesuai dokumen resmi</div>
        <div class="hero-pills">
            <span class="hero-pill" id="pillSchool">🏫 Memuat sekolah...</span>
            <span class="hero-pill" id="pillTA">📋 Pilih gelombang</span>
        </div>
    </div>
</div>

<!-- Banner sekolah terpilih -->
<div class="school-banner" id="schoolBanner">
    🏫 Mendaftar ke: <span id="bannerName">—</span>
</div>

<!-- Steps -->
<div class="steps-wrap" id="stepsWrap">
    <div class="steps" id="stepsContainer">
        {{-- Steps dirender oleh JS sesuai mode (URL-mode: 4 step, manual-mode: 5 step) --}}
    </div>
</div>

<div class="main">

<!-- ═══════ PAGE 0: PILIH SEKOLAH ═══════ -->
<div class="form-page active" id="page0">
    <div class="section">
        <div class="section-header">
            <div class="section-num">🏫</div>
            <div><div class="section-title">Pilih Sekolah Tujuan</div></div>
        </div>
        <div class="section-body">
            <div id="p0Err" class="alert alert-error" style="display:none"></div>

            <div class="info-box" style="margin-bottom:16px;">
                <span class="info-icon">ℹ️</span>
                <span>Pilih sekolah yang ingin Anda tuju untuk pendaftaran siswa baru.</span>
            </div>

            <!-- Filter -->
            <div style="display:flex;gap:10px;margin-bottom:14px;flex-wrap:wrap;">
                <input type="text" class="form-input" id="searchSch" placeholder="🔍  Cari nama sekolah..." oninput="filterSchools()" style="flex:1;min-width:160px;">
                <select class="form-select" id="filterLevel" onchange="filterSchools()" style="width:160px;flex-shrink:0;">
                    <option value="">Semua Jenjang</option>
                </select>
            </div>

            <!-- List -->
            <div id="schLoading" style="text-align:center;padding:36px;color:var(--text-muted);font-size:13px;">
                <div style="width:28px;height:28px;border:3px solid var(--border);border-top-color:var(--accent);border-radius:50%;animation:spin .6s linear infinite;margin:0 auto 10px;"></div>
                Memuat daftar sekolah...
            </div>
            <div id="schCards" class="school-cards-wrap" style="display:none;"></div>
            <div id="schEmpty" style="display:none;text-align:center;padding:32px;color:var(--text-muted);font-size:13px;">Sekolah tidak ditemukan.</div>
        </div>
    </div>
    <div class="nav-bar">
        <div class="nav-info" id="navInfo0">Langkah 1 dari 5</div>
        <button class="btn btn-primary" onclick="goPage(1)">Lanjut →</button>
    </div>
</div>

<!-- ═══════ PAGE 1: DATA DIRI ═══════ -->
<div class="form-page" id="page1">
    <div class="section">
        <div class="section-header">
            <div class="section-num">1</div>
            <div><div class="section-title">Data Pribadi Calon Siswa</div></div>
        </div>
        <div class="section-body">
            <div id="p1Err" class="alert alert-error" style="display:none"></div>
            <div class="fg2">
                <div class="form-group cf">
                    <label class="form-label">Nama Lengkap <span class="req">*</span></label>
                    <input type="text" class="form-input" id="nama" placeholder="Sesuai akta kelahiran">
                    <div class="field-err" id="e_nama"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">NIK <span class="req">*</span></label>
                    <input type="text" class="form-input" id="nik" maxlength="16" placeholder="16 digit NIK">
                    <div class="field-err" id="e_nik"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">NISN <span class="req">*</span></label>
                    <input type="text" class="form-input" id="nisn" maxlength="10" placeholder="10 digit NISN">
                    <div class="field-err" id="e_nisn"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Tempat Lahir <span class="req">*</span></label>
                    <input type="text" class="form-input" id="tempat_lahir" placeholder="Kota/kabupaten lahir">
                    <div class="field-err" id="e_tempat_lahir"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Lahir <span class="req">*</span></label>
                    <input type="date" class="form-input" id="tanggal_lahir">
                    <div class="field-err" id="e_tanggal_lahir"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Kelamin <span class="req">*</span></label>
                    <select class="form-select" id="jk">
                        <option value="">-- Pilih --</option>
                        <option>Laki-laki</option><option>Perempuan</option>
                    </select>
                    <div class="field-err" id="e_jk"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Agama <span class="req">*</span></label>
                    <select class="form-select" id="agama">
                        <option value="">-- Pilih --</option>
                        <option>Islam</option><option>Kristen</option><option>Katholik</option>
                        <option>Hindu</option><option>Budha</option><option>Konghucu</option>
                    </select>
                    <div class="field-err" id="e_agama"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Kewarganegaraan</label>
                    <select class="form-select" id="kewarganegaraan"><option value="WNI">WNI</option><option value="WNA">WNA</option></select>
                </div>
                <div class="form-group">
                    <label class="form-label">No. HP <span class="req">*</span></label>
                    <input type="tel" class="form-input" id="no_hp" placeholder="08xxxxxxxxxx">
                    <div class="field-err" id="e_no_hp"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">No. Telepon</label>
                    <input type="tel" class="form-input" id="no_telepon" placeholder="Opsional">
                </div>
                <div class="form-group cf">
                    <label class="form-label">Email <span class="req">*</span></label>
                    <input type="email" class="form-input" id="email" placeholder="email@contoh.com">
                    <div class="form-hint">Email ini digunakan untuk login ke akun siswa</div>
                    <div class="field-err" id="e_email"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Asal Sekolah <span class="req">*</span></label>
                    <input type="text" class="form-input" id="sekolah_asal" placeholder="Nama SMP/MTs asal">
                    <div class="field-err" id="e_sekolah_asal"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">No. Kartu Keluarga</label>
                    <input type="text" class="form-input" id="no_kk" maxlength="16" placeholder="Nomor KK">
                </div>
                <div class="form-group">
                    <label class="form-label">Anak ke-</label>
                    <input type="number" class="form-input" id="anak_ke" min="1" placeholder="1">
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah Saudara Kandung</label>
                    <input type="number" class="form-input" id="jml_saudara_kandung" min="0" placeholder="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Kebutuhan Khusus</label>
                    <input type="text" class="form-input" id="kebutuhan_khusus" placeholder="Jika ada">
                </div>
            </div>
        </div>
    </div>
    <div class="nav-bar">
        <button class="btn btn-outline" onclick="goPage(0)">← Kembali</button>
        <div class="nav-info" id="navInfo1">Langkah 2 dari 5</div>
        <button class="btn btn-primary" onclick="goPage(2)">Lanjut →</button>
    </div>
</div>

<!-- ═══════ PAGE 2: ALAMAT — Cascading Dropdowns ═══════ -->
<div class="form-page" id="page2">
    <div class="section">
        <div class="section-header">
            <div class="section-num">2</div>
            <div><div class="section-title">Alamat Domisili</div></div>
        </div>
        <div class="section-body">

            <div class="sub-title">Wilayah — Pilih secara berurutan</div>
            <div class="fg2">

                <!-- Provinsi -->
                <div class="form-group">
                    <label class="form-label">Provinsi</label>
                    <div class="sel-wrap" id="swProv">
                        <select class="form-select" id="selProv" onchange="onProvChange(this.value)">
                            <option value="">-- Pilih Provinsi --</option>
                        </select>
                        <div class="sel-spin"></div>
                    </div>
                </div>

                <!-- Kota/Kabupaten -->
                <div class="form-group">
                    <label class="form-label">Kota / Kabupaten</label>
                    <div class="sel-wrap" id="swCity">
                        <select class="form-select" id="selCity" disabled onchange="onCityChange(this.value)">
                            <option value="">-- Pilih Kota/Kab --</option>
                        </select>
                        <div class="sel-spin"></div>
                    </div>
                </div>

                <!-- Kecamatan -->
                <div class="form-group">
                    <label class="form-label">Kecamatan</label>
                    <div class="sel-wrap" id="swDist">
                        <select class="form-select" id="selDist" disabled onchange="onDistChange(this.value)">
                            <option value="">-- Pilih Kecamatan --</option>
                        </select>
                        <div class="sel-spin"></div>
                    </div>
                </div>

                <!-- Kelurahan/Desa -->
                <div class="form-group">
                    <label class="form-label">Kelurahan / Desa</label>
                    <input type="text" class="form-input" id="selSub" placeholder="Ketik nama kelurahan/desa..." oninput="onSubChange(this.value)">
                </div>

            </div>

            <div class="sub-title" style="margin-top:20px;">Detail Alamat</div>
            <div class="fg2">
                <div class="form-group cf">
                    <label class="form-label">Alamat Lengkap</label>
                    <input type="text" class="form-input" id="alamat" placeholder="Nama jalan, nomor rumah, dll">
                </div>
                <div class="form-group">
                    <label class="form-label">RT</label>
                    <input type="text" class="form-input" id="rt" maxlength="5" placeholder="001">
                </div>
                <div class="form-group">
                    <label class="form-label">RW</label>
                    <input type="text" class="form-input" id="rw" maxlength="5" placeholder="001">
                </div>
                <div class="form-group">
                    <label class="form-label">Dusun</label>
                    <input type="text" class="form-input" id="dusun" placeholder="Nama dusun">
                </div>
                <div class="form-group">
                    <label class="form-label">Kode Pos</label>
                    <input type="text" class="form-input" id="kode_pos" maxlength="5" placeholder="35xxx">
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Tinggal</label>
                    <select class="form-select" id="jenis_tinggal">
                        <option value="">-- Pilih --</option>
                        <option>Bersama Orang Tua</option><option>Wali</option>
                        <option>Kos</option><option>Asrama</option><option>Panti Asuhan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Alat Transportasi ke Sekolah</label>
                    <select class="form-select" id="alat_transportasi">
                        <option value="">-- Pilih --</option>
                        <option>Jalan Kaki</option><option>Sepeda</option><option>Sepeda Motor</option>
                        <option>Mobil Pribadi</option><option>Angkutan Umum</option><option>Jemputan Sekolah</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Jarak ke Sekolah (km)</label>
                    <input type="number" class="form-input" id="jarak_rumah_kesekolah" min="0" step="0.1" placeholder="0.0">
                </div>
            </div>

            <div class="sub-title" style="margin-top:20px;">Data Fisik</div>
            <div class="fg2">
                <div class="form-group">
                    <label class="form-label">Berat Badan (kg)</label>
                    <input type="number" class="form-input" id="berat_badan" min="0" step="0.1" placeholder="50">
                </div>
                <div class="form-group">
                    <label class="form-label">Tinggi Badan (cm)</label>
                    <input type="number" class="form-input" id="tinggi_badan" min="0" step="0.1" placeholder="155">
                </div>
            </div>
        </div>
    </div>
    <div class="nav-bar">
        <button class="btn btn-outline" onclick="goPage(1)">← Kembali</button>
        <div class="nav-info" id="navInfo2">Langkah 3 dari 5</div>
        <button class="btn btn-primary" onclick="goPage(3)">Lanjut →</button>
    </div>
</div>

<!-- ═══════ PAGE 3: DATA ORANG TUA ═══════ -->
<div class="form-page" id="page3">
    <div class="section">
        <div class="section-header">
            <div class="section-num">3</div>
            <div><div class="section-title">Data Orang Tua / Wali</div></div>
        </div>
        <div class="section-body">
            <div class="sub-title">Data Ayah</div>
            <div class="fg2">
                <div class="form-group"><label class="form-label">NIK Ayah</label><input type="text" class="form-input" id="nik_ayah" maxlength="16" placeholder="16 digit NIK"></div>
                <div class="form-group"><label class="form-label">Nama Ayah</label><input type="text" class="form-input" id="nama_ayah" placeholder="Nama lengkap"></div>
                <div class="form-group"><label class="form-label">Tahun Lahir</label><input type="text" class="form-input" id="tahun_lahir_ayah" maxlength="4" placeholder="1975"></div>
                <div class="form-group"><label class="form-label">Pendidikan Terakhir</label>
                    <select class="form-select" id="jenjang_pendidikan_ayah">
                        <option value="">-- Pilih --</option>
                        <option>SD/Sederajat</option><option>SMP/Sederajat</option><option>SMA/Sederajat</option>
                        <option>D1/D2</option><option>D3</option><option>S1/D4</option><option>S2</option><option>S3</option><option>Tidak Sekolah</option>
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Pekerjaan</label><input type="text" class="form-input" id="pekerjaan_ayah" placeholder="Wiraswasta, PNS, dll"></div>
                <div class="form-group"><label class="form-label">Penghasilan</label>
                    <select class="form-select" id="penghasilan_ayah">
                        <option value="">-- Pilih --</option>
                        <option value="< 1 Juta">Kurang dari Rp 1 Juta</option>
                        <option value="1 - 2 Juta">Rp 1 – 2 Juta</option><option value="2 - 5 Juta">Rp 2 – 5 Juta</option>
                        <option value="5 - 10 Juta">Rp 5 – 10 Juta</option><option value="> 10 Juta">Lebih dari Rp 10 Juta</option>
                        <option value="Tidak Berpenghasilan">Tidak Berpenghasilan</option>
                    </select>
                </div>
            </div>

            <div class="sub-title">Data Ibu</div>
            <div class="fg2">
                <div class="form-group"><label class="form-label">NIK Ibu</label><input type="text" class="form-input" id="nik_ibu" maxlength="16" placeholder="16 digit NIK"></div>
                <div class="form-group"><label class="form-label">Nama Ibu</label><input type="text" class="form-input" id="nama_ibu" placeholder="Nama lengkap"></div>
                <div class="form-group"><label class="form-label">Tahun Lahir</label><input type="text" class="form-input" id="tahun_lahir_ibu" maxlength="4" placeholder="1978"></div>
                <div class="form-group"><label class="form-label">Pendidikan Terakhir</label>
                    <select class="form-select" id="jenjang_pendidikan_ibu">
                        <option value="">-- Pilih --</option>
                        <option>SD/Sederajat</option><option>SMP/Sederajat</option><option>SMA/Sederajat</option>
                        <option>D1/D2</option><option>D3</option><option>S1/D4</option><option>S2</option><option>S3</option><option>Tidak Sekolah</option>
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Pekerjaan</label><input type="text" class="form-input" id="pekerjaan_ibu" placeholder="Ibu Rumah Tangga, dll"></div>
                <div class="form-group"><label class="form-label">Penghasilan</label>
                    <select class="form-select" id="penghasilan_ibu">
                        <option value="">-- Pilih --</option>
                        <option value="< 1 Juta">Kurang dari Rp 1 Juta</option>
                        <option value="1 - 2 Juta">Rp 1 – 2 Juta</option><option value="2 - 5 Juta">Rp 2 – 5 Juta</option>
                        <option value="5 - 10 Juta">Rp 5 – 10 Juta</option><option value="> 10 Juta">Lebih dari Rp 10 Juta</option>
                        <option value="Tidak Berpenghasilan">Tidak Berpenghasilan</option>
                    </select>
                </div>
            </div>

            <div class="sub-title">Data Wali <small style="font-weight:400;text-transform:none;font-size:11px;color:var(--text-muted)">(jika berbeda dengan orang tua)</small></div>
            <div class="fg2">
                <div class="form-group"><label class="form-label">NIK Wali</label><input type="text" class="form-input" id="nik_wali" maxlength="16" placeholder="16 digit NIK"></div>
                <div class="form-group"><label class="form-label">Nama Wali</label><input type="text" class="form-input" id="nama_wali" placeholder="Nama lengkap"></div>
                <div class="form-group"><label class="form-label">Tahun Lahir</label><input type="text" class="form-input" id="tahun_lahir_wali" maxlength="4" placeholder="1970"></div>
                <div class="form-group"><label class="form-label">Pendidikan Terakhir</label>
                    <select class="form-select" id="jenjang_pendidikan_wali">
                        <option value="">-- Pilih --</option>
                        <option>SD/Sederajat</option><option>SMP/Sederajat</option><option>SMA/Sederajat</option>
                        <option>D1/D2</option><option>D3</option><option>S1/D4</option><option>S2</option><option>S3</option><option>Tidak Sekolah</option>
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Pekerjaan</label><input type="text" class="form-input" id="pekerjaan_wali" placeholder="Pekerjaan wali"></div>
                <div class="form-group"><label class="form-label">Penghasilan</label>
                    <select class="form-select" id="penghasilan_wali">
                        <option value="">-- Pilih --</option>
                        <option value="< 1 Juta">Kurang dari Rp 1 Juta</option>
                        <option value="1 - 2 Juta">Rp 1 – 2 Juta</option><option value="2 - 5 Juta">Rp 2 – 5 Juta</option>
                        <option value="5 - 10 Juta">Rp 5 – 10 Juta</option><option value="> 10 Juta">Lebih dari Rp 10 Juta</option>
                        <option value="Tidak Berpenghasilan">Tidak Berpenghasilan</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="nav-bar">
        <button class="btn btn-outline" onclick="goPage(2)">← Kembali</button>
        <div class="nav-info" id="navInfo3">Langkah 4 dari 5</div>
        <button class="btn btn-primary" onclick="goPage(4)">Lanjut →</button>
    </div>
</div>

<!-- ═══════ PAGE 4: PENDAFTARAN & REVIEW ═══════ -->
<div class="form-page" id="page4">
    <div class="section">
        <div class="section-header">
            <div class="section-num">4</div>
            <div><div class="section-title">Pilihan Pendaftaran</div></div>
        </div>
        <div class="section-body">
            <div id="p4Err" class="alert alert-error" style="display:none"></div>

            <div id="loadForms" class="info-box" style="margin-bottom:14px;">
                <span class="info-icon">⏳</span><span>Memuat formulir pendaftaran...</span>
            </div>

            <div id="formSelWrap" style="display:none;">
                <div class="fg2">
                    <div class="form-group">
                        <label class="form-label">Gelombang / Formulir <span class="req">*</span></label>
                        <select class="form-select" id="reg_form_id">
                            <option value="">-- Pilih Gelombang --</option>
                        </select>
                        <div class="field-err" id="e_reg_form"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Asal Sekolah (konfirmasi) <span class="req">*</span></label>
                        <input type="text" class="form-input" id="school_origin" placeholder="Nama SMP/MTs asal">
                        <div class="field-err" id="e_school_origin"></div>
                    </div>
                </div>

                <div class="info-box" style="margin-top:16px;">
                    <span class="info-icon">🔑</span>
                    <div>
                        Akun siswa akan dibuat otomatis setelah mendaftar:<br>
                        <strong>Email:</strong> sesuai yang Anda isi &nbsp;·&nbsp;
                        <strong>Password default:</strong> <code class="pw">123456</code><br>
                        <span style="color:var(--error);font-size:12px;">⚠️ Segera ganti password setelah login pertama!</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Review -->
    <div class="section" id="reviewSec" style="display:none;">
        <div class="section-header">
            <div class="section-num" style="background:var(--success);">✓</div>
            <div><div class="section-title">Ringkasan Data — Periksa sebelum mengirim</div></div>
        </div>
        <div class="section-body">
            <div class="rv-school" id="rvSchool"></div>
            <div class="review-grid" id="rvGrid"></div>
        </div>
    </div>

    <div class="nav-bar">
        <button class="btn btn-outline" onclick="goPage(3)">← Kembali</button>
        <div class="nav-info" id="navInfo4">Langkah 5 dari 5</div>
        <button class="btn btn-success" id="btnSubmit" onclick="doSubmit()" style="display:none;">✓ Daftar Sekarang</button>
    </div>
</div>

<!-- ═══════ SUCCESS ═══════ -->
<div class="form-page" id="pageSuccess">
    <div class="success-wrap">
        <div class="succ-icon">🎉</div>
        <div class="succ-title">Pendaftaran Berhasil!</div>
        <div class="succ-sub">Data Anda telah dikirim ke <strong id="succSchool">sekolah</strong>.<br>Akun siswa sudah siap digunakan.</div>
        <div class="cred-box">
            <div class="cred-title">🔑 Informasi Login Akun Siswa</div>
            <div class="cred-row"><span class="cred-lbl">Nama</span><span class="cred-val" id="credNama">—</span></div>
            <div class="cred-row"><span class="cred-lbl">Email</span><span class="cred-val" id="credEmail">—</span></div>
            <div class="cred-row"><span class="cred-lbl">Password Default</span><span class="cred-val">123456</span></div>
            <div class="cred-row"><span class="cred-lbl">Status</span><span class="cred-val" style="background:#dcfce7;color:#166534;">✓ Terdaftar</span></div>
        </div>
        <div class="warn-note">⚠️ Screenshot atau catat informasi login di atas!</div>
        <div class="mute-note">Gunakan email & password tersebut untuk masuk ke portal siswa.</div>
    </div>
</div>

</div><!-- /.main -->

<script>
// ══════════════════════════════════════════════════════════════
//  CONFIG
// ══════════════════════════════════════════════════════════════
const API        = '/public';
const API_PUBLIC = '/public';

// Slug sekolah dari URL (diisi oleh Blade, null jika tidak ada)
const URL_SCHOOL_SLUG = @json($schoolSlug ?? null);

// State
let curPage       = 0;
let urlMode       = !!URL_SCHOOL_SLUG;  // true = sekolah ditentukan dari URL
let allSchools    = [];
let selSchool     = null;
let regForms      = [];
let locText = { provinsi: '', kabupaten: '', kecamatan: '', kelurahan: '' };

// ══════════════════════════════════════════════════════════════
//  INIT
// ══════════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
    buildSteps();         // render step indicators sesuai mode
    fetchSchools();       // selalu fetch (untuk fallback manual mode)
    fetchProvinces();
    document.getElementById('sekolah_asal').addEventListener('input', e => {
        document.getElementById('school_origin').value = e.target.value;
    });
});

// ──────────────────────────────────────────────────────────────
//  Build steps sesuai mode
// ──────────────────────────────────────────────────────────────
function buildSteps() {
    const container = document.getElementById('stepsContainer');
    if (urlMode) {
        // 4 step: Data Diri, Alamat, Orang Tua, Pendaftaran
        container.innerHTML = `
            <div class="step"><div class="step-circle active" id="sc1">1</div><span class="step-label active" id="sl1">Data Diri</span></div>
            <div class="step-line" id="ln1"></div>
            <div class="step"><div class="step-circle" id="sc2">2</div><span class="step-label" id="sl2">Alamat</span></div>
            <div class="step-line" id="ln2"></div>
            <div class="step"><div class="step-circle" id="sc3">3</div><span class="step-label" id="sl3">Orang Tua</span></div>
            <div class="step-line" id="ln3"></div>
            <div class="step"><div class="step-circle" id="sc4">4</div><span class="step-label" id="sl4">Pendaftaran</span></div>`;
    } else {
        // 5 step: Pilih Sekolah + 4 step di atas
        container.innerHTML = `
            <div class="step"><div class="step-circle active" id="sc1">1</div><span class="step-label active" id="sl1">Pilih Sekolah</span></div>
            <div class="step-line" id="ln1"></div>
            <div class="step"><div class="step-circle" id="sc2">2</div><span class="step-label" id="sl2">Data Diri</span></div>
            <div class="step-line" id="ln2"></div>
            <div class="step"><div class="step-circle" id="sc3">3</div><span class="step-label" id="sl3">Alamat</span></div>
            <div class="step-line" id="ln3"></div>
            <div class="step"><div class="step-circle" id="sc4">4</div><span class="step-label" id="sl4">Orang Tua</span></div>
            <div class="step-line" id="ln4"></div>
            <div class="step"><div class="step-circle" id="sc5">5</div><span class="step-label" id="sl5">Pendaftaran</span></div>`;
    }
}

// ══════════════════════════════════════════════════════════════
//  PAGE 0 — SEKOLAH
// ══════════════════════════════════════════════════════════════
async function fetchSchools() {
    try {
        const [sr, lr] = await Promise.all([
            fetch(`${API_PUBLIC}/schools?per_page=500`),
            fetch(`${API_PUBLIC}/school-levels`)
        ]);
        const sd = await sr.json();
        const ld = await lr.json();

        allSchools = (sd.data?.data || sd.data || sd || []);

        // Level filter (hanya relevan di manual mode)
        const levels = ld.data || ld || [];
        const fLvl   = document.getElementById('filterLevel');
        if (fLvl) {
            levels.forEach(l => {
                const o = document.createElement('option');
                o.value = l.id; o.textContent = l.name; fLvl.appendChild(o);
            });
        }

        if (urlMode && URL_SCHOOL_SLUG) {
            // ── URL MODE: otomatis pilih sekolah dari slug ──
            await autoSelectSchoolBySlug(URL_SCHOOL_SLUG);
        } else {
            // ── MANUAL MODE: tampilkan daftar sekolah ──
            document.getElementById('schLoading').style.display = 'none';
            renderSchools(allSchools);
        }
    } catch(e) {
        const loading = document.getElementById('schLoading');
        if (loading) loading.innerHTML =
            '<span style="color:var(--error)">⚠️ Gagal memuat daftar sekolah. Coba refresh halaman.</span>';
    }
}

async function autoSelectSchoolBySlug(slug) {
    try {
        // Coba cari di allSchools dulu (sudah di-fetch)
        let s = allSchools.find(x => x.slug === slug);
        // Jika tidak ada, fetch langsung by slug
        if (!s) {
            const res  = await fetch(`${API_PUBLIC}/schools/${slug}`);
            const data = await res.json();
            s = data.data || data;
        }
        if (!s || !s.id) throw new Error('not found');

        // Tambahkan ke allSchools jika belum ada
        if (!allSchools.find(x => x.id === s.id)) allSchools.push(s);

        selSchool = s;
        applySchoolUI(s);

        // Langsung ke page1 tanpa user harus klik apapun
        document.getElementById('page0').classList.remove('active');
        document.getElementById('page1').classList.add('active');
        curPage = 1;
        // Di URL mode step dimulai dari 1=Data Diri, di manual 2=Data Diri
        updateSteps(urlMode ? 1 : 2);
    } catch(e) {
        // Sekolah tidak ditemukan: fallback ke manual mode
        urlMode = false;
        buildSteps();
        const loading = document.getElementById('schLoading');
        if (loading) loading.innerHTML =
            `<span style="color:var(--error)">⚠️ Sekolah "<strong>${slug}</strong>" tidak ditemukan. Silakan pilih sekolah secara manual.</span>`;
        renderSchools(allSchools);
    }
}

function applySchoolUI(s) {
    const abbr = (s.name || 'SIP').substring(0, 3).toUpperCase();
    document.getElementById('hLogo').textContent       = abbr;
    document.getElementById('hName').textContent       = s.name || 'Sekolah';
    document.getElementById('heroTitle').textContent   = `Pendaftaran – ${s.name}`;
    document.getElementById('pillSchool').textContent  = `🏫 ${s.name}`;
    document.getElementById('bannerName').textContent  = s.name;
    document.getElementById('schoolBanner').style.display = 'block';
    const p0err = document.getElementById('p0Err');
    if (p0err) p0err.style.display = 'none';
}

function renderSchools(list) {
    const wrap  = document.getElementById('schCards');
    const empty = document.getElementById('schEmpty');

    if (!list.length) {
        wrap.style.display  = 'none';
        empty.style.display = 'block';
        return;
    }
    empty.style.display = 'none';
    wrap.style.display  = 'block';

    wrap.innerHTML = list.map(s => {
        const isSel  = selSchool && selSchool.id === s.id;
        const abbr   = (s.name || 'S').substring(0, 3).toUpperCase();
        const city   = s.city?.name || s.city?.city_name || '';
        const type   = s.type || '';
        const detail = [type, city].filter(Boolean).join(' · ');
        return `
        <div class="school-card ${isSel ? 'selected' : ''}" onclick="pickSchool(${s.id})" id="sc_${s.id}">
            <div class="school-logo">${esc(abbr)}</div>
            <div style="flex:1;min-width:0;">
                <div class="school-name">${esc(s.name || '—')}</div>
                <div class="school-info">${esc(detail)}</div>
            </div>
            <div class="check-ring" id="chk_${s.id}">${isSel ? '✓' : ''}</div>
        </div>`;
    }).join('');
}

function filterSchools() {
    const q    = document.getElementById('searchSch').value.toLowerCase();
    const lvl  = document.getElementById('filterLevel').value;
    const list = allSchools.filter(s => {
        const mq = !q || (s.name||'').toLowerCase().includes(q) || (s.city?.name||'').toLowerCase().includes(q);
        const ml = !lvl || String(s.level) === String(lvl) || String(s.school_level_id) === String(lvl);
        return mq && ml;
    });
    renderSchools(list);
}

function pickSchool(id) {
    // deselect prev
    if (selSchool) {
        const prev = document.getElementById('sc_' + selSchool.id);
        const prevChk = document.getElementById('chk_' + selSchool.id);
        if (prev) prev.classList.remove('selected');
        if (prevChk) prevChk.textContent = '';
    }
    selSchool = allSchools.find(s => s.id === id) || null;
    if (!selSchool) return;

    document.getElementById('sc_'  + id).classList.add('selected');
    document.getElementById('chk_' + id).textContent = '✓';

    applySchoolUI(selSchool);
}

// ══════════════════════════════════════════════════════════════
//  PAGE 2 — CASCADING LOCATION DROPDOWNS
// ══════════════════════════════════════════════════════════════
async function fetchProvinces() {
    const sw = document.getElementById('swProv');
    sw.classList.add('loading');
    try {
        const res   = await fetch(`${API_PUBLIC}/provinces`);
        const data  = await res.json();
        const items = normalize(data);
        const sel   = document.getElementById('selProv');
        items.forEach(p => addOpt(sel, p.id, p.name));
    } catch(e) { console.error('provinces:', e); }
    finally { sw.classList.remove('loading'); }
}

async function onProvChange(provId) {
    locText.provinsi  = selOptText('selProv');
    locText.kabupaten = '';
    locText.kecamatan = '';
    locText.kelurahan = '';

    resetSel('selCity',  '-- Pilih Kota/Kab --', true);
    resetSel('selDist',  '-- Pilih Kecamatan --', true);
    const subInp1 = document.getElementById('selSub'); if (subInp1) subInp1.value = '';
    if (!provId) return;

    const sw = document.getElementById('swCity');
    sw.classList.add('loading');
    try {
        // Coba berbagai query param yang mungkin digunakan API
        const res = await fetch(`${API_PUBLIC}/cities?prov=${provId}`);
        const data  = await res.json();
        const items = normalize(data);
        const sel   = document.getElementById('selCity');
        sel.innerHTML = '<option value="">-- Pilih Kota/Kab --</option>';
        items.forEach(c => addOpt(sel, c.id, c.name));
        sel.disabled = false;
    } catch(e) { console.error('cities:', e); }
    finally { sw.classList.remove('loading'); }
}

async function onCityChange(cityId) {
    locText.kabupaten = selOptText('selCity');
    locText.kecamatan = '';
    locText.kelurahan = '';

    resetSel('selDist', '-- Pilih Kecamatan --', true);
    const subInp2 = document.getElementById('selSub'); if (subInp2) subInp2.value = '';
    if (!cityId) return;

    const sw = document.getElementById('swDist');
    sw.classList.add('loading');
    try {
        const res = await fetch(`${API_PUBLIC}/districts?kab=${cityId}`);
        const data  = await res.json();
        const items = normalize(data);
        const sel   = document.getElementById('selDist');
        sel.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        items.forEach(d => addOpt(sel, d.id, d.name));
        sel.disabled = false;
    } catch(e) { console.error('districts:', e); }
    finally { sw.classList.remove('loading'); }
}

function onDistChange(distId) {
    locText.kecamatan = selOptText('selDist');
    locText.kelurahan = '';
    // Kelurahan diketik manual — reset field saja
    const inp = document.getElementById('selSub');
    if (inp) inp.value = '';
}

function onSubChange(val) {
    locText.kelurahan = val || '';
}

// ── Helpers untuk lokasi ──────────────────────────────────
function normalize(data) {
    // Support berbagai struktur response: { data: [] }, { data: { data: [] } }, []
    // Province: prov_id & prov_name | Subdistrict: subdis_id & subdis_name | City/District: id & name
    const raw = data?.data?.data || data?.data || data || [];
    return (Array.isArray(raw) ? raw : []).map(item => ({
        id:   item.id        ?? item.prov_id    ?? item.subdis_id   ?? '',
        name: item.name      ?? item.prov_name  ?? item.subdis_name ??
              item.city_name ?? item.district_name                  ?? '—'
    })).filter(x => x.id !== '');
}

function addOpt(sel, val, text) {
    const o = document.createElement('option');
    o.value = val; o.textContent = text; sel.appendChild(o);
}

function resetSel(id, placeholder, disable) {
    const el = document.getElementById(id);
    if (!el) return;
    el.innerHTML = `<option value="">${placeholder}</option>`;
    el.disabled  = disable;
}

function selOptText(id) {
    const el = document.getElementById(id);
    if (!el || !el.value) return '';
    return el.options[el.selectedIndex]?.text || '';
}

// ══════════════════════════════════════════════════════════════
//  PAGE 4 — REG FORMS
// ══════════════════════════════════════════════════════════════
async function fetchRegForms() {
    if (!selSchool) return;
    const loadBox  = document.getElementById('loadForms');
    const formWrap = document.getElementById('formSelWrap');

    loadBox.style.display  = '';
    formWrap.style.display = 'none';

    try {
        const res  = await fetch(`${API_PUBLIC}/registration-form/sch/${selSchool.id}`);
        const data = await res.json();
        regForms   = data.data || data || [];

        loadBox.style.display  = 'none';
        formWrap.style.display = '';

        const sel = document.getElementById('reg_form_id');
        sel.innerHTML = '<option value="">-- Pilih Gelombang --</option>';
        regForms.forEach(f => addOpt(sel, f.id, `${f.title || 'Gelombang'} – TA ${f.ta || '—'}`));

        if (regForms.length) {
            document.getElementById('pillTA').textContent = `📋 TA ${regForms[0].ta || '—'}`;
        } else {
            sel.innerHTML = '<option value="">Tidak ada gelombang aktif</option>';
        }

        document.getElementById('btnSubmit').style.display = '';
    } catch(e) {
        loadBox.innerHTML =
            '<span class="info-icon">⚠️</span><span style="color:var(--error)">Gagal memuat formulir pendaftaran.</span>';
    }
}

// ══════════════════════════════════════════════════════════════
//  NAVIGATION
// ══════════════════════════════════════════════════════════════
function goPage(n) {
    if (n > curPage && !validate(curPage)) return;

    const fromId = curPage === 0 ? 'page0' : 'page' + curPage;
    document.getElementById(fromId).classList.remove('active');

    const toId = n === 0 ? 'page0' : 'page' + n;
    document.getElementById(toId).classList.add('active');

    updateSteps(urlMode ? n : n + 1);
    updateNavInfo(n);
    curPage = n;

    if (n === 4) {
        fetchRegForms();
        buildReview();
    }
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function updateNavInfo(page) {
    const total = urlMode ? 4 : 5;
    // page0=langkah1 (manual), page1=langkah1 (url) or langkah2 (manual), etc.
    const step = urlMode ? page : page + 1;
    const el = document.getElementById('navInfo' + page);
    if (el) el.textContent = `Langkah ${step} dari ${total}`;
}

function updateSteps(active) {
    const total = urlMode ? 4 : 5;
    for (let i = 1; i <= total; i++) {
        const sc = document.getElementById('sc' + i);
        const sl = document.getElementById('sl' + i);
        const ln = document.getElementById('ln' + i);
        if (!sc) continue;
        sc.classList.remove('active', 'done');
        sl.classList.remove('active', 'done');
        if (ln) ln.classList.remove('done');

        if      (i < active)  { sc.classList.add('done');   sc.textContent = '✓'; sl.classList.add('done'); }
        else if (i === active){ sc.classList.add('active'); sc.textContent = i;   sl.classList.add('active'); }
        else                  { sc.textContent = i; }

        if (ln && i < active) ln.classList.add('done');
    }
}

// ══════════════════════════════════════════════════════════════
//  VALIDATION
// ══════════════════════════════════════════════════════════════
function validate(page) {
    clearErrs();
    let ok = true;

    if (page === 0) {
        if (!selSchool) {
            const eb = document.getElementById('p0Err');
            eb.textContent = 'Pilih sekolah tujuan terlebih dahulu.';
            eb.style.display = '';
            return false;
        }
    }
    if (page === 1) {
        ok = chk('nama',         'Nama lengkap wajib diisi')    && ok;
        ok = chkLen('nik', 16,   'NIK harus 16 digit')          && ok;
        ok = chk('nisn',         'NISN wajib diisi')            && ok;
        ok = chk('tempat_lahir', 'Tempat lahir wajib diisi')    && ok;
        ok = chk('tanggal_lahir','Tanggal lahir wajib diisi')   && ok;
        ok = chk('jk',           'Jenis kelamin wajib dipilih') && ok;
        ok = chk('agama',        'Agama wajib dipilih')         && ok;
        ok = chk('no_hp',        'No. HP wajib diisi')          && ok;
        ok = chkEmail('email',   'Format email tidak valid')    && ok;
        ok = chk('sekolah_asal', 'Asal sekolah wajib diisi')   && ok;
        if (!ok) showErr('p1Err', 'Harap lengkapi field bertanda bintang (*)');
    }
    if (page === 4) {
        ok = chk('reg_form_id',   'Gelombang wajib dipilih',   'e_reg_form')     && ok;
        ok = chk('school_origin', 'Asal sekolah wajib diisi',  'e_school_origin') && ok;
        if (!ok) showErr('p4Err', 'Harap lengkapi pilihan pendaftaran');
    }
    return ok;
}

function chk(id, msg, eid) {
    const el = document.getElementById(id);
    if (!el || !el.value.trim()) { fe(eid || 'e_' + id, msg); if (el) el.classList.add('err'); return false; }
    return true;
}
function chkLen(id, len, msg) {
    const el = document.getElementById(id);
    if (!el || el.value.replace(/\D/g, '').length !== len) { fe('e_' + id, msg); if (el) el.classList.add('err'); return false; }
    return true;
}
function chkEmail(id, msg) {
    const el = document.getElementById(id);
    if (!el || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(el.value)) { fe('e_' + id, msg); if (el) el.classList.add('err'); return false; }
    return true;
}
function fe(id, msg) { const el = document.getElementById(id); if (el) { el.textContent = msg; el.classList.add('show'); } }
function showErr(id, msg) { const el = document.getElementById(id); if (el) { el.textContent = msg; el.style.display = ''; } }
function clearErrs() {
    document.querySelectorAll('.field-err').forEach(e => { e.classList.remove('show'); e.textContent = ''; });
    document.querySelectorAll('.form-input,.form-select').forEach(e => e.classList.remove('err'));
    ['p0Err','p1Err','p4Err'].forEach(id => { const el = document.getElementById(id); if (el) el.style.display = 'none'; });
}

// ══════════════════════════════════════════════════════════════
//  BUILD REVIEW
// ══════════════════════════════════════════════════════════════
function buildReview() {
    const formSel  = document.getElementById('reg_form_id');
    const formName = formSel.options[formSel.selectedIndex]?.text || '—';

    // School box
    document.getElementById('rvSchool').innerHTML = `
        <div class="rv-school-ico">🏫</div>
        <div>
            <div class="rv-school-name">${esc(selSchool?.name || '—')}</div>
            <div class="rv-school-form">${esc(formName)}</div>
        </div>`;

    const rows = [
        ['Nama Lengkap',  g('nama')],
        ['NISN',          g('nisn')],
        ['NIK',           g('nik')],
        ['Tempat Lahir',  g('tempat_lahir')],
        ['Tanggal Lahir', g('tanggal_lahir')],
        ['Jenis Kelamin', g('jk')],
        ['Agama',         g('agama')],
        ['Email',         g('email')],
        ['No. HP',        g('no_hp')],
        ['Asal Sekolah',  g('sekolah_asal')],
        ['Provinsi',      locText.provinsi  || '—'],
        ['Kota/Kab',      locText.kabupaten || '—'],
        ['Kecamatan',     locText.kecamatan || '—'],
        ['Kelurahan',     locText.kelurahan || '—'],
    ];

    document.getElementById('rvGrid').innerHTML = rows.map(([l, v]) => `
        <div class="rv-item">
            <div class="rv-label">${l}</div>
            <div class="rv-val">${v || '—'}</div>
        </div>`).join('');

    document.getElementById('reviewSec').style.display = '';
}

// ══════════════════════════════════════════════════════════════
//  SUBMIT
// ══════════════════════════════════════════════════════════════
async function doSubmit() {
    if (!validate(4)) return;

    const btn = document.getElementById('btnSubmit');
    btn.classList.add('btn-spin');
    btn.textContent = 'Memproses...';
    btn.disabled    = true;
    document.getElementById('p4Err').style.display = 'none';

    try {
        // ── STEP 1: Buat UserCandidate ───────────────────
        const payload = {
            nik:                     g('nik'),
            nama:                    g('nama'),
            nisn:                    g('nisn'),
            tempat_lahir:            g('tempat_lahir'),
            tanggal_lahir:           g('tanggal_lahir'),
            jk:                      g('jk'),
            agama:                   g('agama'),
            kewarganegaraan:         g('kewarganegaraan') || 'WNI',
            no_hp:                   g('no_hp'),
            no_telepon:              g('no_telepon')              || null,
            email:                   g('email'),
            password:                '123456',
            alamat:                  g('alamat')                  || null,
            rt:                      g('rt')                      || null,
            rw:                      g('rw')                      || null,
            dusun:                   g('dusun')                   || null,
            kelurahan:               locText.kelurahan             || null,
            kecamatan:               locText.kecamatan             || null,
            kabupaten:               locText.kabupaten             || null,
            provinsi:                locText.provinsi              || null,
            kode_pos:                g('kode_pos')                || null,
            jenis_tinggal:           g('jenis_tinggal')           || null,
            alat_transportasi:       g('alat_transportasi')       || null,
            jarak_rumah_kesekolah:   gNum('jarak_rumah_kesekolah'),
            berat_badan:             gNum('berat_badan'),
            tinggi_badan:            gNum('tinggi_badan'),
            no_kk:                   g('no_kk')                   || null,
            anak_ke:                 gInt('anak_ke'),
            jml_saudara_kandung:     gInt('jml_saudara_kandung'),
            kebutuhan_khusus:        g('kebutuhan_khusus')        || null,
            sekolah_asal:            g('sekolah_asal')            || null,
            // Ayah
            nik_ayah:                g('nik_ayah')                || null,
            nama_ayah:               g('nama_ayah')               || null,
            tahun_lahir_ayah:        g('tahun_lahir_ayah')        || null,
            jenjang_pendidikan_ayah: g('jenjang_pendidikan_ayah') || null,
            pekerjaan_ayah:          g('pekerjaan_ayah')          || null,
            penghasilan_ayah:        g('penghasilan_ayah')        || null,
            // Ibu
            nik_ibu:                 g('nik_ibu')                 || null,
            nama_ibu:                g('nama_ibu')                || null,
            tahun_lahir_ibu:         g('tahun_lahir_ibu')         || null,
            jenjang_pendidikan_ibu:  g('jenjang_pendidikan_ibu')  || null,
            pekerjaan_ibu:           g('pekerjaan_ibu')           || null,
            penghasilan_ibu:         g('penghasilan_ibu')         || null,
            // Wali
            nik_wali:                g('nik_wali')                || null,
            nama_wali:               g('nama_wali')               || null,
            tahun_lahir_wali:        g('tahun_lahir_wali')        || null,
            jenjang_pendidikan_wali: g('jenjang_pendidikan_wali') || null,
            pekerjaan_wali:          g('pekerjaan_wali')          || null,
            penghasilan_wali:        g('penghasilan_wali')        || null,
        };

        const r1 = await fetch(`${API}/student`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(payload)
        });
        const d1 = await r1.json();

        if (!r1.ok) { renderApiErr(d1); resetBtn(btn); return; }

        const studentId = d1.data?.id || d1.id;
        if (!studentId) { showErr('p4Err', 'Gagal mendapatkan ID siswa dari server.'); resetBtn(btn); return; }

        // ── STEP 2: Submit Pendaftaran ───────────────────
        const r2 = await fetch(`${API}/registration`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({
                student_id:           studentId,
                school_id:            selSchool.id,
                registration_form_id: parseInt(g('reg_form_id')),
                school_origin:        g('school_origin') || g('sekolah_asal'),
                registered_by:        '9',
                status:               '0',
            })
        });
        const d2 = await r2.json();

        if (!r2.ok) { renderApiErr(d2); resetBtn(btn); return; }

        // ── SUCCESS ──────────────────────────────────────
        document.getElementById('page4').classList.remove('active');
        document.getElementById('pageSuccess').classList.add('active');
        document.getElementById('credNama').textContent  = g('nama');
        document.getElementById('credEmail').textContent = g('email');
        document.getElementById('succSchool').textContent = selSchool?.name || 'sekolah';
        window.scrollTo({ top: 0, behavior: 'smooth' });

    } catch(e) {
        showErr('p4Err', 'Koneksi error. Periksa jaringan dan coba lagi.');
        resetBtn(btn);
    }
}

function renderApiErr(result) {
    let msg = '<strong>Gagal mendaftar:</strong><ul>';
    if (result.errors) {
        for (const f in result.errors) result.errors[f].forEach(m => { msg += `<li>${m}</li>`; });
    } else if (result.message) {
        msg += `<li>${result.message}</li>`;
    } else {
        msg += '<li>Terjadi kesalahan. Coba lagi.</li>';
    }
    msg += '</ul>';
    const eb = document.getElementById('p4Err');
    eb.innerHTML = msg; eb.style.display = '';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function resetBtn(btn) {
    btn.classList.remove('btn-spin');
    btn.textContent = '✓ Daftar Sekarang';
    btn.disabled    = false;
}

// ── Helpers ──────────────────────────────────────────────────
function g(id)    { const el = document.getElementById(id); return el ? el.value.trim() : ''; }
function gNum(id) { const v = g(id); return v !== '' ? parseFloat(v) : null; }
function gInt(id) { const v = g(id); return v !== '' ? parseInt(v)   : null; }
function esc(str) {
    if (str == null) return '';
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}
</script>
</body>
</html>