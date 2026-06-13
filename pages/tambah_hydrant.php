<?php 
include '../config/koneksi.php'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Hydrant</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="card shadow p-4">
        <h4 class="mb-4">Tambah Data Hydrant</h4>

        <form method="POST">
            
            <div class="mb-3">
                <label class="form-label">Nama Hydrant</label>
                <input type="text" name="nama_hydrant" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Lokasi</label>
                <input type="text" name="lokasi" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Kondisi</label>
                <select name="kondisi" class="form-control" required>
                    <option value="">-- Pilih Kondisi --</option>
                    <option value="Baik">Baik</option>
                    <option value="Rusak">Rusak</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control"></textarea>
            </div>

            <button type="submit" name="simpan" class="btn btn-danger">
                Simpan
            </button>

            <a href="sarpras.php" class="btn btn-secondary">Kembali</a>

        </form>
    </div>
</div>

<?php
if(isset($_POST['simpan'])){
    $nama   = $_POST['nama_hydrant'];
    $lokasi = $_POST['lokasi'];
    $kondisi = $_POST['kondisi'];
    $keterangan = $_POST['keterangan'];

    $query = mysqli_query($koneksi, "INSERT INTO hydrant 
        (nama_hydrant, lokasi, kondisi, keterangan)
        VALUES ('$nama', '$lokasi', '$kondisi', '$keterangan')");

    if($query){
        echo "<script>
                alert('Data berhasil ditambahkan!');
                window.location='sarpras.php';
              </script>";
    } else {
        echo "Gagal: " . mysqli_error($koneksi);
    }
}
?>

</body>
</html>