<?php
include '../config/koneksi.php';

$id = $_GET['id'];

$query = mysqli_query($koneksi, "SELECT * FROM kategori WHERE id_kategori='$id'");
$data = mysqli_fetch_assoc($query);

if(isset($_POST['update'])){

    $nama_kategori = $_POST['nama_kategori'];
    $bidang        = $_POST['bidang'];
    $unit          = $_POST['unit'];
    $status        = $_POST['status'];
    $keadaan          = $_POST['keadaan'];

    $update = mysqli_query($koneksi, "UPDATE kategori SET
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#f5f6fa;
            font-family:Segoe UI;
        }

        .main{
            padding:40px;
        }

        .card-modern{
            border:none;
            border-radius:20px;
            box-shadow:0 2px 10px rgba(0,0,0,0.05);
        }

        .btn-purple{
            background:#6f42c1;
            color:white;
            border:none;
            border-radius:14px;
            padding:10px 20px;
        }

        .btn-purple:hover{
            background:#5b35a0;
            color:white;
        }
    </style>
</head>
<body>

<div class="main">

    <div class="mb-4">
        <h2 class="fw-bold">Edit Kategori</h2>
        <p class="text-muted">Ubah data kategori sarpras</p>
    </div>

    <div class="card card-modern">
        <div class="card-body p-4">

            <form action="" method="POST">

                <div class="mb-3">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text"
                           name="nama_kategori"
                           class="form-control"
                           value="<?= $data['nama_kategori']; ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Bidang</label>
                    <input type="text"
                           name="bidang"
                           class="form-control"
                           value="<?= $data['bidang']; ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Unit</label>
                    <input type="text"
                           name="unit"
                           class="form-control"
                           value="<?= $data['unit']; ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="Aktif"
                        <?= ($data['status']=='Aktif') ? 'selected' : ''; ?>>
                            Aktif
                        </option>

                        <option value="Tidak Aktif"
                        <?= ($data['status']=='Tidak Aktif') ? 'selected' : ''; ?>>
                            Tidak Aktif
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Keadaan</label>
                    <input type="text"
                           name="keadaan"
                           class="form-control"
                           value="<?= $data['keadaan']; ?>">
                </div>

                <button type="submit"
                        name="update"
                        class="btn btn-purple">
                    Simpan Perubahan
                </button>

                <a href="master_kategori.php"
                   class="btn btn-secondary">
                    Kembali
                </a>

            </form>

        </div>
    </div>

</div>

</body>
</html>
```
