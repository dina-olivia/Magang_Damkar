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

// --- LANGKAH 2: PROSES UPDATE STATUS ---
if (isset($_GET['update_status']) && isset($_GET['no_lp'])) {
    $status_baru = mysqli_real_escape_string($conn, $_GET['update_status']);
    $no_lp = mysqli_real_escape_string($conn, $_GET['no_lp']);

    $query_update = "UPDATE laporan_kejadian SET status = '$status_baru' WHERE nomor_laporan = '$no_lp'";
    if (mysqli_query($conn, $query_update)) {
        // Menggunakan redirect JS agar halaman segar kembali
        echo "<script>window.location='monitoring_kejadian.php';</script>";
    }
}

// --- LANGKAH 3: PROSES HAPUS DATA ---
if (isset($_GET['hapus'])) {
    $no_lp = mysqli_real_escape_string($conn, $_GET['hapus']);
    $query_hapus = "DELETE FROM laporan_kejadian WHERE nomor_laporan = '$no_lp'";
    if (mysqli_query($conn, $query_hapus)) {
        echo "<script>window.location='monitoring_kejadian.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Damkar - Padang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', sans-serif; }
        
        /* Sidebar Space */
        .main-wrapper { margin-left: 260px; min-height: 100vh; padding: 40px 30px; transition: all 0.3s; }

        /* --- AWAL PEMBUNGKUS UTAMA --- */
        .content-card-wrapper {
            background: white;
            border-radius: 25px; /* Membuat sudut kotak putih melengkung */
            overflow: hidden;    /* Memastikan header tidak keluar jalur lengkungan */
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            border: none;
        }

        /* Header Merah Melengkung */
        .custom-header-red {
            background: linear-gradient(135deg, #dc3545 0%, #901b26 100%);
            padding: 40px;
            color: white;
            border-radius: 0 0 50px 50px; /* Lengkungan khas di bagian bawah header */
            margin-bottom: 20px;
        }
        /* --- AKHIR PEMBUNGKUS UTAMA --- */

        /* Statistik Cards */
        .card-stat { border: none; border-radius: 15px; color: white; padding: 20px; transition: transform 0.2s; }
        .card-stat:hover { transform: translateY(-5px); }
        .box-masuk   { background: linear-gradient(45deg, #0d6efd, #004db3); }
        .box-proses  { background: linear-gradient(45deg, #ffc107, #e6ac00); color: #212529; }
        .box-selesai { background: linear-gradient(45deg, #198754, #105a38); }

        /* Table Styling */
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

        @media (max-width: 992px) { .main-wrapper { margin-left: 0; padding: 15px; } }
    </style>
</head>
<body class="d-flex">

    <?php include '../config/sidebar.php'; ?>

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
                                <div><p class="m-0 small">Laporan Masuk</p><h3 class="fw-bold m-0"><?= mysqli_num_rows(mysqli_query($conn, "SELECT * FROM laporan_kejadian WHERE status='Masuk'")) ?></h3></div>
                                <i class="bi bi-envelope-open fs-1 opacity-25"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-stat box-proses">
                            <div class="d-flex justify-content-between align-items-center">
                                <div><p class="m-0 small">Dalam Proses</p><h3 class="fw-bold m-0"><?= mysqli_num_rows(mysqli_query($conn, "SELECT * FROM laporan_kejadian WHERE status='Proses'")) ?></h3></div>
                                <i class="bi bi-arrow-repeat fs-1 opacity-25"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-stat box-selesai">
                            <div class="d-flex justify-content-between align-items-center">
                                <div><p class="m-0 small">Laporan Selesai</p><h3 class="fw-bold m-0"><?= mysqli_num_rows(mysqli_query($conn, "SELECT * FROM laporan_kejadian WHERE status='Selesai'")) ?></h3></div>
                                <i class="bi bi-check-circle fs-1 opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table align-middle m-0">
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
                                $res = mysqli_query($conn, "SELECT * FROM laporan_kejadian ORDER BY tanggal DESC");
                                while ($row = mysqli_fetch_assoc($res)) {
                                    
                                    // Logika warna status
                                    $st = strtolower($row['status']);
                                    $cls = ($st == 'masuk') ? 'bg-primary' : (($st == 'proses') ? 'bg-warning text-dark' : 'bg-success');
                                ?>
                                <tr>
                                    <td class="fw-bold text-muted small">#<?= $row['nomor_laporan'] ?></td>
                                    <td>
                                        <span class="fw-bold text-dark d-block"><?= strtoupper($row['jenis_kejadian'] ?? 'RESCUE') ?></span>
                                        <span class="small text-muted"><i class="bi bi-clock me-1"></i><?= date('d M Y', strtotime($row['tanggal'])) ?></span>
                                    </td>
                                    <td>
                                        <span class="small text-dark fw-semibold"><i class="bi bi-geo-alt-fill text-danger me-1"></i><?= $row['lokasi'] ?? '-' ?></span>
                                    </td>
                                    <td>
                                        <span class="d-inline-block text-truncate small text-muted" style="max-width: 150px;" title="<?= $row['deskripsi'] ?>">
                                            <?= $row['deskripsi'] ?? '-' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="d-block fw-bold small"><?= $row['pelapor'] ?></span>
                                        <span class="text-danger small"><?= $row['no_hp'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill <?= $cls ?> px-3 py-2 shadow-sm" style="font-size: 10px;">
                                            <?= strtoupper($row['status']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="detail_kejadian.php?no_lp=<?= $row['nomor_laporan'] ?>" class="btn btn-outline-info btn-sm rounded-3">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            
                                            <div class="dropdown">
                                                <button class="btn btn-outline-primary btn-sm rounded-3" data-bs-toggle="dropdown">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <ul class="dropdown-menu shadow border-0">
                                                    <li><a class="dropdown-item small" href="?update_status=Masuk&no_lp=<?= $row['nomor_laporan'] ?>">Masuk</a></li>
                                                    <li><a class="dropdown-item small" href="?update_status=Proses&no_lp=<?= $row['nomor_laporan'] ?>">Proses</a></li>
                                                    <li><a class="dropdown-item small" href="?update_status=Selesai&no_lp=<?= $row['nomor_laporan'] ?>">Selesai</a></li>
                                                </ul>
                                            </div>

                                            <a href="?hapus=<?= $row['nomor_laporan'] ?>" class="btn btn-outline-danger btn-sm rounded-3" onclick="return confirm('Hapus data ini?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
        </div>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>