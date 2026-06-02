<!-- komentar -->
<?php include 'config/koneksi.php'; ?>
<?php
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM laporan_kejadian"));
$menunggu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM laporan_kejadian WHERE status='menunggu'"));
$proses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM laporan_kejadian WHERE status='proses'"));
$selesai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM laporan_kejadian WHERE status='selesai'"));
$armada = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM armada WHERE status='siaga'"));

$stats = [
    ['Laporan Masuk', $menunggu['t'], 'bi-megaphone'],
    ['Dalam Proses', $proses['t'], 'bi-exclamation-triangle'],
    ['Selesai', $selesai['t'], 'bi-check-circle'],
    ['Armada Siaga', $armada['t'], 'bi-truck']
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
                
                <a href="pages/manajemen_kejadian.php"><i class="bi bi-megaphone"></i> Manajemen Kejadian</a>

                <div class="collapse sub-menu" id="menuManajemenKejadian">
                    <a href="pages/input_laporan.php">Input Laporan</a>
                    <a href="pages/monitoring_kejadian.php">Monitoring Kejadian</a>
                    <a href="pages/detail_kejadian.php">Detail Kejadian</a>
                    <a href="pages/timeline_kronologi.php">Timeline Kronologi</a>
                </div>


                <a href="#menuOperasional" data-bs-toggle="collapse"
                    class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-clipboard-check"></i> Operasional</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuOperasional">
                    <a href="pages/penugasan_tim.php">Penugasan Tim</a>
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

                <a href="pages/laporan.php"><i class="bi bi-file-earmark-text"></i> Laporan</a>
                <a href="pages/manajemen_user.php"><i class="bi bi-person"></i> Manajemen User</a>

                <a href="logout.php" class="mt-4 text-danger"><i class="bi bi-box-arrow-left"></i> Keluar</a>
            </div>
        </div>
    </div>

    <div id="main-content">
        <header class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold m-0 text-uppercase">Command Center</h2>
                <p class="text-muted m-0">Sistem Informasi Manajemen Kebakaran & Penyelamatan</p>
            </div>
            <div class="text-end">
                <span class="badge bg-danger mb-1">SIAGA 1</span>
                <div class="fw-bold small"><?php echo date('d M Y | H:i'); ?> WIB</div>
            </div>
        </header>

        <div class="row g-4 mb-5">
            <?php
            $stats = [
                ['Laporan Masuk', '0', 'bi-megaphone'],
                ['Dalam Proses', '0', 'bi- exclamation-triangle'],
                ['Armada Siaga', '0', 'bi-truck'],
                ['Hydrant Baik', '0', 'bi-droplet-half']
            ];
            foreach ($stats as $s): ?>
                <div class="col-md-3">
                    <div class="card card-custom shadow-sm p-4 bg-white text-center">
                        <h6 class="text-muted text-uppercase small fw-bold"><?= $s[0] ?></h6>
                        <h2 class="fw-bold m-0"><?= $s[1] ?></h2>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="card border-0 shadow-sm p-5 text-center bg-white rounded-4">
            <i class="bi bi-shield-lock text-light" style="font-size: 4rem;"></i>
            <h4 class="mt-4 fw-bold">Belum Ada Data Terbaru</h4>
            <p class="text-muted">Pantau kejadian dan status armada melalui menu operasional.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>