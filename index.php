<?php 
    include 'config/koneksi.php';
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
        <!-- sidebar -->
         <?php include 'config/sidebar.php'  ?>
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