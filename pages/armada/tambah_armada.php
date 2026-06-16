<?php include '../../config/koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tambah Armada – Command Center</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="../../assets/css/style.css">
  
 <style>
    /* Tambahkan style ini untuk centering */
    html, body {
      height: 100%;
      margin: 0;
    }
    
    #main-content {
      min-height: 100vh;
      display: flex;
      align-items: center; /* Vertikal Center */
      justify-content: center; /* Horizontal Center */
      padding: 20px;
    }

    .container-fluid {
      max-width: 800px; /* Diperbesar dari 650px ke 800px */
      width: 100%;
    }

    :root {
      --danger-red: #dc2626;
      --soft-bg: #fef2f2;
    }
    
    body { background-color: #f1f5f9; font-family: 'Inter', sans-serif; }
    
    .card-custom {
      border: none;
      border-radius: 20px; /* Sudut lebih melengkung */
      border-left: 10px solid var(--danger-red); /* Border lebih tebal */
      box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
      padding: 40px !important; /* Padding diperbesar agar form lebih lega */
    }

    .btn-danger-custom {
      background: var(--danger-red);
      color: white;
      border-radius: 12px;
      padding: 14px 30px;
      font-weight: 700;
      transition: 0.3s;
    }
    .btn-danger-custom:hover { background: #b91c1c; color: white; }
    
    .header-box { border-bottom: 2px solid var(--soft-bg); margin-bottom: 30px; padding-bottom: 20px; }
    .text-danger-custom { color: var(--danger-red); }
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
                
                <a href="../pengaturan.php"><i class="bi bi-gear"></i> Pengaturan</a>
                <a href="../../logout.php" class="mt-4 text-danger"><i class="bi bi-box-arrow-left"></i> Keluar</a>
            </div>
        </div>
    </div>

  <div id="main-content">
    <div class="container-fluid">
      <div class="header-box">
        <h1 class="h3 fw-bold text-dark"><i class="bi bi-fire text-danger-custom me-2"></i>Tambah Armada</h1>
        <p class="text-muted small">Input data unit operasional baru ke dalam sistem</p>
      </div>

      <div class="card card-custom">
        <form action="proses_tambah.php" method="POST">
          <div class="row g-4"> <div class="col-12 form-floating">
              <input type="text" class="form-control" name="plat_no" placeholder="BA 1234 A" required>
              <label class="text-secondary ps-4"><i class="bi bi-card-heading me-2"></i>Nomor Plat Kendaraan</label>
            </div>
            
            <div class="col-md-6 form-floating">
              <input type="text" class="form-control" name="jenis" placeholder="Truck" required>
              <label class="text-secondary ps-4">Jenis Kendaraan</label>
            </div>
            
            <div class="col-md-6 form-floating">
              <input type="text" class="form-control" name="merk" placeholder="Mitsubishi" required>
              <label class="text-secondary ps-4">Merk / Model</label>
            </div>

            <div class="col-md-6 form-floating">
              <input type="number" class="form-control" name="tahun" placeholder="2024" required>
              <label class="text-secondary ps-4">Tahun</label>
            </div>

            <div class="col-md-6 form-floating">
              <select class="form-select" name="status" required>
                <option value="Tersedia">Tersedia</option>
                <option value="Digunakan">Digunakan</option>
                <option value="Perbaikan">Perbaikan</option>
              </select>
              <label class="text-secondary ps-4">Status Unit</label>
            </div>
          </div>
          
          <div class="pt-5 mt-4 border-top d-flex gap-3">
            <button type="submit" class="btn btn-danger-custom flex-grow-1">
              <i class="bi bi-check2-circle me-2"></i>Simpan Armada
            </button>
            <a href="armada.php" class="btn btn-light px-5" style="border-radius: 12px; border: 1px solid #dee2e6;">Batal</a>
          </div>
        </form>
      </div>
    </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>