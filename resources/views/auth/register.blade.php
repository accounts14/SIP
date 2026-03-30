<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP — Register</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --blue-900: #0a1628;
            --blue-800: #0f2044;
            --blue-700: #1a3a6e;
            --blue-600: #1d4ed8;
            --blue-500: #2563eb;
            --blue-400: #3b82f6;
            --blue-300: #93c5fd;
            --blue-100: #dbeafe;
            --white: #ffffff;
            --gray-400: #94a3b8;
            --red-400: #f87171;
        }

        html, body {
            height: 100%;
            font-family: 'DM Sans', sans-serif;
            background: var(--blue-900);
            overflow-x: hidden;
        }

        /* ── Background ── */
        .bg { position: fixed; inset: 0; background: linear-gradient(135deg, #0a1628 0%, #0f2044 50%, #1a3a6e 100%); }

        .bg-grid {
            position: fixed; inset: 0;
            background-image:
                linear-gradient(rgba(59,130,246,0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(59,130,246,0.06) 1px, transparent 1px);
            background-size: 60px 60px;
            animation: gridDrift 20s linear infinite;
        }
        @keyframes gridDrift { from { background-position: 0 0; } to { background-position: 60px 60px; } }

        .orb {
            position: fixed; border-radius: 50%;
            filter: blur(80px); opacity: 0.15;
            animation: orbFloat 8s ease-in-out infinite;
        }
        .orb-1 { width: 500px; height: 500px; background: var(--blue-500); top: -150px; left: -100px; animation-delay: 0s; }
        .orb-2 { width: 400px; height: 400px; background: var(--blue-400); bottom: -100px; right: -80px; animation-delay: 3s; }
        .orb-3 { width: 250px; height: 250px; background: #60a5fa; top: 40%; left: 30%; opacity: 0.07; animation-delay: 5s; }

        @keyframes orbFloat {
            0%, 100% { transform: translateY(0) scale(1); }
            50%       { transform: translateY(-25px) scale(1.04); }
        }

        /* ── Layout ── */
        .wrapper {
            position: relative; z-index: 10;
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 520px;
        }

        /* ── Left Panel ── */
        .left {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 80px;
            animation: fadeInLeft 0.8s ease forwards;
        }
        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-40px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .brand { display: flex; align-items: center; gap: 16px; margin-bottom: 56px; }

        .brand-logo {
            width: 52px; height: 52px;
            background: linear-gradient(135deg, var(--blue-500), var(--blue-400));
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Sora', sans-serif; font-weight: 700; font-size: 20px; color: white;
            box-shadow: 0 0 30px rgba(37,99,235,0.5);
        }

        .brand-name { font-family: 'Sora', sans-serif; font-size: 22px; font-weight: 600; color: var(--white); }
        .brand-tagline { font-size: 12px; color: var(--blue-300); letter-spacing: 0.08em; text-transform: uppercase; margin-top: 2px; }

        .hero-title {
            font-family: 'Sora', sans-serif;
            font-size: clamp(32px, 3.5vw, 50px);
            font-weight: 700; color: var(--white); line-height: 1.2; margin-bottom: 20px;
        }
        .hero-title span {
            background: linear-gradient(90deg, var(--blue-400), var(--blue-300));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }

        .hero-desc { font-size: 15px; color: var(--blue-300); line-height: 1.7; max-width: 380px; margin-bottom: 48px; }

        /* Steps */
        .steps { display: flex; flex-direction: column; gap: 20px; }

        .step {
            display: flex; align-items: flex-start; gap: 16px;
            opacity: 0; animation: fadeInUp 0.6s ease forwards;
        }
        .step:nth-child(1) { animation-delay: 0.3s; }
        .step:nth-child(2) { animation-delay: 0.5s; }
        .step:nth-child(3) { animation-delay: 0.7s; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .step-icon {
            width: 40px; height: 40px; flex-shrink: 0;
            background: rgba(37,99,235,0.15);
            border: 1px solid rgba(37,99,235,0.3);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }

        .step-title { font-family: 'Sora', sans-serif; font-size: 14px; font-weight: 600; color: var(--white); margin-bottom: 3px; }
        .step-desc  { font-size: 13px; color: var(--gray-400); line-height: 1.5; }

        /* ── Right Panel ── */
        .right {
            display: flex; align-items: center; justify-content: center;
            padding: 40px 48px;
            background: rgba(255,255,255,0.03);
            backdrop-filter: blur(20px);
            border-left: 1px solid rgba(255,255,255,0.06);
            animation: fadeInRight 0.8s ease forwards;
            overflow-y: auto;
        }
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(40px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .card { width: 100%; max-width: 400px; padding: 8px 0; }

        .card-header { margin-bottom: 28px; }
        .card-title  { font-family: 'Sora', sans-serif; font-size: 24px; font-weight: 700; color: var(--white); margin-bottom: 6px; }
        .card-sub    { font-size: 14px; color: var(--gray-400); }

        /* Role Tabs */
        .role-tabs {
            display: flex;
            background: rgba(255,255,255,0.05);
            border-radius: 12px; padding: 4px;
            margin-bottom: 24px;
            border: 1px solid rgba(255,255,255,0.08);
        }

        .role-tab {
            flex: 1; padding: 10px 6px;
            border: none; background: transparent;
            border-radius: 9px;
            font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 500;
            color: var(--gray-400); cursor: pointer;
            transition: all 0.3s ease;
            display: flex; align-items: center; justify-content: center; gap: 6px;
        }
        .role-tab.active {
            background: var(--blue-600); color: var(--white);
            box-shadow: 0 4px 15px rgba(37,99,235,0.4);
        }
        .role-tab .icon { font-size: 15px; }

        /* Alert */
        .alert {
            padding: 12px 16px; border-radius: 10px;
            font-size: 13px; margin-bottom: 18px;
            display: none; align-items: center; gap: 8px;
        }
        .alert.error  { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; }
        .alert.success { background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3); color: #86efac; }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25%       { transform: translateX(-5px); }
            75%       { transform: translateX(5px); }
        }

        /* Form */
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

        .form-group { margin-bottom: 16px; }

        .form-label {
            display: flex; align-items: center; justify-content: space-between;
            font-size: 12px; font-weight: 500;
            color: var(--blue-300); margin-bottom: 7px;
            letter-spacing: 0.03em;
        }

        .label-req { color: var(--red-400); font-size: 11px; }

        .input-wrap { position: relative; }

        .input-icon {
            position: absolute; left: 13px; top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400); font-size: 15px; pointer-events: none;
        }

        .form-input {
            width: 100%;
            padding: 12px 12px 12px 40px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif; font-size: 14px;
            color: var(--white); outline: none;
            transition: all 0.3s ease;
        }
        .form-input::placeholder { color: var(--gray-400); }
        .form-input:focus {
            border-color: var(--blue-500);
            background: rgba(37,99,235,0.08);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
        }
        .form-input.error-field { border-color: rgba(239,68,68,0.5); }

        .field-error { font-size: 11px; color: var(--red-400); margin-top: 5px; display: none; }

        .toggle-pass {
            position: absolute; right: 12px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: var(--gray-400); cursor: pointer;
            font-size: 15px; transition: color 0.2s;
        }
        .toggle-pass:hover { color: var(--blue-300); }

        /* Password Strength */
        .pass-strength { margin-top: 8px; }
        .strength-bar {
            height: 3px; border-radius: 3px;
            background: rgba(255,255,255,0.1);
            overflow: hidden; margin-bottom: 4px;
        }
        .strength-fill {
            height: 100%; width: 0%;
            border-radius: 3px;
            transition: all 0.4s ease;
        }
        .strength-label { font-size: 11px; color: var(--gray-400); }

        /* Divider */
        .divider {
            display: flex; align-items: center; gap: 12px;
            margin: 6px 0 18px;
            color: var(--gray-400); font-size: 11px;
            text-transform: uppercase; letter-spacing: 0.06em;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1; height: 1px;
            background: rgba(255,255,255,0.08);
        }

        /* Terms */
        .terms {
            display: flex; align-items: flex-start; gap: 10px;
            font-size: 12px; color: var(--gray-400);
            margin-bottom: 20px; cursor: pointer;
        }
        .terms input { margin-top: 2px; accent-color: var(--blue-500); cursor: pointer; flex-shrink: 0; }
        .terms a { color: var(--blue-400); text-decoration: none; }
        .terms a:hover { color: var(--blue-300); }

        /* Submit */
        .btn-register {
            width: 100%; padding: 14px;
            background: linear-gradient(135deg, var(--blue-600), var(--blue-500));
            border: none; border-radius: 10px;
            font-family: 'Sora', sans-serif; font-size: 15px; font-weight: 600;
            color: white; cursor: pointer;
            transition: all 0.3s ease; position: relative; overflow: hidden;
            box-shadow: 0 4px 20px rgba(37,99,235,0.4);
        }
        .btn-register::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), transparent);
            opacity: 0; transition: opacity 0.3s;
        }
        .btn-register:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(37,99,235,0.5); }
        .btn-register:hover::before { opacity: 1; }
        .btn-register:active { transform: translateY(0); }
        .btn-register:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

        .btn-register.loading .btn-text { opacity: 0; }
        .btn-register.loading::after {
            content: ''; position: absolute;
            width: 20px; height: 20px;
            border: 2px solid rgba(255,255,255,0.3); border-top-color: white;
            border-radius: 50%; top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            animation: spin 0.6s linear infinite;
        }
        @keyframes spin { to { transform: translate(-50%, -50%) rotate(360deg); } }

        /* Footer */
        .card-foot {
            text-align: center; margin-top: 20px;
            font-size: 13px; color: var(--gray-400);
        }
        .card-foot a { color: var(--blue-400); text-decoration: none; font-weight: 500; }
        .card-foot a:hover { color: var(--blue-300); }

        /* Responsive */
        @media (max-width: 960px) {
            .wrapper { grid-template-columns: 1fr; }
            .left { display: none; }
            .right { border-left: none; background: var(--blue-900); min-height: 100vh; }
        }
        @media (max-width: 400px) {
            .form-row { grid-template-columns: 1fr; }
            .right { padding: 30px 24px; }
        }
    </style>
</head>
<body>

<div class="bg"></div>
<div class="bg-grid"></div>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

<div class="wrapper">

    <!-- Left Panel -->
    <div class="left">
        <div class="brand">
            <div class="brand-logo">SIP</div>
            <div>
                <div class="brand-name">Sistem Informasi Pendidikan</div>
                <div class="brand-tagline">Platform Manajemen Sekolah Terpadu</div>
            </div>
        </div>

        <h1 class="hero-title">
            Bergabung &<br>
            Mulai <span>Kelola</span><br>
            Sekolahmu
        </h1>

        <p class="hero-desc">
            Daftarkan akun untuk mengakses seluruh fitur manajemen sekolah, 
            data siswa, dan laporan pendidikan secara terpadu.
        </p>

        <div class="steps">
            <div class="step">
                <div class="step-icon">📝</div>
                <div>
                    <div class="step-title">Isi Data Akun</div>
                    <div class="step-desc">Lengkapi informasi nama, email, dan password akun kamu.</div>
                </div>
            </div>
            <div class="step">
                <div class="step-icon">✅</div>
                <div>
                    <div class="step-title">Verifikasi Email</div>
                    <div class="step-desc">Cek inbox email untuk konfirmasi akun yang baru dibuat.</div>
                </div>
            </div>
            <div class="step">
                <div class="step-icon">🚀</div>
                <div>
                    <div class="step-title">Akses Dashboard</div>
                    <div class="step-desc">Masuk dan mulai kelola data sekolah dari dashboard SIP.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Panel -->
    <div class="right">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Buat Akun Baru</h2>
                <p class="card-sub">Pilih tipe akun yang akan didaftarkan</p>
            </div>

            <!-- Role Tabs -->
            <div class="role-tabs">
                <button class="role-tab active" onclick="switchRole('superadmin', this)">
                    <span class="icon">👑</span> Pimpinan SIP
                </button>
                <button class="role-tab" onclick="switchRole('admin', this)">
                    <span class="icon">🛡</span> Admin SIP
                </button>
            </div>

            <!-- Alert -->
            <div class="alert error" id="alertError">
                <span>⚠</span><span id="alertErrorMsg">Terjadi kesalahan.</span>
            </div>
            <div class="alert success" id="alertSuccess">
                <span>✓</span><span id="alertSuccessMsg">Akun berhasil dibuat!</span>
            </div>

            <!-- Form -->
            <form id="registerForm" onsubmit="handleRegister(event)" novalidate>
                @csrf
                <input type="hidden" name="role" id="roleInput" value="superadmin">

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            Nama Depan <span class="label-req">*</span>
                        </label>
                        <div class="input-wrap">
                            <span class="input-icon">👤</span>
                            <input type="text" name="first_name" id="firstName" class="form-input" placeholder="Budi" required>
                        </div>
                        <div class="field-error" id="errFirstName">Nama depan wajib diisi.</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            Nama Belakang
                        </label>
                        <div class="input-wrap">
                            <span class="input-icon">👤</span>
                            <input type="text" name="last_name" id="lastName" class="form-input" placeholder="Santoso">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Email <span class="label-req">*</span>
                    </label>
                    <div class="input-wrap">
                        <span class="input-icon">✉</span>
                        <input type="email" name="email" id="emailInput" class="form-input" placeholder="email@sip.id" required>
                    </div>
                    <div class="field-error" id="errEmail">Email tidak valid.</div>
                </div>

                <div class="divider">Keamanan Akun</div>

                <div class="form-group">
                    <label class="form-label">
                        Password <span class="label-req">*</span>
                    </label>
                    <div class="input-wrap">
                        <span class="input-icon">🔒</span>
                        <input type="password" name="password" id="passInput" class="form-input" placeholder="Min. 8 karakter" required oninput="checkStrength(this.value)">
                        <button type="button" class="toggle-pass" onclick="togglePass('passInput')">👁</button>
                    </div>
                    <div class="pass-strength">
                        <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                        <div class="strength-label" id="strengthLabel">Masukkan password</div>
                    </div>
                    <div class="field-error" id="errPass">Password min. 8 karakter.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Konfirmasi Password <span class="label-req">*</span>
                    </label>
                    <div class="input-wrap">
                        <span class="input-icon">🔒</span>
                        <input type="password" name="password_confirmation" id="passConfirm" class="form-input" placeholder="Ulangi password" required>
                        <button type="button" class="toggle-pass" onclick="togglePass('passConfirm')">👁</button>
                    </div>
                    <div class="field-error" id="errPassConfirm">Password tidak cocok.</div>
                </div>

                <label class="terms">
                    <input type="checkbox" id="termsCheck" required>
                    Saya menyetujui <a href="#">Syarat & Ketentuan</a> serta <a href="#">Kebijakan Privasi</a> SIP.
                </label>

                <button type="submit" class="btn-register" id="btnRegister">
                    <span class="btn-text">Buat Akun Sekarang</span>
                </button>
            </form>

            <div class="card-foot">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
            </div>
        </div>
    </div>
</div>

<script>
    let currentRole = 'superadmin';

    function switchRole(role, el) {
        currentRole = role;
        document.getElementById('roleInput').value = role;
        document.querySelectorAll('.role-tab').forEach(b => b.classList.remove('active'));
        el.classList.add('active');
        hideAlerts();
    }

    function togglePass(id) {
        const inp = document.getElementById(id);
        inp.type = inp.type === 'password' ? 'text' : 'password';
    }

    function checkStrength(val) {
        const fill  = document.getElementById('strengthFill');
        const label = document.getElementById('strengthLabel');
        let score = 0;
        if (val.length >= 8)               score++;
        if (/[A-Z]/.test(val))             score++;
        if (/[0-9]/.test(val))             score++;
        if (/[^A-Za-z0-9]/.test(val))      score++;

        const map = [
            { w: '0%',   color: 'transparent', text: 'Masukkan password' },
            { w: '25%',  color: '#ef4444',      text: 'Lemah' },
            { w: '50%',  color: '#f97316',      text: 'Cukup' },
            { w: '75%',  color: '#eab308',      text: 'Kuat' },
            { w: '100%', color: '#22c55e',      text: 'Sangat Kuat' },
        ];

        const s = val.length === 0 ? map[0] : map[score] || map[1];
        fill.style.width = s.w;
        fill.style.background = s.color;
        label.textContent = s.text;
        label.style.color = s.color === 'transparent' ? 'var(--gray-400)' : s.color;
    }

    function validateForm() {
        let valid = true;

        // First name
        const fn = document.getElementById('firstName');
        if (!fn.value.trim()) {
            showFieldError('errFirstName', fn); valid = false;
        } else hideFieldError('errFirstName', fn);

        // Email
        const em = document.getElementById('emailInput');
        const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRe.test(em.value)) {
            showFieldError('errEmail', em); valid = false;
        } else hideFieldError('errEmail', em);

        // Password
        const pw = document.getElementById('passInput');
        if (pw.value.length < 8) {
            showFieldError('errPass', pw); valid = false;
        } else hideFieldError('errPass', pw);

        // Confirm
        const pc = document.getElementById('passConfirm');
        if (pc.value !== pw.value || !pc.value) {
            showFieldError('errPassConfirm', pc); valid = false;
        } else hideFieldError('errPassConfirm', pc);

        // Terms
        if (!document.getElementById('termsCheck').checked) {
            showAlert('error', 'Harap setujui syarat & ketentuan terlebih dahulu.');
            valid = false;
        }

        return valid;
    }

    function showFieldError(errId, input) {
        document.getElementById(errId).style.display = 'block';
        input.classList.add('error-field');
    }
    function hideFieldError(errId, input) {
        document.getElementById(errId).style.display = 'none';
        input.classList.remove('error-field');
    }

    function showAlert(type, msg) {
        hideAlerts();
        const box = document.getElementById(type === 'error' ? 'alertError' : 'alertSuccess');
        const msgEl = document.getElementById(type === 'error' ? 'alertErrorMsg' : 'alertSuccessMsg');
        msgEl.textContent = msg;
        box.style.display = 'flex';
        box.style.animation = 'none';
        box.offsetHeight;
        box.style.animation = type === 'error' ? 'shake 0.4s ease' : '';
    }

    function hideAlerts() {
        document.getElementById('alertError').style.display = 'none';
        document.getElementById('alertSuccess').style.display = 'none';
    }

    function handleRegister(e) {
        e.preventDefault();
        if (!validateForm()) return;

        const btn = document.getElementById('btnRegister');
        btn.classList.add('loading');
        btn.disabled = true;

        const firstName = document.getElementById('firstName').value.trim();
        const lastName  = document.getElementById('lastName').value.trim();

        fetch('/register', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                name: lastName ? `${firstName} ${lastName}` : firstName,
                email: document.getElementById('emailInput').value,
                password: document.getElementById('passInput').value,
                password_confirmation: document.getElementById('passConfirm').value,
                role: currentRole,
            })
        })
        .then(res => res.json())
        .then(result => {
            btn.classList.remove('loading');
            btn.disabled = false;

            if (result.token || result.access_token || result.user) {
                showAlert('success', 'Akun berhasil dibuat! Mengalihkan ke halaman login...');
                setTimeout(() => window.location.href = '/login', 2000);
            } else if (result.errors) {
                const firstError = Object.values(result.errors)[0][0];
                showAlert('error', firstError);
            } else {
                showAlert('error', result.message || 'Gagal membuat akun.');
            }
        })
        .catch(() => {
            btn.classList.remove('loading');
            btn.disabled = false;
            showAlert('error', 'Terjadi kesalahan. Silakan coba lagi.');
        });
    }
</script>
</body>
</html>