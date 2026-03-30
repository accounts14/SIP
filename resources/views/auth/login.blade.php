<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP — Login</title>
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
            --blue-50:  #eff6ff;
            --white: #ffffff;
            --gray-100: #f1f5f9;
            --gray-400: #94a3b8;
            --gray-600: #475569;
        }

        html, body {
            height: 100%;
            font-family: 'DM Sans', sans-serif;
            background: var(--blue-900);
            overflow: hidden;
        }

        /* ── Background ── */
        .bg {
            position: fixed; inset: 0;
            background: linear-gradient(135deg, #0a1628 0%, #0f2044 50%, #1a3a6e 100%);
        }

        .bg-grid {
            position: fixed; inset: 0;
            background-image:
                linear-gradient(rgba(59,130,246,0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(59,130,246,0.06) 1px, transparent 1px);
            background-size: 60px 60px;
            animation: gridDrift 20s linear infinite;
        }

        @keyframes gridDrift {
            from { background-position: 0 0; }
            to   { background-position: 60px 60px; }
        }

        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            animation: orbFloat 8s ease-in-out infinite;
        }
        .orb-1 {
            width: 500px; height: 500px;
            background: var(--blue-500);
            top: -150px; left: -100px;
            animation-delay: 0s;
        }
        .orb-2 {
            width: 400px; height: 400px;
            background: var(--blue-400);
            bottom: -100px; right: -80px;
            animation-delay: 3s;
        }
        .orb-3 {
            width: 300px; height: 300px;
            background: #60a5fa;
            top: 50%; right: 20%;
            animation-delay: 5s;
            opacity: 0.08;
        }

        @keyframes orbFloat {
            0%, 100% { transform: translateY(0px) scale(1); }
            50%       { transform: translateY(-30px) scale(1.05); }
        }

        /* ── Layout ── */
        .wrapper {
            position: relative; z-index: 10;
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 480px;
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

        .brand {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 60px;
        }

        .brand-logo {
            width: 52px; height: 52px;
            background: linear-gradient(135deg, var(--blue-500), var(--blue-400));
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 20px;
            color: white;
            box-shadow: 0 0 30px rgba(37,99,235,0.5);
        }

        .brand-name {
            font-family: 'Sora', sans-serif;
            font-size: 22px;
            font-weight: 600;
            color: var(--white);
            letter-spacing: 0.02em;
        }

        .brand-tagline {
            font-size: 12px;
            color: var(--blue-300);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .hero-title {
            font-family: 'Sora', sans-serif;
            font-size: clamp(36px, 4vw, 56px);
            font-weight: 700;
            color: var(--white);
            line-height: 1.15;
            margin-bottom: 20px;
        }

        .hero-title span {
            background: linear-gradient(90deg, var(--blue-400), var(--blue-300));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-desc {
            font-size: 16px;
            color: var(--blue-300);
            line-height: 1.7;
            max-width: 420px;
            margin-bottom: 50px;
        }

        .stats {
            display: flex;
            gap: 40px;
        }

        .stat-item {
            border-left: 2px solid var(--blue-700);
            padding-left: 16px;
            animation: fadeInUp 0.8s ease forwards;
            opacity: 0;
        }
        .stat-item:nth-child(1) { animation-delay: 0.4s; }
        .stat-item:nth-child(2) { animation-delay: 0.6s; }
        .stat-item:nth-child(3) { animation-delay: 0.8s; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .stat-num {
            font-family: 'Sora', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--white);
        }

        .stat-label {
            font-size: 12px;
            color: var(--gray-400);
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        /* ── Right Panel (Form) ── */
        .right {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 48px;
            background: rgba(255,255,255,0.03);
            backdrop-filter: blur(20px);
            border-left: 1px solid rgba(255,255,255,0.06);
            animation: fadeInRight 0.8s ease forwards;
        }

        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(40px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .card {
            width: 100%;
            max-width: 380px;
        }

        .card-header {
            margin-bottom: 36px;
        }

        .card-title {
            font-family: 'Sora', sans-serif;
            font-size: 26px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 8px;
        }

        .card-sub {
            font-size: 14px;
            color: var(--gray-400);
        }

        /* Role Switcher */
        .role-switcher {
            display: flex;
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
            padding: 4px;
            margin-bottom: 28px;
            border: 1px solid rgba(255,255,255,0.08);
        }

        .role-btn {
            flex: 1;
            padding: 10px;
            border: none;
            background: transparent;
            border-radius: 9px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 500;
            color: var(--gray-400);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .role-btn.active {
            background: var(--blue-600);
            color: var(--white);
            box-shadow: 0 4px 15px rgba(37,99,235,0.4);
        }

        .role-badge {
            display: inline-block;
            font-size: 10px;
            background: rgba(59,130,246,0.2);
            color: var(--blue-300);
            padding: 2px 8px;
            border-radius: 20px;
            margin-left: 6px;
            vertical-align: middle;
        }

        .role-btn.active .role-badge {
            background: rgba(255,255,255,0.2);
            color: white;
        }

        /* Form */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--blue-300);
            margin-bottom: 8px;
            letter-spacing: 0.03em;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px; top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 16px;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            padding: 13px 14px 13px 42px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: var(--white);
            outline: none;
            transition: all 0.3s ease;
        }

        .form-input::placeholder { color: var(--gray-400); }

        .form-input:focus {
            border-color: var(--blue-500);
            background: rgba(37,99,235,0.1);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
        }

        .toggle-pass {
            position: absolute;
            right: 14px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: var(--gray-400);
            cursor: pointer;
            font-size: 16px;
            transition: color 0.2s;
        }
        .toggle-pass:hover { color: var(--blue-300); }

        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--gray-400);
            cursor: pointer;
        }

        .remember input[type="checkbox"] {
            width: 15px; height: 15px;
            accent-color: var(--blue-500);
            cursor: pointer;
        }

        .forgot {
            font-size: 13px;
            color: var(--blue-400);
            text-decoration: none;
            transition: color 0.2s;
        }
        .forgot:hover { color: var(--blue-300); }

        /* Submit Button */
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--blue-600), var(--blue-500));
            border: none;
            border-radius: 10px;
            font-family: 'Sora', sans-serif;
            font-size: 15px;
            font-weight: 600;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(37,99,235,0.4);
        }

        .btn-login::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(37,99,235,0.5);
        }
        .btn-login:hover::before { opacity: 1; }
        .btn-login:active { transform: translateY(0); }

        .btn-login.loading .btn-text { opacity: 0; }
        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 20px; height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }

        /* Alert */
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            display: none;
            animation: shake 0.4s ease;
        }
        .alert.error {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.3);
            color: #fca5a5;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25%       { transform: translateX(-6px); }
            75%       { transform: translateX(6px); }
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0;
            color: var(--gray-400);
            font-size: 12px;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.08);
        }

        /* Footer */
        .card-foot {
            text-align: center;
            margin-top: 24px;
            font-size: 12px;
            color: var(--gray-400);
        }

        .card-foot span { color: var(--blue-300); }
        .card-foot a { color: var(--blue-400); text-decoration: none; font-weight: 500; }
        .card-foot a:hover { color: var(--blue-300); }

        /* Responsive */
        @media (max-width: 900px) {
            .wrapper { grid-template-columns: 1fr; }
            .left { display: none; }
            .right {
                border-left: none;
                background: var(--blue-900);
            }
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
            Kelola Sekolah<br>
            Lebih <span>Cerdas</span><br>
            & Efisien
        </h1>

        <p class="hero-desc">
            Platform terpadu untuk manajemen data sekolah, pendaftaran siswa, 
            dan pemantauan performa pendidikan secara real-time.
        </p>

        <div class="stats">
            <div class="stat-item">
                <div class="stat-num">500+</div>
                <div class="stat-label">Sekolah</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">12K+</div>
                <div class="stat-label">Siswa</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">98%</div>
                <div class="stat-label">Uptime</div>
            </div>
        </div>
    </div>

    <!-- Right Panel -->
    <div class="right">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Selamat Datang</h2>
                <p class="card-sub">Masuk ke panel administrasi SIP</p>
            </div>

            <!-- Role Switcher -->
            <div class="role-switcher">
                <button class="role-btn active" onclick="switchRole('superadmin', this)">
                    Pimpinan <span class="role-badge">SIP</span>
                </button>
                <button class="role-btn" onclick="switchRole('admin', this)">
                    Admin <span class="role-badge">SIP</span>
                </button>
            </div>

            <!-- Alert -->
            <div class="alert error" id="alertBox">
                <span>⚠</span>
                <span id="alertMsg">Email atau password salah.</span>
            </div>

            <!-- Form -->
            <form id="loginForm" onsubmit="handleLogin(event)">
                @csrf
                <input type="hidden" name="role" id="roleInput" value="superadmin">

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <div class="input-wrap">
                        <span class="input-icon">✉</span>
                        <input
                            type="email"
                            name="email"
                            class="form-input"
                            placeholder="email@sip.id"
                            required
                            autocomplete="email"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrap">
                        <span class="input-icon">🔒</span>
                        <input
                            type="password"
                            name="password"
                            id="passInput"
                            class="form-input"
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                        >
                        <button type="button" class="toggle-pass" onclick="togglePass()">👁</button>
                    </div>
                </div>

                <div class="form-footer">
                    <label class="remember">
                        <input type="checkbox" name="remember"> Ingat saya
                    </label>
                    <a href="#" class="forgot">Lupa password?</a>
                </div>

                <button type="submit" class="btn-login" id="btnLogin">
                    <span class="btn-text">Masuk ke Dashboard</span>
                </button>
            </form>

            <div class="card-foot">
                Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
            </div>
        </div>
    </div>
</div>

<script>
    let currentRole = 'superadmin';

    function switchRole(role, el) {
        currentRole = role;
        document.getElementById('roleInput').value = role;
        document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('alertBox').style.display = 'none';
    }

    function togglePass() {
        const inp = document.getElementById('passInput');
        inp.type = inp.type === 'password' ? 'text' : 'password';
    }

    function handleLogin(e) {
    e.preventDefault();
    const btn = document.getElementById('btnLogin');
    btn.classList.add('loading');
    btn.disabled = true;

    const form = document.getElementById('loginForm');
    const data = new FormData(form);

    fetch(`/login/${currentRole}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': data.get('_token'),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            email: data.get('email'),
            password: data.get('password'),
        })
    })
    .then(res => res.json())
    .then(result => {
    console.log('FULL RESULT:', JSON.stringify(result)); 
    btn.classList.remove('loading');
    btn.disabled = false;

    if (result.token || result.access_token) {
        localStorage.setItem('token', result.token || result.access_token);

        const role = result.role;
        const type = result.type;

        // Redirect berdasarkan role + type
        if (role === 'superadmin') {
            window.location.href = '/pimpinan';
        } else if (role === 'admin' && type === 'sip') {
            window.location.href = '/dashboard';
        } else if (role === 'admin' && (type === 'school_admin' || type === 'school_head')) {
            window.location.href = '/school-dashboard'; // buat route ini nanti
        } else {
            window.location.href = '/dashboard';
        }
    } else {
        showAlert(result.message || 'Email atau password salah.');
    }
    })
    .catch(() => {
        btn.classList.remove('loading');
        btn.disabled = false;
        showAlert('Terjadi kesalahan. Coba lagi.');
    });
}

    function showAlert(msg) {
        const box = document.getElementById('alertBox');
        document.getElementById('alertMsg').textContent = msg;
        box.style.display = 'flex';
        box.style.animation = 'none';
        box.offsetHeight; // reflow
        box.style.animation = 'shake 0.4s ease';
    }
</script>

</body>
</html>