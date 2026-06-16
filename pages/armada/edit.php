<?php
include '../../config/koneksi.php';

// Ensure $conn is available (some configs may use $koneksi)
if (!isset($conn) && isset($conn)) {
  $conn = $conn;
}

// Check if $conn is defined
if (!isset($conn)) {
  die("Database connection failed.");
}

// 1. Ambil ID dari URL
$id = $_GET['id'];

// 2. Ambil data lama dari database
$query = $conn->prepare("SELECT * FROM armada WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();
$data = $query->get_result()->fetch_assoc();

// 3. Proses Update jika tombol ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $plat_no = $_POST['plat_no'];
    $jenis   = $_POST['jenis'];
    $merk    = $_POST['merk'];
    $tahun   = $_POST['tahun'];
    $status  = $_POST['status'];

    $update = $conn->prepare("UPDATE armada SET plat_no=?, jenis=?, merk=?, tahun=?, status=? WHERE id=?");
    $update->bind_param("sssssi", $plat_no, $jenis, $merk, $tahun, $status, $id);

    if ($update->execute()) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location='armada.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Edit Armada – Damkar Padang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    :root { --danger-red: #dc2626; }
    body { background-color: #f1f5f9; font-family: 'Inter', sans-serif; }
    .card-custom { border-radius: 20px; border-left: 10px solid var(--danger-red); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); padding: 40px; }
    .btn-danger-custom { background: var(--danger-red); color: white; border-radius: 12px; padding: 12px 30px; font-weight: 700; }
  </style>
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">

  <div class="container" style="max-width: 800px;">
    <div class="mb-4 text-center">
      <h2 class="fw-bold"><i class="bi bi-pencil-square text-danger"></i> Edit Data Armada</h2>
    </div>

    <div class="card card-custom">
      <form method="POST">
        <div class="row g-4">
          <div class="col-12 form-floating">
            <input type="text" class="form-control" name="plat_no" value="<?php echo $data['plat_no']; ?>" required>
            <label class="ps-4">Nomor Plat Kendaraan</label>
          </div>
          <div class="col-md-6 form-floating">
            <input type="text" class="form-control" name="jenis" value="<?php echo $data['jenis']; ?>" required>
            <label class="ps-4">Jenis Kendaraan</label>
          </div>
          <div class="col-md-6 form-floating">
            <input type="text" class="form-control" name="merk" value="<?php echo $data['merk']; ?>" required>
            <label class="ps-4">Merk / Model</label>
          </div>
          <div class="col-md-6 form-floating">
            <input type="number" class="form-control" name="tahun" value="<?php echo $data['tahun']; ?>" required>
            <label class="ps-4">Tahun Pengadaan</label>
          </div>
          <div class="col-md-6 form-floating">
            <select class="form-select" name="status">
              <option value="Tersedia" <?php if($data['status']=='Tersedia') echo 'selected'; ?>>Tersedia</option>
              <option value="Digunakan" <?php if($data['status']=='Digunakan') echo 'selected'; ?>>Digunakan</option>
              <option value="Perbaikan" <?php if($data['status']=='Perbaikan') echo 'selected'; ?>>Perbaikan</option>
              <option value="Rusak" <?php if($data['status']=='Rusak') echo 'selected'; ?>>Rusak</option>
            </select>
            <label class="ps-4">Status Unit</label>
          </div>
        </div>
        <div class="pt-5 mt-4 border-top d-flex gap-3">
          <button type="submit" class="btn btn-danger-custom flex-grow-1">Update Data</button>
          <a href="armada.php" class="btn btn-light px-5" style="border: 1px solid #dee2e6; border-radius: 12px;">Batal</a>
        </div>
      </form>
    </div>
  </div>

</body>
</html>