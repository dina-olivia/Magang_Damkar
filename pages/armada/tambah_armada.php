<?php include '../../config/koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tambah Armada – Damkar v.1</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="../assets/css/style.css">

  <style>
    /* Kustomisasi Form Input agar senada dengan UI Inter */
    .form-label {
      font-weight: 600;
      font-size: 0.8rem;
      color: var(--text-dark);
      text-transform: uppercase;
      letter-spacing: 0.03em;
      margin-bottom: 8px;
    }
    .form-control, .form-select {
      padding: 11px 15px;
      font-size: 0.9rem;
      border-radius: 8px;
      border: 1px solid #e2e8f0;
      color: var(--text-dark);
    }
    .form-control:focus, .form-select:focus {
      border-color: var(--purple-primary);
      box-shadow: 0 0 0 3px rgba(63, 55, 201, 0.15);
    }
  </style>
</head>
<body>

  <?php include '../../config/sidebar.php'; ?>

  <div id="main-content">
    
    <div class="mb-4">
      <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--text-dark); margin: 0;">Tambah Unit Baru</h1>
      <p style="font-size: 0.85rem; color: var(--text-muted); margin: 4px 0 0 0;">Tambahkan data unit pemadam kebakaran baru ke dalam sistem</p>
    </div>

    <div class="card card-custom p-4" style="max-width: 700px;">
      <form action="proses_tambah.php" method="POST">
        
        <div class="mb-3">
          <label class="form-label">Nomor Plat Kendaraan</label>
          <input type="text" class="form-control" name="plat_no" placeholder="Contoh: BA 1234 A" required>
        </div>
        
        <div class="mb-3">
          <label class="form-label">Jenis Kendaraan</label>
          <input type="text" class="form-control" name="jenis" placeholder="Contoh: Truck / Mobil Pompa" required>
        </div>
        
        <div class="mb-3">
          <label class="form-label">Merk / Model</label>
          <input type="text" class="form-control" name="merk" placeholder="Contoh: Mitsubishi Fuso" required>
        </div>
        
        <div class="mb-3">
          <label class="form-label">Tahun Pengadaan</label>
          <input type="number" class="form-control" name="tahun" placeholder="Contoh: 2024" required>
        </div>
        
        <div class="mb-4">
          <label class="form-label">Status Unit</label>
          <select class="form-select" name="status" required>
            <option value="Tersedia">Tersedia</option>
            <option value="Digunakan">Digunakan</option>
            <option value="Perbaikan">Perbaikan</option>
            <option value="Rusak">Rusak</option>
          </select>
        </div>
        
        <div class="pt-3 border-top d-flex gap-2">
          <button type="submit" class="btn btn-purple">Simpan Data</button>
          <a href="armada.php" class="btn btn-light" style="border: 1px solid #e2e8f0; border-radius: 8px; font-weight: 600; padding: 10px 22px; font-size: 0.9rem; color: var(--text-muted);">Kembali</a>
        </div>

      </form>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>