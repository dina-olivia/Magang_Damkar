<?php 
include '../../config/koneksi.php'; 

if (isset($_POST['submit'])) {
    $nama_kategori = $_POST['nama_kategori'];
    $bidang        = $_POST['bidang'];
    $unit          = $_POST['unit'];
    $status        = $_POST['status'];
    $aksi          = $_POST['aksi']; // Input untuk Keadaan

    if (!($conn instanceof mysqli)) {
        die('Database connection not established');
    }

    $stmt = $conn->prepare("INSERT INTO kategori (nama_kategori, bidang, unit, status, aksi) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nama_kategori, $bidang, $unit, $status, $aksi);
    $query = $stmt->execute();

    if ($query) {
        echo "<script>
                alert('Kategori berhasil ditambahkan!');
                window.location='master_kategori.php';
              </script>";
    } else {
        echo "<script>alert('Kategori gagal ditambahkan!');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Kategori</title>
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
        .btn-submit {
            background: #b91c1c;
            color: #fff;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            border: none;
            transition: 0.3s;
        }
        .btn-submit:hover { background: #991b1b; color: #fff; transform: translateY(-2px); }
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
            <h3 class="fw-bold" style="color: #1e293b;">Tambah Kategori</h3>
            <p class="text-muted">Tambahkan kategori sarana dan prasarana baru</p>
        </div>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-tag-fill me-1 text-danger"></i> Nama Kategori</label>
                <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Alat Pelindung Diri" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="bi bi-diagram-3-fill me-1 text-danger"></i> Bidang</label>
                    <input type="text" name="bidang" class="form-control" placeholder="Contoh: Operasional" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="bi bi-truck-flatbed me-1 text-danger"></i> Unit</label>
                    <input type="text" name="unit" class="form-control" placeholder="Contoh: Regu A" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-check-circle-fill me-1 text-danger"></i> Status</label>
                <select name="status" class="form-select" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="Aktif">Aktif</option>
                    <option value="Tidak Aktif">Tidak Aktif</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label"><i class="bi bi-info-circle-fill me-1 text-danger"></i> Keadaan</label>
                <input type="text" name="aksi" class="form-control" placeholder="Contoh: Baik / Layak Pakai" required>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <a href="master_kategori.php" class="btn btn-batal">Batal</a>
                <button type="submit" name="submit" class="btn btn-submit">
                    <i class="bi bi-plus-circle-fill me-1"></i> Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>