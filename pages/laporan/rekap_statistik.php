<?php
$page = 'rekap_statistik';

$koneksiPath = dirname(__DIR__, 2) . '/config/koneksi.php';
if (!file_exists($koneksiPath)) {
    die('File koneksi tidak ditemukan: ' . $koneksiPath);
}
require_once $koneksiPath;
if (!isset($conn)) {
    die('Koneksi database gagal: variabel $conn tidak terdefinisi.');
}

// ── 1. Mengatur Filter Tahun (Gunakan type casting integer untuk keamanan query) ──
$filter_tahun = isset($_GET['tahun']) ? (int) $_GET['tahun'] : (int) date('Y');

// ── 2. Query Mengambil Data Akumulasi Per Bulan ───────────────────────────────
$query = "SELECT rk.bulan, rk.jumlah_kebakaran, rk.jumlah_rescue, rk.kerugian 
          FROM rekap_kejadian rk 
          WHERE rk.tahun = '$filter_tahun' 
          ORDER BY rk.bulan ASC";
$result = mysqli_query($conn, $query);

// ── 3. Mempersiapkan Wadah Data untuk Grafik & Tabel ──────────────────────────
$months_name = [
    '01' => 'Jan',
    '02' => 'Feb',
    '03' => 'Mar',
    '04' => 'Apr',
    '05' => 'Mei',
    '06' => 'Jun',
    '07' => 'Jul',
    '08' => 'Agu',
    '09' => 'Sep',
    '10' => 'Okt',
    '11' => 'Nov',
    '12' => 'Des'
];

// Inisialisasi data 12 bulan kosong agar grafik & tabel aman dari Undefined Index Notice
$kebakaran_chart = array_fill_keys(array_keys($months_name), 0);
$rescue_chart = array_fill_keys(array_keys($months_name), 0);
$kerugian_chart = array_fill_keys(array_keys($months_name), 0);

$total_kebakaran = 0;
$total_rescue = 0;
$total_kerugian = 0;

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $bln = str_pad($row['bulan'], 2, '0', STR_PAD_LEFT);
        if (array_key_exists($bln, $months_name)) {
            $kebakaran_chart[$bln] = (int) $row['jumlah_kebakaran'];
            $rescue_chart[$bln] = (int) $row['jumlah_rescue'];
            $kerugian_chart[$bln] = (float) $row['kerugian'];

            // Mengaktifkan kalkulasi penjumlahan total data dari database
            $total_kebakaran += $row['jumlah_kebakaran'];
            $total_rescue += $row['jumlah_rescue'];
            $total_kerugian += $row['kerugian'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Statistik Analitik - E-DAMKAR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }

        .stat-box {
            background: #fff;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            border-left: 5px solid #dee2e6;
        }

        .border-kebakaran {
            border-left-color: #dc3545;
        }

        .border-rescue {
            border-left-color: #0dcaf0;
        }

        .border-kerugian {
            border-left-color: #ffc107;
        }

        /* ── CSS KHUSUS MODE CETAK ── */
        @media print {

            /* Sembunyikan sidebar bawaan template, navbar, dan tombol filter sistem */
            .sidebar,
            #sidebar,
            #filter-box,
            .btn-print-group,
            nav,
            header button,
            .navbar {
                display: none !important;
            }

            /* Reset pembatas margin kiri template agar konten berada tepat di tengah kertas */
            body,
            #main-content,
            main,
            .main-container {
                background-color: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
                left: 0 !important;
                position: static !important;
                width: 100% !important;
            }

            /* Ubah komponen card menjadi flat border agar tidak pudar di kertas printer */
            .card,
            .stat-box {
                border: 1px solid #000000 !important;
                box-shadow: none !important;
                background: #ffffff !important;
                page-break-inside: avoid;
            }

            /* Memaksa browser mencetak grafik dan warna latar tabel secara solid */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            @page {
                size: A4 landscape;
                margin: 10mm 15mm;
            }
        }
    </style>
</head>

<body>

     <div id="sidebar" class="shadow">
        <div class="sidebar-header">
            <img src="../../assets/img/logo_damkar.png" alt="Logo" width="140" height="80"
                onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/b/b0/Logo_Damkar.png'">
            <span class="fw-bold ms-2">DAMKAR PADANG</span>
        </div>

        <div class="sidebar-content">
            <div class="nav flex-column mt-2">
                <a href="../../index.php"><i class="bi bi-speedometer2"></i> Dashboard</a>

                <!-- Manajemen Kejadian -->
                <a href="#menuManajemenKejadian" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-megaphone"></i> Manajemen Kejadian</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuManajemenKejadian">
                    <a href="../manajemen/input_laporan.php">Input Laporan</a>
                    <a href="../manajemen/monitoring_kejadian.php">Monitoring Kejadian</a>
                    <a href="../manajemen/detail_kejadian.php">Detail Kejadian</a>
                </div>

                <!-- Operasional (Aktif & Terbuka Otomatis) -->
                <a href="#menuOperasional" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center" aria-expanded="true">
                    <span><i class="bi bi-clipboard-check"></i> Operasional</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuOperasional">
                    <a href="../operasional/penugasan_tim.php" class="active">Penugasan Tim</a>
                    <a href="../operasional/monitoring_armada.php">Monitoring Armada</a>
                    <a href="../operasional/status_penanganan.php">Status Penanganan</a>
                    <a href="../operasional/riwayat_penugasan.php">Riwayat Penugasan</a>
                </div>

                <!-- Personil -->
                <a href="#menuPersonil" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-people"></i> Personil</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuPersonil">
                    <a href="../personil/personil.php">Data Personil</a>
                    <a href="../personil/penempatan_pos.php">Penempatan Pos</a>
                    <a href="../personil/jadwal_piket.php">Jadwal Piket</a>
                    <a href="../personil/riwayat_tugas.php">Riwayat Tugas</a>
                </div>

                <a href="../armada.php"><i class="bi bi-truck"></i> Armada</a>

                <!-- Sarpras -->
                <a href="#menuSarpras" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-tools"></i> Sarpras</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuSarpras">
                    <a href="../Sarpras/sarpras.php">Data Sarpras</a>
                    <a href="../Sarpras/master_bidang.php">Master Bidang</a>
                    <a href="../Sarpras/master_kategori.php">Master Kategori</a>
                </div>

                <!-- Laporan & Analitik -->
                <a href="#menuLaporan" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-file-earmark-text"></i> Laporan & Analitik</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu show" id="menuLaporan">
                    <a href="laporan_kejadian.php">Laporan Kejadian</a>
                    <a href="rekap_statistik.php">Rekap Statistik & Analisis</a>
                    <a href="cetak_export.php">Cetak & Export Dokumen</a>
                </div>
                
                <a href="../pengaturan.php"><i class="bi bi-gear"></i> Pengaturan</a>
                <a href="../../logout.php" class="mt-4 text-danger"><i class="bi bi-box-arrow-left"></i> Keluar</a>
            </div>
        </div>
    </div>

    <div id="main-content" class="p-4">

        <header class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold m-0 text-uppercase"><i class="bi bi-graph-up-arrow text-danger me-2"></i>Rekap
                    Statistik Kejadian</h2>
                <p class="text-muted m-0">Visualisasi Data Tren Kebakaran, Operasi Rescue & Estimasi Kerugian Kota
                    Padang Tahun
                    <?= $filter_tahun ?>
                </p>
            </div>
            <div class="btn-print-group">
                <button onclick="window.print()" class="btn btn-dark shadow-sm px-3">
                    <i class="bi bi-printer me-2"></i> Cetak Statistik
                </button>
            </div>
        </header>

        <div class="card border-0 shadow-sm rounded-4 p-3 mb-4" id="filter-box">
            <form method="GET" action="" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Periode Tahun Analitik</label>
                    <select name="tahun" class="form-select fw-semibold">
                        <?php
                        $start_year = 2020;
                        $end_year = (int) date('Y');
                        for ($y = $end_year; $y >= $start_year; $y--) {
                            $selected = ($filter_tahun == $y) ? 'selected' : '';
                            echo "<option value='$y' $selected>$y</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="bi bi-funnel"></i>
                        Filter</button>
                </div>
            </form>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-box border-kebakaran">
                    <span class="text-muted text-uppercase small fw-bold d-block mb-1">Total Insiden Kebakaran</span>
                    <h2 class="fw-extrabold m-0 text-danger">
                        <?= number_format($total_kebakaran, 0, ',', '.') ?> <small
                            class="fs-6 text-muted fw-normal">Kasus</small>
                    </h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box border-rescue">
                    <span class="text-muted text-uppercase small fw-bold d-block mb-1">Total Penyelamatan
                        (Rescue)</span>
                    <h2 class="fw-extrabold m-0 text-info">
                        <?= number_format($total_rescue, 0, ',', '.') ?> <small
                            class="fs-6 text-muted fw-normal">Aksi</small>
                    </h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box border-kerugian">
                    <span class="text-muted text-uppercase small fw-bold d-block mb-1">Total Nilai Kerugian</span>
                    <h2 class="fw-extrabold m-0 text-warning">Rp
                        <?= number_format($total_kerugian, 0, ',', '.') ?>
                    </h2>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-7 col-lg-7 col-md-12">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                    <h5 class="fw-bold text-dark mb-4"><i class="bi bi-pie-chart-fill me-2 text-primary"></i>Grafik
                        Batang Perbandingan Tren Kejadian</h5>
                    <div style="position: relative; height:320px;">
                        <canvas id="statistikChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-xl-5 col-lg-5 col-md-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="m-0 fw-bold text-dark"><i class="bi bi-list-ol me-2 text-success"></i>Angka Indikator
                            Bulanan</h5>
                    </div>
                    <div class="table-responsive" style="max-height: 330px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0 text-center small">
                            <thead class="table-light text-uppercase sticky-top">
                                <tr>
                                    <th class="text-start ps-3 py-3">Bulan</th>
                                    <th><i class="bi bi-fire text-danger"></i> Kebakaran</th>
                                    <th><i class="bi bi-life-preserver text-info"></i> Rescue</th>
                                    <th class="text-end pe-3">Kerugian</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($months_name as $code => $name): ?>
                                    <tr>
                                        <td class="text-start ps-3 fw-bold">
                                            <?= $name ?>
                                        </td>
                                        <td class="text-danger fw-semibold">
                                            <?= $kebakaran_chart[$code] ?>
                                        </td>
                                        <td class="text-info fw-semibold">
                                            <?= $rescue_chart[$code] ?>
                                        </td>
                                        <td class="text-end pe-3 text-muted">Rp
                                            <?= number_format($kerugian_chart[$code], 0, ',', '.') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        const ctx = document.getElementById('statistikChart').getContext('2d');

        const labelBulan = <?= json_encode(array_values($months_name)) ?>;
        const dataKebakaran = <?= json_encode(array_values($kebakaran_chart)) ?>;
        const dataRescue = <?= json_encode(array_values($rescue_chart)) ?>;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labelBulan,
                datasets: [
                    {
                        label: 'Kebakaran',
                        data: dataKebakaran,
                        backgroundColor: '#dc3545',
                        borderRadius: 6
                    },
                    {
                        label: 'Rescue / Penyelamatan',
                        data: dataRescue,
                        backgroundColor: '#0dcaf0',
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    </script>
</body>

</html>