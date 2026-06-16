<?php
require_once __DIR__ . '/../config/koneksi.php';
if (!isset($conn)) {
    die('Error: Database connection not established.');
}

$kategori = mysqli_query($conn, "SELECT * FROM kategori");

if(isset($_POST['simpan'])){

    $nama = $_POST['nama_barang'];
    $jenis = $_POST['jenis'];
    $kondisi = $_POST['kondisi'];
    $lokasi = $_POST['lokasi'];

    $query = mysqli_query($conn, "INSERT INTO sarpras
    (nama_alat, jenis, kondisi, lokasi)
    VALUES(
        '$nama',
        '$jenis',
        '$kondisi',
        '$lokasi'
    )");

    if($query){
        echo "<script>alert('Data berhasil disimpan!')</script>";
    } else {
        die("Error: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Sarpras</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f5f6fa;
    font-family:Segoe UI;
}

.box{
    background:white;
    padding:30px;
    border-radius:20px;
    margin-top:40px;
}

</style>

</head>
<body>

<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-7">

            <div class="box">

                <h3 class="fw-bold mb-4">
                    Tambah Data Sarpras
                </h3>

                <form method="POST">

                    <div class="mb-3">

                        <label>Nama Barang</label>

                        <input
                            type="text"
                            name="nama_barang"
                            class="form-control"
                            required
                        >

                    </div>

                    <div class="mb-3">

    <label>Jenis</label>

    <input 
        type="text" 
        name="jenis" 
        class="form-control"
        placeholder="Contoh: Peralatan Pemadam"
        required
    >

</div>

                    <div class="mb-3">

                        <label>Kondisi</label>

                        <select
                            name="kondisi"
                            class="form-select"
                        >

                            <option>Baik</option>
                            <option>Perbaikan</option>
                            <option>Rusak</option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label>Lokasi</label>

                        <input
                            type="text"
                            name="lokasi"
                            class="form-control"
                        >

                    </div>

                    <button
                        type="submit"
                        name="simpan"
                        class="btn btn-danger"
                    >
                        Simpan
                    </button>

                    <a
                        href="index.php"
                        class="btn btn-secondary"
                    >
                        Kembali
                    </a>

                </form>

            </div>

        </div>

    </div>

</div>

</body>
</html>