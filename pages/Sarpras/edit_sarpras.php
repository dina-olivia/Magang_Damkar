<?php
require_once __DIR__ . '/../../config/koneksi.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID tidak ditemukan!'); window.location='sarpras.php';</script>";
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$data = mysqli_query($conn, "SELECT * FROM sarpras WHERE id_sarpras='$id'");
$row = mysqli_fetch_assoc($data);

if (!$row) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='sarpras.php';</script>";
    exit;
}

if (isset($_POST['update'])) {
    $nama_alat = mysqli_real_escape_string($conn, $_POST['nama_alat']);
    $jenis     = mysqli_real_escape_string($conn, $_POST['jenis']);
    $kondisi   = mysqli_real_escape_string($conn, $_POST['kondisi']);
    $lokasi    = mysqli_real_escape_string($conn, $_POST['lokasi']);

    $update = mysqli_query($conn, "UPDATE sarpras SET
        nama_alat='$nama_alat',
        jenis='$jenis',
        kondisi='$kondisi',
        lokasi='$lokasi'
        WHERE id_sarpras='$id'");

    if ($update) {
        echo "<script>alert('Data berhasil diupdate!'); window.location='sarpras.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Sarpras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { background: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; }
        .main-content { padding: 40px 20px; }
        .form-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
        }
        .form-label { font-weight: 600; color: #334155; margin-bottom: 8px; }
        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #b91c1c;
            box-shadow: 0 0 0 4px rgba(185, 28, 28, 0.1);
        }
        .btn-update {
            background: #b91c1c;
            color: #fff;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            border: none;
            transition: 0.3s;
        }
        .btn-update:hover { background: #991b1b; color: #fff; transform: translateY(-2px); }
        .btn-batal {
            border-radius: 10px;
            padding: 12px 24px;
            background: #f1f5f9;
            color: #475569;
            font-weight: 600;
            text-decoration: none;
        }
        .btn-batal:hover { background: #e2e8f0; color: #1e293b; }
    </style>
</head>
<body>

<div class="main-content">
    <div class="form-card">
        <div class="text-center mb-4">
            <h3 class="fw-bold" style="color: #1e293b;">Edit Data Sarpras</h3>
            <p class="text-muted">Perbarui data sarana dan prasarana</p>
        </div>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-tools me-1 text-danger"></i> Nama Alat</label>
                <input type="text" name="nama_alat" class="form-control" value="<?= htmlspecialchars($row['nama_alat']); ?>" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="bi bi-grid-fill me-1 text-danger"></i> Jenis</label>
                    <input type="text" name="jenis" class="form-control" value="<?= htmlspecialchars($row['jenis']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="bi bi-shield-check me-1 text-danger"></i> Kondisi</label>
                    <select name="kondisi" class="form-select" required>
                        <option value="Baik" <?= $row['kondisi'] == 'Baik' ? 'selected' : ''; ?>>Baik</option>
                        <option value="Rusak Ringan" <?= $row['kondisi'] == 'Rusak Ringan' ? 'selected' : ''; ?>>Rusak Ringan</option>
                        <option value="Rusak Berat" <?= $row['kondisi'] == 'Rusak Berat' ? 'selected' : ''; ?>>Rusak Berat</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label"><i class="bi bi-geo-alt-fill me-1 text-danger"></i> Lokasi</label>
                <input type="text" name="lokasi" class="form-control" value="<?= htmlspecialchars($row['lokasi']); ?>" required>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <a href="sarpras.php" class="btn btn-batal">Batal</a>
                <button type="submit" name="update" class="btn btn-update">
                    <i class="bi bi-check-circle-fill me-1"></i> Update Data
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>