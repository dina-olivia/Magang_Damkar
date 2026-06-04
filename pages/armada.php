<?php include '../config/koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Data Armada – Damkar v.1</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="../assets/css/style.css">

  <style>
    /* Sinkronisasi warna komponen tabel dengan variabel CSS kamu */
    .table-custom tbody td {
      padding: 16px 20px;
      font-size: 0.85rem;
      vertical-align: middle;
      color: var(--text-dark);
      border-bottom: 1px solid #e2e8f0;
    }
    .table-custom tbody tr:hover {
      background-color: #f8fafc;
    }

    /* Style Badge Status Pill agar adaptif */
    .pill {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.7rem;
      font-weight: 700;
      text-transform: uppercase;
    }
    .pill-rusak { background: #FCEBEB; color: #A32D2D; }
    .pill-perbaikan { background: #FAEEDA; color: #854F0B; }
    .pill-digunakan, .pill-siaga, .pill-tersedia { background: #EAF3DE; color: #3B6D11; }

    .action-btn { color: var(--purple-primary); text-decoration: none; margin-right: 15px; font-size: 0.85rem; font-weight: 500; }
    .action-btn:hover { text-decoration: underline; }
    .action-delete { color: var(--fire-red); }
  </style>
</head>
<body>

  <?php include '../config/sidebar.php'; ?>

  <div id="main-content">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--text-dark); margin: 0;">Data Armada</h1>
        <p style="font-size: 0.85rem; color: var(--text-muted); margin: 4px 0 0 0;">Daftar unit pemadam kebakaran dalam sistem</p>
      </div>
      <a href="tambah_armada.php" class="btn btn-purple d-inline-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i> Tambah Unit Baru
      </a>
    </div>

    <div class="card card-custom overflow-hidden">
      <table class="table table-custom m-0">
        <thead>
          <tr>
            <th style="width: 70px; text-align: center;">No</th>
            <th>No Plat</th>
            <th>Jenis Kendaraan</th>
            <th>Merk / Model</th>
            <th>Tahun</th>
            <th>Status</th>
            <th style="width: 160px; text-align: center;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          $tampil = mysqli_query($conn, "SELECT * FROM armada");
          if(mysqli_num_rows($tampil) > 0) {
            while ($data = mysqli_fetch_array($tampil)) {
              $status_class = strtolower(trim($data['status']));
          ?>
          <tr>
            <td style="text-align: center; color: var(--text-muted); font-weight: 500;"><?php echo $no++; ?></td>
            <td><strong style="color: var(--text-dark);"><?php echo $data['plat_no']; ?></strong></td>
            <td><?php echo $data['jenis']; ?></td>
            <td><?php echo $data['merk']; ?></td>
            <td><?php echo $data['tahun']; ?></td>
            <td>
              <span class="pill pill-<?php echo $status_class; ?>">
                <?php echo $data['status']; ?>
              </span>
            </td>
            <td style="text-align: center;">
              <a href="edit.php?id=<?php echo $data['id']; ?>" class="action-btn"><i class="bi bi-pencil-square"></i> Edit</a>
              <a href="hapus.php?id=<?php echo $data['id']; ?>" class="action-btn action-delete" onclick="return confirm('Hapus unit ini?')"><i class="bi bi-trash"></i> Hapus</a>
            </td>
          </tr>
          <?php 
            }
          } else {
          ?>
          <tr>
            <td colspan="7">
              <div class="empty-state">
                <i class="bi bi-truck-flatbed"></i>
                <h6>Belum Ada Data Terbaru</h6>
              </div>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>