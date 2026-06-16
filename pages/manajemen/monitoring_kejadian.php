<?php
// --- LANGKAH 1: KONEKSI DATABASE ---
$host = "localhost";
$user = "root";
$pass = "";
$db   = "app_damkar"; 

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// --- LANGKAH 2: PROSES UPDATE STATUS & TIMESTAMP REAL-TIME ---
if (isset($_GET['update_status']) && isset($_GET['no_lp'])) {
    $status_baru = mysqli_real_escape_string($conn, $_GET['update_status']);
    $no_lp = mysqli_real_escape_string($conn, $_GET['no_lp']);
    $st_lowercase = strtolower($status_baru);

    if ($st_lowercase == 'proses') {
        $query_update = "UPDATE laporan_kejadian SET status = '$status_baru', waktu_proses = NOW() WHERE nomor_laporan = '$no_lp'";
    } elseif ($st_lowercase == 'selesai') {
        $query_update = "UPDATE laporan_kejadian SET status = '$status_baru', waktu_selesai = NOW() WHERE nomor_laporan = '$no_lp'";
    } else {
        $query_update = "UPDATE laporan_kejadian SET status = '$status_baru', waktu_proses = NULL, waktu_selesai = NULL WHERE nomor_laporan = '$no_lp'";
    }

    if (mysqli_query($conn, $query_update)) {
        echo "<script>window.location='monitoring_kejadian.php';</script>";
        exit;
    }
}

// --- LANGKAH 3: PROSES HAPUS DATA ---
if (isset($_GET['hapus'])) {
    $no_lp = mysqli_real_escape_string($conn, $_GET['hapus']);
    $query_hapus = "DELETE FROM laporan_kejadian WHERE nomor_laporan = '$no_lp'";
    if (mysqli_query($conn, $query_hapus)) {
        echo "<script>window.location='monitoring_kejadian.php';</script>";
        exit;
    }
}

// --- CONFIGURATION PAGINATION ---
$limit = 5; // Batas jumlah data per halaman 
$page = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
if ($page < 1) { $page = 1; }

$offset = ($page - 1) * $limit;

// Hitung total seluruh data untuk menentukan jumlah halaman
$total_data_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM laporan_kejadian");
$total_data_row = mysqli_fetch_assoc($total_data_query);
$total_data = $total_data_row['total'];
$total_halaman = ceil($total_data / $limit);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Damkar - Padang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', sans-serif; }
        .main-wrapper { margin-left: 260px; min-height: 100vh; padding: 40px 30px; transition: all 0.3s; }

        .content-card-wrapper {
            background: white;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            border: none;
        }

        .custom-header-red {
            background: linear-gradient(135deg, #dc3545 0%, #901b26 100%);
            padding: 40px;
            color: white;
            border-radius: 0 0 50px 50px;
            margin-bottom: 20px;
        }

        .card-stat { border: none; border-radius: 15px; color: white; padding: 20px; transition: transform 0.2s; }
        .card-stat:hover { transform: translateY(-5px); }
        .box-masuk   { background: linear-gradient(45deg, #0d6efd, #004db3); }
        .box-proses  { background: linear-gradient(45deg, #ffc107, #e6ac00); color: #212529; }
        .box-selesai { background: linear-gradient(45deg, #198754, #105a38); }

        .table-container { padding: 0 20px 20px 20px; }
        .table thead th { 
            background-color: #f8f9fa; 
            color: #6c757d; 
            font-size: 11px; 
            text-transform: uppercase; 
            letter-spacing: 1px; 
            padding: 15px;
            border: none;
        }
        .table tbody td { padding: 15px; border-bottom: 1px solid #f0f0f0; }

        /* Custom Pagination Damkar */
        .pagination .page-link {
            color: #dc3545;
            border-radius: 8px;
            margin: 0 3px;
            border: 1px solid #e2e8f0;
        }
        .pagination .page-item.active .page-link {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }
        .pagination .page-link:hover {
            background-color: #f8d7da;
            color: #a01b26;
        }

        @media (max-width: 992px) { .main-wrapper { margin-left: 0; padding: 15px; } }
    </style>
</head>
<body class="d-flex">

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
                <div class="collapse sub-menu show" id="menuManajemenKejadian">
                    <a href="input_laporan.php">Input Laporan</a>
                    <a href="monitoring_kejadian.php">Monitoring Kejadian</a>
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
                <div class="collapse sub-menu" id="menuLaporan">
                    <a href="../laporan/laporan_kejadian.php">Laporan Kejadian</a>
                    <a href="../laporan/rekap_statistik.php">Rekap Statistik & Analisis</a>
                    <a href="../laporan/cetak_export.php">Cetak & Export Dokumen</a>
                </div>
                
                <a href="../pengaturan.php"><i class="bi bi-gear"></i> Pengaturan</a>
                <a href="../../logout.php" class="mt-4 text-danger"><i class="bi bi-box-arrow-left"></i> Keluar</a>
            </div>
        </div>
    </div>

    <div class="main-wrapper w-100">
        <div class="container-fluid">
            <div class="content-card-wrapper">
                
                <div class="custom-header-red">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold m-0 text-uppercase" style="letter-spacing: 1px;">Monitoring Kejadian</h2>
                            <p class="m-0 opacity-75">Manajemen Data Kejadian Damkar Kota Padang</p>
                        </div>
                        <a href="input_laporan.php" class="btn btn-light text-danger fw-bold rounded-pill px-4 shadow">
                            <i class="bi bi-plus-circle-fill me-2"></i>Tambah Laporan
                        </a>
                    </div>
                </div>

                <div class="p-4">
                    <div class="row g-4 mb-5">
                        <div class="col-md-4">
                            <div class="card-stat box-masuk">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div><p class="m-0 small">Laporan Masuk 📥</p><h3 class="fw-bold m-0"><?= mysqli_num_rows(mysqli_query($conn, "SELECT * FROM laporan_kejadian WHERE status='Masuk'")) ?></h3></div>
                                    <i class="bi bi-envelope-open fs-1 opacity-25"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-stat box-proses">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div><p class="m-0 small">Dalam Proses 🚒</p><h3 class="fw-bold m-0"><?= mysqli_num_rows(mysqli_query($conn, "SELECT * FROM laporan_kejadian WHERE status='Proses'")) ?></h3></div>
                                    <i class="bi bi-arrow-repeat fs-1 opacity-25"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-stat box-selesai">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div><p class="m-0 small">Laporan Selesai ✅</p><h3 class="fw-bold m-0"><?= mysqli_num_rows(mysqli_query($conn, "SELECT * FROM laporan_kejadian WHERE status='Selesai'")) ?></h3></div>
                                    <i class="bi bi-check-circle fs-1 opacity-25"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table align-middle m-0 mb-4">
                                <thead>
                                    <tr>
                                        <th>No. LP</th>
                                        <th>Kejadian</th>
                                        <th>Lokasi / Alamat</th>
                                        <th>Keterangan</th>
                                        <th>Pelapor</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Menggunakan LIMIT dan OFFSET untuk memotong data per halaman
                                    $res = mysqli_query($conn, "SELECT * FROM laporan_kejadian ORDER BY tanggal DESC LIMIT $limit OFFSET $offset");
                                    
                                    if (mysqli_num_rows($res) > 0) {
                                        while ($row = mysqli_fetch_assoc($res)) {
                                            $st = strtolower($row['status']);
                                            $cls = ($st == 'masuk') ? 'bg-primary' : (($st == 'proses') ? 'bg-warning text-dark' : 'bg-success');
                                    ?>
                                    <tr>
                                        <td class="fw-bold text-muted small">#<?= htmlspecialchars($row['nomor_laporan']) ?></td>
                                        <td>
                                            <span class="fw-bold text-dark d-block"><?= htmlspecialchars(strtoupper($row['jenis_kejadian'] ?? 'RESCUE')) ?></span>
                                            <span class="small text-muted"><i class="bi bi-clock me-1"></i><?= date('d M Y', strtotime($row['tanggal'])) ?></span>
                                        </td>
                                        <td>
                                            <span class="small text-dark fw-semibold"><i class="bi bi-geo-alt-fill text-danger me-1"></i><?= htmlspecialchars($row['lokasi'] ?? '-') ?></span>
                                        </td>
                                        <td>
                                            <span class="d-inline-block text-truncate small text-muted" style="max-width: 150px;" title="<?= htmlspecialchars($row['deskripsi']) ?>">
                                                <?= htmlspecialchars($row['deskripsi'] ?? '-') ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="d-block fw-bold small"><?= htmlspecialchars($row['pelapor']) ?></span>
                                            <span class="text-danger small"><?= htmlspecialchars($row['no_hp']) ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill <?= $cls ?> px-3 py-2 shadow-sm" style="font-size: 10px;">
                                                <?= htmlspecialchars(strtoupper($row['status'])) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="detail_kejadian.php?no_lp=<?= urlencode($row['nomor_laporan']) ?>" class="btn btn-outline-info btn-sm rounded-3">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                
                                                <div class="dropdown">
                                                    <button class="btn btn-outline-primary btn-sm rounded-3" data-bs-toggle="dropdown">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <ul class="dropdown-menu shadow border-0">
                                                        <li><a class="dropdown-item small" href="?update_status=Masuk&no_lp=<?= urlencode($row['nomor_laporan']) ?>">Masuk 📥</a></li>
                                                        <li><a class="dropdown-item small" href="?update_status=Proses&no_lp=<?= urlencode($row['nomor_laporan']) ?>">Proses 🚒</a></li>
                                                        <li><a class="dropdown-item small" href="?update_status=Selesai&no_lp=<?= urlencode($row['nomor_laporan']) ?>">Selesai ✅</a></li>
                                                    </ul>
                                                </div>

                                                <a href="?hapus=<?= urlencode($row['nomor_laporan']) ?>" class="btn btn-outline-danger btn-sm rounded-3" onclick="return confirm('Hapus data ini?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php 
                                        } 
                                    } else {
                                        echo "<tr><td colspan='7' class='text-center text-muted py-4'>Tidak ada data pada halaman ini.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if ($total_halaman > 1): ?>
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                
                                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?halaman=<?= $page - 1 ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo; Previous</span>
                                    </a>
                                </li>

                                <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
                                    <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                        <a class="page-link" href="?halaman=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <li class="page-item <?= ($page >= $total_halaman) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?halaman=<?= $page + 1 ?>" aria-label="Next">
                                        <span aria-hidden="true">Next &raquo;</span>
                                    </a>
                                </li>
                                
                            </ul>
                        </nav>
                        <?php endif; ?>
                        </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>