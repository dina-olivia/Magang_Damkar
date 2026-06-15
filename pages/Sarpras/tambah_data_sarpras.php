<?php
include '../config/koneksi.php';

if (isset($_POST['submit'])) {
    $nama_alat = $_POST['nama_alat'];
    $jenis     = $_POST['jenis'];
    $kondisi   = $_POST['kondisi'];
    $lokasi    = $_POST['lokasi'];

    $query = mysqli_query($koneksi, "INSERT INTO sarpras
    (nama_alat, jenis, kondisi, lokasi)
    VALUES
    ('$nama_alat', '$jenis', '$kondisi', '$lokasi')");

    if ($query) {
        echo "<script>
                alert('Data berhasil ditambahkan!');
                window.location='sarpras.php';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal disimpan!');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Sarpras</title>
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
        <h2 class="fw-bold mb-3">Tambah Data Sarpras</h2>
        <p class="text-muted">Silakan isi data sarana & prasarana</p>

        <form action="" method="POST">

            <div class="mb-3">
                <label class="form-label">Nama Alat</label>
                <input type="text" name="nama_alat" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Jenis</label>
                <input type="text" name="jenis" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Kondisi</label>
                <select name="kondisi" class="form-control" required>
                    <option value="">-- Pilih Kondisi --</option>
                    <option value="Baik">Baik</option>
                    <option value="Rusak Ringan">Rusak Ringan</option>
                    <option value="Rusak Berat">Rusak Berat</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Lokasi</label>
                <input type="text" name="lokasi" class="form-control" required>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" name="submit" class="btn btn-danger">Simpan</button>
                <a href="sarpras.php" class="btn btn-secondary">Kembali</a>
            </div>

        </form>
    </div>
</div>

</body>
</html>