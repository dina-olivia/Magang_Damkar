<?php 
include 'config/koneksi.php';

if (!isset($conn)) {
    die("Error: Database connection failed");
}

// 1. Mengambil data asli dari Database secara Real-Time
$total_query = mysqli_query($conn, "SELECT COUNT(*) as t FROM laporan_kejadian");
$total = mysqli_fetch_assoc($total_query) ?: ['t' => 0];

$masuk_query = mysqli_query($conn, "SELECT COUNT(*) as t FROM laporan_kejadian WHERE LOWER(TRIM(status))='masuk'");
$masuk = mysqli_fetch_assoc($masuk_query) ?: ['t' => 0];

$proses_query = mysqli_query($conn, "SELECT COUNT(*) as t FROM laporan_kejadian WHERE LOWER(TRIM(status))='proses'");
$proses = mysqli_fetch_assoc($proses_query) ?: ['t' => 0];

$selesai_query = mysqli_query($conn, "SELECT COUNT(*) as t FROM laporan_kejadian WHERE LOWER(TRIM(status))='selesai'");
$selesai = mysqli_fetch_assoc($selesai_query) ?: ['t' => 0];

$armada_query = mysqli_query($conn, "SELECT COUNT(*) as t FROM armada WHERE LOWER(TRIM(status))='siaga'");
$armada = mysqli_fetch_assoc($armada_query) ?: ['t' => 0];

// 2. Pemetaan array statistik untuk kartu tampilan
$stats = [
    [
        'title' => 'Laporan Masuk', 
        'count' => $masuk['t'], 
        'icon' => 'bi-megaphone-fill', 
        'bg_color' => 'linear-gradient(135deg, #ff9244 0%, #ff6000 100%)' 
    ],
    [
        'title' => 'Dalam Proses', 
        'count' => $proses['t'], 
        'icon' => 'bi-exclamation-triangle-fill', 
        'bg_color' => 'linear-gradient(135deg, #f35353 0%, #d32f2f 100%)' 
    ],
    [
        'title' => 'Selesai', 
        'count' => $selesai['t'], 
        'icon' => 'bi-check-circle-fill', 
        'bg_color' => 'linear-gradient(135deg, #42d279 0%, #2e7d32 100%)' 
    ],
    [
        'title' => 'Armada Siaga', 
        'count' => $armada['t'], 
        'icon' => 'bi-truck', 
        'bg_color' => 'linear-gradient(135deg, #4facfe 0%, #0066eb 100%)' 
    ]
];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-DAMKAR Kota Padang</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        .card-animate {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none !important;
            border-radius: 16px !important;
        }
        .card-animate:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0,0,0,0.15) !important;
        }
        .icon-box-bg {
            background: rgba(255, 255, 255, 0.25);
            padding: 12px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
        }
        .number-display {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1;
        }
    </style>
</head>

<body>

    <div id="sidebar" class="shadow">
        <div class="sidebar-header">
            <img src="/MAGANG/Magang_Damkar/assets/img/logo_damkar.png" alt="Logo" width="140" height="80">
            <span class="fw-bold ms-2">DAMKAR PADANG</span>
        </div>

        <div class="sidebar-content">
            <div class="nav flex-column mt-2">
                <a href="index.php" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>

                <a href="#menuManajemenKejadian" data-bs-toggle="collapse"
                    class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-clipboard-check"></i> Manajemen Kejadian</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>

                <div class="collapse sub-menu" id="menuManajemenKejadian">
                    <a href="pages/input_laporan.php">Input Laporan</a>
                    <a href="pages/monitoring_kejadian.php">Monitoring Kejadian</a>
                    <a href="pages/detail_kejadian.php">Detail Kejadian</a>
                </div>

                <a href="#menuOperasional" data-bs-toggle="collapse"
                    class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-clipboard-check"></i> Operasional</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuOperasional">
                    <a href="pages/melani/penugasan_tim.php">Penugasan Tim</a>
                    <a href="pages/monitoring_armada.php">Monitoring Armada</a>
                    <a href="pages/status_penanganan.php">Status Penanganan</a>
                    <a href="pages/riwayat_penugasan.php">Riwayat Penugasan</a>
                </div>

                <a href="#menuPersonil" data-bs-toggle="collapse"
                    class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-clipboard-check"></i> Personil</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuPersonil">
                    <a href="pages/melani/personil.php">Data Personil</a>
                    <a href="pages/melani/penempatan_pos.php">Penempatan Pos</a>
                    <a href="pages/melani/jadwal_piket.php">Jadwal Piket</a>
                    <a href="pages/melani/riwayat_tugas.php">Riwayat Tugas</a>
                </div>
                <a href="pages/armada.php"><i class="bi bi-truck"></i> Armada</a>

                <a href="#menuSarpras" data-bs-toggle="collapse"
                    class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-tools"></i> Sarpras</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuSarpras">
                    <a href="pages/sarpras.php">Data Sarpras</a>
                    <a href="pages/master_bidang.php">Master Bidang</a>
                    <a href="pages/master_kategori.php">Master Kategori</a>
                </div>

                <a href="#menuLaporan" data-bs-toggle="collapse"
                    class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-file-earmark-bar-graph"></i> Laporan & Statistik</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuLaporan">
                    <a href="pages/laporan/laporan_kejadian.php">Laporan Kejadian</a>
                    <a href="pages/laporan/rekap_statistik.php">Rekap Statistik & Grafik</a>
                    <a href="pages/laporan/export_excel.php">Cetak & Export Dokumen</a>
                </div>
                <a href="pages/manajemen_user.php"><i class="bi bi-person"></i> Manajemen User</a>

                <a href="logout.php" class="mt-4 text-danger"><i class="bi bi-box-arrow-left"></i> Keluar</a>
            </div>
        </div>
    </div>

    <div id="main-content">
        <header class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold m-0 text-uppercase" style="letter-spacing: 1px;">Command Center</h2>
                <p class="text-muted m-0">Sistem Informasi Manajemen Kebakaran & Penyelamatan</p>
            </div>
            <div class="text-end">
                <span class="badge bg-danger mb-1 px-3 py-2 rounded-3">SIAGA 1</span>
                <div id="live-clock" class="fw-bold text-secondary small mt-1">Memuat waktu...</div>
            </div>
        </header>

        <div class="row g-4 mb-5">
            <?php foreach ($stats as $s): ?>
                <div class="col-md-3">
                    <div class="card card-animate p-4 h-100 shadow-sm" style="background: <?= $s['bg_color'] ?>;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase small fw-bold mb-2" style="letter-spacing: 0.5px;"><?= $s['title'] ?></h6>
                                <div class="number-display text-white mt-1"><?= $s['count'] ?></div>
                            </div>
                            <div class="icon-box-bg shadow-sm">
                                <i class="bi <?= $s['icon'] ?> fs-3 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="card border-0 shadow-sm p-4 bg-white rounded-4 border-start border-danger border-4">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="p-3 bg-light rounded-3 text-danger me-3">
                        <i class="bi bi-activity fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Status Sinkronisasi Sistem</h5>
                        <p class="m-0 text-muted small">Memantau database terpusat secara real-time.</p>
                    </div>
                </div>
                <div class="text-end">
                    <span class="badge bg-dark px-3 py-2 rounded-pill fs-6 fw-semibold">
                        Total: <?= $total['t'] ?> Laporan Masuk
                    </span>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function updateClock() {
            const now = new Date();
            
            // Format Hari / Tanggal
            const tanggal = String(now.getDate()).padStart(2, '0');
            const bulanArray = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
            const bulan = bulanArray[now.getMonth()];
            const tahun = now.getFullYear();
            
            // Format Jam, Menit, Detik
            const jam = String(now.getHours()).padStart(2, '0');
            const menit = String(now.getMinutes()).padStart(2, '0');
            const detik = String(now.getSeconds()).padStart(2, '0');
            
            // Gabungkan menjadi format: DD Mmm YYYY | HH:mm:ss WIB
            const finalString = `${tanggal} ${bulan} ${tahun} | ${jam}:${menit}:${detik} WIB`;
            
            document.getElementById('live-clock').textContent = finalString;
        }

        // Jalankan fungsi setiap 1 detik (1000 milidetik)
        setInterval(updateClock, 1000);
        
        // Jalankan pertama kali saat halaman dibuka tanpa menunggu 1 detik pertama
        updateClock();
    </script>
</body>
</html>