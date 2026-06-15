<?php 
include 'config/koneksi.php';

// Memastikan variabel $conn sudah siap digunakan sesuai standarisasi file personil.php
if (!isset($conn)) {
    die("Error: Variabel koneksi database \$conn tidak ditemukan. Periksa kembali file koneksi.php Anda.");
}

// ── Pengambilan Data Statistik Terpusat ───────────────────────
$menunggu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM laporan_kejadian WHERE status='masuk'"))['t'] ?? 0;
$proses   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM laporan_kejadian WHERE status='proses'"))['t'] ?? 0;
$selesai  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM laporan_kejadian WHERE status='selesai'"))['t'] ?? 0;
$armada   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM armada WHERE status='siaga'"))['t'] ?? 0;

// Menyelaraskan query total personil dengan menggunakan tabel yang sama: 'tbl_daftar'
$personil_query = mysqli_query($conn, "SELECT COUNT(*) as c FROM tbl_daftar");
$personil       = ($personil_query ? mysqli_fetch_assoc($personil_query)['c'] : 0);

// Total Semua Laporan Masuk
$total_query = mysqli_query($conn, "SELECT COUNT(*) as t FROM laporan_kejadian");
$total       = mysqli_fetch_assoc($total_query) ?: ['t' => 0];

// Struktur Pemetaan Kartu Utama Dashboard
$stats = [
    [
        'title' => 'Laporan Masuk', 
        'count' => $menunggu, 
        'icon' => 'bi-megaphone-fill', 
        'bg_color' => 'linear-gradient(135deg, #ff9244 0%, #ff6000 100%)' 
    ],
    [
        'title' => 'Dalam Proses', 
        'count' => $proses, 
        'icon' => 'bi-exclamation-triangle-fill', 
        'bg_color' => 'linear-gradient(135deg, #f35353 0%, #d32f2f 100%)' 
    ],
    [
        'title' => 'Selesai', 
        'count' => $selesai, 
        'icon' => 'bi-check-circle-fill', 
        'bg_color' => 'linear-gradient(135deg, #42d279 0%, #2e7d32 100%)' 
    ],
    [
        'title' => 'Armada Siaga', 
        'count' => $armada, 
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
    <title>Dashboard - DAMKAR Padang</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        /* Penyelarasan Layout Dasar Main Content & Sidebar Horizontal Bar */
        body {
            background-color: #f8fafc;
            margin: 0;
            display: flex;
        }

        #sidebar {
            flex-shrink: 0;
        }

        #main-content {
            flex-grow: 1;
            padding: 30px;
            min-width: 0;
        }

        /* Desain Komponen Grid Kartu Statistik Utama */
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

        /* Desain Komponen Ringkasan Baris Kedua */
        .stat-card-new {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .stat-icon {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        .stat-card-new .label {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 600;
        }
        .stat-card-new .value {
            font-size: 1.2rem;
            font-weight: 700;
        }
    </style>
</head>

<body>

    <div id="sidebar" class="shadow">
        <div class="sidebar-header">
            <img src="assets/img/logo_damkar.png" alt="Logo" width="140" height="80"
                onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/b/b0/Logo_Damkar.png'">
            <span class="fw-bold ms-2">DAMKAR PADANG</span>
        </div>

        <div class="sidebar-content">
            <div class="nav flex-column mt-2">
                <a href="index.php" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>

                <a href="#menuManajemenKejadian" data-bs-toggle="collapse"
                    class="d-flex justify-content-between align-items-center collapsed">
                    <span><i class="bi bi-megaphone"></i> Manajemen Kejadian</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuManajemenKejadian">
                    <a href="pages/manajemen/input_laporan.php">Input Laporan</a>
                    <a href="pages/manajemen/monitoring_kejadian.php">Monitoring Kejadian</a>
                    <a href="pages/manajemen/detail_kejadian.php">Detail Kejadian</a>
                    <a href="pages/manajemen/timeline_kronologi.php">Timeline Kronologi</a>
                </div>

                <a href="#menuOperasional" data-bs-toggle="collapse"
                    class="d-flex justify-content-between align-items-center collapsed">
                    <span><i class="bi bi-clipboard-check"></i> Operasional</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuOperasional">
                    <a href="pages/operasional/penugasan_tim.php">Penugasan Tim</a>
                    <a href="pages/operasional/monitoring_armada.php">Monitoring Armada</a>
                    <a href="pages/operasional/status_penanganan.php">Status Penanganan</a>
                    <a href="pages/operasional/riwayat_penugasan.php">Riwayat Penugasan</a>
                </div>

                <a href="#menuPersonil" data-bs-toggle="collapse"
                    class="d-flex justify-content-between align-items-center collapsed">
                    <span><i class="bi bi-people"></i> Personil</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuPersonil">
                    <a href="pages/personil/personil.php">Data Personil</a>
                    <a href="pages/personil/penempatan_pos.php">Penempatan Pos</a>
                    <a href="pages/personil/jadwal_piket.php">Jadwal Piket</a>
                    <a href="pages/personil/riwayat_tugas.php">Riwayat Tugas</a>
                </div>

                <a href="pages/armada.php"><i class="bi bi-truck"></i> Armada</a>

                <a href="#menuSarpras" data-bs-toggle="collapse"
                    class="d-flex justify-content-between align-items-center collapsed">
                    <span><i class="bi bi-tools"></i> Sarpras</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuSarpras">
                    <a href="pages/Sarpras/sarpras.php">Data Sarpras</a>
                    <a href="pages/Sarpras/master_bidang.php">Master Bidang</a>
                    <a href="pages/Sarpras/master_kategori.php">Master Kategori</a>
                </div>

                <a href="#menuLaporan" data-bs-toggle="collapse"
                    class="d-flex justify-content-between align-items-center collapsed">
                    <span><i class="bi bi-file-earmark-text"></i> Laporan & Analitik</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuLaporan">
                    <a href="pages/laporan/laporan_kejadian.php">Laporan Kejadian</a>
                    <a href="pages/laporan/rekap_statistik.php">Rekap Statistik & Analisis</a>
                    <a href="pages/laporan/cetak_export.php">Cetak & Export Dokumen</a>
                </div>

                <a href="pages/pengaturan.php"><i class="bi bi-gear"></i> Pengaturan</a>

                <a href="logout.php" class="mt-4 text-danger"><i class="bi bi-box-arrow-left"></i> Keluar</a>
            </div>
        </div>
    </div>

    <div id="main-content">
        
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h2 class="fw-bold m-0 text-uppercase">Command Center</h2>
                <div class="text-muted small">Sistem Informasi Manajemen Kebakaran & Penyelamatan Kota Padang</div>
            </div>
            <div class="text-end d-flex align-items-center gap-3">
                <span class="badge bg-danger px-3 py-2 rounded-3 fw-bold" style="font-size: 0.85rem;">SIAGA 1</span>
                <div class="text-secondary small fw-medium">
                    <i class="bi bi-clock-fill me-1 text-danger"></i> <span id="live-clock">Memuat waktu...</span>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <?php foreach ($stats as $s): ?>
                <div class="col-md-3">
                    <div class="card card-animate p-4 h-100 shadow-sm" style="background: <?= $s['bg_color'] ?>;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase small fw-bold mb-2" style="letter-spacing: 0.5px; font-size: 0.75rem;"><?= $s['title'] ?></h6>
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

        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-card-new">
                    <div class="stat-icon" style="background:#fee2e2;">
                        <i class="bi bi-fire" style="color:#ef4444;"></i>
                    </div>
                    <div>
                        <div class="label">Dalam Proses</div>
                        <div class="value" style="color:#ef4444;"><?= $proses ?></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card-new">
                    <div class="stat-icon" style="background:#dcfce7;">
                        <i class="bi bi-check-circle-fill" style="color:#16a34a;"></i>
                    </div>
                    <div>
                        <div class="label">Selesai</div>
                        <div class="value" style="color:#16a34a;"><?= $selesai ?></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card-new">
                    <div class="stat-icon" style="background:#dbeafe;">
                        <i class="bi bi-truck" style="color:#2563eb;"></i>
                    </div>
                    <div>
                        <div class="label">Armada Siaga</div>
                        <div class="value" style="color:#2563eb;"><?= $armada ?></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card-new">
                    <div class="stat-icon" style="background:#f3e8ff;">
                        <i class="bi bi-people-fill" style="color:#9333ea;"></i>
                    </div>
                    <div>
                        <div class="label">Total Personil</div>
                        <div class="value" style="color:#9333ea;"><?= $personil ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm p-4 bg-white rounded-4 border-start border-danger border-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center">
                    <div class="p-3 bg-light rounded-3 text-danger me-3">
                        <i class="bi bi-activity fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1" style="font-size: 1.1rem;">Status Sinkronisasi Sistem</h5>
                        <p class="m-0 text-muted small">Memantau pembacaan data pelaporan dinamis secara berkala.</p>
                    </div>
                </div>
                <div>
                    <span class="badge bg-dark px-3 py-2 rounded-pill fs-6 fw-semibold">
                        Total: <?= $total['t'] ?> Laporan Masuk
                    </span>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fungsi Pembaruan Jam Real-Time Sistem Command Center
        function updateClock() {
            const now = new Date();
            const tanggal = String(now.getDate()).padStart(2, '0');
            const bulanArray = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
            const bulan = bulanArray[now.getMonth()];
            const tahun = now.getFullYear();
            
            const jam = String(now.getHours()).padStart(2, '0');
            const menit = String(now.getMinutes()).padStart(2, '0');
            const detik = String(now.getSeconds()).padStart(2, '0');
            
            document.getElementById('live-clock').textContent = `${tanggal} ${bulan} ${tahun} | ${jam}:${menit}:${detik} WIB`;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>

</html>