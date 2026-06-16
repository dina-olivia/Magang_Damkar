<?php require_once __DIR__ . '/../../config/koneksi.php';

// STATISTICS - Mengambil data agregasi dari tabel riwayat_tugas dan tbl_daftar
$total_personil = mysqli_num_rows(mysqli_query($conn, "SELECT DISTINCT nama_personil FROM tbl_daftar WHERE status = 'Aktif'"));

$stat_query = mysqli_query($conn, "
    SELECT 
        COUNT(id) AS total_penugasan,
        IFNULL(ROUND(AVG(rating), 1), 0.0) AS rata_rating,
        IFNULL(SUM(durasi_jam), 0) AS total_jam
    FROM riwayat_tugas
");
$stat = mysqli_fetch_assoc($stat_query);

// QUERY TOP 3 PERSONIL TERBAIK (Urutan Penugasan Terbanyak berdasarkan NAMA PERSONIL)
$query_top3 = mysqli_query($conn, "
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
$query_riwayat = mysqli_query($conn, "
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        /* ================= MAIN CONTENT HEADER ================= */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
        }

        .header-left h1 {
            font-size: 28px;
            font-weight: 800;
            color: #1e293b;
            text-transform: uppercase;
            margin: 0;
        }

        .header-left p {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #64748b;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-right .bg-danger {
            background-color: #dc3545 !important;
            padding: 8px 16px !important;
            font-size: 14px !important;
            border-radius: 8px !important;
        }

        .timestamp {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 15px;
            color: #4a5568;
            font-weight: 500;
        }

        .timestamp i {
            color: #dc3545;
            font-size: 18px;
        }

        /* ================= CARDS GRID ================= */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            margin-bottom: 35px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05), 0 1px 2px rgba(0,0,0,0.02);
            display: flex;
            flex-direction: column;
            gap: 12px;
            position: relative;
            border-bottom: 4px solid #ef4444;
        }

        .stat-card-info h3 {
            font-size: 13px;
            color: #64748b;
            font-weight: 600;
            margin: 0;
        }

        .stat-card-info h1 {
            font-size: 32px;
            color: #1e293b;
            font-weight: 700;
            margin: 0;
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
            margin: 0;
        }

        .top3-info p {
            font-size: 14px;
            color: #718096;
            margin: 2px 0 0 0;
        }

        /* ================= TABLE DESIGN ================= */
        .section-box {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .section-box h2 {
            font-size: 18px;
            color: #1e293b;
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
            padding: 14px 16px;
            background: #f8fafc;
            color: #64748b;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            border-bottom: 2px solid #edf2f7;
            text-align: left;
        }

        table td {
            padding: 16px 16px;
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
            margin: 0;
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
            color: #ef4444;
            border: 1px solid #ef4444;
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-lihat-detail:hover {
            background: #ef4444;
            color: #fff;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);
        }

        .empty-row {
            text-align: center;
            padding: 40px !important;
            color: #a0aec0;
        }
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

                <a href="#menuManajemenKejadian" data-bs-toggle="collapse"
                    class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-megaphone"></i> Manajemen Kejadian</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuManajemenKejadian">
                    <a href="../manajemen/input_laporan.php">Input Laporan</a>
                    <a href="../manajemen/monitoring_kejadian.php">Monitoring Kejadian</a>
                </div>

                <a href="#menuOperasional" data-bs-toggle="collapse"
                    class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-clipboard-check"></i> Operasional</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuOperasional">
                    <a href="../operasional/penugasan_tim.php">Penugasan Tim</a>
                    <a href="../operasional/monitoring_armada.php">Monitoring Armada</a>
                    <a href="../operasional/status_penanganan.php">Status Penanganan</a>
                </div>

                <a href="#menuPersonil" data-bs-toggle="collapse"
                    class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-people"></i> Personil</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu show" id="menuPersonil">
                    <a href="personil.php">Data Personil</a>
                    <a href="penempatan_pos.php">Penempatan Pos</a>
                    <a href="jadwal_piket.php">Jadwal Piket</a>
                    <a href="riwayat_tugas.php" class="active">Riwayat Tugas</a>
                </div>

                 <!-- Armada -->
                <a href="#menuArmada" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-truck"></i> Armada</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuArmada">
                    <a href="../armada/armada.php">Data Armada</a>
                </div>

                <a href="#menuSarpras" data-bs-toggle="collapse"
                    class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-tools"></i> Sarpras</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuSarpras">
                    <a href="../Sarpras/sarpras.php">Data Sarpras</a>
                    <a href="../Sarpras/master_bidang.php">Master Bidang</a>
                    <a href="../Sarpras/master_kategori.php">Master Kategori</a>
                </div>

                <a href="#menuLaporan" data-bs-toggle="collapse"
                    class="d-flex justify-content-between align-items-center">
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

    <div id="main-content">
        <div class="header">
            <div class="header-left">
                <h1>Riwayat Tugas Personil</h1>
                <p>Pantau performa dan riwayat penugasan personil</p>
            </div>
            <div class="header-right">
                <span class="badge bg-danger text-white fw-bold px-3 py-2 rounded-3" style="font-size: 14px; letter-spacing: 0.5px;">SIAGA 1</span>
                <div class="timestamp">
                    <i class="bi bi-clock-fill"></i>
                    <span id="liveClock">Memuat waktu...</span>
                </div>
            </div>
        </div>

        <div class="cards-grid">
            <div class="stat-card">
                <div class="stat-card-info"><h3>Total Personil</h3><h1><?= $total_personil; ?></h1></div>
            </div>
            <div class="stat-card">
                <div class="stat-card-info"><h3>Total Penugasan</h3><h1><?= $stat['total_penugasan']; ?></h1></div>
            </div>
            <div class="stat-card">
                <div class="stat-card-info"><h3>Rata-rata Rating</h3><h1><?= $stat['rata_rating']; ?> <span style="font-size:18px; color:#f59e0b;">★</span></h1></div>
            </div>
            <div class="stat-card">
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
                        <td colspan="7" class="empty-row"><i class="fa-solid fa-users-slash" style="font-size:30px; margin-bottom:10px; display:block;"></i>Belum Ada Data Riwayat Tugas.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fungsi Live Clock Realtime (Bahasa Indonesia)
        function updateLiveClock() {
            const clockElement = document.getElementById('liveClock');
            if (!clockElement) return;

            const sekarang = new Date();
            
            const namaHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            
            const hari = namaHari[sekarang.getDay()];
            const tanggal = String(sekarang.getDate()).padStart(2, '0');
            const bulan = namaBulan[sekarang.getMonth()];
            const tahun = sekarang.getFullYear();
            
            const jam = String(sekarang.getHours()).padStart(2, '0');
            const menit = String(sekarang.getMinutes()).padStart(2, '0');
            const detik = String(sekarang.getSeconds()).padStart(2, '0');
            
            clockElement.innerHTML = `${hari}, ${tanggal} ${bulan} ${tahun} | ${jam}:${menit}:${detik} WIB`;
        }

        // Jalankan pertama kali dan perbarui setiap 1 detik
        updateLiveClock();
        setInterval(updateLiveClock, 1000);

        // Logika Realtime Search Table
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