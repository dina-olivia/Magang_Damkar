<?php
// --- KONEKSI DATABASE ---
$host = "localhost";
$user = "root";
$pass = "";
$db   = "app_damkar"; 

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if (!isset($_GET['no_lp']) || empty($_GET['no_lp'])) {
    echo "<script>alert('Silahkan pilih laporan terlebih dahulu!'); window.location='monitoring_kejadian.php';</script>";
    exit;
}

$no_lp = mysqli_real_escape_string($conn, $_GET['no_lp']);
$query = mysqli_query($conn, "SELECT * FROM laporan_kejadian WHERE nomor_laporan = '$no_lp'");
$data  = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='monitoring_kejadian.php';</script>";
    exit;
}
$lokasi    = isset($data['lokasi']) ? $data['lokasi'] : 'Lokasi tidak spesifik';
$deskripsi = isset($data['deskripsi']) ? $data['deskripsi'] : 'Tidak ada rincian tambahan.';

// --- LOGIKA WAKTU (Pencegahan Error Deprecated di PHP 8.1+) ---
$status        = strtolower($data['status']);
$waktu_awal    = date('H:i', strtotime($data['created_at'])) . ' WIB'; 

$waktu_proses  = (!empty($data['waktu_proses']) && $data['waktu_proses'] != '0000-00-00 00:00:00') 
                 ? date('H:i', strtotime($data['waktu_proses'])) . ' WIB' 
                 : ''; // String kosong agar tidak memicu error di htmlspecialchars()

$waktu_selesai = (!empty($data['waktu_selesai']) && $data['waktu_selesai'] != '0000-00-00 00:00:00') 
                 ? date('H:i', strtotime($data['waktu_selesai'])) . ' WIB' 
                 : ''; // String kosong agar tidak memicu error di htmlspecialchars()

// --- LOGIKA WARNA & STATUS STEPPER HORIZONTAL ---
$step1_class = 'disabled'; $step1_icon = '1';
$step2_class = 'disabled'; $step2_icon = '2';
$step3_class = 'disabled'; $step3_icon = '3';
$line_progress = '0%'; 

if ($status == 'masuk') {
    $step1_class = 'active';
    $step1_icon = '<i class="bi bi-check-lg"></i>';
    $line_progress = '0%';
} elseif ($status == 'proses') {
    $step1_class = 'completed';
    $step1_icon = '<i class="bi bi-check-lg"></i>';
    $step2_class = 'active';
    $step2_icon = '<i class="bi bi-check-lg"></i>';
    $line_progress = '50%';
} elseif ($status == 'selesai') {
    $step1_class = 'completed';
    $step1_icon = '<i class="bi bi-check-lg"></i>';
    $step2_class = 'completed';
    $step2_icon = '<i class="bi bi-check-lg"></i>';
    $step3_class = 'active';
    $step3_icon = '<i class="bi bi-check-lg"></i>';
    $line_progress = '100%';
}
?>

<!DOCTYPE html>
<html lang="id"> 
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan #<?= htmlspecialchars($no_lp) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <style>
        :root { --damkar-red: #e63946; --damkar-dark: #9f0925; }
        body { background-color: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .main-wrapper { margin-left: 260px; padding: 40px; }
        .title-large { font-weight: 700; font-size: 2.5rem; color: #dc3545; letter-spacing: -1px; }
        
        .btn-kembali {
            background-color: var(--damkar-red);
            color: white;
            padding: 12px 35px;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            transition: 0.3s;
            border: none;
        }
        .btn-kembali:hover {
            background-color: #c1272d;
            color: white;
            transform: translateX(-5px);
            box-shadow: 0 10px 20px rgba(248, 2, 23, 0.3);
        }

        .card-main { border: none; border-radius: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.05); background: white; overflow: hidden; }
        .hero-info { background: linear-gradient(135deg, #a00428 0%, #457b9d 100%); color: white; padding: 40px; }
        .info-grid { background: #f1f5f9; border-radius: 20px; padding: 25px; }
        
        /* --- STEPPER HORIZONTAL FULL WIDTH ATAS --- */
        .damkar-stepper-box {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin: 10px 0 10px 0;
            padding: 0 60px;
        }
        .damkar-line-bg {
            position: absolute;
            top: 22px;
            left: 0;
            height: 4px;
            width: 100%;
            background-color: #e2e8f0;
            z-index: 1;
        }
        .damkar-line-fill {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: <?= $line_progress ?>;
            background: linear-gradient(90deg, #dc3545, #901b26);
            transition: width 0.5s ease;
        }
        .damkar-node {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .damkar-circle {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background-color: #cbd5e1;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            border: 4px solid #f8fafc;
            transition: all 0.3s ease;
        }
        .damkar-label {
            margin-top: 8px;
            font-size: 0.85rem;
            font-weight: 700;
            color: #64748b;
            background: #f8fafc;
            padding: 2px 10px;
            border-radius: 8px;
        }
        .damkar-node.active .damkar-circle {
            background-color: #dc3545;
            color: white;
            box-shadow: 0 0 0 5px rgba(220, 53, 69, 0.25);
        }
        .damkar-node.active .damkar-label { color: #dc3545; }
        .damkar-node.completed .damkar-circle { background-color: #dc3545; color: white; }
        .damkar-node.completed .damkar-label { color: #212529; }

        /* --- STYLING TIMELINE RIWAYAT KANAN --- */
        .timeline-history {
            list-style: none;
            padding: 0;
            position: relative;
        }
        .timeline-history::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 20px;
            height: calc(100% - 40px);
            width: 3px;
            background: #e2e8f0;
        }
        .timeline-item {
            position: relative;
            padding-left: 55px;
            margin-bottom: 25px;
        }
        .timeline-item:last-child { margin-bottom: 0; }
        .timeline-badge {
            position: absolute;
            left: 0;
            top: 2px;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #f1f5f9;
            border: 2px solid #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            z-index: 2;
            transition: all 0.3s ease;
        }
        
        /* State Status Done / Selesai Dilalui */
        .timeline-item.done .timeline-badge {
            background: #fee2e2;
            border-color: #fca5a5;
        }
        /* State Status Current / Berjalan Saat Ini */
        .timeline-item.current .timeline-badge {
            background: #dc3545;
            border-color: #dc3545;
            box-shadow: 0 0 0 5px rgba(220, 53, 69, 0.15);
        }
        
        .timeline-card {
            padding: 18px;
            border-radius: 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        .timeline-item.current .timeline-card {
            background: #fff5f5;
            border-color: #fecaca;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.05);
        }
        .timeline-item.waiting { opacity: 0.5; }

        @media (max-width: 992px) { 
            .main-wrapper { margin-left: 0; padding: 20px; } 
            .damkar-stepper-box { padding: 0 10px; }
        }
    </style>
</head>
<body>

    <?php include '../config/sidebar.php'; ?>

    <div class="main-wrapper w-100">
        <div class="container-fluid">
            
            <!-- HEADER ATAS -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="title-large m-0">DETAIL PENANGANAN</h1>
                    <p class="text-muted fw-medium mb-0">Informasi lengkap laporan kejadian di lapangan</p>
                </div>
                <a href="monitoring_kejadian.php" class="btn-kembali">
                    <i class="bi bi-arrow-left me-2"></i> Kembali
                </a>
            </div>

            <!-- CONTAINER STEPPER DI BAWAH JUDUL -->
            <div class="card-main p-4 mb-5" style="background-color: #f8fafc; box-shadow: none; border: 1px solid #e2e8f0;">
                <div class="damkar-stepper-box">
                    <div class="damkar-line-bg">
                        <div class="damkar-line-fill"></div>
                    </div>
                    
                    <div class="damkar-node <?= $step1_class ?>">
                        <div class="damkar-circle"><?= $step1_icon ?></div>
                        <div class="damkar-label">Masuk</div>
                    </div>
                    
                    <div class="damkar-node <?= $step2_class ?>">
                        <div class="damkar-circle"><?= $step2_icon ?></div>
                        <div class="damkar-label">Proses</div>
                    </div>
                    
                    <div class="damkar-node <?= $step3_class ?>">
                        <div class="damkar-circle"><?= $step3_icon ?></div>
                        <div class="damkar-label">Selesai</div>
                    </div>
                </div>
            </div>

            <!-- DETAIL KONTEN BAWAH -->
            <div class="row g-4">
                <!-- KOLOM KIRI: CARD UTAMA KEJADIAN -->
                <div class="col-lg-8">
                    <div class="card-main mb-4">
                        <div class="hero-info">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-white text-primary mb-2 rounded-pill px-3 py-2 fw-bold" style="font-size: 0.7rem;">
                                        STATUS: <?= htmlspecialchars(strtoupper($data['status'])) ?>
                                    </span>
                                    <h2 class="fw-bold m-0"><?= htmlspecialchars(strtoupper($data['jenis_kejadian'])) ?></h2>
                                    <small class="opacity-75">ID Laporan: #<?= htmlspecialchars($no_lp) ?></small>
                                </div>
                                <i class="bi bi-shield-fire" style="font-size: 4rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                        
                        <div class="card-body p-4 p-md-5">
                            <div class="row g-4 mb-4">
                                <div class="col-md-4">
                                    <div class="info-grid">
                                        <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 0.65rem;">Pelapor</small>
                                        <div class="fw-bold"><?= htmlspecialchars($data['pelapor']) ?></div>
                                        <div class="text-danger small fw-bold"><?= htmlspecialchars($data['no_hp']) ?></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-grid">
                                        <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 0.65rem;">Lokasi TKP</small>
                                        <div class="fw-bold"><?= htmlspecialchars($lokasi) ?></div>
                                        <div class="text-muted small">Kota Padang</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-grid">
                                        <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 0.65rem;">Tanggal Kejadian</small>
                                        <div class="fw-bold"><?= date('d F Y', strtotime($data['created_at'])) ?></div>
                                        <div class="text-muted small">Pukul <?= str_replace(' WIB', '', $waktu_awal) ?> WIB</div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 rounded-4" style="background: #fff5f5; border: 1px dashed #fecaca;">
                                <h6 class="fw-bold text-danger mb-2"><i class="bi bi-chat-square-text-fill me-2"></i>Deskripsi Kronologi</h6>
                                <p class="m-0 text-dark" style="line-height: 1.8; text-align: justify;">
                                    <?= htmlspecialchars($deskripsi) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: RIWAYAT KRONOLOGI TERKINI (MENARIK & EMOTE) -->
                <div class="col-lg-4">
                    <div class="card-main p-4" style="border-top: 4px solid var(--damkar-red);">
                        <div class="mb-4">
                            <h5 class="fw-bold m-0 d-flex align-items-center">
                                <i class="bi bi-clock-history text-danger me-2"></i> Riwayat Progres
                            </h5>
                        </div>
                        
                        <!-- CONTAINER REFRESH OTOMATIS -->
                        <div id="panel-live-status">
                            <div class="timeline-history">
                                
                                <!-- KRONOLOGI 1: LAPORAN MASUK -->
                                <div class="timeline-item <?= ($status == 'masuk') ? 'current' : 'done' ?>">
                                    <div class="timeline-badge">📥</div>
                                    <div class="timeline-card">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="fw-bold m-0 <?= ($status == 'masuk') ? 'text-danger' : 'text-dark' ?>">Laporan Masuk</h6>
                                            <span class="badge bg-secondary" style="font-size: 0.7rem;"><?= htmlspecialchars($waktu_awal) ?></span>
                                        </div>
                                        <p class="small text-muted mb-0" style="font-size: 0.78rem;">Informasi insiden divalidasi oleh sistem utama Pusdalops Damkar.</p>
                                    </div>
                                </div>

                                <!-- KRONOLOGI 2: ARMADA MENUJU LOKASI -->
                                <?php if ($status == 'masuk'): ?>
                                    <div class="timeline-item waiting">
                                        <div class="timeline-badge">🚒</div>
                                        <div class="timeline-card">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <h6 class="fw-bold text-muted m-0">Armada Menuju Lokasi</h6>
                                                <span class="badge bg-light text-muted" style="font-size: 0.7rem;">--:--</span>
                                            </div>
                                            <p class="small text-muted mb-0" style="font-size: 0.78rem;">Menunggu instruksi komando regu wilayah.</p>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="timeline-item <?= ($status == 'proses') ? 'current' : 'done' ?>">
                                        <div class="timeline-badge" style="<?= ($status == 'proses') ? 'background: #fffbeb; border-color: #f59e0b;' : '' ?>">🚒</div>
                                        <div class="timeline-card">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <h6 class="fw-bold m-0 <?= ($status == 'proses') ? 'text-warning' : 'text-dark' ?>">Armada Menuju Lokasi</h6>
                                                <span class="badge bg-warning text-dark" style="font-size: 0.7rem;"><?= htmlspecialchars($waktu_proses) ?></span>
                                            </div>
                                            <p class="small text-muted mb-0" style="font-size: 0.78rem;">Personel damkar dikerahkan ke lokasi untuk melakukan penanganan.</p>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- KRONOLOGI 3: OPERASI SELESAI -->
                                <?php if ($status == 'selesai'): ?>
                                    <div class="timeline-item current">
                                        <div class="timeline-badge" style="background: #e8f5e9; border-color: #2e7d32;">✅</div>
                                        <div class="timeline-card" style="background: #f1f8e9; border-color: #c5e1a5;">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <h6 class="fw-bold text-success m-0">Operasi Selesai (Aman)</h6>
                                                <span class="badge bg-success" style="font-size: 0.7rem;"><?= htmlspecialchars($waktu_selesai) ?></span>
                                            </div>
                                            <p class="small text-muted mb-0" style="font-size: 0.78rem;">Kondisi area hijau kondusif dan pendinginan selesai.</p>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="timeline-item waiting">
                                        <div class="timeline-badge">✅</div>
                                        <div class="timeline-card">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <h6 class="fw-bold text-muted m-0">Operasi Selesai (Aman)</h6>
                                                <span class="badge bg-light text-muted" style="font-size: 0.7rem;">--:--</span>
                                            </div>
                                            <p class="small text-muted mb-0" style="font-size: 0.78rem;">Menunggu konfirmasi status hijau lapangan.</p>
                                        </div>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SCRIPT AUTO UPDATE DATA BERKALA -->
    <script>
        setInterval(function(){
            const urlParams = new URLSearchParams(window.location.search);
            const noLp = urlParams.get('no_lp');
            
            if(noLp) {
                fetch(window.location.href)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        
                        // Pembaruan komponen tanpa reload browser penuh
                        document.querySelector('.damkar-stepper-box').innerHTML = doc.querySelector('.damkar-stepper-box').innerHTML;
                        document.querySelector('#panel-live-status').innerHTML = doc.querySelector('#panel-live-status').innerHTML;
                        document.querySelector('.hero-info').innerHTML = doc.querySelector('.hero-info').innerHTML;
                    })
                    .catch(err => console.warn('Gagal memuat pembaruan data: ', err));
            }
        }, 5000); 
    </script>
</body>
</html>