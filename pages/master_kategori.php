<?php
include '../config/koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Master Kategori</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>

:root{
    --fire-red:#b91c1c;
    --dark-sidebar:#0f172a;
    --sidebar-text:#94a3b8;
}

#sidebar{
    width:280px;
    height:100vh;
    position:fixed;
    left:0;
    top:0;
    background:var(--dark-sidebar);
    display:flex;
    flex-direction:column;
}

#sidebar a{
    color:var(--sidebar-text);
    text-decoration:none;
    padding:12px 25px;
    display:block;
}

#sidebar a:hover,
#sidebar a.active{
    background:#1e293b;
    color:white;
}

.sidebar-header{
    background:var(--fire-red);
    color:white;
    padding:20px;
    text-align:center;
}

.main-content{
    margin-left:280px;
    padding:30px;
}
        body{
            background:#f5f6fa;
            font-family:Segoe UI;
        }

        .main{
            padding:30px;
        }

        .card-modern{
            border:none;
            border-radius:20px;
            box-shadow:0 2px 10px rgba(0,0,0,0.05);
        }

        .btn-purple{
            background:#6f42c1;
            color:white;
            border:none;
            border-radius:14px;
            padding:10px 18px;
        }

        .btn-purple:hover{
            background:#5b35a0;
            color:white;
        }

        .badge-status{
            background:#d1fae5;
            color:#065f46;
            padding:7px 14px;
            border-radius:20px;
            font-size:12px;
            font-weight:600;
        }

        .table tbody tr:hover{
            background:#fafafa;
        }

        .sidebar-content{
    flex-grow:1;
    overflow-y:auto;
    max-height:calc(100vh - 80px);
}

    </style>
</head>

<body>
<div id="sidebar" class="shadow">
    <div class="sidebar-header text-center flex-column">
        <img src="../assets/logo_damkar.png" width="70">
        <h6 class="fw-bold mt-2 mb-0">DAMKAR PADANG</h6>
    </div>

    <div class="sidebar-content">
        <div class="nav flex-column mt-2">

            <a href="../index.php">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <a href="manajemen_kejadian.php">
                <i class="bi bi-megaphone"></i> Manajemen Kejadian
            </a>

            <!-- OPERASIONAL -->
            <a href="#menuOperasional" data-bs-toggle="collapse"
               class="d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clipboard-check"></i> Operasional</span>
                <i class="bi bi-chevron-down small"></i>
            </a>

            <div class="collapse sub-menu" id="menuOperasional">
                <a href="penugasan_tim.php"><i class="bi bi-dot"></i> Penugasan Tim</a>
                <a href="monitoring_armada.php"><i class="bi bi-dot"></i> Monitoring Armada</a>
                <a href="status_penanganan.php"><i class="bi bi-dot"></i> Status Penanganan</a>
                <a href="riwayat_penugasan.php"><i class="bi bi-dot"></i> Riwayat Penugasan</a>
            </div>

            <a href="personil.php">
                <i class="bi bi-people"></i> Personil
            </a>

            <a href="armada.php">
                <i class="bi bi-truck"></i> Armada
            </a>

            <!-- SARPRAS -->
            <a href="#menuSarpras" data-bs-toggle="collapse"
               class="d-flex justify-content-between align-items-center">
                <span><i class="bi bi-tools"></i> Sarpras</span>
                <i class="bi bi-chevron-down small"></i>
            </a>

            <div class="collapse show sub-menu" id="menuSarpras">
                <a href="sarpras.php"><i class="bi bi-dot"></i> Data Sarpras</a>
                <a href="master_bidang.php"><i class="bi bi-dot"></i> Master Bidang</a>
                <a href="master_kategori.php" class="active"><i class="bi bi-dot"></i> Master Kategori</a>
            </div>

            <a href="laporan.php">
                <i class="bi bi-file-earmark-text"></i> Laporan
            </a>

            <a href="pengaturan.php">
                <i class="bi bi-gear"></i> Pengaturan
            </a>

            <a href="../logout.php" class="mt-4 text-danger">
                <i class="bi bi-box-arrow-left"></i> Keluar
            </a>

        </div>
    </div>
</div>

<div class="main-content">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <small class="text-uppercase text-muted fw-bold">
                MASTER DATA
            </small>

            <h1 class="fw-bold">
                Pengelolaan Kategori
            </h1>

            <p class="text-muted">
                Kelola kategori sarpras per bidang
            </p>
        </div>

        <a href="tambah_kategori.php" class="btn-purple">
    <i class="bi bi-plus-lg"></i>
    Tambah Kategori
</a>

    </div>

    <!-- Statistik -->
    <div class="row g-4 mb-4">

        <div class="col-md-4">
            <div class="card card-modern">
                <div class="card-body">

                    <small class="text-muted">
                        Bidang Operasional
                    </small>

                    <?php
$op = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kategori WHERE bidang='Operasional'");
$data_op = mysqli_fetch_assoc($op);
?>

<h2 class="fw-bold mt-2"><?= $data_op['total']; ?></h2>

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-modern">
                <div class="card-body">

                    <small class="text-muted">
                        Bidang Rescue
                    </small>

                    <?php
$rescue = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kategori WHERE bidang='Rescue'");
$data_rescue = mysqli_fetch_assoc($rescue);
?>

<h2 class="fw-bold mt-2"><?= $data_rescue['total']; ?></h2>

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-modern">
                <div class="card-body">

                    <small class="text-muted">
                        Sarpras & Logistik
                    </small>

                    <?php
$logistik = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kategori WHERE bidang='Sarpras & Logistik'");
$data_logistik = mysqli_fetch_assoc($logistik);
?>

<h2 class="fw-bold mt-2"><?= $data_logistik['total']; ?></h2>

                </div>
            </div>
        </div>

    </div>

   <!-- Search -->
<div class="card card-modern mb-4">

    <div class="card-body">

        <form method="GET">
            <div class="row g-3">

                <div class="col-md-10">

                    <input type="text"
                    name="keyword"
                    class="form-control rounded-4"
                    placeholder="Cari kategori..."
                    value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : ''; ?>">

                </div>

                <div class="col-md-2">

                    <button type="submit" class="btn btn-purple w-100">
                        Cari
                    </button>

                </div>

            </div>
        </form>

    </div>

</div>

    <!-- Table -->
    <div class="card card-modern">

        <div class="card-body p-0">

           <table class="table align-middle mb-0">

                <thead class="table-light">

                    <tr>
                        <th class="p-3">Kategori</th>
                        <th>Bidang</th>
                        <th>Unit</th>
                        <th>Status</th>
<th>Keadaan</th>
<th class="text-center">Aksi</th>
                    </tr>

                </thead>

                <tbody>

<?php

$keyword = isset($_GET['keyword']) 
    ? mysqli_real_escape_string($koneksi, $_GET['keyword']) 
    : '';

$query = mysqli_query($koneksi, "
    SELECT * FROM kategori
    WHERE nama_kategori LIKE '%$keyword%'
    OR bidang LIKE '%$keyword%'
");

if(mysqli_num_rows($query) > 0){

    while($data = mysqli_fetch_assoc($query)){
?>

<tr>

    <td class="p-3">
        <strong><?= $data['nama_kategori']; ?></strong>
    </td>

    <td>
        <span class="badge bg-danger">
            <?= $data['bidang']; ?>
        </span>
    </td>

    <td>
        <?= $data['unit']; ?>
    </td>

    <td>
    <span class="badge-status">
        <?= strtoupper($data['status']); ?>
    </span>
</td>

<td>
    <?= $data['keadaan']; ?>
</td>

<td class="text-center">

    <a href="edit_kategori.php?id=<?= $data['id_kategori']; ?>"
       class="btn btn-warning btn-sm me-1">
        <i class="bi bi-pencil"></i>
    </a>

    <a href="hapus_kategori.php?id=<?= $data['id_kategori']; ?>"
       class="btn btn-danger btn-sm"
       onclick="return confirm('Yakin ingin menghapus kategori ini?')">
        <i class="bi bi-trash"></i>
    </a>

</td>

</tr>

<?php
    }
}
else{
?>

<tr>
    <td colspan="5" class="text-center p-4">
        Belum ada data kategori
    </td>
</tr>

<?php } ?>

</tbody>

            </table>

        </div>

    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html