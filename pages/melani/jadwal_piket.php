<?php require_once __DIR__ . '/../../config/koneksi.php';

// STATISTICS
$total_jadwal   = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM jadwal_piket"));
$jumlah_shift   = mysqli_num_rows(mysqli_query($koneksi, "SELECT DISTINCT shift FROM jadwal_piket WHERE shift != ''"));
$total_personil = mysqli_num_rows(mysqli_query($koneksi, "SELECT DISTINCT nama_personil FROM jadwal_piket"));

// DISTRIBUSI BOX (Menghitung total personil yang terdaftar di masing-masing shift secara keseluruhan)
$query_distribusi = mysqli_query($koneksi, "
    SELECT 
        shift,
        COUNT(id) as jumlah
    FROM jadwal_piket
    WHERE shift IN ('Pagi', 'Siang', 'Malam')
    GROUP BY shift
");

// QUERY UTAMA: Menambahkan jp.jam_kerja ke dalam GROUP BY agar lolos dari rule ONLY_FULL_GROUP_BY
$query = mysqli_query($koneksi, "
    SELECT 
        jp.tanggal, 
        jp.shift, 
        jp.jam_kerja,
        GROUP_CONCAT(CONCAT('<div class=\"personil-row\"><i class=\"fa-solid fa-user-shield\" style=\"color:#e53e3e; margin-right:8px;\"></i>', tbl_daftar.nama_personil, ' <span style=\"color:#718096; font-size:12px;\">(NIP: ', tbl_daftar.nip, ')</span></div>') SEPARATOR '') AS daftar_personil,
        COUNT(jp.id) AS jumlah_piket
    FROM jadwal_piket jp
    INNER JOIN tbl_daftar ON jp.nama_personil = tbl_daftar.nama_personil 
    GROUP BY jp.tanggal, jp.shift, jp.jam_kerja
    ORDER BY jp.tanggal DESC, jp.shift ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Piket - DAMKAR PADANG</title>
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
            width: 50px;
            height: auto;
        }

        .brand-text h2 {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: 0.5px;
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
            display: block;
            color: #a3afc7;
            text-decoration: none;
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
        }

        .status-badge {
            background: #e53e3e;
            color: white;
            padding: 6px 14px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 13px;
            letter-spacing: 0.5px;
            display: inline-block;
            margin-bottom: 5px;
        }

        .timestamp {
            font-size: 15px;
            font-weight: 600;
            color: #4a5568;
        }

        /* ================= CARDS ================= */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
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
        }

        .dist-box h1 {
            font-size: 32px;
            color: #e53e3e;
            margin-top: 10px;
        }

        /* ================= TOOLBAR & TABLE ================= */
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
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
        }

        .search-box input {
            padding: 10px 12px 10px 35px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            width: 250px;
            font-size: 14px;
            outline: none;
            background: #f8fafc;
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
            cursor: pointer;
        }

        .btn-add:hover { background: #c53030; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            padding: 14px 18px;
            background: #f8fafc;
            color: #64748b;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            border-bottom: 2px solid #edf2f7;
            text-align: left;
        }

        table td {
            padding: 16px 18px;
            border-bottom: 1px solid #edf2f7;
            font-size: 14px;
            color: #2d3748;
            vertical-align: top;
        }

        /* Desain list personil di dalam baris */
        .personil-container {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .personil-row {
            padding: 6px 10px;
            background: #f8fafc;
            border-radius: 6px;
            border-left: 3px solid #e53e3e;
            font-weight: 600;
        }

        .badge-jumlah {
            background: #fee2e2;
            color: #991b1b;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            display: inline-block;
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

        /* ================= MODAL POPOUP ================= */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex; justify-content: center; align-items: center;
            z-index: 2000; visibility: hidden; opacity: 0;
            transition: all 0.3s ease;
        }
        .modal-overlay.open { visibility: visible; opacity: 1; }
        .modal-box {
            background: white; border-radius: 12px; width: 500px; overflow: hidden;
            transform: translateY(-30px); transition: transform 0.3s ease;
        }
        .modal-overlay.open .modal-box { transform: translateY(0); }
        .modal-header-popup { background: #d71920; color: white; padding: 18px 24px; display: flex; justify-content: space-between; align-items: center; }
        .modal-close-btn { background: none; border: none; color: white; font-size: 20px; cursor: pointer; }
        .modal-body-popup { padding: 24px; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: #4a5568; margin-bottom: 6px; }
        .form-control-popup { width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px; outline: none; }
        .modal-footer-popup { padding: 16px 24px; border-top: 1px solid #edf2f7; display: flex; justify-content: flex-end; gap: 10px; }
        .btn-cancel { background: #e2e8f0; color: #4a5568; border: none; padding: 10px 16px; border-radius: 6px; font-weight: 600; cursor: pointer; }
        .btn-save { background: #e53e3e; color: white; border: none; padding: 10px 16px; border-radius: 6px; font-weight: 600; cursor: pointer; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand">
            <img src="../../assets/logo_damkar.png" alt="Logo" width="50">
            <div class="brand-text">
                <h2>DAMKAR</h2>
                <h2>PADANG</h2>
            </div>
        </div>
        <ul class="menu-list">
            <li class="menu-item"><a href="../../index.php"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
            <li class="menu-item"><a href="#"><i class="fa-solid fa-bullhorn"></i> Manajemen Kejadian</a></li>
            <li class="menu-item"><a href="#"><i class="fa-solid fa-paste"></i> Operasional</a></li>
            <li class="menu-item active">
                <a href="#"><i class="fa-solid fa-users"></i> Personil</a>
                <ul class="submenu">
                    <li><a href="personil.php">Data Personil</a></li>
                    <li><a href="penempatan_pos.php">Penempatan Pos</a></li>
                    <li class="active"><a href="jadwal_piket.php">Jadwal Piket</a></li>
                    <li><a href="riwayat_tugas.php">Riwayat Tugas</a></li>
                </ul>
            </li>
            <li class="menu-item"><a href="#"><i class="fa-solid fa-truck-fire"></i> Armada</a></li>
            <li class="menu-item"><a href="#"><i class="fa-solid fa-file-invoice"></i> Laporan</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <div class="header-left">
                <h1>Jadwal Piket Personil</h1>
                <p>Kelola Pengaturan Shift Dan Tugas Piket Anggota Damkar</p>
            </div>
            <div class="header-right">
                <div class="status-badge">SIAGA 1</div>
                <div class="timestamp"><?= date('d M Y | H:i'); ?> WIB</div>
            </div>
        </div>

        <div class="cards-grid">
            <div class="stat-card"><h3>Total Data Piket</h3><h1><?= $total_jadwal; ?></h1></div>
            <div class="stat-card"><h3>Jumlah Shift</h3><h1><?= $jumlah_shift; ?></h1></div>
            <div class="stat-card"><h3>Personil Terlibat</h3><h1><?= $total_personil; ?></h1></div>
        </div>

        <div class="section-box">
            <h2>Distribusi Total Personil per Shift</h2>
            <div class="distribusi-container">
                <?php 
                if(mysqli_num_rows($query_distribusi) > 0) {
                    while($d = mysqli_fetch_array($query_distribusi)){ 
                ?>
                    <div class="dist-box">
                        <p><?= htmlspecialchars($d['shift']); ?></p>
                        <h1><?= $d['jumlah']; ?></h1>
                    </div>
                <?php 
                    }
                } else {
                    echo "<div class='dist-box' style='grid-column: span 4; color: #a0aec0;'>Belum ada data shift piket.</div>";
                }
                ?>
            </div>
        </div>

        <div class="section-box">
            <div class="table-header">
                <h2>Daftar Jadwal Piket Anggota</h2>
                <div class="toolbar-right">
                    <div class="search-box">
                        <i class="fa fa-search"></i>
                        <input type="text" id="searchInput" placeholder="Cari tanggal atau shift...">
                    </div>
                    <button class="btn-add" onclick="openModal()">
                        <i class="fa fa-plus"></i> Tambah Anggota Piket
                    </button>
                </div>
            </div>

            <table id="tabelJadwal">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Shift</th>
                        <th>Jam Kerja</th>
                        <th style="width: 40%;">Personil Piket</th>
                        <th style="text-align: center;">Jumlah</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(mysqli_num_rows($query) > 0) {
                        while($data = mysqli_fetch_array($query)){ 
                    ?>
                    <tr>
                        <td><?= date('d M Y', strtotime($data['tanggal'])); ?></td>
                        <td><strong style="color:#d71920;"><?= htmlspecialchars($data['shift']); ?></strong></td>
                        <td><?= htmlspecialchars($data['jam_kerja']); ?></td>
                        <td>
                            <div class="personil-container">
                                <?= $data['daftar_personil']; ?>
                            </div>
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            <span class="badge-jumlah"><?= $data['jumlah_piket']; ?> Orang</span>
                        </td>
                        <td style="text-align: center; vertical-align: middle;" class="action-links">
                            <a href="edit.php?menu=jadwal_piket&tanggal=<?= $data['tanggal']; ?>&shift=<?= $data['shift']; ?>" class="action-edit" title="Edit Grup"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="hapus.php?menu=jadwal_piket&tanggal=<?= $data['tanggal']; ?>&shift=<?= $data['shift']; ?>" class="action-delete" title="Hapus Grup" onclick="return confirm('Hapus seluruh jadwal pada tanggal dan shift ini?')"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else { 
                    ?>
                    <tr>
                        <td colspan="6" class="empty-row"><i class="fa-solid fa-users-slash" style="font-size:30px; margin-bottom:10px; display:block;"></i>Belum Ada Data Jadwal Piket Personil.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal-overlay" id="modalTambah">
        <div class="modal-box">
            <div class="modal-header-popup">
                <h3><i class="fa-solid fa-calendar-plus" style="margin-right: 8px;"></i>Tambah Anggota Piket</h3>
                <button class="modal-close-btn" onclick="closeModal()">&times;</button>
            </div>
            <form action="" method="POST">
                <div class="modal-body-popup">
                    <div class="form-group">
                        <label>Tanggal Piket</label>
                        <input type="date" name="tanggal" class="form-control-popup" required>
                    </div>
                    <div class="form-group">
                        <label>Shift</label>
                        <select name="shift" class="form-control-popup" required>
                            <option value="">-- Pilih Shift --</option>
                            <option value="Pagi">Pagi</option>
                            <option value="Siang">Siang</option>
                            <option value="Malam">Malam</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jam Kerja</label>
                        <input type="text" name="jam_kerja" class="form-control-popup" value="08:00 - 08:00" required>
                    </div>
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
                </div>
                <div class="modal-footer-popup">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                    <button type="submit" name="simpan_jadwal" class="btn-save"><i class="fa fa-save" style="margin-right: 5px;"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() { document.getElementById('modalTambah').classList.add('open'); }
        function closeModal() { document.getElementById('modalTambah').classList.remove('open'); }

        // Live Search Realtime
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('input', function() {
            const keyword = this.value.toLowerCase();
            const rows = document.querySelectorAll('#tabelJadwal tbody tr');
            rows.forEach(row => {
                if(row.querySelector('.empty-row')) return;
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(keyword) ? '' : 'none';
            });
        });
    </script>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_jadwal'])) {
    $tanggal        = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $shift          = mysqli_real_escape_string($koneksi, $_POST['shift']);
    $jam_kerja      = mysqli_real_escape_string($koneksi, $_POST['jam_kerja']);
    $nama_personil  = mysqli_real_escape_string($koneksi, $_POST['nama_personil']);

    // Validasi pencegahan agar personil yang sama tidak diinput 2 kali di tanggal & shift yang sama
    $cek_ganda = mysqli_query($koneksi, "SELECT * FROM jadwal_piket WHERE tanggal='$tanggal' AND shift='$shift' AND nama_personil='$nama_personil'");
    if(mysqli_num_rows($cek_ganda) > 0) {
        echo "<script>alert('Personil tersebut sudah terdaftar di shift dan tanggal ini!');</script>";
    } else {
        $query_insert = "INSERT INTO jadwal_piket (tanggal, shift, jam_kerja, nama_personil) VALUES ('$tanggal', '$shift', '$jam_kerja', '$nama_personil')";
        if (mysqli_query($koneksi, $query_insert)) {
            echo "<script>alert('Anggota berhasil ditambahkan ke jadwal piket!'); window.location.href = 'jadwal_piket.php';</script>";
            exit;
        }
    }
}
?>