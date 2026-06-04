<?php $page = 'laporan_kejadian'; ?>
<?php
include '../../config/koneksi.php';

$current_page = basename($_SERVER['PHP_SELF']);
$path = $_SERVER['PHP_SELF'];
$root_folder = '/Magang_DAMKAR';
$clean_path = str_replace($root_folder, '', $path);
$levels = substr_count($clean_path, '/');
$base_url = ($levels > 1) ? str_repeat('../', $levels - 1) : '';

// ── 1. Filter Waktu (Harian, Bulanan, Tahunan) ──
$filter_tipe = $_GET['filter_tipe'] ?? 'semua';
$where_clause = " WHERE 1=1 ";

if ($filter_tipe == 'harian') {
    $tgl = $_GET['tanggal'] ?? date('Y-m-d');
    $where_clause .= " AND DATE(tanggal) = '$tgl' ";
} elseif ($filter_tipe == 'bulanan') {
    $bulan = $_GET['bulan'] ?? date('m');
    $tahun = $_GET['tahun'] ?? date('Y');
    $where_clause .= " AND MONTH(tanggal) = '$bulan' AND YEAR(tanggal) = '$tahun' ";
} elseif ($filter_tipe == 'tahunan') {
    $tahun = $_GET['tahun'] ?? date('Y');
    $where_clause .= " AND YEAR(tanggal) = '$tahun' ";
}

// ── 2. Rekap Statistik (Sesuai Filter) ──
$row_total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM laporan_kejadian $where_clause"));
$row_masuk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS masuk FROM laporan_kejadian $where_clause AND status = 'masuk'"));
$row_proses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS proses FROM laporan_kejadian $where_clause AND status = 'proses'"));
$row_selesai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS selesai FROM laporan_kejadian $where_clause AND status = 'selesai'"));

// Data tabel terkoneksi langsung dari database manajemen kejadian
$result_tabel = mysqli_query($conn, "SELECT * FROM laporan_kejadian $where_clause ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kejadian - E-DAMKAR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= $base_url ?>assets/css/style.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
            border-bottom: 4px solid #dee2e6;
        }

        .stat-total {
            border-bottom-color: #0d6efd;
        }

        .stat-masuk {
            border-bottom-color: #dc3545;
        }

        .stat-proses {
            border-bottom-color: #ffc107;
        }

        .stat-selesai {
            border-bottom-color: #198754;
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

    <?php include $base_url . 'config/sidebar.php'; ?>

    <div id="main-content" class="p-4">

        <header class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold m-0 text-uppercase">Rekap Laporan Kejadian</h2>
                <p class="text-muted m-0">Sistem Monitoring & Pelaporan Operasional DAMKAR Kota Padang</p>
            </div>
            <div class="btn-print-group">
                <a href="cetak_export.php?<?= http_build_query($_GET) ?>" class="btn btn-dark shadow-sm me-2">
                    <i class="bi bi-printer me-2"></i> Cetak Dokumen
                </a>
                <a href="cetak_export.php?unduh=excel&<?= http_build_query($_GET) ?>"
                    class=" btn btn-success shadow-sm">
                    <i class="bi bi-file-earmark-excel me-2"></i> Export Excel
                </a>
            </div>
        </header>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> Penugasan tim berhasil dikirim dan laporan diproses.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Gagal!</strong> <?= htmlspecialchars($_GET['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" id="filter-box">
            <h5 class="fw-bold text-muted mb-3"><i class="bi bi-funnel-fill me-2"></i>Filter Rentang Waktu</h5>
            <form method="GET" action="" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Tipe Laporan</label>
                    <select name="filter_tipe" id="filter_tipe" class="form-select" onchange="toggleFilterFields()">
                        <option value="semua" <?= $filter_tipe == 'semua' ? 'selected' : '' ?>>Semua Data</option>
                        <option value="harian" <?= $filter_tipe == 'harian' ? 'selected' : '' ?>>Laporan Harian</option>
                        <option value="bulanan" <?= $filter_tipe == 'bulanan' ? 'selected' : '' ?>>Laporan Bulanan</option>
                        <option value="tahunan" <?= $filter_tipe == 'tahunan' ? 'selected' : '' ?>>Laporan Tahunan</option>
                    </select>
                </div>

                <div class="col-md-4 filter-field" id="field-harian" style="display:none;">
                    <label class="form-label small fw-bold">Pilih Tanggal</label>
                    <input type="date" name="tanggal" class="form-control"
                        value="<?= $_GET['tanggal'] ?? date('Y-m-d') ?>">
                </div>

                <div class="col-md-3 filter-field" id="field-bulanan" style="display:none;">
                    <label class="form-label small fw-bold">Pilih Bulan</label>
                    <select name="bulan" class="form-select">
                        <?php
                        $months = [
                            '01' => 'Januari',
                            '02' => 'Februari',
                            '03' => 'Maret',
                            '04' => 'April',
                            '05' => 'Mei',
                            '06' => 'Juni',
                            '07' => 'Juli',
                            '08' => 'Agustus',
                            '09' => 'September',
                            '10' => 'Oktober',
                            '11' => 'November',
                            '12' => 'Desember'
                        ];
                        foreach ($months as $num => $name) {
                            $selected = ($_GET['bulan'] ?? date('m')) == $num ? 'selected' : '';
                            echo "<option value='$num' $selected>$name</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-2 filter-field" id="field-tahun" style="display:none;">
                    <label class="form-label small fw-bold">Pilih Tahun</label>
                    <select name="tahun" class="form-select">
                        <?php
                        $start_year = 2020;
                        $end_year = date('Y');
                        for ($y = $end_year; $y >= $start_year; $y--) {
                            $selected = ($_GET['tahun'] ?? date('Y')) == $y ? 'selected' : '';
                            echo "<option value='$y' $selected>$y</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-filter"></i> Terapkan</button>
                </div>
            </form>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stat-card stat-total text-center">
                    <h6 class="text-muted text-uppercase small fw-bold mb-1">Total Kejadian</h6>
                    <h2 class="fw-bold m-0 text-primary"><?= (int) $row_total['total'] ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card stat-masuk text-center">
                    <h6 class="text-muted text-uppercase small fw-bold mb-1">Laporan Masuk</h6>
                    <h2 class="fw-bold m-0 text-danger"><?= (int) $row_masuk['masuk'] ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card stat-proses text-center">
                    <h6 class="text-muted text-uppercase small fw-bold mb-1">Dalam Proses</h6>
                    <h2 class="fw-bold m-0 text-warning"><?= (int) $row_proses['proses'] ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card stat-selesai text-center">
                    <h6 class="text-muted text-uppercase small fw-bold mb-1">Selesai Ditangani</h6>
                    <h2 class="fw-bold m-0 text-success"><?= (int) $row_selesai['selesai'] ?></h2>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="m-0 fw-bold text-dark"><i class="bi bi-table me-2 text-danger"></i>Data Integrasi Kejadian,
                    Personil & Armada</h5>
                <input type="text" id="searchInput" class="form-control form-control-sm w-25"
                    placeholder="Cari laporan...">
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-uppercase">
                        <tr>
                            <th class="py-3 px-4">No. Laporan &amp; Jenis</th>
                            <th>Lokasi Kejadian</th>
                            <th>Pelapor</th>
                            <th>Personil & Armada Terjun</th>
                            <th>Tanggal Lapor</th>
                            <th>Status</th>
                            <th class="text-center th-aksi">Aksi Status</th>
                        </tr>
                    </thead>
                    <tbody id="tabelBody">
                        <?php if (mysqli_num_rows($result_tabel) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result_tabel)):
                                $st = strtolower($row['status']);
                                if ($st === 'selesai')
                                    $badge = 'bg-success text-white';
                                elseif ($st === 'proses')
                                    $badge = 'bg-warning text-dark';
                                else
                                    $badge = 'bg-danger text-white';
                                ?>
                                <tr>
                                    <td class="py-3 px-4">
                                        <small
                                            class="text-muted d-block fw-semibold mb-1"><?= htmlspecialchars($row['nomor_laporan']) ?></small>
                                        <span
                                            class="fw-bold text-danger text-uppercase d-block"><?= htmlspecialchars($row['jenis_kejadian']) ?></span>
                                    </td>
                                    <td>
                                        <small class="text-muted d-block text-wrap" style="max-width:220px">
                                            <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($row['lokasi']) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="fw-semibold d-block"><?= htmlspecialchars($row['pelapor']) ?></span>
                                        <small class="text-muted"><i
                                                class="bi bi-whatsapp text-success me-1"></i><?= htmlspecialchars($row['no_hp'] ?? '-') ?></small>
                                    </td>
                                    <td>
                                        <small class="d-block text-dark fw-medium">
                                            <i class="bi bi-people-fill text-secondary me-1"></i>Regu:
                                            <?= htmlspecialchars($row['personil_regu'] ?? 'Belum Ditugaskan') ?>
                                        </small>
                                        <small class="d-block text-muted">
                                            <i class="bi bi-truck text-secondary me-1"></i>Armada:
                                            <?= htmlspecialchars($row['armada_sarpras'] ?? '-') ?>
                                        </small>
                                    </td>
                                    <td>
                                        <small
                                            class="fw-medium text-dark"><?= date('d M Y', strtotime($row['tanggal'])) ?></small>
                                    </td>
                                    <td>
                                        <span
                                            class="badge <?= $badge ?> rounded-pill px-3 py-2 small text-uppercase"><?= htmlspecialchars($row['status']) ?></span>
                                    </td>
                                    <td class="text-center td-aksi">
                                        <?php if ($st === 'masuk'): ?>
                                            <a href="proses.php?id=<?= $row['id'] ?>"
                                                class="btn btn-warning btn-sm fw-bold shadow-sm px-3">
                                                <i class="bi bi-arrow-right-circle me-1"></i> Proses
                                            </a>
                                        <?php elseif ($st === 'proses'): ?>
                                            <a href="proses_selesai.php?id=<?= $row['id'] ?>"
                                                class="btn btn-success btn-sm fw-bold shadow-sm px-3"
                                                onclick="return confirm('Apakah penanganan laporan ini sudah benar-benar selesai?')">
                                                <i class="bi bi-check-circle me-1"></i> Selesai
                                            </a>
                                        <?php else: ?>
                                            <button class="btn btn-light btn-sm text-muted border-0" disabled><i
                                                    class="bi bi-check-all text-success"></i> Tuntas</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr id="emptyRow">
                                <td colspan="7" class="py-5 text-center bg-white">
                                    <i class="bi bi-database-exclamation text-light" style="font-size:3rem"></i>
                                    <h6 class="fw-bold text-muted mt-2">TIDAK ADA DATA LAPORAN PADA PERIODE INI</h6>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleFilterFields() {
            const tipe = document.getElementById('filter_tipe').value;
            document.querySelectorAll('.filter-field').forEach(el => el.style.display = 'none');

            if (tipe === 'harian') {
                document.getElementById('field-harian').style.display = 'block';
            } else if (tipe === 'bulanan') {
                document.getElementById('field-bulanan').style.display = 'block';
                document.getElementById('field-tahun').style.display = 'block';
            } else if (tipe === 'tahunan') {
                document.getElementById('field-tahun').style.display = 'block';
            }
        }
        document.addEventListener("DOMContentLoaded", toggleFilterFields);

        document.getElementById('searchInput').addEventListener('input', function () {
            const keyword = this.value.toLowerCase();
            const rows = document.querySelectorAll('#tabelBody tr:not(#emptyRow)');
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(keyword) ? '' : 'none';
            });
        });
    </script>
</body>

</html>