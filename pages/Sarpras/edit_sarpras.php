<?php
include '../config/koneksi.php';

$id = $_GET['id'];

$data = mysqli_query($conn, "SELECT * FROM sarpras WHERE id_sarpras='$id'");
$row = mysqli_fetch_assoc($data);

if (isset($_POST['update'])) {
    $nama_alat = $_POST['nama_alat'];
    $jenis     = $_POST['jenis'];
    $kondisi   = $_POST['kondisi'];
    $lokasi    = $_POST['lokasi'];

    $update = mysqli_query($conn, "UPDATE sarpras SET
        nama_alat='$nama_alat',
        jenis='$jenis',
        kondisi='$kondisi',
        lokasi='$lokasi'
        WHERE id_sarpras='$id'
    ");

    if ($update) {
        echo "<script>
                alert('Data berhasil diupdate!');
                window.location='sarpras.php';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal diupdate!');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Sarpras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .container-form { max-width: 700px; margin: 50px auto; }
        .card { border: none; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    </style>
</head>
<body>

<div class="container-form">
    <div class="card p-4">

        <h2 class="fw-bold mb-3">Edit Data Sarpras</h2>
        <p class="text-muted">Ubah data sarana & prasarana</p>

        <form action="" method="POST">

            <div class="mb-3">
                <label class="form-label">Nama Alat</label>
                <input type="text" name="nama_alat" class="form-control"
                       value="<?= $row['nama_alat']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Jenis</label>
                <input type="text" name="jenis" class="form-control"
                       value="<?= $row['jenis']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Kondisi</label>
                <select name="kondisi" class="form-control" required>
                    <option value="">-- Pilih Kondisi --</option>
                    <option value="Baik" <?= $row['kondisi'] == 'Baik' ? 'selected' : ''; ?>>Baik</option>
                    <option value="Rusak Ringan" <?= $row['kondisi'] == 'Rusak Ringan' ? 'selected' : ''; ?>>Rusak Ringan</option>
                    <option value="Rusak Berat" <?= $row['kondisi'] == 'Rusak Berat' ? 'selected' : ''; ?>>Rusak Berat</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Lokasi</label>
                <input type="text" name="lokasi" class="form-control"
                       value="<?= $row['lokasi']; ?>" required>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" name="update" class="btn btn-danger">Update</button>
                <a href="sarpras.php" class="btn btn-secondary">Kembali</a>
            </div>

        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>