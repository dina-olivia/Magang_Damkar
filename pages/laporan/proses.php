<?php
include '../../config/koneksi.php';

// Pastikan ada parameter ID yang dikirim melalui URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: laporan_kejadian.php?error=ID laporan tidak valid.");
    exit;
}

$id_laporan = intval($_GET['id']);

// 1. Ambil data laporan untuk ditampilkan sebagai informasi di form penugasan
$query_laporan = mysqli_query($conn, "SELECT * FROM laporan_kejadian WHERE id = $id_laporan");
$data_laporan = mysqli_fetch_assoc($query_laporan);

if (!$data_laporan) {
    header("Location: laporan_kejadian.php?error=Laporan tidak ditemukan.");
    exit;
}

// PERBAIKAN DI SINI: Menggunakan kolom 'nama_personil' dan status 'Aktif' (Huruf A Kapital) sesuai database Anda
$query_tbl_daftar = mysqli_query($conn, "SELECT id, nama_personil, jabatan FROM tbl_daftar WHERE status = 'Aktif' ORDER BY nama_personil ASC");

// 2. Proses ketika Form Penugasan dikirim (Submit)
if (isset($_POST['ganti_status_proses'])) {
    $komandan_id = intval($_POST['komandan_id']);

    // Ubah format 'datetime-local' (dari HTML5) menjadi format standar MySQL (YYYY-MM-DD HH:MM:SS)
    $waktu_input = $_POST['waktu_berangkat'];
    $waktu_berangkat = date('Y-m-d H:i:s', strtotime($waktu_input));

    // Mulai Database Transaction agar aman dan sinkron
    mysqli_begin_transaction($conn);

    try {
        // A. Update status di tabel laporan_kejadian menjadi 'proses'
        $stmt_update = mysqli_prepare($conn, "UPDATE laporan_kejadian SET status = 'proses' WHERE id = ?");
        mysqli_stmt_bind_param($stmt_update, "i", $id_laporan);
        mysqli_stmt_execute($stmt_update);

        // B. Insert data penugasan baru ke tabel penugasan
        $status_tugas = 'dalam penanganan';

        $query_insert = "INSERT INTO penugasan (laporan_id, waktu_berangkat, komandan_id, status) VALUES (?, ?, ?, ?)";
        $stmt_insert = mysqli_prepare($conn, $query_insert);

        // "isis" berarti: i = integer (laporan_id), s = string (waktu), i = integer (komandan_id), s = string (status)
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card-detail {
            border-left: 4px solid #dc3545;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-4">
                    <a href="laporan_kejadian.php" class="btn btn-outline-secondary me-3">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <h3 class="fw-bold m-0 text-uppercase">Verifikasi & Inisiasi Penugasan</h3>
                </div>

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

                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>