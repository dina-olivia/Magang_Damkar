<?php
include '../../config/koneksi.php';

$query_status = mysqli_query($conn, "SELECT l.id, l.nomor_laporan, l.lokasi, l.jenis_kejadian, l.status, l.waktu_proses, l.waktu_selesai, COALESCE(l.personil_regu, '') AS personil_regu, COALESCE(l.armada_sarpras, '') AS armada_sarpras
FROM laporan_kejadian l
ORDER BY l.tanggal DESC, l.id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Status Penanganan – Damkar v.1</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
  <style>
    /* Core Style & Reset */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --bg: #f8f9fa;
      --surface: #ffffff;
      --surface2: #f0f1f5;
      --border: rgba(0,0,0,.08);
      --text: #1a1b1e;
      --text2: #94a3b8;
      --blue: #185FA5; --blue-bg: #E6F1FB; --blue-mid: #378ADD;
      --green: #3B6D11; --green-bg: #EAF3DE;
      --amber: #854F0B; --amber-bg: #FAEEDA;
      --red: #A32D2D; --red-bg: #FCEBEB;
      --sidebar-w: 260px;
      --radius-lg: 14px;
    }

    body { font-family: 'Segoe UI', system-ui, sans-serif; background: var(--bg); color: var(--text); }

    /* Main Content Adjustment */
    #main-content {
      margin-left: 260px;
      transition: margin 0.3s ease;
      min-height: 100vh;
      background: var(--bg);
    }

    @media (max-width: 991px) {
      #main-content {
        margin-left: 0;
      }
    }

    .topbar {
      height: 60px; background: var(--surface); border-bottom: 1px solid var(--border);
      display: flex; align-items: center; justify-content: space-between; padding: 0 30px;
    }

    .content { padding: 30px; }

    /* Header */
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
    
    /* Card Penanganan Grid */
    .penanganan-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; }
    .pen-card { 
      background: var(--surface); border-radius: var(--radius-lg); border: 1px solid var(--border); 
      padding: 20px; display: flex; flex-direction: column; gap: 12px;
      box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }
    
    .pen-header { display: flex; justify-content: space-between; align-items: flex-start; }
    .id-tag { font-size: 10px; font-weight: 700; color: #475569; background: var(--surface2); padding: 4px 8px; border-radius: 4px; }
    
    .title { font-size: 16px; font-weight: 600; color: var(--text); }
    .location { font-size: 13px; color: #64748b; display: flex; align-items: center; gap: 4px; }

    /* Progress Bar */
    .progress-container { margin-top: 10px; }
    .progress-info { display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 6px; font-weight: 600; }
    .progress-bar { height: 8px; background: var(--surface2); border-radius: 10px; overflow: hidden; }
    .progress-fill { height: 100%; border-radius: 10px; transition: width 0.5s ease; }

    .unit-info { 
      margin-top: 5px; padding-top: 12px; border-top: 1px solid var(--border);
      display: flex; align-items: center; justify-content: space-between; font-size: 12px;
    }
    .badge-live { display: inline-flex; align-items: center; gap: 6px; color: var(--red); font-weight: 700; font-size: 11px; letter-spacing: 0.5px; }
    .dot { width: 8px; height: 8px; background: var(--red); border-radius: 50%; animation: pulse 1.5s infinite; }

    @keyframes pulse {
      0% { transform: scale(0.9); opacity: 1; }
      50% { transform: scale(1.2); opacity: 0.4; }
      100% { transform: scale(0.9); opacity: 1; }
    }
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
                <a href="#menuOperasional" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center" aria-expanded="true">
                    <span><i class="bi bi-clipboard-check"></i> Operasional</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu show" id="menuOperasional">
                    <a href="../operasional/penugasan_tim.php">Penugasan Tim</a>
                    <a href="../operasional/monitoring_armada.php">Monitoring Armada</a>
                    <a href="status_penanganan.php" class="active">Status Penanganan</a>
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
                    <a href="../armada/armada.php">Data Armada</a>
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
    <header class="topbar">
      <div class="topbar-left">
        <h2 style="font-size: 16px; font-weight: 600;">Monitoring Insiden Aktif</h2>
      </div>
      <div class="badge-live"><div class="dot"></div> LIVE UPDATES</div>
    </header>

    <div class="content">
      <div class="page-header">
        <div>
          <h1 style="font-size: 20px; font-weight: 700;">Status Penanganan</h1>
          <p style="font-size: 13px; color: #64748b; margin-top: 4px;">Daftar kejadian yang sedang dalam penanganan unit armada di lapangan</p>
        </div>
      </div>

      <div class="penanganan-grid">
        <?php if ($query_status && mysqli_num_rows($query_status) > 0): ?>
          <?php while ($row = mysqli_fetch_assoc($query_status)): ?>
            <?php
              $status_key = strtolower($row['status'] ?? '');
              $status_text = 'Status tidak diketahui';
              $progress_value = 40;
              $progress_color = 'var(--amber)';
              $stage_text = 'Menunggu verifikasi';

              if ($status_key === 'masuk') {
                $status_text = 'Laporan Masuk';
                $progress_value = 25;
                $progress_color = 'var(--amber)';
                $stage_text = 'Menunggu respon unit';
              } elseif ($status_key === 'proses') {
                $status_text = 'Dalam Penanganan';
                $progress_value = 65;
                $progress_color = 'var(--red)';
                $stage_text = 'Proses penanganan di lapangan';
              } elseif ($status_key === 'selesai') {
                $status_text = 'Selesai';
                $progress_value = 100;
                $progress_color = 'var(--green)';
                $stage_text = 'Penanganan selesai';
              }

              $time_label = 'Waktu belum tersedia';
              if (!empty($row['waktu_proses']) && $row['waktu_proses'] !== '0000-00-00 00:00:00') {
                $time_label = date('H:i', strtotime($row['waktu_proses']));
              } elseif (!empty($row['waktu_selesai']) && $row['waktu_selesai'] !== '0000-00-00 00:00:00') {
                $time_label = date('H:i', strtotime($row['waktu_selesai']));
              }

              $unit_info = trim($row['armada_sarpras'] ?: $row['personil_regu'] ?: '-');
            ?>

            <div class="pen-card">
              <div class="pen-header">
                <span class="id-tag"><?= htmlspecialchars($row['nomor_laporan'] ?? 'N/A') ?></span>
                <div style="text-align: right;">
                  <span style="font-size: 11px; color: #64748b;">Mulai: <?= htmlspecialchars($time_label) ?></span>
                </div>
              </div>

              <div class="title"><?= htmlspecialchars($row['jenis_kejadian'] ?: 'Kejadian Tidak Diketahui') ?></div>
              <div class="location"><i class="ti ti-map-pin"></i> <?= htmlspecialchars($row['lokasi'] ?: 'Lokasi tidak tersedia') ?></div>

              <div class="progress-container">
                <div class="progress-info">
                  <span><?= htmlspecialchars($stage_text) ?></span>
                  <span><?= $progress_value ?>%</span>
                </div>
                <div class="progress-bar">
                  <div class="progress-fill" style="width: <?= $progress_value ?>%; background: <?= $progress_color ?>;"></div>
                </div>
              </div>

              <div class="unit-info">
                <span>Unit: <strong><?= htmlspecialchars($unit_info) ?></strong></span>
                <a href="../manajemen/detail_kejadian.php?no_lp=<?= urlencode($row['nomor_laporan'] ?? '') ?>" style="color: var(--blue); text-decoration: none; font-weight: 600;">Detail <i class="ti ti-arrow-right"></i></a>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <div class="pen-card">
            <div class="pen-header">
              <span class="id-tag">--</span>
            </div>
            <div class="title">Tidak ada kejadian aktif</div>
            <div class="location"><i class="ti ti-map-pin"></i> Belum ada data lokasi penanganan</div>
            <div class="progress-container">
              <div class="progress-info">
                <span>Menunggu data</span>
                <span>0%</span>
              </div>
              <div class="progress-bar">
                <div class="progress-fill" style="width: 0%; background: var(--blue);"></div>
              </div>
            </div>
            <div class="unit-info">
              <span>Unit: <strong>-</strong></span>
              <span style="color: var(--blue); font-weight: 600;">Perbarui data kejadian untuk melihat status</span>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

</body>
</html>