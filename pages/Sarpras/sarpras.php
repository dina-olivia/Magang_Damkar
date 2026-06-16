<?php
include_once __DIR__ . '/../../config/koneksi.php';
if (!isset($conn) && isset($koneksi)) {
    $conn = $koneksi;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sarpras - DAMKAR Padang</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    
    <style>
        body {
            background-color: #f8f9fa;
        }

        #main-content {
            margin-left: 260px;
            transition: margin 0.3s ease;
        }

        @media (max-width: 991px) {
            #main-content {
                margin-left: 0;
            }
        }

        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .nav-tabs .nav-link { color: #64748b; font-weight: 600; }
        .nav-tabs .nav-link.active { color: #b91c1c; border-bottom: 3px solid #b91c1c; border-top: none; border-left: none; border-right: none; }
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
                <a href="#menuOperasional" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-clipboard-check"></i> Operasional</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuOperasional">
                    <a href="../operasional/penugasan_tim.php">Penugasan Tim</a>
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
                <a href="#menuSarpras" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center" aria-expanded="true">
                    <span><i class="bi bi-tools"></i> Sarpras</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu show" id="menuSarpras">
                    <a href="sarpras.php" class="active">Data Sarpras</a>
                    <a href="master_bidang.php">Master Bidang</a>
                    <a href="master_kategori.php">Master Kategori</a>
                </div>

                <!-- Laporan & Analitik -->
                <a href="#menuLaporan" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-file-earmark-text"></i> Laporan & Analitik</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuLaporan">
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
$query = mysqli_query($conn, "SELECT * FROM sarpras");

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
$query = mysqli_query($conn, "SELECT * FROM hydrant");

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 