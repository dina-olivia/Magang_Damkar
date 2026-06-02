<?php require_once __DIR__ . '/../../config/koneksi.php';

// Statistik
$total_penempatan = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM penempatan_pos"));
$penempatan_aktif = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM penempatan_pos WHERE status='Aktif'"));
$jumlah_pos = mysqli_num_rows(mysqli_query($koneksi, "SELECT DISTINCT pos_penempatan FROM penempatan_pos"));

// Distribusi
$query_distribusi = mysqli_query($koneksi, "
    SELECT 
        pos_penempatan,
        COUNT(id) as jumlah
    FROM penempatan_pos
    GROUP BY pos_penempatan
");

// 1. DATA PENEMPATAN DIUBAH MENGGUNAKAN INNER JOIN AGAR SINKRON DENGAN TBL_DAFTAR
$query = mysqli_query($koneksi, "
    SELECT penempatan_pos.*, tbl_daftar.nama_personil AS nama_asli, tbl_daftar.nip AS nip_asli 
    FROM penempatan_pos 
    INNER JOIN tbl_daftar ON penempatan_pos.nama_personil = tbl_daftar.nama_personil 
    ORDER BY penempatan_pos.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penempatan Pos - DAMKAR PADANG</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: #f4f7f6;
            display: flex;
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            width: 280px;
            background: #111625;
            min-height: 100vh;
            color: #a3afc7;
            position: fixed;
            left: 0;
            top: 0;
        }

        .brand {
            background: #d71920;
            padding: 25px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            color: white;
        }

        .brand img {
            width: 140px;
            height: 80px;
        }

        .brand-text h2 {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .brand-text p {
            font-size: 14px;
            opacity: 0.9;
        }

        .menu-list {
            list-style: none;
            margin-top: 15px;
        }

        .menu-item a {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: #a3afc7;
            text-decoration: none;
            font-size: 15px;
            gap: 15px;
            transition: all 0.3s;
        }

        .menu-item a:hover, .menu-item.active > a {
            background: #171e30;
            color: white;
        }

        .menu-item.active {
            border-left: 4px solid #d71920;
        }

        .submenu {
            list-style: none;
            background: #0d111d;
            padding: 5px 0;
        }

        .submenu li a {
            padding: 12px 25px 12px 55px;
            font-size: 14px;
        }

        .submenu li.active a {
            color: white;
            font-weight: bold;
        }

        /* ================= MAIN CONTENT ================= */
        .main-content {
            margin-left: 280px;
            flex: 1;
            padding: 40px;
        }

        /* ================= HEADER ================= */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
        }

        .header-left h1 {
            font-size: 32px;
            font-weight: 800;
            color: #1a202c;
            text-transform: uppercase;
        }

        .header-left p {
            margin-top: 5px;
            font-size: 15px;
            color: #718096;
        }

        .header-right {
            text-align: right;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 5px;
        }

        .status-badge {
            background: #e53e3e;
            color: white;
            padding: 6px 14px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 13px;
            letter-spacing: 0.5px;
        }

        .timestamp {
            font-size: 15px;
            font-weight: 600;
            color: #4a5568;
        }

        /* ================= STATISTIC CARDS ================= */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 35px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            border-bottom: 4px solid #e53e3e;
        }

        .stat-card h3 {
            font-size: 13px;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .stat-card h1 {
            font-size: 42px;
            color: #1a202c;
            font-weight: 700;
        }

        /* ================= DISTRIBUSI BOX ================= */
        .section-box {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 35px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }

        .section-box h2 {
            font-size: 18px;
            color: #1a202c;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .distribusi-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .dist-box {
            background: #f8fafc;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            border: 1px solid #e2e8f0;
        }

        .dist-box p {
            font-size: 14px;
            color: #64748b;
            font-weight: 500;
        }

        .dist-box h1 {
            font-size: 32px;
            color: #e53e3e;
            margin-top: 10px;
        }

        /* ================= DATATABLE TOOLBAR ================= */
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .table-header h2 {
            font-size: 18px;
            font-weight: 700;
            color: #1a202c;
        }

        .toolbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .search-box {
            position: relative;
        }

        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 14px;
        }

        .search-box input {
            padding: 10px 12px 10px 35px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            width: 220px;
            font-size: 14px;
            outline: none;
            background: #f8fafc;
        }

        .filter-select {
            padding: 10px 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            color: #4a5568;
            outline: none;
            background: #f8fafc;
            cursor: pointer;
        }

        .btn-add {
            background: #e53e3e;
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
            cursor: pointer;
        }

        .btn-add:hover {
            background: #c53030;
        }

        /* ================= DATA TABLE ================= */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        table th {
            padding: 14px 18px;
            background: #f8fafc;
            color: #64748b;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            border-bottom: 2px solid #edf2f7;
        }

        table td {
            padding: 16px 18px;
            border-bottom: 1px solid #edf2f7;
            font-size: 14px;
            color: #2d3748;
        }

        table tr:hover td {
            background: #f8fafc;
        }

        .emp-name {
            font-weight: 600;
            color: #1a202c;
        }

        .emp-sub {
            color: #718096;
            font-size: 12px;
            margin-top: 3px;
        }

        .badge-active {
            background: #c6f6d5;
            color: #22543d;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
        }

        .badge-inactive {
            background: #fed7d7;
            color: #742a2a;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
        }

        .action-links a {
            margin-right: 10px;
            font-size: 16px;
        }

        .action-edit { color: #3182ce; }
        .action-delete { color: #e53e3e; }

        .empty-row {
            text-align: center;
            padding: 40px !important;
            color: #a0aec0;
        }
        .empty-row i {
            font-size: 40px;
            margin-bottom: 10px;
            display: block;
        }

        /* ================= CSS MODAL ================= */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex; justify-content: center; align-items: center;
            z-index: 2000; visibility: hidden; opacity: 0;
            transition: all 0.3s ease;
        }
        .modal-overlay.open {
            visibility: visible; opacity: 1;
        }
        .modal-box {
            background: white; border-radius: 12px; width: 550px;
            max-width: 90%; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            transform: translateY(-30px); transition: transform 0.3s ease;
        }
        .modal-overlay.open .modal-box {
            transform: translateY(0);
        }
        .modal-header-popup {
            background: #d71920; color: white; padding: 18px 24px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .modal-header-popup h3 { font-size: 18px; font-weight: 700; }
        .modal-close-btn { background: none; border: none; color: white; font-size: 20px; cursor: pointer; }
        .modal-body-popup { padding: 24px; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: #4a5568; margin-bottom: 6px; }
        .form-control-popup {
            width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0;
            border-radius: 6px; font-size: 14px; outline: none; background: #f8fafc;
        }
        .form-control-popup:focus { border-color: #e53e3e; box-shadow: 0 0 0 3px rgba(229,62,62,0.1); }
        .modal-footer-popup { padding: 16px 24px; border-top: 1px solid #edf2f7; display: flex; justify-content: flex-end; gap: 10px; }
        .btn-cancel { background: #e2e8f0; color: #4a5568; border: none; padding: 10px 16px; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 14px; }
        .btn-save { background: #e53e3e; color: white; border: none; padding: 10px 16px; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 14px; }
        .btn-save:hover { background: #c53030; }

        /* RESPONSIVE */
        @media(max-width: 1200px) {
            .cards-grid, .distribusi-container { grid-template-columns: repeat(2, 1fr); }
        }
        @media(max-width: 768px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; padding: 20px; }
            .cards-grid, .distribusi-container { grid-template-columns: 1fr; }
            .table-header { flex-direction: column; align-items: flex-start; }
            .toolbar-right { width: 100%; flex-wrap: wrap; }
            .search-box input { width: 100%; }
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand">
            <img src="../../assets/img/logo_damkar.png" alt="Logo" width="140" height="80">
            <div class="brand-text">
                <h2>DAMKAR</h2>
                <h2>PADANG</h2>
            </div>
        </div>
        <ul class="menu-list">
            <li class="menu-item">
                <a href="../../index.php"><i class="fa-solid fa-gauge"></i> Dashboard</a>
            </li>
            <li class="menu-item">
                <a href="#"><i class="fa-solid fa-bullhorn"></i> Manajemen Kejadian</a>
            </li>
            <li class="menu-item">
                <a href="#"><i class="fa-solid fa-paste"></i> Operasional <i class="fa-solid fa-chevron-down" style="margin-left:auto; font-size:12px;"></i></a>
                <ul class="submenu">
                    <li><a href="penugasan_tim.php">Penugasan Tim</a></li>
                    <li><a href="monitoring_armada.php">Monitoring Armada</a></li>
                    <li><a href="status_penanganan.php">Status Penanganan</a></li>
                    <li><a href="riwayat_penugasan.php">Riwayat Penugasan</a></li>
                </ul>
            </li>
            <li class="menu-item active">
                <a href="#"><i class="fa-solid fa-users"></i> Personil <i class="fa-solid fa-chevron-up" style="margin-left:auto; font-size:12px;"></i></a>
                <ul class="submenu">
                    <li><a href="personil.php">Data Personil</a></li>
                    <li class="active"><a href="penempatan_pos.php">Penempatan Pos</a></li>
                    <li><a href="jadwal_piket.php">Jadwal Piket</a></li>
                    <li><a href="riwayat_tugas.php">Riwayat Tugas</a></li>
                </ul>
            </li>
            <li class="menu-item">
                <a href="#"><i class="fa-solid fa-truck-fire"></i> Armada</a>
            </li>
            <li class="menu-item">
                <a href="#"><i class="fa-solid fa-screwdriver-wrench"></i> Sarpras <i class="fa-solid fa-chevron-down" style="margin-left:auto; font-size:12px;"></i></a>
            </li>
            <li class="menu-item">
                <a href="#"><i class="fa-solid fa-file-invoice"></i> Laporan</a>
            </li>
        </ul>
    </div>

    <div class="main-content">

        <div class="header">
            <div class="header-left">
                <h1>Penempatan Pos Personil</h1>
                <p>Kelola Penempatan Personil Disetiap Pos Damkar</p>
            </div>
            <div class="header-right">
                <div class="status-badge">SIAGA 1</div>
                <div class="timestamp"><?= date('d M Y | H:i'); ?> WIB</div>
            </div>
        </div>

        <div class="cards-grid">
            <div class="stat-card">
                <h3>Total Penempatan</h3>
                <h1><?= $total_penempatan; ?></h1>
            </div>
            <div class="stat-card">
                <h3>Penempatan Aktif</h3>
                <h1><?= $penempatan_aktif; ?></h1>
            </div>
            <div class="stat-card">
                <h3>Jumlah Pos</h3>
                <h1><?= $jumlah_pos; ?></h1>
            </div>
            <div class="stat-card">
                <h3>Personil Siaga</h3>
                <h1><?= $penempatan_aktif; ?></h1>
            </div>
        </div>

        <div class="section-box">
            <h2>Distribusi Personil per Pos</h2>
            <div class="distribusi-container" id="distribusiContainer">
                <?php 
                if(mysqli_num_rows($query_distribusi) > 0) {
                    while($d = mysqli_fetch_array($query_distribusi)){ 
                ?>
                    <div class="dist-box">
                        <p><?= htmlspecialchars($d['pos_penempatan']); ?></p>
                        <h1><?= $d['jumlah']; ?></h1>
                    </div>
                <?php 
                    }
                } else {
                    echo "<div class='dist-box' style='grid-column: span 4; color: #a0aec0;'>Belum ada data distribusi pos.</div>";
                }
                ?>
            </div>
        </div>

        <div class="section-box">
            
            <div class="table-header">
                <h2>Daftar Penempatan Personil</h2>
                <div class="toolbar-right">
                    <div class="search-box">
                        <i class="fa fa-search"></i>
                        <input type="text" id="searchInput" placeholder="Cari nama / Pos...">
                    </div>
                    <select class="filter-select" id="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>
                    <button class="btn-add" onclick="openModal()">
                        <i class="fa fa-plus"></i> Tambah Penempatan
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table id="tabelPenempatan">
                    <thead>
                        <tr>
                            <th>Personil</th>
                            <th>Pos Penempatan</th>
                            <th>Tanggal Penempatan</th>
                            <th>Masa Tugas</th>
                            <th>Status</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(mysqli_num_rows($query) > 0) {
                            while($data = mysqli_fetch_array($query)){ 
                        ?>
                        <tr>
                            <td>
                                <div class="emp-name"><?= htmlspecialchars($data['nama_asli']); ?></div>
                                <div class="emp-sub">NIP : <?= htmlspecialchars($data['nip_asli']); ?></div> 
                            </td>
                            <td><?= htmlspecialchars($data['pos_penempatan']); ?></td>
                            <td><?= date('d M Y', strtotime($data['tanggal_penempatan'])); ?></td>
                            <td><?= htmlspecialchars($data['masa_penugasan']); ?></td>
                            <td>
                                <span class="<?= $data['status'] == 'Aktif' ? 'badge-active' : 'badge-inactive'; ?>">
                                    <?= htmlspecialchars($data['status']); ?>
                                </span>
                            </td>
                            <td class="action-links" style="text-align: center;">
                                <a href="edit.php?menu=penempatan_pos&id=<?= $data['id']; ?>" class="action-edit" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="hapus.php?menu=penempatan_pos&id=<?= $data['id']; ?>" class="action-delete" title="Hapus" onclick="return confirm('Yakin hapus data?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else { 
                        ?>
                        <tr>
                            <td colspan="6" class="empty-row">
                                <i class="fa-solid fa-users-slash"></i>
                                Belum Ada Data Penempatan Personil.<br>
                                <span style="font-size: 13px; color: #cbd5e1;">Klik tombol "Tambah Penempatan" untuk menambahkan data.</span>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <div class="modal-overlay" id="modalTambah">
        <div class="modal-box">
            <div class="modal-header-popup">
                <h3><i class="fa-solid fa-user-plus" style="margin-right: 8px;"></i>Tambah Penempatan</h3>
                <button class="modal-close-btn" onclick="closeModal()">&times;</button>
            </div>
            <form action="" method="POST">
                <div class="modal-body-popup">
                    <div class="form-group">
                        <label>Nama Personil</label>
                        <select name="nama_personil" class="form-control-popup" required>
                            <option value="">-- Pilih Anggota Damkar --</option>
                            <?php
                            $ambil_personil = mysqli_query($koneksi, "SELECT nama_personil, nip FROM tbl_daftar ORDER BY nama_personil ASC");
                            while ($personil = mysqli_fetch_assoc($ambil_personil)) {
                                echo "<option value='".htmlspecialchars($personil['nama_personil'])."'>".htmlspecialchars($personil['nama_personil'])." - (NIP: ".htmlspecialchars($personil['nip']).")</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Pos Penempatan</label>
                        <select name="pos_penempatan" class="form-control-popup" required>
                            <option value="">-- Pilih Pos --</option>
                            <option value="Pos Pusat">Pos Pusat</option>
                            <option value="Pos Kuranji">Pos Kuranji</option>
                            <option value="Pos Koto Tangah">Pos Koto Tangah</option>
                            <option value="Pos Bungus">Pos Bungus</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Penempatan</label>
                        <input type="date" name="tanggal_penempatan" class="form-control-popup" required>
                    </div>
                    <div class="form-group">
                        <label>Masa Penugasan</label>
                        <input type="text" name="masa_penugasan" class="form-control-popup" placeholder="Contoh: 6 Bulan / 1 Tahun" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control-popup" required>
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer-popup">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                    <button type="submit" name="simpan_penempatan" class="btn-save"><i class="fa fa-save" style="margin-right: 5px;"></i>Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('modalTambah').classList.add('open');
        }
        function closeModal() {
            document.getElementById('modalTambah').classList.remove('open');
        }

        // Live Filter Search dan Status
        const searchInput = document.getElementById('searchInput');
        const filterStatus = document.getElementById('filterStatus');

        function filterTable() {
            const keyword = searchInput.value.toLowerCase();
            const statusVal = filterStatus.value.toLowerCase();
            const rows = document.querySelectorAll('#tabelPenempatan tbody tr');

            rows.forEach(row => {
                if(row.querySelector('.empty-row')) return;

                const text = row.innerText.toLowerCase();
                const statusText = row.cells[4]?.innerText.toLowerCase() ?? '';

                if(text.includes(keyword) && (statusVal === '' || statusText.includes(statusVal))) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        searchInput.addEventListener('input', filterTable);
        filterStatus.addEventListener('change', filterTable);
    </script>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_penempatan'])) {
    
    // PROSES INSERT YANG SUDAH DISINKRONKAN DENGAN STRUKTUR TABEL PENEMPATAN_POS (NAMA_PERSONIL)
    $nama_personil      = mysqli_real_escape_string($koneksi, $_POST['nama_personil']);
    $pos_penempatan     = mysqli_real_escape_string($koneksi, $_POST['pos_penempatan']);
    $tanggal_penempatan = mysqli_real_escape_string($koneksi, $_POST['tanggal_penempatan']);
    $masa_penugasan     = mysqli_real_escape_string($koneksi, $_POST['masa_penugasan']);
    $status             = mysqli_real_escape_string($koneksi, $_POST['status']);

    // Memasukkan data ke kolom nama_personil (bukan nip) sesuai blueprint database asli
    $query_insert = "INSERT INTO penempatan_pos (nama_personil, pos_penempatan, tanggal_penempatan, masa_penugasan, status) 
                     VALUES ('$nama_personil', '$pos_penempatan', '$tanggal_penempatan', '$masa_penugasan', '$status')";

    if (mysqli_query($koneksi, $query_insert)) {
        echo "<script>
                alert('Data penempatan pos baru berhasil disimpan!');
                window.location.href = 'penempatan_pos.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Gagal menyimpan data penempatan: " . mysqli_real_escape_string($koneksi, mysqli_error($koneksi)) . "');
              </script>";
    }
}
?>