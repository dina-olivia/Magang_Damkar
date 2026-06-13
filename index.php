<<<<<<< HEAD
<?php
include 'config/auth.php';
=======
<?php 
>>>>>>> c234622724096cb442c46a5afba35dc345b8abe9
include 'config/koneksi.php';

// ── Statistik ────────────────────────────────────────────────
$menunggu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM laporan_kejadian WHERE status='masuk'"))['t'] ?? 0;
$proses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM laporan_kejadian WHERE status='proses'"))['t'] ?? 0;
$selesai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM laporan_kejadian WHERE status='selesai'"))['t'] ?? 0;
$armada = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM armada WHERE status='siaga'"))['t'] ?? 0;
$personil = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM tbl_daftar"))['t'] ?? 0;

// ── Laporan terbaru ──────────────────────────────────────────
$recent = mysqli_query($conn, "SELECT * FROM laporan_kejadian ORDER BY created_at DESC LIMIT 6");

// ── Grafik: kejadian per bulan (tahun ini) ───────────────────
$tahun = date('Y');
$grafik_query = mysqli_query($conn, "
    SELECT MONTH(tanggal) as bln, jenis_kejadian, COUNT(*) as total
    FROM laporan_kejadian
    WHERE YEAR(tanggal) = '$tahun'
    GROUP BY MONTH(tanggal), jenis_kejadian
");
$kebakaran_data = array_fill(1, 12, 0);
$rescue_data = array_fill(1, 12, 0);
$banjir_data = array_fill(1, 12, 0);
if ($grafik_query) {
    while ($g = mysqli_fetch_assoc($grafik_query)) {
        $m = (int) $g['bln'];
        if ($g['jenis_kejadian'] == 'kebakaran')
            $kebakaran_data[$m] = (int) $g['total'];
        if ($g['jenis_kejadian'] == 'rescue')
            $rescue_data[$m] = (int) $g['total'];
        if ($g['jenis_kejadian'] == 'banjir')
            $banjir_data[$m] = (int) $g['total'];
    }
}

<<<<<<< HEAD
// ── Pie chart: distribusi jenis kejadian ─────────────────────
$pie_query = mysqli_query($conn, "
    SELECT jenis_kejadian, COUNT(*) as total
    FROM laporan_kejadian
    GROUP BY jenis_kejadian
");
$pie_labels = [];
$pie_data = [];
$pie_colors = [];
$color_map = ['kebakaran' => '#ef4444', 'banjir' => '#3b82f6', 'rescue' => '#f59e0b', 'lainnya' => '#6b7280'];
if ($pie_query) {
    while ($p = mysqli_fetch_assoc($pie_query)) {
        $pie_labels[] = ucfirst($p['jenis_kejadian']);
        $pie_data[] = (int) $p['total'];
        $pie_colors[] = $color_map[$p['jenis_kejadian']] ?? '#94a3b8';
    }
}

$armada_list = mysqli_query($conn, "SELECT kode_armada, jenis, status, merk FROM armada ORDER BY status ASC LIMIT 5");
=======
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
>>>>>>> c234622724096cb442c46a5afba35dc345b8abe9
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - E-DAMKAR Kota Padang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="assets/css/style.css">
<<<<<<< HEAD
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Stat cards */
        .stat-card-new {
            background: white;
            border-radius: 14px;
            padding: 20px 22px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: transform 0.2s;
        }

        .stat-card-new:hover {
            transform: translateY(-3px);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .stat-card-new .label {
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .stat-card-new .value {
            font-size: 28px;
            font-weight: 800;
            line-height: 1;
        }

        /* Section title */
        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Card custom */
        .dash-card {
            background: white;
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            height: 100%;
        }

        /* Status armada badge */
        .armada-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .armada-item:last-child {
            border-bottom: none;
        }

        .armada-name {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
        }

        .armada-jenis {
            font-size: 11px;
            color: #94a3b8;
        }

        /* Tabel */
        .table th {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
            font-weight: 600;
        }

        .table td {
            font-size: 13px;
        }

        /* Header jam */
        .live-time {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #cbd5e1;
        }

        .empty-state i {
            font-size: 3rem;
        }
=======
    
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
>>>>>>> c234622724096cb442c46a5afba35dc345b8abe9
    </style>
</head>

<body>

<<<<<<< HEAD
    <?php include 'config/sidebar.php'; ?>
=======
    <div id="sidebar" class="shadow">
        <div class="sidebar-header">
            <img src="/MAGANG/Magang_Damkar/assets/img/logo_damkar.png" alt="Logo" width="140" height="80">
            <span class="fw-bold ms-2">DAMKAR PADANG</span>
        </div>
>>>>>>> c234622724096cb442c46a5afba35dc345b8abe9

    <div id="main-content" style="background:#f8fafc;min-height:100vh;">
        <div class="p-4">

<<<<<<< HEAD
            <!-- Header -->
            <header class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold m-0" style="font-size:22px;color:#1e293b;">
                        <i class="bi bi-speedometer2 text-danger me-2"></i>Command Center
                    </h2>
                    <p class="text-muted m-0 small">Sistem Informasi Manajemen Kebakaran & Penyelamatan — Kota Padang
                    </p>
                </div>
                <div class="text-end">
                    <span class="badge bg-danger mb-1 px-3 py-2">
                        <span
                            style="width:7px;height:7px;border-radius:50%;background:white;display:inline-block;margin-right:5px;animation:blink 1.2s infinite;"></span>
                        SIAGA 1
                    </span>
                    <div class="live-time" id="liveClock"></div>
=======
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
>>>>>>> c234622724096cb442c46a5afba35dc345b8abe9
                </div>
            </header>

<<<<<<< HEAD
            <!-- ── KARTU STATISTIK ── -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4 col-lg-2-4">
                    <div class="stat-card-new">
                        <div class="stat-icon" style="background:#fef3c7;">
                            <i class="bi bi-megaphone" style="color:#d97706;"></i>
                        </div>
                        <div>
                            <div class="label">Laporan Masuk</div>
                            <div class="value" style="color:#d97706;"><?= $menunggu ?></div>
=======
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
>>>>>>> c234622724096cb442c46a5afba35dc345b8abe9
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2-4">
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
                <div class="col-6 col-md-4 col-lg-2-4">
                    <div class="stat-card-new">
                        <div class="stat-icon" style="background:#dcfce7;">
                            <i class="bi bi-check-circle" style="color:#16a34a;"></i>
                        </div>
                        <div>
                            <div class="label">Selesai</div>
                            <div class="value" style="color:#16a34a;"><?= $selesai ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2-4">
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
                <div class="col-6 col-md-4 col-lg-2-4">
                    <div class="stat-card-new">
                        <div class="stat-icon" style="background:#f3e8ff;">
                            <i class="bi bi-people" style="color:#9333ea;"></i>
                        </div>
                        <div>
                            <div class="label">Total Personil</div>
                            <div class="value" style="color:#9333ea;"><?= $personil ?></div>
                        </div>
                    </div>
                </div>
            </div>

<<<<<<< HEAD
            <!-- ── GRAFIK ROW ── -->
            <div class="row g-3 mb-4">

                <!-- Grafik Batang -->
                <div class="col-lg-8">
                    <div class="dash-card">
                        <div class="section-title">
                            <i class="bi bi-bar-chart-fill text-danger"></i>
                            Tren Kejadian Per Bulan — <?= $tahun ?>
                        </div>
                        <div style="position:relative;height:250px;">
                            <canvas id="grafikBulan"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="col-lg-4">
                    <div class="dash-card">
                        <div class="section-title">
                            <i class="bi bi-pie-chart-fill text-warning"></i>
                            Distribusi Jenis Kejadian
                        </div>
                        <?php if (count($pie_data) > 0): ?>
                            <div style="position:relative;height:220px;">
                                <canvas id="grafikPie"></canvas>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="bi bi-pie-chart"></i>
                                <p class="small mt-2">Belum ada data</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- ── TABEL & ARMADA ── -->
            <div class="row g-3">

                <!-- Laporan Terbaru -->
                <div class="col-lg-8">
                    <div class="dash-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="section-title mb-0">
                                <i class="bi bi-clock-history text-danger"></i>
                                Laporan Terbaru
                            </div>
                            <a href="pages/manajemen/monitoring_kejadian.php" class="btn btn-sm btn-outline-danger">
                                Lihat Semua
                            </a>
                        </div>

                        <?php if ($recent && mysqli_num_rows($recent) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No. Laporan</th>
                                            <th>Tanggal</th>
                                            <th>Pelapor</th>
                                            <th>Lokasi</th>
                                            <th>Jenis</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_assoc($recent)):
                                            $jenis_badge = ['kebakaran' => 'danger', 'banjir' => 'primary', 'rescue' => 'warning', 'lainnya' => 'secondary'];
                                            $status_map = ['masuk' => ['warning', 'Masuk'], 'proses' => ['info', 'Proses'], 'selesai' => ['success', 'Selesai']];
                                            $b = $jenis_badge[$row['jenis_kejadian']] ?? 'secondary';
                                            [$sc, $sl] = $status_map[$row['status']] ?? ['secondary', $row['status']];
                                            ?>
                                            <tr>
                                                <td><span
                                                        class="fw-bold text-danger"><?= htmlspecialchars($row['nomor_laporan']) ?></span>
                                                </td>
                                                <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                                <td><?= htmlspecialchars($row['pelapor']) ?></td>
                                                <td class="text-truncate" style="max-width:120px;">
                                                    <?= htmlspecialchars($row['lokasi']) ?>
                                                </td>
                                                <td><span
                                                        class="badge bg-<?= $b ?>"><?= ucfirst($row['jenis_kejadian']) ?></span>
                                                </td>
                                                <td><span class="badge bg-<?= $sc ?>"><?= $sl ?></span></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <p class="small mt-2">Belum ada laporan</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Status Armada -->
                <div class="col-lg-4">
                    <div class="dash-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="section-title mb-0">
                                <i class="bi bi-truck text-primary"></i>
                                Status Armada
                            </div>
                            <a href="pages/manajemen/monitoring_armada.php"
                                class="btn btn-sm btn-outline-primary">Semua</a>
                        </div>

                        <?php if ($armada_list && mysqli_num_rows($armada_list) > 0):
                            while ($arm = mysqli_fetch_assoc($armada_list)):
                                $s = $arm['status'];
                                $sc = $s == 'siaga' ? 'success' : ($s == 'bertugas' ? 'warning' : 'danger');
                                ?>
                                <div class="armada-item">
                                    <div>
                                        <div class="armada-name"><?= htmlspecialchars($arm['nama_armada']) ?></div>
                                        <div class="armada-jenis"><?= htmlspecialchars($arm['jenis']) ?></div>
                                    </div>
                                    <span class="badge bg-<?= $sc ?>"><?= ucfirst($s) ?></span>
                                </div>
                            <?php endwhile; else: ?>
                            <div class="empty-state">
                                <i class="bi bi-truck"></i>
                                <p class="small mt-2">Belum ada data armada</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
=======
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
>>>>>>> c234622724096cb442c46a5afba35dc345b8abe9
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<<<<<<< HEAD
    <script>
        // ── Jam real-time ────────────────────────────────────────────
        function updateClock() {
            const now = new Date();
            const opts = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
            const tgl = now.toLocaleDateString('id-ID', opts);
            const jam = now.toLocaleTimeString('id-ID');
            document.getElementById('liveClock').textContent = tgl + ' | ' + jam + ' WIB';
        }
        updateClock();
        setInterval(updateClock, 1000);

        // ── Grafik Batang ────────────────────────────────────────────
        const bulanLabel = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        new Chart(document.getElementById('grafikBulan'), {
            type: 'bar',
            data: {
                labels: bulanLabel,
                datasets: [
                    {
                        label: 'Kebakaran',
                        data: <?= json_encode(array_values($kebakaran_data)) ?>,
                        backgroundColor: '#ef4444',
                        borderRadius: 6
                    },
                    {
                        label: 'Rescue',
                        data: <?= json_encode(array_values($rescue_data)) ?>,
                        backgroundColor: '#f59e0b',
                        borderRadius: 6
                    },
                    {
                        label: 'Banjir',
                        data: <?= json_encode(array_values($banjir_data)) ?>,
                        backgroundColor: '#3b82f6',
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { font: { size: 12 } } } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });

        // ── Pie Chart ────────────────────────────────────────────────
        <?php if (count($pie_data) > 0): ?>
            new Chart(document.getElementById('grafikPie'), {
                type: 'doughnut',
                data: {
                    labels: <?= json_encode($pie_labels) ?>,
                    datasets: [{
                        data: <?= json_encode($pie_data) ?>,
                        backgroundColor: <?= json_encode($pie_colors) ?>,
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { font: { size: 12 } } }
                    },
                    cutout: '65%'
                }
            });
        <?php endif; ?>
    </script>
    <style>
        @keyframes blink {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: 0.2
            }
        }

        @media(max-width:768px) {
            .col-lg-2-4 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }
    </style>
</body>
=======
    
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
>>>>>>> c234622724096cb442c46a5afba35dc345b8abe9

        // Jalankan fungsi setiap 1 detik (1000 milidetik)
        setInterval(updateClock, 1000);
        
        // Jalankan pertama kali saat halaman dibuka tanpa menunggu 1 detik pertama
        updateClock();
    </script>
</body>
</html>