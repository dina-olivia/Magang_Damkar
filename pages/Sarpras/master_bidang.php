<?php require_once dirname(__DIR__, 2) . '/config/koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Bidang - DAMKAR Padang</title>
    
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
        
        /* Specific styles for master_bidang cards if needed, 
           but trying to keep it consistent with sarpras.php layout */
        .btn-tambah {
            background: #e63946; color: #fff;
            border: none; border-radius: 10px;
            padding: 10px 20px; font-size: 14px; font-weight: 600;
            display: flex; align-items: center; gap: 7px;
            cursor: pointer; text-decoration: none; white-space: nowrap;
            transition: background 0.2s, transform 0.15s;
        }
        .btn-tambah:hover { background: #c1121f; transform: translateY(-1px); color: #fff; }

        .search-box {
            background: #fff; border-radius: 12px;
            padding: 14px 16px; margin-bottom: 28px;
            display: flex; gap: 10px; align-items: center;
            border: 1.5px solid #e5e7eb;
            width: 100%;
        }
        .search-box input {
            flex: 1; border: none; outline: none;
            font-size: 14px; color: #1a1f2e; background: transparent;
        }
        .btn-cari {
            background: #1a1f2e; color: #fff;
            border: none; border-radius: 8px;
            padding: 8px 18px; font-size: 13px; font-weight: 600;
            cursor: pointer; transition: background 0.2s;
        }
        .btn-cari:hover { background: #e63946; }

        .card-bidang {
            background: #fff;
            border-radius: 16px;
            border: 1.5px solid #e5e7eb;
            padding: 22px;
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
            position: relative;
            overflow: hidden;
            width: 100%;
            margin-bottom: 24px;
        }
        .card-bidang::before {
            content: ''; position: absolute;
            top: 0; left: 0; right: 0; height: 4px;
            border-radius: 16px 16px 0 0;
        }
        .card-bidang.danger::before  { background: #e63946; }
        .card-bidang:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,0.09); border-color: #d1d5db; }

        .card-top { display: flex; justify-content: space-between; align-items: flex-start; }
        .icon-box {
            width: 52px; height: 52px; border-radius: 13px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; color: #fff; flex-shrink: 0;
        }
        .icon-box.danger  { background: #e63946; }

        .card-actions { display: flex; gap: 6px; }
        .btn-edit, .btn-hapus {
            border: none; border-radius: 8px;
            padding: 6px 14px; font-size: 12px; font-weight: 600;
            cursor: pointer; text-decoration: none;
            display: inline-flex; align-items: center; gap: 4px;
            transition: opacity 0.2s, transform 0.15s;
        }
        .btn-edit  { background: #fef3c7; color: #92400e; }
        .btn-hapus { background: #fee2e2; color: #991b1b; }
        .btn-edit:hover, .btn-hapus:hover { opacity: 0.8; transform: translateY(-1px); }

        .card-title { font-size: 16px; font-weight: 700; margin: 16px 0 6px 0; }
        .card-title.danger  { color: #e63946; }

        .card-desc { font-size: 13px; color: #6b7280; margin: 0 0 18px 0; line-height: 1.6; }

        .badge-urutan {
            display: inline-flex; align-items: center; gap: 5px;
            background: #f3f4f6; color: #374151;
            font-size: 12px; font-weight: 600;
            border-radius: 7px; padding: 5px 12px;
        }
        .badge-urutan i { font-size: 13px; color: #9ca3af; }
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

                <!-- Operasional -->
                <a href="#menuOperasional" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-clipboard-check"></i> Operasional</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuOperasional">
                    <a href="../operasional/penugasan_tim.php">Penugasan Tim</a>
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

                <!-- Sarpras (Expanded for this page) -->
                <a href="#menuSarpras" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center" aria-expanded="true">
                    <span><i class="bi bi-tools"></i> Sarpras</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu show" id="menuSarpras">
                    <a href="sarpras.php">Data Sarpras</a>
                    <a href="master_bidang.php" class="active">Master Bidang</a>
                    <a href="master_kategori.php">Master Kategori</a>
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

<!-- MAIN CONTENT -->
<div id="main-content" class="p-4">

    <div class="page-header">
        <div>
            <h1>Master Bidang</h1>
            <p>Kelola bidang dalam sistem Damkar Padang</p>
        </div>
        <a href="tambah_bidang.php" class="btn-tambah">
    <i class="bi bi-plus-lg"></i> Tambah Bidang
</a>
    </div>

    <form method="GET">

    <div class="search-box">
        <i class="bi bi-search search-icon"></i>

        <input
            type="text"
            name="keyword"
            placeholder="Cari bidang..."
            value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : ''; ?>"
        >

        <button type="submit" class="btn-cari">
            Cari
        </button>
    </div>

</form>

    <div class="cards-grid">

<?php

$keyword = isset($_GET['keyword'])
    ? mysqli_real_escape_string($conn, $_GET['keyword'])
    : '';

$query = mysqli_query($conn, "
    SELECT * FROM bidang
    WHERE nama_bidang LIKE '%$keyword%'
");

while($data = mysqli_fetch_assoc($query)){
?>

<div class="card-bidang danger">
    <div class="card-top">
        <div class="icon-box danger">
            <i class="bi bi-fire"></i>
        </div>

        <div class="card-actions">
            <a href="edit_bidang.php?id=<?= $data['id_bidang']; ?>" class="btn-edit">
                <i class="bi bi-pencil"></i> Edit
            </a>

            <a href="hapus_bidang.php?id=<?= $data['id_bidang']; ?>" 
               class="btn-hapus"
               onclick="return confirm('Yakin ingin menghapus bidang ini?')">
                <i class="bi bi-trash"></i> Hapus
            </a>
        </div>
    </div>

    <h5 class="card-title danger">
        <?= $data['nama_bidang']; ?>
    </h5>

    <p class="card-desc">
        <?= $data['deskripsi']; ?>
    </p>

    <span class="badge-urutan">
        <i class="bi bi-hash"></i> Urutan : <?= $data['urutan']; ?>
    </span>
</div>

<?php } ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>