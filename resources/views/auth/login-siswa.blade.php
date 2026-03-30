<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIP — Login Siswa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --blue-900: #1e3a5f;
            --blue-800: #1e4976;
            --blue-700: #1a5c94;
            --blue-600: #2563eb;
            --blue-500: #3b82f6;
            --blue-400: #60a5fa;
            --blue-100: #dbeafe;
            --blue-50:  #eff6ff;
            --amber:    #f59e0b;
            --white:    #ffffff;
            --gray-50:  #f8fafc;
            --gray-400: #94a3b8;
            --gray-600: #475569;
            --gray-900: #0f172a;
        }

        html, body { height: 100%; font-family: 'Plus Jakarta Sans', sans-serif; background: #0c1a2e; overflow: hidden; }

        /* ── Background ── */
        .bg {
            position: fixed; inset: 0;
            background: linear-gradient(160deg, #0c1a2e 0%, #0f2a4a 50%, #0a1520 100%);
        }
        .bg-dots {
            position: fixed; inset: 0; opacity: .03;
            background-image: radial-gradient(circle, #60a5fa 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .orb-1 {
            position: fixed; width: 500px; height: 500px; border-radius: 50%;
            background: radial-gradient(circle, #2563eb 0%, transparent 70%);
            top: -150px; left: -100px; opacity: .10; filter: blur(80px);
            animation: p1 10s ease-in-out infinite;
        }
        .orb-2 {
            position: fixed; width: 350px; height: 350px; border-radius: 50%;
            background: radial-gradient(circle, #3b82f6 0%, transparent 70%);
            bottom: -100px; right: -80px; opacity: .08; filter: blur(80px);
            animation: p2 13s ease-in-out infinite;
        }
        @keyframes p1 { 0%,100%{transform:scale(1) translate(0,0)} 50%{transform:scale(1.1) translate(20px,-15px)} }
        @keyframes p2 { 0%,100%{transform:scale(1) translate(0,0)} 50%{transform:scale(1.08) translate(-15px,10px)} }

        /* ── Layout ── */
        .wrapper {
            position: relative; z-index: 10;
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 420px;
        }

        /* ── Left panel ── */
        .left {
            display: flex; flex-direction: column;
            justify-content: space-between;
            padding: 36px 60px;
            border-right: 1px solid rgba(96,165,250,.06);
        }

        .brand { display: flex; align-items: center; gap: 14px; }
        .brand-logo {
            width: 42px; height: 42px; border-radius: 11px;
            background: linear-gradient(135deg, var(--blue-700), var(--blue-500));
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 14px; color: white;
            box-shadow: 0 0 20px rgba(37,99,235,.3);
        }
        .brand-name { font-size: 14px; font-weight: 700; color: rgba(255,255,255,.9); }
        .brand-sub  { font-size: 11px; color: var(--blue-400); margin-top: 1px; }

        .showcase { flex: 1; display: flex; flex-direction: column; justify-content: center; padding: 20px 0; }

        .eyebrow {
            font-size: 11px; font-weight: 600; color: var(--blue-400);
            text-transform: uppercase; letter-spacing: .12em;
            display: flex; align-items: center; gap: 8px; margin-bottom: 16px;
        }
        .eyebrow::before { content: ''; display: block; width: 24px; height: 1px; background: var(--blue-500); }

        .showcase-title {
            font-size: clamp(30px, 3vw, 44px); font-weight: 800; color: white;
            line-height: 1.15; margin-bottom: 16px; letter-spacing: -.02em;
        }
        .showcase-title .accent {
            background: linear-gradient(90deg, var(--blue-400), var(--amber));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }

        .showcase-desc { font-size: 15px; color: rgba(255,255,255,.45); line-height: 1.7; max-width: 400px; margin-bottom: 28px; }

        .steps { display: flex; flex-direction: column; gap: 10px; }
        .step {
            display: flex; align-items: center; gap: 14px;
            padding: 12px 16px;
            background: rgba(255,255,255,.03);
            border: 1px solid rgba(96,165,250,.08);
            border-radius: 10px;
            transition: border-color .2s;
        }
        .step:hover { border-color: rgba(96,165,250,.2); }
        .step-num {
            width: 28px; height: 28px; border-radius: 8px; flex-shrink: 0;
            background: rgba(37,99,235,.2); border: 1px solid rgba(37,99,235,.3);
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; color: var(--blue-400);
        }
        .step-text { font-size: 13px; color: rgba(255,255,255,.65); font-weight: 500; }
        .step-sub  { font-size: 11px; color: rgba(255,255,255,.3); margin-top: 1px; }

        .daftar-link {
            margin-top: 20px; font-size: 13px; color: rgba(255,255,255,.4);
        }
        .daftar-link a { color: var(--blue-400); text-decoration: none; font-weight: 600; }
        .daftar-link a:hover { color: var(--blue-300, #93c5fd); }

        /* ── Right panel (form) ── */
        .right {
            background: rgba(255,255,255,.025);
            backdrop-filter: blur(20px);
            display: flex; flex-direction: column; justify-content: center;
            padding: 32px 44px;
            border-left: 1px solid rgba(255,255,255,.05);
        }

        .form-title { font-size: 22px; font-weight: 800; color: white; letter-spacing: -.02em; margin-bottom: 6px; }
        .form-sub   { font-size: 13px; color: rgba(255,255,255,.4); margin-bottom: 24px; }

        /* Alert */
        .alert {
            padding: 10px 14px; border-radius: 8px; margin-bottom: 18px;
            font-size: 12px; font-weight: 600; display: none; border: 1px solid;
        }
        .alert.err  { background: rgba(239,68,68,.1); border-color: rgba(239,68,68,.3); color: #fca5a5; }
        .alert.show { display: block; animation: slideIn .2s ease; }
        @keyframes slideIn { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }

        /* Field */
        .field { margin-bottom: 14px; }
        .field-label {
            display: block; font-size: 11px; font-weight: 700;
            color: rgba(255,255,255,.4); text-transform: uppercase;
            letter-spacing: .08em; margin-bottom: 7px;
        }
        .field-wrap { position: relative; }
        .field-icon {
            position: absolute; left: 13px; top: 50%;
            transform: translateY(-50%); font-size: 14px; pointer-events: none; opacity: .4;
        }
        .field-input {
            width: 100%; padding: 12px 14px 12px 40px;
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px; color: white; outline: none; transition: all .2s;
        }
        .field-input::placeholder { color: rgba(255,255,255,.2); }
        .field-input:focus {
            border-color: rgba(37,99,235,.5);
            background: rgba(37,99,235,.05);
            box-shadow: 0 0 0 3px rgba(37,99,235,.08);
        }
        .pass-toggle {
            position: absolute; right: 12px; top: 50%;
            transform: translateY(-50%); background: none; border: none;
            cursor: pointer; color: rgba(255,255,255,.3); font-size: 14px;
            padding: 4px; transition: color .15s;
        }
        .pass-toggle:hover { color: rgba(255,255,255,.7); }

        .remember-row {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 18px;
        }
        .remember-check {
            display: flex; align-items: center; gap: 8px;
            font-size: 12px; color: rgba(255,255,255,.4); cursor: pointer;
        }
        .remember-check input { accent-color: var(--blue-500); }
        .forgot-link { font-size: 12px; color: var(--blue-400); text-decoration: none; font-weight: 600; }

        /* Submit btn */
        .btn-submit {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, var(--blue-700), var(--blue-600));
            border: 1px solid rgba(37,99,235,.3);
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px; font-weight: 700; color: white;
            cursor: pointer; transition: all .2s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            box-shadow: 0 4px 20px rgba(37,99,235,.2);
            position: relative; overflow: hidden;
        }
        .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 24px rgba(37,99,235,.3); }
        .btn-submit:active { transform: translateY(0); }
        .btn-submit:disabled { opacity: .55; cursor: not-allowed; transform: none; }
        .btn-submit.loading .btn-text { opacity: 0; }
        .btn-submit.loading::after {
            content: ''; position: absolute;
            width: 18px; height: 18px;
            border: 2px solid rgba(255,255,255,.3);
            border-top-color: white; border-radius: 50%;
            animation: spin .6s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .security { display: flex; align-items: center; justify-content: center; gap: 6px; font-size: 11px; color: rgba(255,255,255,.2); margin-top: 14px; }
        .security-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--blue-500); box-shadow: 0 0 6px var(--blue-500); animation: blink 2s ease-in-out infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }

        @media (max-width: 800px) {
            .wrapper { grid-template-columns: 1fr; overflow-y: auto; }
            html, body { overflow: auto; }
            .left { display: none; }
            .right { min-height: 100vh; padding: 48px 28px; justify-content: flex-start; padding-top: 64px; }
        }
    </style>
</head>
<body>
<div class="bg"></div>
<div class="bg-dots"></div>
<div class="orb-1"></div>
<div class="orb-2"></div>

<div class="wrapper">
    <!-- Left panel -->
    <div class="left">
        <div class="brand">
            <div class="brand-logo">SIP</div>
            <div>
                <div class="brand-name">Sistem Informasi Pendidikan</div>
                <div class="brand-sub">Portal Calon Siswa</div>
            </div>
        </div>

        <div class="showcase">
            <div class="eyebrow">Status Pendaftaran</div>
            <h1 class="showcase-title">
                Pantau progress<br>
                <span class="accent">pendaftaran kamu</span><br>
                secara real-time
            </h1>
            <p class="showcase-desc">
                Masuk ke portal untuk melihat status pendaftaranmu, informasi sekolah tujuan, dan notifikasi verifikasi.
            </p>

            <div class="steps">
                <div class="step">
                    <div class="step-num">1</div>
                    <div>
                        <div class="step-text">Cek status pendaftaran</div>
                        <div class="step-sub">Menunggu · Verifikasi · Seleksi · Diterima</div>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">2</div>
                    <div>
                        <div class="step-text">Lihat info sekolah tujuan</div>
                        <div class="step-sub">Detail & kontak sekolah</div>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">3</div>
                    <div>
                        <div class="step-text">Perbarui data diri</div>
                        <div class="step-sub">Edit profil jika ada kesalahan</div>
                    </div>
                </div>
            </div>

            <div class="daftar-link">
                Belum mendaftar? <a href="/daftar-siswa">Daftar sekarang →</a>
            </div>
        </div>

        <div style="font-size:11px;color:rgba(255,255,255,.15)">© 2025 SIP — Sistem Informasi Pendidikan</div>
    </div>

    <!-- Right panel -->
    <div class="right">
        <div class="form-title">Selamat datang</div>
        <div class="form-sub">Masuk dengan email yang kamu daftarkan</div>

        <div class="alert err" id="alertBox"></div>

        <form onsubmit="doLogin(event)">
            <div class="field">
                <label class="field-label">Email</label>
                <div class="field-wrap">
                    <span class="field-icon">✉️</span>
                    <input type="email" class="field-input" id="emailInput"
                        placeholder="email@kamu.com" required autocomplete="email">
                </div>
            </div>

            <div class="field">
                <label class="field-label">Password</label>
                <div class="field-wrap">
                    <span class="field-icon">🔒</span>
                    <input type="password" class="field-input" id="passInput"
                        placeholder="Password kamu" required autocomplete="current-password">
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
                <span class="btn-text">Masuk</span>
            </button>
        </form>

        <div class="security">
            <div class="security-dot"></div>
            Koneksi aman · SSL Encrypted
        </div>

        <div style="text-align:center;margin-top:20px;font-size:12px;color:rgba(255,255,255,.3)">
            Belum punya akun? <a href="/daftar-siswa" style="color:var(--blue-400);text-decoration:none;font-weight:600;">Daftar siswa baru</a>
        </div>
    </div>
</div>

<script>
function togglePass() {
    const inp = document.getElementById('passInput');
    inp.type = inp.type === 'password' ? 'text' : 'password';
}

function showAlert(msg) {
    const el = document.getElementById('alertBox');
    el.textContent = msg; el.classList.add('show');
}
function hideAlert() {
    document.getElementById('alertBox').classList.remove('show');
}

async function doLogin(e) {
    e.preventDefault(); hideAlert();
    const btn   = document.getElementById('btnLogin');
    const email = document.getElementById('emailInput').value.trim();
    const pass  = document.getElementById('passInput').value;
    const csrf  = document.querySelector('meta[name="csrf-token"]').content;

    btn.classList.add('loading'); btn.disabled = true;

    try {
        // Login sebagai role 'member'
        const res    = await fetch('/login/member', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ email, password: pass })
        });
        const result = await res.json();

        if (!res.ok) {
            showAlert(result.message || 'Email atau password salah.');
            return;
        }

        // Validasi bahwa ini akun siswa (role=member)
        if (result.role !== 'member') {
            showAlert('Akun ini bukan akun siswa. Gunakan portal yang sesuai.');
            return;
        }

        // Simpan token dan data user
        localStorage.setItem('token',      result.token);
        localStorage.setItem('user_id',    result.id);
        localStorage.setItem('user_name',  result.name);
        localStorage.setItem('user_email', result.email);

        // Redirect ke dashboard siswa
        window.location.href = '/siswa/dashboard';

    } catch(err) {
        showAlert('Koneksi error. Periksa jaringan dan coba lagi.');
    } finally {
        btn.classList.remove('loading'); btn.disabled = false;
    }
}
</script>
</body>
</html>