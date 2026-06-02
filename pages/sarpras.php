<?php 
include '../config/koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sarpras - DAMKAR Padang</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root { 
            --fire-red: #b91c1c; 
            --dark-sidebar: #0f172a; 
            --sidebar-text: #94a3b8;
        }
        
        body { background-color: #f8f9fa; margin: 0; padding: 0; display: flex; }

        /* SIDEBAR FIXED & SCROLLABLE */
        #sidebar {
            width: 280px; height: 100vh; position: fixed; left: 0; top: 0;
            background-color: var(--dark-sidebar); display: flex; flex-direction: column; z-index: 1000;
        }

        .sidebar-header {
            padding: 20px; background-color: var(--fire-red); color: white;
            display: flex; align-items: center; flex-shrink: 0;
        }

        .sidebar-content { flex-grow: 1; overflow-y: auto; overflow-x: hidden; }
        .sidebar-content::-webkit-scrollbar { width: 5px; }
        .sidebar-content::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }

        #sidebar a {
            color: var(--sidebar-text); text-decoration: none; padding: 12px 25px;
            display: flex; align-items: center; font-size: 0.95rem; border-left: 4px solid transparent; transition: 0.2s;
        }

        #sidebar a i { margin-right: 12px; font-size: 1.1rem; }

        #sidebar a:hover, #sidebar a.active {
            background-color: #1e293b; color: white; border-left: 4px solid #ef4444;
        }

        /* Sub-menu styling */
        .sub-menu { background-color: #1a2236; }
        .sub-menu a { padding-left: 50px !important; font-size: 0.85rem !important; }

        /* AREA KONTEN UTAMA */
        #main-content {
            margin-left: 280px; padding: 40px; width: calc(100% - 280px); min-height: 100vh;
        }

        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .nav-tabs .nav-link { color: #64748b; font-weight: 600; }
        .nav-tabs .nav-link.active { color: var(--fire-red); border-bottom: 3px solid var(--fire-red); border-top: none; border-left: none; border-right: none; }
    </style>
</head>
<body>

<div id="sidebar" class="shadow">
<div class="sidebar-header text-center">
    <img src="../assets/logo_damkar.png" width="80">
    <h6 class="fw-bold mt-2 mb-0">DAMKAR PADANG</h6>
</div>
    
    <div class="sidebar-content">
        <div class="nav flex-column mt-2">
            <a href="../index.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
            
            <a href="manajemen_kejadian.php"><i class="bi bi-megaphone"></i> Manajemen Kejadian</a>
            
            <a href="#menuOperasional" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clipboard-check"></i> Operasional</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse show sub-menu" id="menuOperasional">
                <a href="penugasan_tim.php"><i class="bi bi-dot"></i> Penugasan Tim</a>
                <a href="monitoring_armada.php"><i class="bi bi-dot"></i> Monitoring Armada</a>
                <a href="status_penanganan.php"><i class="bi bi-dot"></i> Status Penanganan</a>
                <a href="riwayat_penugasan.php"><i class="bi bi-dot"></i> Riwayat Penugasan</a>
            </div>

            <a href="personil.php"><i class="bi bi-people"></i> Personil</a>
            <a href="armada.php"><i class="bi bi-truck"></i> Armada</a>
            <a href="sarpras.php" class="active"><i class="bi bi-tools"></i> Sarpras</a>
            <a href="laporan.php"><i class="bi bi-file-earmark-text"></i> Laporan</a>
            <a href="pengaturan.php"><i class="bi bi-gear"></i> Pengaturan</a>
            
            <a href="../logout.php" class="mt-4 text-danger"><i class="bi bi-box-arrow-left"></i> Keluar</a>
        </div>
    </div>
</div>

<div id="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold m-0">Sarana & Prasarana</h2>
        <p class="text-muted">
            Kelola data peralatan penyelamatan dan titik hydrant.
        </p>
    </div>

    <div>
    <a href="tambah_data_sarpras.php" class="btn btn-secondary me-2">
        <i class="bi bi-plus-circle"></i> Tambah Peralatan
    </a>

    <a href="tambah_hydrant.php" class="btn btn-danger">
        <i class="bi bi-plus-circle"></i> Tambah Hydrant
    </a>
</div>
</div>

    <div class="card card-custom bg-white p-4">
        <ul class="nav nav-tabs mb-4" id="sarprasTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="peralatan-tab" data-bs-toggle="tab" data-bs-target="#peralatan" type="button">Data Peralatan</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="hydrant-tab" data-bs-toggle="tab" data-bs-target="#hydrant" type="button">Data Hydrant</button>
            </li>
        </ul>

        <div class="tab-content" id="sarprasTabContent">
            <div class="tab-pane fade show active" id="peralatan" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
    <tr>
        <th>Nama Alat</th>
        <th>Jenis</th>
        <th>Kondisi</th>
        <th>Lokasi</th>
        <th style="width:180px; text-align:center;">
            Aksi
        </th>
    </tr>
</thead>
                       <tbody>
<?php
$query = mysqli_query($koneksi, "SELECT * FROM sarpras");

if (mysqli_num_rows($query) > 0) {
    while ($data = mysqli_fetch_assoc($query)) {
?><tr>
    <td><?= $data['nama_alat']; ?></td>
    <td><?= $data['jenis']; ?></td>
    <td><?= $data['kondisi']; ?></td>
    <td><?= $data['lokasi']; ?></td>

    <td class="text-center align-middle">
    <div class="d-flex justify-content-center gap-2">
        <a href="edit_sarpras.php?id=<?= $data['id_sarpras']; ?>" 
           class="btn btn-warning btn-sm">
           Edit
        </a>

        <a href="hapus_sarpras.php?id=<?= $data['id_sarpras']; ?>" 
           class="btn btn-danger btn-sm"
           onclick="return confirm('Yakin ingin menghapus data ini?')">
           Hapus
        </a>
    </div>
</td>
</tr>
<?php
    }
} else {
?>
    <tr>
        <td colspan="5" class="text-center py-4 text-muted small">
            Belum ada data peralatan.
        </td>
    </tr>
<?php } ?>
</tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="hydrant" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
    <tr>
        <th>Nama Hydrant</th>
        <th>Lokasi</th>
        <th>Kondisi</th>
        <th>Keterangan</th>
        <th>Aksi</th>
    </tr>
</thead>
                        <tbody>
<?php
$query = mysqli_query($koneksi, "SELECT * FROM hydrant");

if ($query && mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
?>
    <tr>
    <td><?= $row['nama_hydrant']; ?></td>
    <td><?= $row['lokasi']; ?></td>
    <td><?= $row['kondisi']; ?></td>
    <td><?= $row['keterangan']; ?></td>
    <td>
        <a href="edit_hydrant.php?id=<?= $row['id_hydrant']; ?>" class="btn btn-warning btn-sm">Edit</a>
        <a href="hapus_hydrant.php?id=<?= $row['id_hydrant']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>
    </td>
</tr>
<?php
    } 
} else {
?>
    <tr>
        <td colspan="4" class="text-center text-muted">
            Belum ada data hydrant
        </td>
    </tr>
<?php
} 
?>
</tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>