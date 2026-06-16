<?php
$koneksiPath = __DIR__ . '/../config/koneksi.php';
if (!file_exists($koneksiPath)) {
    $koneksiPath = __DIR__ . '/../../config/koneksi.php';
}
if (file_exists($koneksiPath)) {
    include_once $koneksiPath;
} else {
    die('Koneksi file tidak ditemukan.');
}
if (!isset($conn)) {
    die('Koneksi database belum tersedia.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Data Armada – Damkar v.1</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="../../assets/css/style.css">

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

  <div id="sidebar" class="shadow">
        <div class="sidebar-header">
            <img src="../../assets/img/logo_damkar.png" alt="Logo" width="140" height="80"
                onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/b/b0/Logo_Damkar.png'">
            <span class="fw-bold ms-2">DAMKAR PADANG</span>
        </div>

        <div class="sidebar-content">
            <div class="nav flex-column mt-2">
                <a href="../../index.php"><i class="bi bi-speedometer2"></i> Dashboard</a>

                <!-- Manajemen Kejadian -->
                <a href="#menuManajemenKejadian" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-megaphone"></i> Manajemen Kejadian</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuManajemenKejadian">
                    <a href="../manajemen/input_laporan.php">Input Laporan</a>
                    <a href="../manajemen/monitoring_kejadian.php">Monitoring Kejadian</a>
                </div>

                <!-- Operasional (Aktif & Terbuka Otomatis) -->
                <a href="#menuOperasional" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-clipboard-check"></i> Operasional</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuOperasional">
                    <a href="../operasional/penugasan_tim.php">Penugasan Tim</a>
                    <a href="../operasional/monitoring_armada.php">Monitoring Armada</a>
                    <a href="../operasional/status_penanganan.php">Status Penanganan</a>
                </div>

                <!-- Personil -->
                <a href="#menuPersonil" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-people"></i> Personil</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuPersonil">
                    <a href="../personil/personil.php">Data Personil</a>
                    <a href="../personil/penempatan_pos.php">Penempatan Pos</a>
                    <a href="../personil/jadwal_piket.php">Jadwal Piket</a>
                    <a href="../personil/riwayat_tugas.php">Riwayat Tugas</a>
                </div>

                 <!-- Armada -->
                <a href="#menuArmada" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-truck"></i> Armada</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuArmada">
                    <a href="armada.php">Data Armada</a>
                </div>

                <!-- Sarpras -->
                <a href="#menuSarpras" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-tools"></i> Sarpras</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuSarpras">
                    <a href="../Sarpras/sarpras.php">Data Sarpras</a>
                    <a href="../Sarpras/master_bidang.php">Master Bidang</a>
                    <a href="../Sarpras/master_kategori.php">Master Kategori</a>
                </div>

                <!-- Laporan & Analitik -->
                <a href="#menuLaporan" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-file-earmark-text"></i> Laporan & Analitik</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuLaporan">
                    <a href="laporan_kejadian.php">Laporan Kejadian</a>
                    <a href="rekap_statistik.php">Rekap Statistik & Analisis</a>
                    <a href="cetak_export.php">Cetak & Export Dokumen</a>
                </div>
                
                <a href="../manajemen_user.php"><i class="bi bi-gear"></i> Manajemen User</a>
                <a href="../../logout.php" class="mt-4 text-danger"><i class="bi bi-box-arrow-left"></i> Keluar</a>
            </div>
        </div>
    </div>

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
              <a href="edit.php?id=<?php echo $data['id']; ?>" class="action-btn"><i class="bi bi-pencil-square"></i> </a>
              <a href="hapus.php?id=<?php echo $data['id']; ?>" class="action-btn action-delete" onclick="return confirm('Hapus unit ini?')"><i class="bi bi-trash"></i> </a>
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