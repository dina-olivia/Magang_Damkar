<?php
session_start();

// HANYA redirect jika user SUDAH login
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// handle messages
$error = '';
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'invalid')  $error = 'Email atau password salah. Silakan coba lagi.';
    if ($_GET['error'] == 'empty')    $error = 'Email dan password wajib diisi.';
    if ($_GET['error'] == 'timeout')  $error = 'Sesi Anda telah berakhir. Silakan login kembali.';
}
$success = (isset($_GET['logout']) && $_GET['logout'] == '1');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-DAMKAR Kota Padang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #0f172a;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* ── NAVBAR ── */
        .top-bar {
            padding: 16px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            background: rgba(255,255,255,0.03);
        }
        .top-brand { display: flex; align-items: center; gap: 12px; }
        .top-brand-icon {
            width: 40px; height: 40px;
            background: #b91c1c;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .top-brand-icon i { font-size: 20px; color: white; }
        .top-brand-text { color: white; font-size: 15px; font-weight: 700; }
        .top-brand-text span { color: #94a3b8; font-size: 12px; font-weight: 400; display: block; margin-top: 1px; }
        .top-badge {
            background: rgba(185,28,28,0.2);
            border: 1px solid rgba(185,28,28,0.4);
            color: #fca5a5;
            font-size: 11px;
            font-weight: 600;
            padding: 5px 14px;
            border-radius: 20px;
            display: flex; align-items: center; gap: 6px;
        }
        .top-badge::before {
            content: '';
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #ef4444;
            animation: blink 1.5s infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.2; }
        }

        /* ── HERO ── */
        .hero {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 420px;
            gap: 60px;
            align-items: center;
            padding: 60px 40px;
            max-width: 1100px;
            margin: 0 auto;
            width: 100%;
        }
        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(185,28,28,0.15);
            border: 1px solid rgba(185,28,28,0.3);
            color: #fca5a5;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 20px;
            margin-bottom: 24px;
        }
        .hero-title {
            font-size: 44px;
            font-weight: 800;
            color: white;
            line-height: 1.15;
            margin-bottom: 20px;
        }
        .hero-title span { color: #ef4444; }
        .hero-desc {
            font-size: 15px;
            color: #94a3b8;
            line-height: 1.8;
            margin-bottom: 40px;
        }
        .hero-stats { display: flex; gap: 32px; }
        .stat-num { font-size: 26px; font-weight: 800; color: white; }
        .stat-lbl { font-size: 12px; color: #64748b; margin-top: 2px; }
        .stat-divider { width: 1px; background: rgba(255,255,255,0.08); }

        /* ── FORM BOX ── */
        .login-box {
            background: #1e293b;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            padding: 40px;
        }
        .login-box-title { font-size: 22px; font-weight: 700; color: white; margin-bottom: 4px; }
        .login-box-sub { font-size: 13px; color: #64748b; margin-bottom: 32px; }
        .f-label { font-size: 13px; font-weight: 600; color: #94a3b8; margin-bottom: 8px; display: block; }
        .input-wrap { position: relative; margin-bottom: 20px; }
        .input-icon {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%);
            color: #475569; font-size: 16px; pointer-events: none;
        }
        .f-input {
            width: 100%;
            background: #0f172a;
            border: 1.5px solid rgba(255,255,255,0.08);
            border-radius: 10px;
            padding: 12px 14px 12px 42px;
            font-size: 14px;
            color: white;
            outline: none;
            transition: border-color 0.2s;
        }
        .f-input::placeholder { color: #475569; }
        .f-input:focus { border-color: #b91c1c; }
        .pass-toggle {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: #475569; cursor: pointer; padding: 0; font-size: 16px;
        }
        .pass-toggle:hover { color: #94a3b8; }
        .btn-masuk {
            width: 100%;
            background: #b91c1c;
            color: white; border: none;
            border-radius: 10px;
            padding: 13px;
            font-size: 15px; font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            margin-top: 4px;
        }
        .btn-masuk:hover { background: #991b1b; }
        .btn-masuk:active { transform: scale(0.98); }
        .alert-box {
            border-radius: 10px; padding: 12px 16px;
            font-size: 13px; margin-bottom: 20px;
            display: flex; align-items: center; gap: 10px;
        }
        .alert-error { background: rgba(185,28,28,0.15); border: 1px solid rgba(185,28,28,0.3); color: #fca5a5; }
        .alert-success { background: rgba(22,163,74,0.15); border: 1px solid rgba(22,163,74,0.3); color: #86efac; }
        .divider-line { height: 1px; background: rgba(255,255,255,0.06); margin: 24px 0; }
        .info-row {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 14px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 10px;
        }
        .info-row i { color: #475569; font-size: 16px; flex-shrink: 0; }
        .info-row p { font-size: 12px; color: #64748b; margin: 0; }

        /* ── FOOTER ── */
        .footer {
            text-align: center; padding: 20px;
            font-size: 12px; color: #334155;
            border-top: 1px solid rgba(255,255,255,0.04);
        }

        @media (max-width: 768px) {
            .hero { grid-template-columns: 1fr; padding: 30px 20px; gap: 36px; }
            .hero-title { font-size: 30px; }
            .top-bar { padding: 14px 20px; }
        }
    </style>
</head>
<body>

<!-- Top Bar -->
<div class="top-bar">
    <div class="top-brand">
        <div class="top-brand-icon"><i class="bi bi-fire"></i></div>
        <div class="top-brand-text">
            E-DAMKAR
            <span>Dinas Pemadam Kebakaran Kota Padang</span>
        </div>
    </div>
    <div class="top-badge">SIAGA 24/7</div>
</div>

<!-- Hero -->
<div class="hero">

    <!-- Kiri -->
    <div>
        <div class="hero-tag">
            <i class="bi bi-shield-check"></i>
            Sistem Informasi Resmi Damkar Kota Padang
        </div>
        <h1 class="hero-title">
            Selamat Datang<br>di <span>E-DAMKAR</span><br>Kota Padang
        </h1>
        <p class="hero-desc">
            Platform terpadu untuk pengelolaan laporan kejadian kebakaran,
            monitoring armada, penugasan tim, dan manajemen operasional
            Dinas Pemadam Kebakaran secara real-time.
        </p>
        <div class="hero-stats">
            <div>
                <div class="stat-num">24/7</div>
                <div class="stat-lbl">Siaga Penuh</div>
            </div>
            <div class="stat-divider"></div>
            <div>
                <div class="stat-num">Real-time</div>
                <div class="stat-lbl">Monitoring</div>
            </div>
            <div class="stat-divider"></div>
            <div>
                <div class="stat-num">Terpadu</div>
                <div class="stat-lbl">Terintegrasi</div>
            </div>
        </div>
    </div>

    <!-- Kanan: Form -->
    <div class="login-box">
        <p class="login-box-title">Masuk ke Sistem</p>
        <p class="login-box-sub">Khusus petugas Damkar Kota Padang</p>

        <?php if ($error): ?>
        <div class="alert-box alert-error">
            <i class="bi bi-exclamation-circle"></i>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="alert-box alert-success">
            <i class="bi bi-check-circle"></i>
            Anda berhasil keluar dari sistem.
        </div>
        <?php endif; ?>

        <form action="proses_login.php" method="POST">

            <label class="f-label">Email</label>
            <div class="input-wrap">
                <i class="bi bi-envelope input-icon"></i>
                <input type="email" name="email" class="f-input"
                       placeholder="nama@damkar-padang.go.id"
                       value="<?= htmlspecialchars($_GET['email'] ?? '') ?>"
                       required>
            </div>

            <label class="f-label">Password</label>
            <div class="input-wrap">
                <i class="bi bi-lock input-icon"></i>
                <input type="password" name="password" class="f-input"
                       id="passInput" placeholder="Masukkan password" required>
                <button type="button" class="pass-toggle" onclick="togglePass()">
                    <i class="bi bi-eye" id="passIcon"></i>
                </button>
            </div>

            <button type="submit" class="btn-masuk">
                <i class="bi bi-box-arrow-in-right"></i> Masuk
            </button>

        </form>

        <div class="divider-line"></div>

        <div class="info-row">
            <i class="bi bi-info-circle"></i>
            <p>Akun dibuat oleh administrator. Hubungi admin jika lupa password.</p>
        </div>
    </div>

</div>

<!-- Footer -->
<div class="footer">
    &copy; <?= date('Y') ?> Dinas Pemadam Kebakaran Kota Padang &mdash; E-DAMKAR
</div>

<script>
function togglePass() {
    const input = document.getElementById('passInput');
    const icon  = document.getElementById('passIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
</body>
</html>