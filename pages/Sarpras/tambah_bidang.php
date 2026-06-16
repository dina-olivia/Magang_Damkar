<?php
// ensure we load the database config using an absolute path relative to this file
require_once __DIR__ . '/../../config/koneksi.php';

// verify $koneksi is available
if (!isset($conn) || !$conn) {
    die('Database connection not established. Please check config/conn.php');
}

if(isset($_POST['simpan'])){

    $nama_bidang = $_POST['nama_bidang'];
    $deskripsi   = $_POST['deskripsi'];
    $urutan      = $_POST['urutan'];

    $query = mysqli_query($conn, "INSERT INTO bidang
    (nama_bidang, deskripsi, urutan)
    VALUES(
        '$nama_bidang',
        '$deskripsi',
        '$urutan'
    )");

    if($query){
        echo "<script>
            alert('Bidang berhasil ditambahkan!');
            window.location='master_bidang.php';
        </script>";
    } else {
        echo mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Bidang</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">

<style>
body{
    background:#f5f6fa;
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
                    Tambah Bidang
                </h3>

                <form method="POST">

                    <div class="mb-3">
                        <label>Nama Bidang</label>
                        <input
                            type="text"
                            name="nama_bidang"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea
                            name="deskripsi"
                            class="form-control"
                            rows="3"
                            required
                        ></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Urutan</label>
                        <input
                            type="number"
                            name="urutan"
                            class="form-control"
                            required
                        >
                    </div>

                    <button
                        type="submit"
                        name="simpan"
                        class="btn btn-danger"
                    >
                        Simpan
                    </button>

                    <a href="master_bidang.php"
                       class="btn btn-secondary">
                        Kembali
                    </a>

                </form>

            </div>

        </div>
    </div>
</div>

</body>
</html>