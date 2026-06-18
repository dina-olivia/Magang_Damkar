<?php
// Set penanda halaman aktif untuk sidebar
$page = 'laporan_kejadian';

// Pastikan koneksi di-include menggunakan path absolut relatif terhadap file ini
require_once __DIR__ . '/../../config/koneksi.php';

// Konfigurasi dinamis untuk base_url agar pemanggilan file config/assets tidak pecah
$path = $_SERVER['PHP_SELF'];
$root_folder = '/Magang_DAMKAR';
$clean_path = str_replace($root_folder, '', $path);
$levels = substr_count($clean_path, '/');
$base_url = ($levels > 1) ? str_repeat('../', $levels - 1) : '';

// ── 1. Sinkronisasi Filter Rentang Waktu ──
$filter_tipe = $_GET['filter_tipe'] ?? 'semua';
$where_clause = " WHERE 1=1 ";
$periode_teks = "Semua Periode Data";

if ($filter_tipe == 'harian') {
    $tgl = $_GET['tanggal'] ?? date('Y-m-d');
    $where_clause .= " AND DATE(tanggal) = '$tgl' ";
    $periode_teks = "Tanggal: " . date('d M Y', strtotime($tgl));
} elseif ($filter_tipe == 'bulanan') {
    $bulan = $_GET['bulan'] ?? date('m');
    $tahun = $_GET['tahun'] ?? date('Y');
    $where_clause .= " AND MONTH(tanggal) = '$bulan' AND YEAR(tanggal) = '$tahun' ";

    $months = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
    $periode_teks = "Bulan " . $months[$bulan] . " " . $tahun;
} elseif ($filter_tipe == 'tahunan') {
    $tahun = $_GET['tahun'] ?? date('Y');
    $where_clause .= " AND YEAR(tanggal) = '$tahun' ";
    $periode_teks = "Tahun " . $tahun;
}

// Periksa apakah kolom personil_regu, armada_sarpras, dan verifikasi ada untuk tampilan cetak/Excel
$personil_exists = false;
$armada_exists = false;
$verifikasi_exists = false;
if ($conn) {
    $check_personil = mysqli_query($conn, "SHOW COLUMNS FROM laporan_kejadian LIKE 'personil_regu'");
    $check_armada = mysqli_query($conn, "SHOW COLUMNS FROM laporan_kejadian LIKE 'armada_sarpras'");
    $check_verifikasi = mysqli_query($conn, "SHOW COLUMNS FROM laporan_kejadian LIKE 'verifikasi'");
    $personil_exists = $check_personil && mysqli_num_rows($check_personil) > 0;
    $armada_exists = $check_armada && mysqli_num_rows($check_armada) > 0;
    $verifikasi_exists = $check_verifikasi && mysqli_num_rows($check_verifikasi) > 0;
}

// Ambil data utama dari database sesuai filter
$result_tabel = mysqli_query($conn, "SELECT * FROM laporan_kejadian $where_clause ORDER BY id DESC");

// ── 2. INTERSEPSI KONTEN JIKA TOMBOL EXCEL DITEKAN ──
if (isset($_GET['unduh']) && $_GET['unduh'] === 'excel') {
    $filename = "Laporan_Kejadian_Damkar_" . $filter_tipe . "_" . date('Ymd') . ".xls";

    header("Content-Type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");
    ?>
    <h2>DATA REKAP LAPORAN KEJADIAN E-DAMKAR</h2>
    <p>Periode: <?= htmlspecialchars($periode_teks) ?></p>

    <table border="1">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>No</th>
                <th>No. Laporan</th>
                <th>Jenis Kejadian</th>
                <th>Lokasi</th>
                <th>Pelapor</th>
                <th>No. HP</th>
                <?php if ($personil_exists): ?><th>Personil</th><?php endif; ?>
                <?php if ($armada_exists): ?><th>Armada</th><?php endif; ?>
                <?php if ($verifikasi_exists): ?><th>Verifikasi</th><?php endif; ?>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($row = mysqli_fetch_assoc($result_tabel)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nomor_laporan']) ?></td>
                    <td><?= htmlspecialchars(strtoupper($row['jenis_kejadian'])) ?></td>
                    <td><?= htmlspecialchars($row['lokasi']) ?></td>
                    <td><?= htmlspecialchars($row['pelapor']) ?></td>
                    <td>'<?= htmlspecialchars($row['no_hp']) ?></td>
                    <?php if ($personil_exists): ?><td><?= htmlspecialchars($row['personil_regu'] ?? '-') ?></td><?php endif; ?>
                    <?php if ($armada_exists): ?><td><?= htmlspecialchars($row['armada_sarpras'] ?? '-') ?></td><?php endif; ?>
                    <?php if ($verifikasi_exists): ?><td><?= htmlspecialchars(strtoupper($row['verifikasi'] === 'palsu' ? 'tolak' : ($row['verifikasi'] ?? '-'))) ?></td><?php endif; ?>
                    <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                    <td><?= htmlspecialchars(strtoupper($row['status'])) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php
    exit; // Stop pemrosesan HTML web agar tidak merusak file Excel
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opsi Cetak & Ekspor Laporan - E-DAMKAR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        #main-content {
            margin-left: 260px;
            transition: all 0.3s;
        }

        @media (max-width: 768px) {
            #main-content {
                margin-left: 0;
            }
        }

        /* Sembunyikan elemen khusus cetak dinas dari pandangan layar komputer biasa */
        .kop-surat-cetak,
        .ttd-area-cetak {
            display: none;
        }

        /* ── CSS MODIFIKASI KHUSUS SAAT PRINTER BEKERJA (DI-UPDATE) ── */
        @media print {

            /* 1. Sembunyikan ALL elemen navigasi, sidebar, tombol, dan background gelap global */
            #config\2f sidebar,
            .sidebar,
            #sidebar,
            #sidebar-wrapper,
            .sidebar-nav,
            .btn-action-area,
            .btn-back-nav,
            .preview-header-monitor,
            header,
            nav,
            .navbar,
            #wrapper.toggled #sidebar-wrapper {
                display: none !important;
                width: 0 !important;
                opacity: 0 !important;
                visibility: hidden !important;
            }

            /* 2. Paksa Konten Utama Bergeser Full ke Kiri (Menghapus Margin Kiri) */
            #main-content,
            .main-content,
            #page-content-wrapper,
            .content-wrapper {
                margin-left: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                background: #fff !important;
            }

            /* 3. Rapikan Halaman Dasar Kertas */
            body {
                background: #fff !important;
                font-family: 'Times New Roman', Times, serif;
                color: #000 !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .card {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                background: transparent !important;
            }

            /* 4. Munculkan Kop Surat & Ttd Dinas di Kertas */
            .kop-surat-cetak {
                display: block !important;
                text-align: center;
                border-bottom: 3px double #000 !important;
                padding-bottom: 5px !important;
                margin-bottom: 20px !important;
            }

            .kop-surat-cetak h4 {
                margin: 2px 0 !important;
                font-size: 14px !important;
                font-weight: normal !important;
            }

            .kop-surat-cetak h2 {
                margin: 2px 0 !important;
                font-size: 18px !important;
                font-weight: bold !important;
            }

            .kop-surat-cetak p {
                margin: 2px 0 !important;
                font-size: 11px !important;
                font-style: italic !important;
            }

            .ttd-area-cetak {
                display: block !important;
                margin-top: 50px !important;
                float: right !important;
                text-align: center !important;
                width: 250px !important;
            }

            .ttd-space {
                height: 60px !important;
            }

            /* 5. Format Tabel Hitam Putih Solid */
            table {
                width: 100% !important;
                border-collapse: collapse !important;
            }

            table th {
                background-color: #f2f2f2 !important;
                color: #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            table th,
            table td {
                border: 1px solid #000 !important;
                padding: 6px !important;
                color: #000 !important;
                font-size: 11px !important;
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

                 <!-- Armada -->
                <a href="#menuArmada" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-truck"></i> Armada</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuArmada">
                    <a href="../armada/armada.php">Data Armada</a>
                </div>

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
        <div class="container-fluid">


            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white btn-action-area">
                <div class="d-flex justify-content-between align-items-center flex-wrap g-3">
                    <div>
                        <h5 class="fw-bold m-0 text-dark"><i class="bi bi-sliders me-2 text-danger"></i>Kontrol Output
                            Dokumen</h5>
                        <small class="text-muted">Silakan pilih metode pengeksporan berkas yang Anda butuhkan di bawah
                            ini.</small>
                    </div>
                    <div>
                        <button onclick="window.print()" class="btn btn-dark fw-bold px-4 me-2 shadow-sm">
                            <i class="bi bi-printer me-2"></i> Cetak Dokumen 
                        </button>
                        <?php
                        $excel_params = $_GET;
                        $excel_params['unduh'] = 'excel';
                        $excel_query = http_build_query($excel_params);
                        ?>
                        <a href="?<?= htmlspecialchars($excel_query) ?>"
                            class="btn btn-success fw-bold px-4 shadow-sm">
                            <i class="bi bi-file-earmark-excel me-2"></i> Ekspor ke Excel 
                        </a>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-5 bg-white">

                <div class="kop-surat-cetak">
                    <h4>PEMERINTAH KOTA PADANG</h4>
                    <h2>DINAS PEMADAM KEBAKARAN</h2>
                    <p>Jl. Setiabudi No. 4, Kota Padang - Sumatera Barat</p>
                </div>

                <div class="text-center border-bottom pb-3 mb-4 preview-header-monitor">
                    <h3 class="fw-bold m-0 text-uppercase">REKAP LAPORAN KEJADIAN</h3>
                    <p class="text-muted m-0">Sistem Monitoring & Pelaporan Operasional DAMKAR Kota Padang</p>
                    <span class="badge bg-light text-dark border mt-2 px-3 py-2 text-uppercase fw-semibold">
                        Periode: <?= $periode_teks ?>
                    </span>
                </div>

                <div class="text-center mb-4 d-none d-print-block">
                    <h4 class="fw-bold m-0 text-uppercase" style="text-decoration: underline;">REKAPITULASI LAPORAN
                        KEJADIAN</h4>
                    <p class="m-1 text-uppercase small">PERIODE: <?= $periode_teks ?></p>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light small text-uppercase">
                            <tr>
                                                <th class="text-center" width="5%">No</th>
                                <th class="text-center" width="5%">No. Laporan</th>
                                <th class="text-center" width="5%">Jenis Kejadian</th>
                                <th class="text-center" width="5%">Lokasi Kejadian</th>
                                <th class="text-center" width="5%">Pelapor</th>
                                <th class="text-center" width="5%">No. HP</th>
                                <?php if ($personil_exists): ?><th class="text-center" width="5%">Personil</th><?php endif; ?>
                                <?php if ($armada_exists): ?><th class="text-center" width="5%">Armada</th><?php endif; ?>
                                <?php if ($verifikasi_exists): ?><th class="text-center" width="5%">Verifikasi</th><?php endif; ?>
                                <th class="text-center" width="5%">Tanggal Lapor</th>
                                <th class="text-center" width="5%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $colspan = 8;
                            if ($personil_exists) $colspan++;
                            if ($armada_exists) $colspan++;
                            if ($verifikasi_exists) $colspan++;
                            mysqli_data_seek($result_tabel, 0); // Kembalikan pointer baris mysql ke index awal
                            if (mysqli_num_rows($result_tabel) > 0):
                                while ($row = mysqli_fetch_assoc($result_tabel)):
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td class="fw-semibold small"><?= htmlspecialchars($row['nomor_laporan']) ?></td>
                                        <td class="fw-bold text-danger text-uppercase">
                                            <?= htmlspecialchars($row['jenis_kejadian']) ?>
                                        </td>
                                        <td><small class="text-muted"><?= htmlspecialchars($row['lokasi']) ?></small></td>
                                        <td><?= htmlspecialchars($row['pelapor']) ?></td>
                                        <td><?= htmlspecialchars($row['no_hp'] ?? '-') ?></td>
                                        <?php if ($personil_exists): ?><td><?= htmlspecialchars($row['personil_regu'] ?? '-') ?></td><?php endif; ?>
                                        <?php if ($armada_exists): ?><td><?= htmlspecialchars($row['armada_sarpras'] ?? '-') ?></td><?php endif; ?>
                                        <?php if ($verifikasi_exists): ?><td><?= htmlspecialchars(ucfirst($row['verifikasi'] === 'palsu' ? 'tolak' : ($row['verifikasi'] ?? '-'))) ?></td><?php endif; ?>
                                        <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                                        <td class="text-center text-uppercase fw-bold small">
                                            <?= htmlspecialchars($row['status']) ?>
                                        </td>
                                    </tr>
                                    <?php
                                endwhile;
                            else:
                                ?>
                                <tr>
                                    <td colspan="<?= $colspan ?>" class="py-5 text-center text-muted fw-bold">
                                        TIDAK ADA DATA LAPORAN PADA PERIODE INI
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="ttd-area-cetak">
                    <p>Padang, <?= date('d M Y') ?></p>
                    <p>Kepala Bidang Operasional,</p>
                    <div class="ttd-space"></div>
                    <p><b><u>(..........................................)</u></b></p>
                    <p>NIP. .....................................</p>
                </div>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>