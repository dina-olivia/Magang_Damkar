<?php 
require_once __DIR__ . '/../../config/koneksi.php'; 

// Cek ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID Bidang tidak ditemukan!'); window.location.href='master_bidang.php';</script>";
    exit;
}

$id_bidang = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil data
$query = mysqli_query($conn, "SELECT * FROM bidang WHERE id_bidang = '$id_bidang'");
$data  = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='master_bidang.php';</script>";
    exit;
}

// Proses Update
if(isset($_POST['update'])){
    $nama_bidang = mysqli_real_escape_string($conn, $_POST['nama_bidang']);
    $deskripsi   = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $urutan      = mysqli_real_escape_string($conn, $_POST['urutan']);

    mysqli_query($conn, "UPDATE bidang SET
        nama_bidang='$nama_bidang',
        deskripsi='$deskripsi',
        urutan='$urutan'
        WHERE id_bidang='$id_bidang'
    ");

    echo "<script>
        alert('Data berhasil diupdate!');
        window.location.href='master_bidang.php';
    </script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Bidang</title>
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
        .form-control {
            border-radius: 10px;
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #b91c1c;
            box-shadow: 0 0 0 4px rgba(185, 28, 28, 0.1);
        }
        .btn-update {
            background: #b91c1c;
            color: #fff;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            transition: 0.3s;
            border: none;
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
                <h3 class="fw-bold" style="color: #1e293b;">Edit Data Bidang</h3>
                <p class="text-muted">Perbarui informasi detail bidang operasional</p>
            </div>
            
            <form method="POST">
                <div class="mb-4">
                    <label class="form-label"><i class="bi bi-tag-fill me-1 text-danger"></i> Nama Bidang</label>
                    <input type="text" name="nama_bidang" class="form-control" value="<?= htmlspecialchars($data['nama_bidang']); ?>" required>
                </div>
                
                <div class="mb-4">
                    <label class="form-label"><i class="bi bi-text-paragraph me-1 text-danger"></i> Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" required><?= htmlspecialchars($data['deskripsi']); ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label"><i class="bi bi-sort-numeric-down me-1 text-danger"></i> Urutan Tampilan</label>
                    <input type="number" name="urutan" class="form-control" value="<?= $data['urutan']; ?>" required>
                </div>

                <div class="d-flex gap-2 justify-content-end mt-4">
                    <a href="master_bidang.php" class="btn btn-batal">Batal</a>
                    <button type="submit" name="update" class="btn btn-update">
                        <i class="bi bi-check-circle-fill me-1"></i> Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>