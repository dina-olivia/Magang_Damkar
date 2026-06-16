<?php
require_once __DIR__ . '/../../config/koneksi.php';

// Validasi ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID Kategori tidak ditemukan!'); window.location='master_kategori.php';</script>";
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil data
$query = mysqli_query($conn, "SELECT * FROM kategori WHERE id_kategori='$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='master_kategori.php';</script>";
    exit;
}

// Proses Update
if(isset($_POST['update'])){
    $nama_kategori = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    $bidang        = mysqli_real_escape_string($conn, $_POST['bidang']);
    $unit          = mysqli_real_escape_string($conn, $_POST['unit']);
    $status        = mysqli_real_escape_string($conn, $_POST['status']);
    $keadaan       = mysqli_real_escape_string($conn, $_POST['keadaan']);

    $update = mysqli_query($conn, "UPDATE kategori SET
        nama_kategori='$nama_kategori',
        bidang='$bidang',
        unit='$unit',
        status='$status',
        keadaan='$keadaan'
        WHERE id_kategori='$id'
    ");

    if($update){
        echo "<script>
                alert('Data berhasil diupdate!');
                window.location='master_kategori.php';
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { background: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; }
        .main { padding: 40px 20px; }
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

<div class="main">
    <div class="form-card">
        <div class="text-center mb-4">
            <h3 class="fw-bold" style="color: #1e293b;">Edit Kategori</h3>
            <p class="text-muted">Ubah data kategori sarpras secara detail</p>
        </div>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-tag-fill me-1 text-danger"></i> Nama Kategori</label>
                <input type="text" name="nama_kategori" class="form-control" value="<?= htmlspecialchars($data['nama_kategori']); ?>" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="bi bi-diagram-3-fill me-1 text-danger"></i> Bidang</label>
                    <input type="text" name="bidang" class="form-control" value="<?= htmlspecialchars($data['bidang']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="bi bi-truck-flatbed me-1 text-danger"></i> Unit</label>
                    <input type="text" name="unit" class="form-control" value="<?= htmlspecialchars($data['unit']); ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-check-circle-fill me-1 text-danger"></i> Status</label>
                <select name="status" class="form-select">
                    <option value="Aktif" <?= ($data['status']=='Aktif') ? 'selected' : ''; ?>>Aktif</option>
                    <option value="Tidak Aktif" <?= ($data['status']=='Tidak Aktif') ? 'selected' : ''; ?>>Tidak Aktif</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label"><i class="bi bi-check-circle-fill me-1 text-danger"></i> Keadaan</label>
                <select name="keadaan" class="form-select">
                    <option value="Siaga" <?= ($data['keadaan']=='Siaga') ? 'selected' : ''; ?>>Siaga</option>
                    <option value="Digunakan" <?= ($data['keadaan']=='Digunakan') ? 'selected' : ''; ?>>Digunakan</option>
                    <option value="Rusak" <?= ($data['keadaan']=='Rusak') ? 'selected' : ''; ?>>Rusak</option>
                </select>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <a href="master_kategori.php" class="btn btn-batal">Batal</a>
                <button type="submit" name="update" class="btn btn-update">
                    <i class="bi bi-save-fill me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>