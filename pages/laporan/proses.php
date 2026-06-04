<?php
// Set penanda halaman aktif untuk sidebar jika diperlukan
$page = 'laporan_kejadian'; 

include '../../config/koneksi.php';

// Pastikan ada parameter ID yang dikirim melalui URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: laporan_kejadian.php?error=ID laporan tidak valid.");
    exit;
}

$id_laporan = intval($_GET['id']);

// Konfigurasi dinamis untuk base_url agar pemanggilan file config/assets tidak pecah
$path = $_SERVER['PHP_SELF'];
$root_folder = '/Magang_DAMKAR';
$clean_path = str_replace($root_folder, '', $path);
$levels = substr_count($clean_path, '/');
$base_url = ($levels > 1) ? str_repeat('../', $levels - 1) : '';

// 1. Ambil data laporan untuk ditampilkan sebagai informasi di form penugasan
$query_laporan = mysqli_query($conn, "SELECT * FROM laporan_kejadian WHERE id = $id_laporan");
$data_laporan = mysqli_fetch_assoc($query_laporan);

if (!$data_laporan) {
    header("Location: laporan_kejadian.php?error=Laporan tidak ditemukan.");
    exit;
}

// Mengambil data personil dari database "tbl_daftar" untuk opsi Komandan
$query_tbl_daftar = mysqli_query($conn, "SELECT id, nama_personil, jabatan FROM tbl_daftar WHERE status = 'Aktif' ORDER BY nama_personil ASC");

// 2. Proses ketika Form Penugasan dikirim (Submit)
if (isset($_POST['ganti_status_proses'])) {
    $komandan_id = intval($_POST['komandan_id']);

    // Ubah format 'datetime-local' menjadi format standar MySQL (YYYY-MM-DD HH:MM:SS)
    $waktu_input = $_POST['waktu_berangkat'];
    $waktu_berangkat = date('Y-m-d H:i:s', strtotime($waktu_input));

    // Mulai Database Transaction agar aman dan sinkron
    mysqli_begin_transaction($conn);

    try {
        // A. Update status di tabel laporan_kejadian menjadi 'proses'
        $stmt_update = mysqli_prepare($conn, "UPDATE laporan_kejadian SET status = 'proses' WHERE id = ?");
        mysqli_stmt_bind_param($stmt_update, "i", $id_laporan);
        mysqli_stmt_execute($stmt_update);

        // B. Insert data penugasan baru ke tabel penugasan sesuai dengan struktur kolom Anda
        $status_tugas = 'dalam penanganan';
        
        // waktu_tiba dan waktu_selesai di-set NULL dulu karena baru berangkat
        $query_insert = "INSERT INTO penugasan (laporan_id, waktu_berangkat, waktu_tiba, waktu_selesai, komandan_id, status) VALUES (?, ?, NULL, NULL, ?, ?)";
        $stmt_insert = mysqli_prepare($conn, $query_insert);

        // "isis" -> laporan_id (i), waktu_berangkat (s), komandan_id (i), status (s)
        mysqli_stmt_bind_param($stmt_insert, "isis", $id_laporan, $waktu_berangkat, $komandan_id, $status_tugas);
        mysqli_stmt_execute($stmt_insert);

        // Jika semua query sukses, terapkan ke database
        mysqli_commit($conn);

        header("Location: laporan_kejadian.php?success=1");
        exit;

    } catch (Exception $e) {
        // Jika ada kegagalan, batalkan semua instrumen query
        mysqli_rollback($conn);
        header("Location: laporan_kejadian.php?error=Gagal memproses laporan: " . urlencode($e->getMessage()));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi & Penugasan Tim - E-DAMKAR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= $base_url ?>assets/css/style.css">
    <style>
        body { background-color: #f8f9fa; }
        .card-detail { border-left: 4px solid #dc3545; }
        
        /* Layouting agar konten bergeser ke kanan jika ada sidebar */
        #main-content {
            margin-left: 260px; /* Sesuaikan dengan lebar sidebar asli Anda */
            transition: all 0.3s;
        }
        @media (max-width: 768px) {
            #main-content { margin-left: 0; }
        }
    </style>
</head>

<body>

    <?php include $base_url . 'config/sidebar.php'; ?>

    <div id="main-content" class="p-4">
        <div class="container-fluid">
            
            <div class="d-flex align-items-center mb-4">
                <a href="laporan_kejadian.php" class="btn btn-outline-secondary me-3">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <div>
                    <h3 class="fw-bold m-0 text-uppercase">Verifikasi & Inisiasi Penugasan</h3>
                    <small class="text-muted">Poli Kontrol Manajemen Lapangan Operasional DAMKAR</small>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-8 col-lg-10">
                    
                    <div class="card border-0 shadow-sm rounded-4 card-detail p-4 mb-4 bg-white">
                        <h5 class="fw-bold text-danger text-uppercase mb-3">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> Detail Laporan Masuk
                        </h5>
                        <table class="table table-sm table-borderless m-0 text-muted">
                            <tr>
                                <td width="30%" class="fw-bold text-dark">No. Laporan</td>
                                <td>: <?= htmlspecialchars($data_laporan['nomor_laporan'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-dark">Jenis Kejadian</td>
                                <td class="text-uppercase text-danger fw-bold">:
                                    <?= htmlspecialchars($data_laporan['jenis_kejadian'] ?? '-') ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-dark">Lokasi</td>
                                <td>: <?= htmlspecialchars($data_laporan['lokasi'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-dark">Pelapor</td>
                                <td>: <?= htmlspecialchars($data_laporan['pelapor'] ?? '-') ?>
                                    (<?= htmlspecialchars($data_laporan['no_hp'] ?? '-') ?>)</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-dark">Keterangan Awal</td>
                                <td>: <?= htmlspecialchars($data_laporan['deskripsi'] ?? '-') ?></td>
                            </tr>
                        </table>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-5">
                        <h5 class="fw-bold text-dark mb-4">
                            <i class="bi bi-shield-fill-check text-success me-2"></i> Pengerahan Armada & Regu Tugas
                        </h5>
                        <form action="" method="POST">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">Waktu Keberangkatan Armada</label>
                                    <input type="datetime-local" name="waktu_berangkat" class="form-control"
                                        value="<?= date('Y-m-d\TH:i') ?>" required>
                                    <div class="form-text">Catat jam berapa roda armada mulai berputar keluar pos.</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">Komandan Regu (Penanggung Jawab)</label>
                                    <select name="komandan_id" class="form-select" required>
                                        <option value="" selected disabled>Pilih Komandan yang Bertugas...</option>
                                        <?php while ($pers = mysqli_fetch_assoc($query_tbl_daftar)): ?>
                                            <option value="<?= $pers['id'] ?>">
                                                <?= htmlspecialchars($pers['nama_personil']) ?>
                                                (<?= htmlspecialchars($pers['jabatan']) ?>)
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                    <div class="form-text">Perwira/Danru yang memimpin operasi di lapangan.</div>
                                </div>

                                <div class="col-12 text-end mt-4">
                                    <hr class="text-muted">
                                    <button type="submit" name="ganti_status_proses"
                                        class="btn btn-warning px-5 fw-bold text-dark shadow-sm">
                                        <i class="bi bi-play-fill me-1"></i> Kerahkan Regu & Proses Laporan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>