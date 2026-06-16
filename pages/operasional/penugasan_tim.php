<?php
// ... (Bagian atas file: cukup biarkan koneksi database saja)
include '../../config/koneksi.php';

if (!isset($conn) || !$conn) {
    die('Koneksi database tidak tersedia.');
}

$laporan_masuk = mysqli_query($conn, "SELECT * FROM laporan_kejadian WHERE status = 'masuk' AND verifikasi = 'valid'");
$query_spt = "SELECT spt.*, laporan_kejadian.nomor_laporan, laporan_kejadian.lokasi, laporan_kejadian.jenis_kejadian 
              FROM spt 
              JOIN laporan_kejadian ON spt.laporan_kejadian_id = laporan_kejadian.id 
              ORDER BY spt.waktu_keberangkatan DESC";
$tampil_spt = mysqli_query($conn, $query_spt);
?>

<?php
// PHP BACKEND SUBMIT FORM (Ganti seluruh bagian bawah ini dengan yang baru)
if (isset($_POST['kirim_tim'])) {
    $laporan_id = mysqli_real_escape_string($conn, $_POST['laporan_kejadian_id']);
    $nama_regu = mysqli_real_escape_string($conn, $_POST['nama_regu']);
    $today = date('Y-m-d');

    // 1. VALIDASI: Cek apakah regu sedang bertugas hari ini
    $cek_tugas = mysqli_query($conn, "SELECT id FROM spt WHERE nama_regu = '$nama_regu' AND DATE(waktu_keberangkatan) = '$today' AND status != 'selesai'");
    
    if (mysqli_num_rows($cek_tugas) > 0) {
        echo "<script>alert('Gagal! Regu $nama_regu sedang menjalankan tugas lain hari ini.'); window.location.href=window.location.pathname;</script>";
    } else {
        // 2. PROSES INSERT
        $tahun = date('Y');
        $query_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM spt");
        $data_count = mysqli_fetch_assoc($query_count);
        $next_id = $data_count['total'] + 1;
        $nomor_spt = "SPT-" . $tahun . "-" . str_pad($next_id, 3, "0", STR_PAD_LEFT);

        $query_insert = "INSERT INTO spt (nomor_spt, laporan_kejadian_id, nama_regu, status) 
                         VALUES ('$nomor_spt', '$laporan_id', '$nama_regu', 'berangkat')";

        if (mysqli_query($conn, $query_insert)) {
            mysqli_query($conn, "UPDATE laporan_kejadian SET status = 'proses' WHERE id = '$laporan_id'");
            echo "<script>alert('Tim Berhasil Ditugaskan!'); window.location.href=window.location.pathname;</script>";
        } else {
            echo "<script>alert('Gagal menugaskan tim: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penugasan Tim - DAMKAR Padang</title>

    <!-- Bootstrap & Icons Bawaan Aplikasi -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">

    <style>
        /* Mengembalikan flexbox utilitas tiruan Tailwind khusus untuk komponen layout utama agar tidak rusak */
        .flex-profile { display: flex; justify-content: flex-end; align-items: center; margin-bottom: 1.5rem; gap: 1rem; }
        .flex-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .info-regu-box { display: flex; justify-content: space-between; align-items: center; border: 1px solid #c21807; border-radius: 1rem; padding: 1rem; background-color: #fff; margin-bottom: 2rem; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); }
        .table-container { background-color: #fff; border-radius: 1rem; border: 1px solid #f1f1f1; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); overflow: hidden; }
        
        /* Modal Custom Style */
        .custom-modal-overlay {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0, 0, 0, 0.5); display: none;
            justify-content: center; align-items: center; z-index: 9999;
        }
        .custom-modal-overlay.show-modal { display: flex; }
        .custom-modal-box { background: white; border-radius: 1rem; width: 450px; max-width: 90%; padding: 1.5rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
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
                <a href="#menuOperasional" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center" aria-expanded="true">
                    <span><i class="bi bi-clipboard-check"></i> Operasional</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu show" id="menuOperasional">
                    <a href="penugasan_tim.php" class="active">Penugasan Tim</a>
                    <a href="monitoring_armada.php">Monitoring Armada</a>
                    <a href="status_penanganan.php">Status Penanganan</a>
                    <a href="riwayat_penugasan.php">Riwayat Penugasan</a>
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
                <a href="#menuSarpras" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-tools"></i> Sarpras</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuSarpras">
                    <a href="../Sarpras/sarpras.php">Data Sarpras</a>
                    <a href="../Sarpras/master_bidang.php">Master Bidang</a>
                    <a href="../Sarpras/master_kategori.php">Master Kategori</a>
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
    <div id="main-content">

        <!-- Header Judul Halaman -->
        <div class="flex-header mt-4">
            <div>
                <h1 class="h3 fw-bold text-dark m-0 flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-text text-danger"></i> PENUGASAN PERSONIL (SPT)
                </h1>
                <p class="text-muted small m-0 mt-1">Manajemen tugas dan pergerakan regu di lapangan</p>
            </div>
            <button onclick="bukaModal()" class="btn btn-danger fw-medium px-4 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2">
                <i class="bi bi-plus-circle"></i> Buat SPT Baru
            </button>
        </div>

        <!-- Kartu Informasi Regu Aktif -->
        <div class="info-regu-box mt-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-danger-subtle p-3 rounded-3 text-danger border border-danger-subtle fs-3">
                    <i class="bi bi-shield-check"></i>
                </div>
            </div>
            <div class="d-flex gap-4 text-end pe-3">
                <div>
                    <p class="text-muted fw-bold m-0" style="font-size: 10px; letter-spacing: 0.1em;">PERSONIL</p>
                    <p class="h5 fw-bold text-danger m-0">3 <span class="text-muted fw-normal fs-6">/ 15</span></p>
                    <p class="text-muted m-0" style="font-size: 9px;">Ready</p>
                </div>
                <div>
                    <p class="text-muted fw-bold m-0" style="font-size: 10px; letter-spacing: 0.1em;">ARMADA</p>
                    <p class="h5 fw-bold text-warning m-0">1 <span class="text-muted fw-normal fs-6">/ 4</span></p>
                    <p class="text-muted m-0" style="font-size: 9px;">Ready</p>
                </div>
            </div>
        </div>

        <div class="mb-3 mt-4">
            <h3 class="h6 fw-bold text-dark m-0 d-flex align-items-center gap-1">
                <i class="bi bi-geo-alt text-danger"></i> Penugasan Lapangan Saat Ini
            </h3>
        </div>

        <!-- Filter & Search data tabel -->
        <div class="d-flex gap-3 mb-3">
            <div class="position-relative flex-grow-1">
                <i class="bi bi-search position-absolute text-muted" style="left: 15px; top: 12px;"></i>
                <input type="text" id="filterInput" placeholder="Cari No. SPT atau Alamat Kejadian..." class="form-control ps-5 py-2 rounded-3 border-light shadow-sm">
            </div>
            <select id="statusFilter" class="form-select rounded-3 border-light shadow-sm w-auto" style="min-width: 180px;">
                <option value="">Semua Status</option>
                <option value="berangkat">berangkat</option>
                <option value="tiba">tiba</option>
                <option value="selesai">selesai</option>
            </select>
        </div>

        <!-- Tabel Render Data SPT -->
        <div class="table-container">
            <table class="table table-hover align-middle m-0" id="tabelSpt">
                <thead class="table-light text-secondary text-uppercase small" style="font-size: 11px;">
                    <tr>
                        <th class="p-3" style="width: 15%;">NO. SPT</th>
                        <th class="p-3">DETAIL KEJADIAN</th>
                        <th class="p-3">KEKUATAN TIM</th>
                        <th class="p-3">WAKTU</th>
                        <th class="p-3">STATUS</th>
                        <th class="p-3 text-center" style="width: 15%;">AKSI</th>
                    </tr>
                </thead>
                <tbody class="small text-dark">
                    <?php if (mysqli_num_rows($tampil_spt) == 0): ?>
                        <tr>
                            <td colspan="6" class="p-5 text-center text-muted">
                                <i class="bi bi-folder-x fs-1 d-block mb-2 text-black-50"></i>
                                Belum ada data penugasan lapangan saat ini.
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php while ($row = mysqli_fetch_assoc($tampil_spt)): ?>
                        <tr class="spt-row" data-status="<?= $row['status']; ?>">
                            <td class="p-3">
                                <span class="badge bg-danger-subtle text-danger font-bold border border-danger-subtle px-2 py-1.5">
                                    <?= $row['nomor_spt']; ?>
                                </span>
                            </td>
                            <td class="p-3">
                                <p class="fw-bold m-0 text-dark"><?= $row['lokasi']; ?></p>
                                <p class="text-muted m-0 mt-0.5" style="font-size: 11px;">
                                    <?= ucfirst($row['jenis_kejadian']); ?> <span class="text-black-50 mx-1">|</span> Ref: <?= $row['nomor_laporan']; ?>
                                </p>
                            </td>
                            <td class="p-3">
                                <div class="text-muted" style="font-size: 11px; line-height: 1.4;">
                                    <p class="m-0"><i class="bi bi-people me-1"></i> 4 Petugas (Danru: A. Supriadi)</p>
                                    <p class="m-0"><i class="bi bi-truck me-1"></i> 1 Armada Unit (🚒 <?= $row['nama_regu']; ?>)</p>
                                </div>
                            </td>
                            <td class="p-3 text-muted whitespace-nowrap">
                                <?= $row['waktu_keberangkatan']; ?>
                            </td>
                            <td class="p-3">
                                <span class="badge <?= $row['status'] == 'berangkat' ? 'bg-danger text-white' : 'bg-secondary text-white' ?> text-capitalize px-2 py-1">
                                    <?= $row['status']; ?>
                                </span>
                            </td>
                            <td class="p-3 text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="cetak_spt.php?id=<?= $row['id']; ?>" target="_blank" class="btn btn-sm btn-outline-secondary border-0" title="Cetak Surat Perintah">
                                        <i class="bi bi-printer fs-5"></i>
                                    </a>
                                    <a href="monitoring_armada.php?spt_id=<?= $row['id']; ?>" class="btn btn-sm btn-dark px-3 rounded-2 fw-medium" style="font-size: 11px; text-decoration: none;">
                                        Monitor
                                    </a>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- MODAL FORM POPUP (Vanilla JS native layout) -->
        <div class="custom-modal-overlay" id="modalSpt">
            <div class="custom-modal-box">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                    <h2 class="h5 fw-bold text-dark m-0">Buat Surat Perintah Tugas</h2>
                    <button type="button" class="btn-close" onclick="tutupModal()"></button>
                </div>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Pilih Laporan Kejadian</label>
                        <select name="laporan_kejadian_id" class="form-select rounded-3" required>
                            <option value="">-- Pilih Kejadian Aktif --</option>
                            <?php 
                            mysqli_data_seek($lPres = $laporan_masuk, 0); 
                            while ($lap = mysqli_fetch_assoc($lPres)): 
                            ?>
                                <option value="<?= $lap['id']; ?>">
                                    <?= $lap['nomor_laporan']; ?> - <?= $lap['lokasi']; ?> (<?= $lap['jenis_kejadian']; ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary">Tugaskan Regu</label>
                        <select name="nama_regu" class="form-select rounded-3" required>
                            <option value="">-- Pilih Regu Damkar --</option>
                            <option value="REGU ALPHA">REGU ALPHA</option>
                            <option value="REGU BRAVO">REGU BRAVO</option>
                            <option value="REGU CHARLIE">REGU CHARLIE</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <button type="button" onclick="tutupModal()" class="btn btn-sm btn-outline-secondary px-3 rounded-3">Batal</button>
                        <button type="submit" name="kirim_tim" class="btn btn-sm btn-danger px-3 rounded-3">Kirim Tim</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Script Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script Fungsional Pengganti AlpineJS -->
    <script>
        function bukaModal() {
            document.getElementById('modalSpt').classList.add('show-modal');
        }
        function tutupModal() {
            document.getElementById('modalSpt').classList.remove('show-modal');
        }
        
        // Penutupan modal ketika klik luar area kotak form
        window.onclick = function(event) {
            let modal = document.getElementById('modalSpt');
            if (event.target == modal) {
                tutupModal();
            }
        }

        // Live Search Realtime & Filter Kategori Status Berdasarkan Pilihan Select Box
        const filterInput = document.getElementById('filterInput');
        const statusFilter = document.getElementById('statusFilter');

        function filterTabel() {
            const keyword = filterInput.value.toLowerCase();
            const selectedStatus = statusFilter.value.toLowerCase();
            const rows = document.querySelectorAll('#tabelSpt tbody .spt-row');

            rows.forEach(row => {
                const textContent = row.innerText.toLowerCase();
                const rowStatus = row.getAttribute('data-status').toLowerCase();
                
                const matchesSearch = textContent.includes(keyword);
                const matchesStatus = selectedStatus === "" || rowStatus === selectedStatus;

                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        filterInput.addEventListener('input', filterTabel);
        statusFilter.addEventListener('change', filterTabel);
    </script>
</body>
</html>

<?php
// PHP BACKEND SUBMIT FORM
if (isset($_POST['kirim_tim'])) {
    $laporan_id = $_POST['laporan_kejadian_id'];
    $nama_regu = $_POST['nama_regu'];

    $tahun = date('Y');
    $query_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM spt");
    $data_count = mysqli_fetch_assoc($query_count);
    $next_id = $data_count['total'] + 1;
    $nomor_spt = "SPT-" . $tahun . "-" . str_pad($next_id, 3, "0", STR_PAD_LEFT);

    $query_insert = "INSERT INTO spt (nomor_spt, laporan_kejadian_id, nama_regu, status) 
                     VALUES ('$nomor_spt', '$laporan_id', '$nama_regu', 'berangkat')";

    if (mysqli_query($conn, $query_insert)) {
        mysqli_query($conn, "UPDATE laporan_kejadian SET status = 'proses' WHERE id = '$laporan_id'");
        echo "<script>alert('Tim Berhasil Ditugaskan!'); window.location.href=window.location.pathname;</script>";
    } else {
        echo "<script>alert('Gagal menugaskan tim: " . mysqli_error($conn) . "');</script>";
    }
}
?>