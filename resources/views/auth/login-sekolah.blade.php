<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal Sekolah — Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --green-950: #052010;
            --green-900: #0a3d20;
            --green-800: #145a30;
            --green-700: #1a7a40;
            --green-600: #16a34a;
            --green-500: #22c55e;
            --green-400: #4ade80;
            --green-300: #86efac;
            --green-100: #dcfce7;
            --amber-400: #fbbf24;
            --amber-300: #fcd34d;
            --white: #ffffff;
            --gray-50:  #f8fafc;
            --gray-200: #e2e8f0;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-900: #0f172a;
        }

        html, body {
            height: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--green-950);
            overflow: hidden;
        }

        /* ── Animated background ── */
        .bg-base {
            position: fixed; inset: 0;
            background: linear-gradient(160deg, #052010 0%, #0a3d20 45%, #0d1f0d 100%);
        }

        /* Subtle leaf/organic pattern overlay */
        .bg-pattern {
            position: fixed; inset: 0;
            opacity: 0.04;
            background-image:
                radial-gradient(circle at 15% 25%, var(--green-400) 1px, transparent 1px),
                radial-gradient(circle at 85% 75%, var(--green-400) 1px, transparent 1px),
                radial-gradient(circle at 50% 50%, var(--green-300) 1px, transparent 1px);
            background-size: 80px 80px, 120px 120px, 60px 60px;
        }

        /* Glowing orbs */
        .orb {
            position: fixed; border-radius: 50%;
            filter: blur(100px); pointer-events: none;
        }
        .orb-1 {
            width: 600px; height: 600px;
            background: radial-gradient(circle, #16a34a 0%, transparent 70%);
            top: -200px; left: -150px; opacity: 0.12;
            animation: pulse1 9s ease-in-out infinite;
        }
        .orb-2 {
            width: 450px; height: 450px;
            background: radial-gradient(circle, #22c55e 0%, transparent 70%);
            bottom: -100px; right: -100px; opacity: 0.10;
            animation: pulse2 12s ease-in-out infinite;
        }
        .orb-3 {
            width: 250px; height: 250px;
            background: radial-gradient(circle, #fbbf24 0%, transparent 70%);
            top: 40%; left: 55%; opacity: 0.06;
            animation: pulse3 7s ease-in-out infinite;
        }

        @keyframes pulse1 { 0%,100%{transform:scale(1) translate(0,0);} 50%{transform:scale(1.1) translate(20px,-20px);} }
        @keyframes pulse2 { 0%,100%{transform:scale(1) translate(0,0);} 50%{transform:scale(1.08) translate(-15px,15px);} }
        @keyframes pulse3 { 0%,100%{transform:scale(1);opacity:.06;} 50%{transform:scale(1.2);opacity:.10;} }

        /* ── Scanline texture ── */
        .scanlines {
            position: fixed; inset: 0; pointer-events: none;
            background: repeating-linear-gradient(
                0deg, transparent, transparent 2px,
                rgba(0,0,0,0.03) 2px, rgba(0,0,0,0.03) 4px
            );
        }

        /* ── Layout ── */
        .wrapper {
            position: relative; z-index: 10;
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 460px;
        }

        /* ── Left Panel ── */
        .left {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 32px 60px;
            border-right: 1px solid rgba(34,197,94,0.08);
            overflow-y: auto;
        }

        .brand {
            display: flex; align-items: center; gap: 14px;
        }
        .brand-logo {
            width: 44px; height: 44px; border-radius: 12px;
            background: linear-gradient(135deg, var(--green-700), var(--green-500));
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 14px; color: white;
            box-shadow: 0 0 20px rgba(34,197,94,0.3);
        }
        .brand-name {
            font-size: 14px; font-weight: 700;
            color: rgba(255,255,255,0.9); letter-spacing: 0.02em;
        }
        .brand-sub {
            font-size: 11px; color: var(--green-400); margin-top: 1px;
            font-family: 'JetBrains Mono', monospace; letter-spacing: 0.05em;
        }

        /* Center feature showcase */
        .showcase {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 20px 0;
        }

        .showcase-eyebrow {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px; font-weight: 500;
            color: var(--green-400);
            letter-spacing: 0.12em; text-transform: uppercase;
            margin-bottom: 16px;
            display: flex; align-items: center; gap: 8px;
        }
        .showcase-eyebrow::before {
            content: '';
            display: block; width: 24px; height: 1px;
            background: var(--green-500);
        }

        .showcase-title {
            font-size: clamp(32px, 3.5vw, 48px);
            font-weight: 800;
            color: white;
            line-height: 1.15;
            margin-bottom: 18px;
            letter-spacing: -0.02em;
        }
        .showcase-title .accent {
            background: linear-gradient(90deg, var(--green-400), var(--amber-300));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .showcase-desc {
            font-size: 15px; color: rgba(255,255,255,0.5);
            line-height: 1.7; max-width: 420px;
            margin-bottom: 24px;
        }

        /* Feature pills */
        .features {
            display: flex; flex-direction: column; gap: 8px;
        }
        .feat {
            display: flex; align-items: center; gap: 14px;
            padding: 14px 18px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(34,197,94,0.1);
            border-radius: 10px;
            transition: border-color .2s, background .2s;
        }
        .feat:hover { border-color: rgba(34,197,94,0.25); background: rgba(34,197,94,0.04); }
        .feat-icon {
            width: 36px; height: 36px; border-radius: 8px; flex-shrink: 0;
            background: rgba(34,197,94,0.12);
            display: flex; align-items: center; justify-content: center;
            font-size: 16px;
        }
        .feat-text { font-size: 13px; color: rgba(255,255,255,0.7); font-weight: 500; }
        .feat-sub  { font-size: 11px; color: rgba(255,255,255,0.35); margin-top: 1px; }

        /* Bottom stats */
        .stats {
            display: flex; gap: 32px;
        }
        .stat-val {
            font-size: 20px; font-weight: 800; color: var(--green-400);
            font-family: 'JetBrains Mono', monospace;
        }
        .stat-label { font-size: 11px; color: rgba(255,255,255,0.35); margin-top: 2px; }

        /* ── Right Panel (Login Form) ── */
        .right {
            background: rgba(255,255,255,0.025);
            backdrop-filter: blur(20px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 28px 44px;
            border-left: 1px solid rgba(255,255,255,0.06);
            overflow-y: auto;
        }

        /* Role tabs */
        .role-tabs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: rgba(0,0,0,0.3);
            border-radius: 12px;
            padding: 4px;
            margin-bottom: 20px;
            border: 1px solid rgba(255,255,255,0.06);
        }
        .tab-btn {
            padding: 10px 16px;
            border: none; background: transparent; cursor: pointer;
            border-radius: 9px;
            transition: all .2s;
            text-align: center;
        }
        .tab-btn .tab-icon { font-size: 16px; display: block; margin-bottom: 3px; }
        .tab-btn .tab-label {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 12px; font-weight: 700;
            color: rgba(255,255,255,0.4);
            display: block; letter-spacing: 0.02em;
        }
        .tab-btn .tab-sub {
            font-size: 10px; color: rgba(255,255,255,0.25);
            font-family: 'JetBrains Mono', monospace;
        }
        .tab-btn.active {
            background: linear-gradient(135deg, rgba(22,163,74,0.25), rgba(34,197,94,0.15));
            border: 1px solid rgba(34,197,94,0.3);
            box-shadow: 0 2px 12px rgba(34,197,94,0.15);
        }
        .tab-btn.active .tab-label { color: var(--green-300); }
        .tab-btn.active .tab-sub   { color: var(--green-400); opacity: .7; }

        /* Form header */
        .form-header { margin-bottom: 18px; }
        .form-title {
            font-size: 22px; font-weight: 800; color: white;
            letter-spacing: -0.02em; margin-bottom: 6px;
        }
        .form-sub { font-size: 13px; color: rgba(255,255,255,0.4); line-height: 1.5; }

        /* Fields */
        .field { margin-bottom: 12px; }
        .field-label {
            display: block; font-size: 11px; font-weight: 700;
            color: rgba(255,255,255,0.45); text-transform: uppercase;
            letter-spacing: 0.08em; margin-bottom: 7px;
        }
        .field-wrap { position: relative; }
        .field-icon {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%);
            font-size: 15px; pointer-events: none;
            opacity: 0.5;
        }
        .field-input {
            width: 100%; padding: 12px 14px 12px 42px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px; color: white;
            outline: none;
            transition: all .2s;
        }
        .field-input::placeholder { color: rgba(255,255,255,0.2); }
        .field-input:focus {
            border-color: rgba(34,197,94,0.5);
            background: rgba(34,197,94,0.05);
            box-shadow: 0 0 0 3px rgba(34,197,94,0.08);
        }
        .field-input.err { border-color: rgba(239,68,68,0.6); }

        /* Password toggle */
        .pass-toggle {
            position: absolute; right: 13px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: rgba(255,255,255,0.3); font-size: 15px;
            padding: 4px; transition: color .15s;
        }
        .pass-toggle:hover { color: rgba(255,255,255,0.7); }

        /* Remember row */
        .remember-row {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 16px;
        }
        .remember-check {
            display: flex; align-items: center; gap: 8px;
            cursor: pointer; font-size: 12px; color: rgba(255,255,255,0.4);
        }
        .remember-check input { accent-color: var(--green-500); width: 14px; height: 14px; cursor: pointer; }
        .forgot-link {
            font-size: 12px; color: var(--green-400);
            text-decoration: none; font-weight: 600;
            transition: color .15s;
        }
        .forgot-link:hover { color: var(--green-300); }

        /* Submit button */
        .btn-submit {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, var(--green-700), var(--green-600));
            border: 1px solid rgba(34,197,94,0.3);
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px; font-weight: 700; color: white;
            cursor: pointer;
            transition: all .2s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            box-shadow: 0 4px 20px rgba(34,197,94,0.2);
            position: relative; overflow: hidden;
        }
        .btn-submit::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), transparent);
            opacity: 0; transition: opacity .2s;
        }
        .btn-submit:hover::before { opacity: 1; }
        .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 24px rgba(34,197,94,0.3); }
        .btn-submit:active { transform: translateY(0); }
        .btn-submit:disabled { opacity: .55; cursor: not-allowed; transform: none; }
        .btn-submit.loading { pointer-events: none; }
        .btn-submit.loading .btn-text { opacity: 0; }
        .btn-submit.loading::after {
            content: '';
            position: absolute;
            width: 18px; height: 18px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin .6s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Alert */
        .alert {
            padding: 11px 14px; border-radius: 8px; margin-bottom: 18px;
            font-size: 12px; font-weight: 600; display: none;
            border: 1px solid;
        }
        .alert.err { background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.3); color: #fca5a5; }
        .alert.show { display: block; animation: slideIn .2s ease; }
        @keyframes slideIn { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:translateY(0); } }

        /* Bottom link */
        .bottom-link {
            text-align: center; margin-top: 24px;
            font-size: 12px; color: rgba(255,255,255,0.3);
        }
        .bottom-link a { color: var(--green-400); text-decoration: none; font-weight: 600; }
        .bottom-link a:hover { color: var(--green-300); }

        /* Divider */
        .divider {
            display: flex; align-items: center; gap: 12px;
            margin: 20px 0;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1; height: 1px;
            background: rgba(255,255,255,0.07);
        }
        .divider span { font-size: 11px; color: rgba(255,255,255,0.2); white-space: nowrap; }

        /* Security badge */
        .security-badge {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            font-size: 11px; color: rgba(255,255,255,0.2);
            font-family: 'JetBrains Mono', monospace;
            margin-top: 12px;
        }
        .security-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--green-500);
            box-shadow: 0 0 6px var(--green-500);
            animation: blink 2s ease-in-out infinite;
        }
        @keyframes blink { 0%,100%{opacity:1;} 50%{opacity:.3;} }

        /* ── Responsive ── */
        @media (max-width: 860px) {
            .wrapper { grid-template-columns: 1fr; overflow-y: auto; }
            html, body { overflow: auto; }
            .left { display: none; }
            .right { min-height: 100vh; padding: 40px 28px; justify-content: flex-start; padding-top: 60px; }
        }
    </style>
</head>
<body>

<div class="bg-base"></div>
<div class="bg-pattern"></div>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>
<div class="scanlines"></div>

<div class="wrapper">

    <!-- ── Left Panel ── -->
    <div class="left">
        <div class="brand">
            <div class="brand-logo">SIP</div>
            <div>
                <div class="brand-name">Sistem Informasi Pendidikan</div>
                <div class="brand-sub">PORTAL SEKOLAH v2.0</div>
            </div>
        </div>

        <div class="showcase">
            <div class="showcase-eyebrow">Portal Manajemen Sekolah</div>
            <h1 class="showcase-title">
                Kelola sekolah<br>
                <span class="accent">lebih efisien</span><br>
                dan terstruktur
            </h1>
            <p class="showcase-desc">
                Platform terpadu untuk admin dan pimpinan sekolah dalam mengelola data siswa, pendaftaran, dan informasi akademik secara real-time.
            </p>

            <div class="features">
                <div class="feat">
                    <div class="feat-icon">📊</div>
                    <div>
                        <div class="feat-text">Dashboard Statistik Real-time</div>
                        <div class="feat-sub">Monitor data siswa dan pendaftaran setiap saat</div>
                    </div>
                </div>
                <div class="feat">
                    <div class="feat-icon">🎓</div>
                    <div>
                        <div class="feat-text">Manajemen Pendaftaran Siswa</div>
                        <div class="feat-sub">Proses PPDB dengan alur terstruktur</div>
                    </div>
                </div>
                <div class="feat">
                    <div class="feat-icon">🏫</div>
                    <div>
                        <div class="feat-text">Profil & Informasi Sekolah</div>
                        <div class="feat-sub">Kelola fasilitas, guru, dan ekskul</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="stats">
            <div>
                <div class="stat-val">100%</div>
                <div class="stat-label">Data Terenkripsi</div>
            </div>
            <div>
                <div class="stat-val">24/7</div>
                <div class="stat-label">Akses Online</div>
            </div>
            <div>
                <div class="stat-val">v2.0</div>
                <div class="stat-label">Versi Terbaru</div>
            </div>
        </div>
    </div>

    <!-- ── Right Panel ── -->
    <div class="right">

        <!-- Role Tabs -->
        <div class="role-tabs">
            <button class="tab-btn active" id="tab-school_admin" onclick="switchRole('school_admin', this)">
                <span class="tab-icon">⚙️</span>
                <span class="tab-label">Admin Sekolah</span>
                <span class="tab-sub">school_admin</span>
            </button>
            <button class="tab-btn" id="tab-school_head" onclick="switchRole('school_head', this)">
                <span class="tab-icon">👨‍💼</span>
                <span class="tab-label">Kepala Sekolah</span>
                <span class="tab-sub">school_head</span>
            </button>
        </div>

        <!-- Form Header -->
        <div class="form-header">
            <div class="form-title" id="formTitle">Selamat Datang, Admin</div>
            <div class="form-sub" id="formSub">Masuk ke portal manajemen sekolah Anda</div>
        </div>

        <!-- Alert -->
        <div class="alert err" id="alertBox"></div>

        <!-- Form -->
        <form id="loginForm" onsubmit="doLogin(event)">
            <input type="hidden" id="roleInput" value="admin">

            <div class="field">
                <label class="field-label">Email</label>
                <div class="field-wrap">
                    <span class="field-icon">✉️</span>
                    <input
                        type="email"
                        class="field-input"
                        id="emailInput"
                        placeholder="email@sekolah.com"
                        required
                        autocomplete="email"
                    >
                </div>
            </div>

            <div class="field">
                <label class="field-label">Password</label>
                <div class="field-wrap">
                    <span class="field-icon">🔒</span>
                    <input
                        type="password"
                        class="field-input"
                        id="passInput"
                        placeholder="Masukkan password"
                        required
                        autocomplete="current-password"
                    >
                    <button type="button" class="pass-toggle" onclick="togglePass()">👁</button>
                </div>
            </div>

            <div class="remember-row">
                <label class="remember-check">
                    <input type="checkbox" id="rememberMe"> Ingat saya
                </label>
                <a href="#" class="forgot-link">Lupa password?</a>
            </div>

            <button type="submit" class="btn-submit" id="btnLogin">
                <span class="btn-text">Masuk ke Portal</span>
            </button>
        </form>

        <div class="security-badge">
            <div class="security-dot"></div>
            Koneksi aman · SSL Encrypted
        </div>
    </div>

</div>

<script>
    // Konfigurasi Role, Type, dan Tampilan
    const ROLES_CONFIG = {
        school_admin: { 
            role: 'admin', 
            type: 'school_admin', 
            title: 'Selamat Datang, Admin', 
            sub: 'Masuk ke portal manajemen sekolah Anda',
            redirect: '/school-dashboard' 
        },
        school_head: { 
            role: 'superadmin', 
            type: 'school_head', 
            title: 'Selamat Datang, Kepala Sekolah', 
            sub: 'Akses laporan dan pengawasan sekolah Anda',
            redirect: '/pimpinan-sekolah' 
        },
    };

    let currentKey = 'school_admin'; // Default tab aktif

    function switchRole(key, el) {
        currentKey = key;
        const config = ROLES_CONFIG[key];

        // Update UI Tab
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        el.classList.add('active');

        // Update Form Text
        document.getElementById('formTitle').textContent = config.title;
        document.getElementById('formSub').textContent = config.sub;
        
        // Update hidden input jika dipakai, tapi di sini kita pakai variabel js
        document.getElementById('roleInput').value = config.role; 
        
        hideAlert();
    }

    function togglePass() {
        const inp = document.getElementById('passInput');
        inp.type = inp.type === 'password' ? 'text' : 'password';
    }

    function showAlert(msg) {
        const el = document.getElementById('alertBox');
        el.textContent = msg;
        el.classList.add('show');
    }
    function hideAlert() {
        const el = document.getElementById('alertBox');
        el.classList.remove('show');
    }

    async function doLogin(e) {
        e.preventDefault();
        hideAlert();

        const btn   = document.getElementById('btnLogin');
        const email = document.getElementById('emailInput').value.trim();
        const pass  = document.getElementById('passInput').value;

        const config = ROLES_CONFIG[currentKey];

        btn.classList.add('loading');
        btn.disabled = true;

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            
            // 1. Gunakan endpoint dinamis berdasarkan Role (admin atau superadmin)
            // Hasil: /login/admin atau /login/superadmin
            const endpoint = `/login/${config.role}`;

            const res = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ email, password: pass })
            });

            const result = await res.json();

            if (!res.ok) {
                showAlert(result.message || 'Email atau password salah.');
                return;
            }

            // 2. Cek apakah login sukses dan ada token
            if (result.token || result.access_token) {
                localStorage.setItem('token', result.token || result.access_token);

                // 3. Validasi Response dari Backend
                const respRole = result.role; // Ambil role dari response
                const respType = result.type; // Ambil type dari response

                // Cek apakah data user cocok dengan tab yang dipilih
                if (respRole !== config.role || respType !== config.type) {
                    localStorage.removeItem('token');
                    // Pesan error jika user punya akses tapi salah tab
                    showAlert(`Akun ini terdaftar sebagai ${respRole} (${respType}). Silakan pilih tab yang sesuai.`);
                    return;
                }

                // 4. Redirect berdasarkan konfigurasi
                window.location.href = config.redirect;

            } else {
                showAlert(result.message || 'Login gagal. Coba lagi.');
            }

        } catch (err) {
            console.error(err);
            showAlert('Koneksi error. Periksa jaringan dan coba lagi.');
        } finally {
            btn.classList.remove('loading');
            btn.disabled = false;
        }
    }
</script>
</body>
</html>