<?php require_once __DIR__ . '/../../config/koneksi.php';

// STATISTICS - Mengambil data agregasi dari tabel riwayat_tugas dan tbl_daftar
$total_personil = mysqli_num_rows(mysqli_query($koneksi, "SELECT DISTINCT nama_personil FROM tbl_daftar WHERE status = 'Aktif'"));

$stat_query = mysqli_query($koneksi, "
    SELECT 
        COUNT(id) AS total_penugasan,
        IFNULL(ROUND(AVG(rating), 1), 0.0) AS rata_rating,
        IFNULL(SUM(durasi_jam), 0) AS total_jam
    FROM riwayat_tugas
");
$stat = mysqli_fetch_assoc($stat_query);

// QUERY TOP 3 PERSONIL TERBAIK (Urutan Penugasan Terbanyak berdasarkan NAMA PERSONIL)
$query_top3 = mysqli_query($koneksi, "
    SELECT 
        p.nama_personil,
        COUNT(r.id) AS total_penugasan
    FROM tbl_daftar p
    INNER JOIN riwayat_tugas r ON p.nama_personil = r.nama_personil
    GROUP BY p.nama_personil
    ORDER BY total_penugasan DESC
    LIMIT 3
");

// QUERY UTAMA: DAFTAR RIWAYAT TUGAS (Menggunakan LEFT JOIN berdasarkan nama_personil)
$query_riwayat = mysqli_query($koneksi, "
    SELECT 
        MAX(p.id) AS id,
        p.nama_personil,
        COUNT(r.id) AS total_penugasan,
        IFNULL(MAX(r.tanggal_tugas), '-') AS tugas_terakhir,
        IFNULL(SUM(r.durasi_jam), 0) AS total_jam,
        IFNULL(SUM(r.kejadian_ditangani), 0) AS total_kejadian,
        IFNULL(ROUND(AVG(r.rating), 1), 0.0) AS rating_rata_rata
    FROM tbl_daftar p
    LEFT JOIN riwayat_tugas r ON p.nama_personil = r.nama_personil
    GROUP BY p.nama_personil
    ORDER BY total_penugasan DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Tugas Personil - DAMKAR PADANG</title>
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
            height: 100vh;
            color: #a3afc7;
            position: fixed;
            left: 0;
            top: 0;
            /* PERBAIKAN 1: Supaya sidebar bisa discroll secara vertikal jika menu terlalu panjang */
            overflow-y: auto; 
        }

        /* Opsional: Mempercantik tampilan scrollbar di sidebar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: #232d45;
            border-radius: 3px;
        }

        .brand {
            background: #d71920;
            padding: 25px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            color: white;
            position: sticky;
            top: 0;
            z-index: 10;
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

        .menu-list {
            list-style: none;
            margin-top: 15px;
        }

        .menu-item a {
            display: flex;
            align-items: center;
            justify-content: space-between; /* Modifikasi untuk panah submenu */
            padding: 15px 25px;
            color: #a3afc7;
            text-decoration: none;
            font-size: 15px;
            transition: all 0.3s;
            cursor: pointer;
        }

        .menu-link-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .menu-item a:hover, .menu-item.active > a {
            background: #171e30;
            color: white;
        }

        .menu-item.active {
            border-left: 4px solid #d71920;
        }

        /* Ikon panah indikator submenu */
        .menu-item .arrow-icon {
            font-size: 12px;
            transition: transform 0.3s;
        }

        /* Rotasi panah saat menu terbuka */
        .menu-item.open .arrow-icon {
            transform: rotate(90deg);
        }

        /* PERBAIKAN 2: Sembunyikan submenu secara default */
        .submenu {
            list-style: none;
            background: #0d111d;
            padding: 5px 0;
            display: none; 
        }

        /* Tampilkan jika parent menu di-klik (memiliki class .open) */
        .menu-item.open .submenu {
            display: block;
        }

        .submenu li a {
            padding: 12px 25px 12px 55px;
            font-size: 14px;
            display: block;
            color: #a3afc7;
            text-decoration: none;
            justify-content: flex-start;
        }

        .submenu li.active a {
            color: white;
            font-weight: bold;
            background: #141a29;
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

        /* ================= CARDS GRID ================= */
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
            display: flex;
            align-items: center;
            gap: 20px;
            border-bottom: 4px solid #e53e3e;
        }

        .stat-card .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .bg-blue { background: #eff6ff; color: #3b82f6; }
        .bg-green { background: #f0fdf4; color: #22c55e; }
        .bg-yellow { background: #fefce8; color: #eab308; }
        .bg-purple { background: #faf5ff; color: #a855f7; }

        .stat-card-info h3 {
            font-size: 13px;
            color: #718096;
            font-weight: 600;
        }

        .stat-card-info h1 {
            font-size: 28px;
            color: #1a202c;
            font-weight: 700;
            margin-top: 2px;
        }

        /* ================= TOP 3 CONTAINER ================= */
        .top3-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 35px;
        }

        .top3-card {
            background: #fffdf5;
            border: 1px solid #fef3c7;
            padding: 20px;
            border-radius: 12px;
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .top3-card .medal-badge {
            position: absolute;
            left: 20px;
            top: 20px;
            font-size: 20px;
        }

        .top3-card .rank-number {
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 14px;
            color: #92400e;
            font-weight: bold;
        }

        .top3-info {
            padding-left: 35px;
        }

        .top3-info h2 {
            font-size: 16px;
            color: #1a202c;
            font-weight: 700;
        }

        .top3-info p {
            font-size: 14px;
            color: #718096;
            margin-top: 2px;
        }

        /* ================= TABLE DESIGN ================= */
        .section-box {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }

        .section-box h2 {
            font-size: 18px;
            color: #1a202c;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
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
            width: 300px;
            font-size: 14px;
            outline: none;
            background: #f8fafc;
        }

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
            vertical-align: middle;
        }

        .personil-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar-initial {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #fee2e2;
            color: #ef4444;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
        }

        .personil-meta h4 {
            font-size: 14px;
            font-weight: 700;
            color: #1a202c;
        }

        .text-bold { font-weight: 700; color: #1a202c; }
        .text-blue { color: #2563eb; font-weight: 700; }
        
        .badge-kejadian {
            background: #f0fdf4;
            color: #16a34a;
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 600;
            display: inline-block;
        }

        .rating-box {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .rating-number { font-weight: 700; color: #d97706; }
        .stars { color: #f59e0b; font-size: 12px; }

        .btn-lihat-detail {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: #fff;
            color: #e53e3e;
            border: 1px solid #e53e3e;
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-lihat-detail:hover {
            background: #e53e3e;
            color: #fff;
            box-shadow: 0 2px 4px rgba(229, 62, 62, 0.2);
        }

        .empty-row {
            text-align: center;
            padding: 40px !important;
            color: #a0aec0;
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
                <a href="../../index.php">
                    <span class="menu-link-content"><i class="fa-solid fa-gauge"></i> Dashboard</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="#">
                    <span class="menu-link-content"><i class="fa-solid fa-fire-extinguisher"></i> Manajemen Kejadian</span>
                </a>
            </li>
            <li class="menu-item">
                <a class="has-submenu">
                    <span class="menu-link-content"><i class="fa-solid fa-route"></i> Operasional</span>
                    <i class="fa-solid fa-chevron-right arrow-icon"></i>
                </a>
                <ul class="submenu">
                    <li><a href="penugasan_tim.php">Penugasan Tim</a></li>
                    <li><a href="monitoring_armada.php">Monitoring Armada</a></li>
                    <li><a href="status_penanganan.php">Status Penanganan</a></li>
                    <li><a href="riwayat_penugasan.php">Riwayat Penugasan</a></li>
                </ul>
            </li>
            <li class="menu-item open active">
                <a class="has-submenu">
                    <span class="menu-link-content"><i class="fa-solid fa-users"></i> Personil</span>
                    <i class="fa-solid fa-chevron-right arrow-icon"></i>
                </a>
                <ul class="submenu">
                    <li><a href="personil.php">Data Personil</a></li>
                    <li><a href="penempatan_pos.php">Penempatan Pos</a></li>
                    <li><a href="jadwal_piket.php">Jadwal Piket</a></li>
                    <li class="active"><a href="riwayat_tugas.php">Riwayat Tugas</a></li>
                </ul>
            </li>
            <li class="menu-item">
                <a href="#">
                    <span class="menu-link-content"><i class="fa-solid fa-truck-fire"></i> Armada</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="#">
                    <span class="menu-link-content"><i class="fa-solid fa-file-invoice"></i> Laporan</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <div class="header-left">
                <h1>Riwayat Tugas Personil</h1>
                <p>Pantau performa dan riwayat penugasan personil</p>
            </div>
            <div class="header-right">
                <div class="status-badge">SIAGA 1</div>
                <div class="timestamp"><?= date('d M Y | H:i'); ?> WIB</div>
            </div>
        </div>

        <div class="cards-grid">
            <div class="stat-card">
                <div class="icon-box bg-blue"><i class="fa-solid fa-file-invoice"></i></div>
                <div class="stat-card-info"><h3>Total Personil</h3><h1><?= $total_personil; ?></h1></div>
            </div>
            <div class="stat-card">
                <div class="icon-box bg-green"><i class="fa-solid fa-award"></i></div>
                <div class="stat-card-info"><h3>Total Penugasan</h3><h1><?= $stat['total_penugasan']; ?></h1></div>
            </div>
            <div class="stat-card">
                <div class="icon-box bg-yellow"><i class="fa-solid fa-star"></i></div>
                <div class="stat-card-info"><h3>Rata-rata Rating</h3><h1><?= $stat['rata_rating']; ?> <span style="font-size:18px; color:#f59e0b;">★</span></h1></div>
            </div>
            <div class="stat-card">
                <div class="icon-box bg-purple"><i class="fa-solid fa-clock"></i></div>
                <div class="stat-card-info"><h3>Jam Operasional</h3><h1><?= $stat['total_jam']; ?> jam</h1></div>
            </div>
        </div>

        <h2 style="font-size: 18px; margin-bottom: 15px; color:#1a202c;"><i class="fa-solid fa-trophy" style="color:#d97706; margin-right:8px;"></i>Top 3 Personil Terbaik</h2>
        <div class="top3-container">
            <?php 
            $rank = 1;
            while($top = mysqli_fetch_assoc($query_top3)) {
                $medals = [1 => '🥇', 2 => '🥈', 3 => '🥉'];
                echo '
                <div class="top3-card">
                    <span class="medal-badge">'.$medals[$rank].'</span>
                    <span class="rank-number">#'.$rank.'</span>
                    <div class="top3-info">
                        <h2>'.htmlspecialchars($top['nama_personil']).'</h2>
                        <p>'.$top['total_penugasan'].' penugasan</p>
                    </div>
                </div>';
                $rank++;
            }
            if ($rank === 1) {
                echo "<div class='top3-card' style='grid-column: span 3; text-align:center; color:#a0aec0;'>Belum ada data penugasan.</div>";
            }
            ?>
        </div>

        <div class="section-box" style="margin-top: 25px;">
            <div class="table-header">
                <h2>Daftar Riwayat Tugas</h2>
                <div class="search-box">
                    <i class="fa fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari personil...">
                </div>
            </div>

            <table id="tabelRiwayat">
                <thead>
                    <tr>
                        <th>Personil</th>
                        <th>Total Penugasan</th>
                        <th>Tugas Terakhir</th>
                        <th>Total Jam</th>
                        <th>Kejadian Ditangani</th>
                        <th>Rating</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(mysqli_num_rows($query_riwayat) > 0) {
                        while($data = mysqli_fetch_array($query_riwayat)){ 
                            $initial = strtoupper(substr($data['nama_personil'], 0, 1));
                            $tgl_tugas = ($data['tugas_terakhir'] != '-') ? date('d M Y', strtotime($data['tugas_terakhir'])) : '-';
                    ?>
                    <tr>
                        <td>
                            <div class="personil-cell">
                                <div class="avatar-initial"><?= $initial; ?></div>
                                <div class="personil-meta">
                                    <h4><?= htmlspecialchars($data['nama_personil']); ?></h4>
                                </div>
                            </div>
                        </td>
                        <td class="text-blue"><?= $data['total_penugasan']; ?> kali</td>
                        <td><?= $tgl_tugas; ?></td>
                        <td class="text-bold"><?= $data['total_jam']; ?> jam</td>
                        <td>
                            <span class="badge-kejadian"><?= $data['total_kejadian']; ?></span>
                        </td>
                        <td>
                            <div class="rating-box">
                                <span class="rating-number"><?= $data['rating_rata_rata']; ?></span>
                                <span class="stars">
                                    <?php 
                                    $floor_rating = floor($data['rating_rata_rata']);
                                    for($i=1; $i<=5; $i++) {
                                        if($i <= $floor_rating) {
                                            echo '★';
                                        } else {
                                            echo '☆';
                                        }
                                    }
                                    ?>
                                </span>
                            </div>
                        </td>
                        <td>
                            <a href="detail.php?menu=riwayat_tugas&id=<?= $data['id'] ?>" class="btn-lihat-detail" title="Lihat Detail">
                                <i class="fa-solid fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else { 
                    ?>
                    <tr>
                        <td colspan="7" class="empty-row"><i class="fa-solid fa-users-slash" style="font-size:30px; margin-bottom:10px; display:block;"></i>Belum Anda Data Riwayat Tugas.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // 1. Logika untuk Buka-Tutup Submenu Sidebar
        const submenus = document.querySelectorAll('.has-submenu');
        
        submenus.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const parentLi = this.parentElement;
                
                // Menutup menu lain jika ingin mode accordion (opsional)
                // document.querySelectorAll('.menu-item').forEach(li => {
                //    if(li !== parentLi) li.classList.remove('open');
                // });

                // Toggle class open pada menu yang diklik
                parentLi.classList.toggle('open');
            });
        });

        // 2. Logika Realtime Search Table
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('input', function() {
            const keyword = this.value.toLowerCase();
            const rows = document.querySelectorAll('#tabelRiwayat tbody tr');
            rows.forEach(row => {
                if(row.querySelector('.empty-row')) return;
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(keyword) ? '' : 'none';
            });
        });
    </script>
</body>
</html>