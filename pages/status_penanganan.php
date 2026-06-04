<?php include '../config/koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Status Penanganan – Damkar v.1</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    /* Core Style & Reset */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --bg: #f4f6f9;
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
      
      /* Warna Sesuai Gambar Mockup */
      --sidebar-bg: #111c34;
      --sidebar-brand-bg: #b91c1c;
      --sidebar-text: #94a3b8;
      --sidebar-text-hover: #ffffff;
      --sidebar-item-active: #1e293b;
    }

    body { font-family: 'Segoe UI', system-ui, sans-serif; background: var(--bg); color: var(--text); overflow: hidden; }
    .app { display: flex; height: 100vh; }

    /* Sidebar Sesuai Gambar */
    .sidebar {
      width: var(--sidebar-w); 
      background: var(--sidebar-bg);
      display: flex; 
      flex-direction: column;
      box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    }
    
    .sidebar-brand { 
      padding: 24px 20px; 
      background: var(--sidebar-brand-bg);
      text-align: center;
    }
    .sidebar-brand h1 { 
      font-size: 16px; 
      font-weight: 800; 
      color: #ffffff; 
      letter-spacing: 1px;
      line-height: 1.3;
    }
    
    .sidebar-nav { 
      padding: 15px 0; 
      flex: 1; 
      overflow-y: auto;
    }
    
    .nav-item {
      display: flex; 
      align-items: center; 
      justify-content: space-between;
      padding: 12px 20px;
      color: var(--sidebar-text); 
      text-decoration: none; 
      font-size: 13px;
      transition: 0.2s;
    }
    .nav-item-content {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .nav-item:hover { 
      background: rgba(255,255,255,0.05); 
      color: var(--sidebar-text-hover); 
    }
    .nav-item.active { 
      background: var(--sidebar-item-active); 
      color: #ffffff; 
      border-left: 4px solid #ef4444; /* Indikator garis di kiri menu induk */
    }

    /* Submenu Styling */
    .submenu {
      background: rgba(0, 0, 0, 0.2);
      padding-left: 15px;
    }
    .submenu .nav-item {
      padding: 10px 20px;
      font-size: 12.5px;
    }
    .submenu .nav-item.active {
      border-left: none;
      color: #38bdf8; /* Highlight biru muda untuk submenu aktif */
      font-weight: 600;
    }

    /* Main Content */
    .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
    
    .topbar {
      height: 60px; background: var(--surface); border-bottom: 1px solid var(--border);
      display: flex; align-items: center; justify-content: space-between; padding: 0 30px;
    }

    .content { padding: 30px; overflow-y: auto; }

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
  <?php include '../config/sidebar.php'; ?>

      <div>
        <a href="#" class="nav-item active">
          <div class="nav-item-content">
            <i class="ti ti-truck"></i> 
            <span>Armada</span>
          </div>
          <i class="ti ti-chevron-down" style="font-size: 12px;"></i>
        </a>
        <div class="submenu">
          <a href="armada.php" class="nav-item">
            <div class="nav-item-content" style="padding-left: 10px;">
              <span>Armada</span>
            </div>
          </a>
          <a href="status_penanganan.php" class="nav-item active">
            <div class="nav-item-content" style="padding-left: 10px;">
              <span>Status Penanganan</span>
            </div>
          </a>
        </div>
      </div>

      <a href="#" class="nav-item">
        <div class="nav-item-content">
          <i class="ti ti-tool"></i> 
          <span>Sarpras</span>
        </div>
        <i class="ti ti-chevron-down" style="font-size: 12px;"></i>
      </a>

      <a href="#" class="nav-item">
        <div class="nav-item-content">
          <i class="ti ti-file-text"></i> 
          <span>Laporan</span>
        </div>
      </a>

      <a href="#" class="nav-item">
        <div class="nav-item-content">
          <i class="ti ti-settings"></i> 
          <span>Pengaturan</span>
        </div>
      </a>

    </nav>
  </aside>

  <main class="main">
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
        
        <div class="pen-card">
          <div class="pen-header">
            <span class="id-tag">KJD-084</span>
            <div style="text-align: right;">
              <span style="font-size: 11px; color: #64748b;">Mulai: 13:22</span>
            </div>
          </div>
          <div class="title">Kebakaran Permukiman Padat</div>
          <div class="location"><i class="ti ti-map-pin"></i> Kelurahan Cimparuh, Pariaman Tengah</div>
          
          <div class="progress-container">
            <div class="progress-info">
              <span>Proses Pemadaman</span>
              <span>65%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" style="width: 65%; background: var(--red);"></div>
            </div>
          </div>

          <div class="unit-info">
            <span>Unit: <strong>DBK-01, TNG-01</strong></span>
            <a href="#" style="color: var(--blue); text-decoration: none; font-weight: 600;">Detail <i class="ti ti-arrow-right"></i></a>
          </div>
        </div>

        <div class="pen-card">
          <div class="pen-header">
            <span class="id-tag">KJD-083</span>
            <div style="text-align: right;">
              <span style="font-size: 11px; color: #64748b;">Mulai: 12:55</span>
            </div>
          </div>
          <div class="title">Penyelamatan Hewan (Animal Rescue)</div>
          <div class="location"><i class="ti ti-map-pin"></i> Jl. Sudirman No. 12</div>
          
          <div class="progress-container">
            <div class="progress-info">
              <span>Evakuasi</span>
              <span>85%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" style="width: 85%; background: var(--blue-mid);"></div>
            </div>
          </div>

          <div class="unit-info">
            <span>Unit: <strong>RSC-01</strong></span>
            <a href="#" style="color: var(--blue); text-decoration: none; font-weight: 600;">Detail <i class="ti ti-arrow-right"></i></a>
          </div>
        </div>

      </div>
    </div>
  </main>
</div>

</body>
</html>