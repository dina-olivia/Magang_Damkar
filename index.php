<?php 
include 'config/koneksi.php';

if (!isset($conn)) {
    die("Error: Variabel koneksi database \$conn tidak ditemukan. Periksa kembali file koneksi.php Anda.");
}

// ── Statistik Laporan Kejadian ─────────────────────────────────
$menunggu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM laporan_kejadian WHERE status='masuk'"))['t'] ?? 0;
$proses   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM laporan_kejadian WHERE status='proses'"))['t'] ?? 0;
$selesai  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM laporan_kejadian WHERE status='selesai'"))['t'] ?? 0;

// ── Statistik Armada Lengkap ───────────────────────────────────
$armada_siaga      = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM armada WHERE LOWER(status)='tersedia'"))['t'] ?? 0;
$armada_digunakan  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM armada WHERE LOWER(status)='digunakan'"))['t'] ?? 0;
$armada_perbaikan  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM armada WHERE LOWER(status)='perbaikan'"))['t'] ?? 0;
$armada_rusak      = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM armada WHERE LOWER(status)='rusak'"))['t'] ?? 0;
$armada_total      = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM armada"))['t'] ?? 0;

// ── Statistik Personil ─────────────────────────────────────────
$personil_query = mysqli_query($conn, "SELECT COUNT(*) as c FROM tbl_daftar");
$personil       = ($personil_query ? mysqli_fetch_assoc($personil_query)['c'] : 0);

// ── Total Semua Laporan ────────────────────────────────────────
$total_query = mysqli_query($conn, "SELECT COUNT(*) as t FROM laporan_kejadian");
$total       = mysqli_fetch_assoc($total_query) ?: ['t' => 0];

// ── Kartu Statistik Utama ──────────────────────────────────────
$stats = [
    [
        'title'    => 'Laporan Masuk', 
        'count'    => $menunggu, 
        'icon'     => 'bi-megaphone-fill', 
        'bg_color' => 'linear-gradient(135deg, #ff9244 0%, #ff6000 100%)' 
    ],
    [
        'title'    => 'Dalam Proses', 
        'count'    => $proses, 
        'icon'     => 'bi-exclamation-triangle-fill', 
        'bg_color' => 'linear-gradient(135deg, #f35353 0%, #d32f2f 100%)' 
    ],
    [
        'title'    => 'Selesai', 
        'count'    => $selesai, 
        'icon'     => 'bi-check-circle-fill', 
        'bg_color' => 'linear-gradient(135deg, #42d279 0%, #2e7d32 100%)' 
    ],
    [
        'title'    => 'Total Armada', 
        'count'    => $armada_total, 
        'icon'     => 'bi-truck', 
        'bg_color' => 'linear-gradient(135deg, #4facfe 0%, #0066eb 100%)' 
    ],
];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – DAMKAR Padang</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        body {
            background-color: #f8fafc;
            margin: 0;
            display: flex;
        }
        #sidebar  { flex-shrink: 0; }
        #main-content { flex-grow: 1; padding: 30px; min-width: 0; }

        /* ── Kartu Statistik Utama ── */
        .card-animate {
            transition: transform .3s ease, box-shadow .3s ease;
            border: none !important;
            border-radius: 16px !important;
        }
        .card-animate:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0,0,0,.15) !important;
        }
        .icon-box-bg {
            background: rgba(255,255,255,.25);
            padding: 12px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 50px; height: 50px;
        }
        .number-display { font-size: 2.5rem; font-weight: 800; line-height: 1; }

        /* ── Stat Card Baris Kedua ── */
        .stat-card-new {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,.02);
        }
        .stat-icon {
            width: 45px; height: 45px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem;
        }
        .stat-card-new .label { font-size: .8rem; color: #64748b; font-weight: 600; }
        .stat-card-new .value { font-size: 1.2rem; font-weight: 700; }

        /* ── Tabel Armada ── */
        .table-armada thead th {
            background: #f1f5f9;
            font-size: .75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #64748b;
            padding: 12px 16px;
            border: none;
        }
        .table-armada tbody td {
            padding: 14px 16px;
            font-size: .85rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
        }
        .table-armada tbody tr:last-child td { border-bottom: none; }
        .table-armada tbody tr:hover { background: #f8fafc; }

        /* ── Pill Badge Status ── */
        .pill {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .3px;
        }
        .pill-siaga     { background: #dcfce7; color: #15803d; }
        .pill-tersedia  { background: #dcfce7; color: #15803d; }
        .pill-digunakan { background: #dbeafe; color: #1d4ed8; }
        .pill-perbaikan { background: #fef3c7; color: #92400e; }
        .pill-rusak     { background: #fee2e2; color: #991b1b; }

        /* ── Progress Bar Armada ── */
        .armada-progress-wrap { display: flex; gap: 8px; align-items: center; }
        .armada-bar {
            height: 8px; border-radius: 99px; flex: 1;
        }

        /* ── Section Header ── */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        .section-title {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-title i { color: #6366f1; }
    </style>
</head>

<body>

    <!-- ══════════════════════════ SIDEBAR ══════════════════════════ -->
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

                <a href="#menuArmada" data-bs-toggle="collapse"
                    class="d-flex justify-content-between align-items-center collapsed">
                    <span><i class="bi bi-truck"></i> Armada</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuArmada">
                    <a href="pages/armada/armada.php">Data Armada</a>
                </div>

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

                <a href="pages/manajemen_user.php"><i class="bi bi-gear"></i> Manajemen User</a>
                <a href="logout.php" class="mt-4 text-danger"><i class="bi bi-box-arrow-left"></i> Keluar</a>
            </div>
        </div>
    </div>
    <!-- ══════════════════════ END SIDEBAR ══════════════════════ -->

    <div id="main-content">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h2 class="fw-bold m-0 text-uppercase">Command Center</h2>
                <div class="text-muted small">Sistem Informasi Manajemen Kebakaran & Penyelamatan Kota Padang</div>
            </div>
            <div class="text-end d-flex align-items-center gap-3">
                <span class="badge bg-danger px-3 py-2 rounded-3 fw-bold" style="font-size:.85rem;">SIAGA 1</span>
                <div class="text-secondary small fw-medium">
                    <i class="bi bi-clock-fill me-1 text-danger"></i>
                    <span id="live-clock">Memuat waktu...</span>
                </div>
            </div>
        </div>

        <!-- ── Baris 1: Kartu Statistik Utama ── -->
        <div class="row g-4 mb-4">
            <?php foreach ($stats as $s): ?>
            <div class="col-md-3">
                <div class="card card-animate p-4 h-100 shadow-sm" style="background:<?= $s['bg_color'] ?>;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 text-uppercase small fw-bold mb-2"
                                style="letter-spacing:.5px; font-size:.75rem;"><?= $s['title'] ?></h6>
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

        <!-- ── Baris 2: Stat Card Ringkasan ── -->
        <div class="row g-3 mb-4">
            <!-- Dalam Proses -->
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
            <!-- Selesai -->
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
            <!-- Armada Siaga -->
            <div class="col-6 col-md-3">
                <div class="stat-card-new">
                    <div class="stat-icon" style="background:#dbeafe;">
                        <i class="bi bi-truck" style="color:#2563eb;"></i>
                    </div>
                    <div>
                        <div class="label">Armada Siaga</div>
                        <div class="value" style="color:#2563eb;"><?= $armada_siaga ?></div>
                    </div>
                </div>
            </div>
            <!-- Total Personil -->
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

        <!-- ── Baris 3: Tabel Armada + Ringkasan Status Armada ── -->
        <div class="row g-4 mb-4">

            <!-- Tabel 5 Armada Terbaru -->
            <div class="col-lg-8">
                <div class="bg-white rounded-4 shadow-sm overflow-hidden h-100">
                    <div class="section-header px-4 pt-4 pb-0">
                        <div class="section-title">
                            <i class="bi bi-truck-front-fill"></i> Data Armada Terkini
                        </div>
                        <a href="pages/armada/armada.php"
                           class="btn btn-sm btn-outline-primary rounded-pill px-3"
                           style="font-size:.75rem;">
                            Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="px-2 pb-2 pt-3">
                        <table class="table table-armada mb-0">
                            <thead>
                                <tr>
                                    <th>No Plat</th>
                                    <th>Jenis Kendaraan</th>
                                    <th>Merk / Model</th>
                                    <th>Tahun</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $armada_dash = mysqli_query($conn, "SELECT * FROM armada ORDER BY id DESC LIMIT 5");
                                if ($armada_dash && mysqli_num_rows($armada_dash) > 0):
                                    while ($a = mysqli_fetch_assoc($armada_dash)):
                                        $sc = strtolower(trim($a['status']));
                                ?>
                                <tr>
                                    <td>
                                        <span class="fw-bold" style="color:#1e293b;">
                                            <?= htmlspecialchars($a['plat_no']) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($a['jenis']) ?></td>
                                    <td><?= htmlspecialchars($a['merk']) ?></td>
                                    <td><?= htmlspecialchars($a['tahun']) ?></td>
                                    <td>
                                        <span class="pill pill-<?= $sc ?>">
                                            <?= htmlspecialchars($a['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endwhile; else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-truck-flatbed fs-2 d-block mb-2 opacity-25"></i>
                                        Belum ada data armada tersedia
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Status Armada (Panel Kanan) -->
            <div class="col-lg-4">
                <div class="bg-white rounded-4 shadow-sm p-4 h-100">
                    <div class="section-title mb-4">
                        <i class="bi bi-bar-chart-fill"></i> Kondisi Armada
                    </div>

                    <!-- Progress Visual per Status -->
                    <?php
                    $armada_status_list = [
                        [
                            'label'   => 'Siaga',
                            'count'   => $armada_siaga,
                            'color'   => '#22c55e',
                            'bg'      => '#dcfce7',
                            'icon'    => 'bi-check-circle-fill',
                        ],
                        [
                            'label'   => 'Digunakan',
                            'count'   => $armada_digunakan,
                            'color'   => '#3b82f6',
                            'bg'      => '#dbeafe',
                            'icon'    => 'bi-truck',
                        ],
                        [
                            'label'   => 'Perbaikan',
                            'count'   => $armada_perbaikan,
                            'color'   => '#f59e0b',
                            'bg'      => '#fef3c7',
                            'icon'    => 'bi-wrench-adjustable',
                        ],
                        [
                            'label'   => 'Rusak',
                            'count'   => $armada_rusak,
                            'color'   => '#ef4444',
                            'bg'      => '#fee2e2',
                            'icon'    => 'bi-exclamation-circle-fill',
                        ],
                    ];
                    $total_arm = max($armada_total, 1); // hindari div/0
                    foreach ($armada_status_list as $ast):
                        $pct = round(($ast['count'] / $total_arm) * 100);
                    ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="d-flex align-items-center gap-2">
                                <div class="stat-icon"
                                     style="background:<?= $ast['bg'] ?>; width:30px; height:30px; font-size:.9rem;">
                                    <i class="bi <?= $ast['icon'] ?>" style="color:<?= $ast['color'] ?>;"></i>
                                </div>
                                <span style="font-size:.82rem; font-weight:600; color:#334155;">
                                    <?= $ast['label'] ?>
                                </span>
                            </div>
                            <span style="font-size:.82rem; font-weight:700; color:<?= $ast['color'] ?>;">
                                <?= $ast['count'] ?> unit
                            </span>
                        </div>
                        <div class="progress" style="height:7px; border-radius:99px; background:#f1f5f9;">
                            <div class="progress-bar"
                                 style="width:<?= $pct ?>%; background:<?= $ast['color'] ?>; border-radius:99px;"
                                 role="progressbar"
                                 aria-valuenow="<?= $pct ?>"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <!-- Total footer -->
                    <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                        <span style="font-size:.8rem; color:#64748b; font-weight:600;">TOTAL UNIT</span>
                        <span class="badge rounded-pill px-3 py-2"
                              style="background:#6366f1; font-size:.85rem; font-weight:700;">
                            <?= $armada_total ?> Unit
                        </span>
                    </div>
                </div>
            </div>

        </div><!-- end row baris 3 -->

        <!-- ── Baris 4: Status Sinkronisasi Sistem ── -->
        <div class="card border-0 shadow-sm p-4 bg-white rounded-4 border-start border-danger border-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center">
                    <div class="p-3 bg-light rounded-3 text-danger me-3">
                        <i class="bi bi-activity fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1" style="font-size:1.1rem;">Status Sinkronisasi Sistem</h5>
                        <p class="m-0 text-muted small">Memantau pembacaan data pelaporan dinamis secara berkala.</p>
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <span class="badge bg-dark px-3 py-2 rounded-pill fs-6 fw-semibold">
                        Total: <?= $total['t'] ?> Laporan
                    </span>
                    <span class="badge px-3 py-2 rounded-pill fs-6 fw-semibold"
                          style="background:#6366f1;">
                        <?= $armada_total ?> Unit Armada
                    </span>
                    <span class="badge px-3 py-2 rounded-pill fs-6 fw-semibold"
                          style="background:#9333ea;">
                        <?= $personil ?> Personil
                    </span>
                </div>
            </div>
        </div>

    </div><!-- end main-content -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateClock() {
            const now = new Date();
            const tgl  = String(now.getDate()).padStart(2,'0');
            const bln  = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"][now.getMonth()];
            const thn  = now.getFullYear();
            const jam  = String(now.getHours()).padStart(2,'0');
            const mnt  = String(now.getMinutes()).padStart(2,'0');
            const dtk  = String(now.getSeconds()).padStart(2,'0');
            document.getElementById('live-clock').textContent =
                `${tgl} ${bln} ${thn} | ${jam}:${mnt}:${dtk} WIB`;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>