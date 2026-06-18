<?php
// Form & handler untuk verifikasi laporan (laporan_kejadian)
include '../../config/koneksi.php';

// Pastikan variabel koneksi tersedia (fallback jika file koneksi menggunakan nama variabel berbeda)
if (!isset($conn) && isset($koneksi)) { $conn = $koneksi; }
if (!isset($conn) && isset($db)) { $conn = $db; }
if (!isset($conn) || !$conn) {
    $conn = @mysqli_connect('localhost','root','','app_damkar');
    if (!$conn) {
        die('Koneksi database tidak tersedia.');
    }
}

// Jika method POST -> proses verifikasi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verifikasi_laporan'])) {
    $id_laporan = intval($_POST['id_laporan']);
    $status_verifikasi = $_POST['status_verifikasi'] ?? '';
    $catatan_operator = mysqli_real_escape_string($conn, $_POST['catatan_operator'] ?? '');
    $sv = strtolower($status_verifikasi);
    // Terima beberapa label: 'valid'/'setuju' => valid, lainnya => palsu
    $verifikasi_val = in_array($sv, ['valid', 'setuju']) ? 'valid' : 'palsu';
    $verifikasi_at = date('Y-m-d H:i:s');

    // Pastikan kolom verifikasi ada dengan cara yang aman (cek INFORMATION_SCHEMA)
    $cols = ['verifikasi', 'catatan_verifikasi', 'verifikasi_at'];
    foreach ($cols as $col) {
        $check = mysqli_query($conn, "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='laporan_kejadian' AND COLUMN_NAME='$col'");
        if (mysqli_num_rows($check) == 0) {
            if ($col === 'verifikasi') {
                mysqli_query($conn, "ALTER TABLE laporan_kejadian ADD COLUMN verifikasi ENUM('pending','valid','palsu') DEFAULT 'pending'");
            } elseif ($col === 'catatan_verifikasi') {
                mysqli_query($conn, "ALTER TABLE laporan_kejadian ADD COLUMN catatan_verifikasi TEXT DEFAULT NULL");
            } else {
                mysqli_query($conn, "ALTER TABLE laporan_kejadian ADD COLUMN verifikasi_at TIMESTAMP NULL DEFAULT NULL");
            }
        }
    }

    // Update record laporan_kejadian
    $update = "UPDATE laporan_kejadian SET verifikasi = '$verifikasi_val', catatan_verifikasi = '$catatan_operator', verifikasi_at = '$verifikasi_at' WHERE id = $id_laporan";
    $res = mysqli_query($conn, $update);

    if ($res) {
        // Jika valid, biarkan status tetap 'masuk' sehingga penugasan bisa mengambilnya.
        // Arahkan pengguna ke menu Penugasan agar laporan yang terverifikasi segera dapat ditindaklanjuti.
        if ($verifikasi_val === 'valid') {
            header('Location: ../operasional/penugasan_tim.php?success=verifikasi');
            exit;
        } else {
            // Jika ditolak, set status laporan menjadi 'ditolak' dan kembali ke monitoring
            // Pastikan enum status memiliki value 'ditolak' (safe ALTER jika belum ada)
            @mysqli_query($conn, "ALTER TABLE laporan_kejadian MODIFY COLUMN status ENUM('masuk','proses','selesai','ditolak') NOT NULL DEFAULT 'masuk'");
            mysqli_query($conn, "UPDATE laporan_kejadian SET status = 'ditolak' WHERE id = $id_laporan");
            header('Location: ../manajemen/monitoring_kejadian.php?success=verifikasi_reject');
            exit;
        }
    } else {
        header('Location: laporan_kejadian.php?error=' . urlencode(mysqli_error($conn)));
        exit;
    }
}

// Jika GET dengan id -> tampilkan form verifikasi sederhana
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $q = mysqli_query($conn, "SELECT * FROM laporan_kejadian WHERE id = $id");
    $lap = mysqli_fetch_assoc($q);
    if (!$lap) {
        header('Location: laporan_kejadian.php?error=Laporan tidak ditemukan');
        exit;
    }
    ?>
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Verifikasi Laporan</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body class="p-4">
        <div class="container">
            <a href="laporan_kejadian.php" class="btn btn-secondary mb-3">&larr; Kembali</a>
            <div class="card">
                <div class="card-header">Verifikasi Laporan - <?= htmlspecialchars($lap['nomor_laporan'] ?? '-') ?></div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>Jenis</th>
                            <td><?= htmlspecialchars($lap['jenis_kejadian']) ?></td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td><?= htmlspecialchars($lap['lokasi']) ?></td>
                        </tr>
                        <tr>
                            <th>Pelapor</th>
                            <td><?= htmlspecialchars($lap['pelapor']) ?> (<?= htmlspecialchars($lap['no_hp'] ?? '-') ?>)
                            </td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td><?= nl2br(htmlspecialchars($lap['deskripsi'] ?? '-')) ?></td>
                        </tr>
                    </table>

                    <form method="POST" action="proses_verifikasi.php">
                        <input type="hidden" name="id_laporan" value="<?= $lap['id'] ?>">
                        <div class="mb-3">
                            <label class="form-label">Hasil Verifikasi</label>
                            <select name="status_verifikasi" class="form-select" required>
                                <option value="Valid">Valid (Sesuai)</option>
                                <option value="Palsu">Palsu (Tidak Sesuai)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan Operator (opsional)</label>
                            <textarea name="catatan_operator" class="form-control" rows="4"></textarea>
                        </div>
                        <button type="submit" name="verifikasi_laporan" class="btn btn-primary">Simpan Verifikasi</button>
                    </form>
                </div>
            </div>
        </div>
    </body>

    </html>
    <?php
    exit;
}
?>