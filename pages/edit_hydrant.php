<?php 
include '../config/koneksi.php';

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM hydrant WHERE id_hydrant='$id'"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Hydrant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="card p-4 shadow">
        <h4>Edit Hydrant</h4>

        <form method="POST">
            <div class="mb-3">
                <label>Nama Hydrant</label>
                <input type="text" name="nama_hydrant" class="form-control" value="<?= $data['nama_hydrant']; ?>">
            </div>

            <div class="mb-3">
                <label>Lokasi</label>
                <input type="text" name="lokasi" class="form-control" value="<?= $data['lokasi']; ?>">
            </div>

            <div class="mb-3">
                <label>Kondisi</label>
                <select name="kondisi" class="form-control">
                    <option <?= $data['kondisi']=='Baik'?'selected':'' ?>>Baik</option>
                    <option <?= $data['kondisi']=='Rusak'?'selected':'' ?>>Rusak</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control"><?= $data['keterangan']; ?></textarea>
            </div>

            <button type="submit" name="update" class="btn btn-danger">Update</button>
            <a href="sarpras.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

<?php
if(isset($_POST['update'])){
    $nama = $_POST['nama_hydrant'];
    $lokasi = $_POST['lokasi'];
    $kondisi = $_POST['kondisi'];
    $keterangan = $_POST['keterangan'];

    mysqli_query($koneksi, "UPDATE hydrant SET
        nama_hydrant='$nama',
        lokasi='$lokasi',
        kondisi='$kondisi',
        keterangan='$keterangan'
        WHERE id_hydrant='$id'
    ");

    echo "<script>alert('Data berhasil diupdate');window.location='sarpras.php';</script>";
}
?>

</body>
</html>