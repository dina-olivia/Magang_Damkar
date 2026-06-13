<?php
include '../config/koneksi.php';

if (isset($_POST['submit'])) {

    $nama_kategori = $_POST['nama_kategori'];
    $bidang        = $_POST['bidang'];
    $unit          = $_POST['unit'];
    $status        = $_POST['status'];
    $aksi          = $_POST['aksi'];

    $query = mysqli_query($koneksi, "INSERT INTO kategori
    (nama_kategori, bidang, unit, status, aksi)
    VALUES
    ('$nama_kategori', '$bidang', '$unit', '$status', '$aksi')");

    if ($query) {
        echo "<script>
                alert('Kategori berhasil ditambahkan!');
                window.location='master_kategori.php';
              </script>";
    } else {
        echo "<script>
                alert('Kategori gagal ditambahkan!');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#f5f6fa;
        }

        .container-form{
            max-width:700px;
            margin:50px auto;
        }

        .card{
            border:none;
            border-radius:16px;
            box-shadow:0 4px 12px rgba(0,0,0,.08);
        }
    </style>
</head>
<body>

<div class="container-form">
    <div class="card p-4">

        <h2 class="fw-bold mb-2">Tambah Kategori</h2>
        <p class="text-muted mb-4">
            Tambahkan kategori sarana dan prasarana
        </p>

        <form action="" method="POST">

    <div class="mb-3">
    <label class="form-label">Nama Kategori</label>
    <input type="text"
           name="nama_kategori"
           class="form-control"
           required>
</div>

    <div class="mb-3">
        <label class="form-label">Bidang</label>
        <input type="text"
               name="bidang"
               class="form-control"
               required>
    </div>

    <div class="mb-3">
        <label class="form-label">Unit</label>
        <input type="text"
               name="unit"
               class="form-control"
               required>
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-control" required>
            <option value="">-- Pilih Status --</option>
            <option value="Aktif">Aktif</option>
            <option value="Tidak Aktif">Tidak Aktif</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Keadaan</label>
        <input type="text"
               name="aksi"
               class="form-control"
               required>
    </div>

    <div class="d-flex gap-2">
        <button type="submit"
                name="submit"
                class="btn btn-primary">
            Simpan
        </button>

        <a href="master_kategori.php"
           class="btn btn-secondary">
            Kembali
        </a>
    </div>

</form>

    </div>
</div>

</body>
</html>