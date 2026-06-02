<?php include '../config/koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Master Bidang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }
        body {
            background: #f0f2f5;
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0; padding: 0;
        }

        :root {
            --fire-red: #b91c1c;
            --dark-sidebar: #0f172a;
            --sidebar-text: #94a3b8;
        }

        #sidebar {
            width: 280px; height: 100vh;
            position: fixed; left: 0; top: 0;
            background-color: var(--dark-sidebar);
            display: flex; flex-direction: column;
            z-index: 1000;
        }

        .sidebar-header {
    padding: 20px;
    background-color: var(--fire-red);
    color: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

        .sidebar-content { flex-grow: 1; overflow-y: auto; overflow-x: hidden; }
        .sidebar-content::-webkit-scrollbar { width: 5px; }
        .sidebar-content::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }

        #sidebar a {
            color: var(--sidebar-text);
            text-decoration: none;
            padding: 12px 25px;
            display: flex; align-items: center;
            font-size: 0.95rem;
            border-left: 4px solid transparent;
            transition: 0.2s;
        }

        #sidebar a i { margin-right: 12px; font-size: 1.1rem; }

        #sidebar a:hover, #sidebar a.active {
            background-color: #1e293b;
            color: white;
            border-left: 4px solid #ef4444;
        }

        .sub-menu { background-color: #1a2236; }
        .sub-menu a { padding-left: 50px !important; font-size: 0.85rem !important; }

        
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            padding: 32px;
            position: relative;
            z-index: 10;
        }

        .page-header {
            display: flex; justify-content: space-between; align-items: flex-start;
            margin-bottom: 28px;
        }
        .page-header h1 { font-size: 26px; font-weight: 700; color: #1a1f2e; margin: 0 0 4px 0; }
        .page-header p { font-size: 14px; color: #6b7280; margin: 0; }

        .btn-tambah {
            background: #e63946; color: #fff;
            border: none; border-radius: 10px;
            padding: 10px 20px; font-size: 14px; font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
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
        }
        .search-box input {
            flex: 1; border: none; outline: none;
            font-size: 14px; font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1a1f2e; background: transparent;
        }
        .search-box input::placeholder { color: #9ca3af; }
        .search-box .search-icon { color: #9ca3af; font-size: 17px; }
        .btn-cari {
            background: #1a1f2e; color: #fff;
            border: none; border-radius: 8px;
            padding: 8px 18px; font-size: 13px; font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer; transition: background 0.2s;
        }
        .btn-cari:hover { background: #e63946; }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .card-bidang {
            background: #fff; border-radius: 16px;
            border: 1.5px solid #e5e7eb; padding: 22px;
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
            position: relative; overflow: hidden;
        }
        .card-bidang::before {
            content: ''; position: absolute;
            top: 0; left: 0; right: 0; height: 4px;
            border-radius: 16px 16px 0 0;
        }
        .card-bidang.danger::before  { background: #e63946; }
        .card-bidang.success::before { background: #2d9e6b; }
        .card-bidang.warning::before { background: #f59e0b; }
        .card-bidang.info::before    { background: #3b82f6; }
        .card-bidang:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,0.09); border-color: #d1d5db; }

        .card-top { display: flex; justify-content: space-between; align-items: flex-start; }
        .icon-box {
            width: 52px; height: 52px; border-radius: 13px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; color: #fff; flex-shrink: 0;
        }
        .icon-box.danger  { background: #e63946; }
        .icon-box.success { background: #2d9e6b; }
        .icon-box.warning { background: #f59e0b; }
        .icon-box.info    { background: #3b82f6; }

        .card-actions { display: flex; gap: 6px; }
        .btn-edit, .btn-hapus {
            border: none; border-radius: 8px;
            padding: 6px 14px; font-size: 12px; font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer; text-decoration: none;
            display: inline-flex; align-items: center; gap: 4px;
            transition: opacity 0.2s, transform 0.15s;
        }
        .btn-edit  { background: #fef3c7; color: #92400e; }
        .btn-hapus { background: #fee2e2; color: #991b1b; }
        .btn-edit:hover, .btn-hapus:hover { opacity: 0.8; transform: translateY(-1px); }

        .card-title { font-size: 16px; font-weight: 700; margin: 16px 0 6px 0; }
        .card-title.danger  { color: #e63946; }
        .card-title.success { color: #2d9e6b; }
        .card-title.warning { color: #d97706; }
        .card-title.info    { color: #2563eb; }

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
    <div class="sidebar-header text-center flex-column">
    <img src="../assets/logo_damkar.png" width="70"
         onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/b/b0/Logo_Damkar.png'">
    <h6 class="fw-bold mt-2 mb-0">DAMKAR PADANG</h6>
</div>

    <div class="sidebar-content">
        <div class="nav flex-column mt-2">
            <a href="../index.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="manajemen_kejadian.php"><i class="bi bi-megaphone"></i> Manajemen Kejadian</a>

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

            <a href="personil.php"><i class="bi bi-people"></i> Personil</a>
            <a href="armada.php"><i class="bi bi-truck"></i> Armada</a>

            <a href="#menuSarpras" data-bs-toggle="collapse"
               class="d-flex justify-content-between align-items-center active">
                <span><i class="bi bi-tools"></i> Sarpras</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse show sub-menu" id="menuSarpras">
                <a href="sarpras.php"><i class="bi bi-dot"></i> Data Sarpras</a>
                <a href="master_bidang.php" class="active"><i class="bi bi-dot"></i> Master Bidang</a>
                <a href="master_kategori.php"><i class="bi bi-dot"></i> Master Kategori</a>
            </div>

            <a href="laporan.php"><i class="bi bi-file-earmark-text"></i> Laporan</a>
            <a href="pengaturan.php"><i class="bi bi-gear"></i> Pengaturan</a>
            <a href="../logout.php" class="mt-4 text-danger"><i class="bi bi-box-arrow-left"></i> Keluar</a>
        </div>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">

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
    ? mysqli_real_escape_string($koneksi, $_GET['keyword'])
    : '';

$query = mysqli_query($koneksi, "
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