<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Data Armada – Damkar v.1</title>
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" />

  <style>
    /* Mengambil core style agar konsisten dengan Dashboard */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --bg: #f4f5f7;
      --surface: #ffffff;
      --surface2: #f0f1f5;
      --border: rgba(0,0,0,.1);
      --text: #1a1b1e;
      --text2: #6b7280;
      --blue: #185FA5; --blue-bg: #E6F1FB; --blue-mid: #378ADD;
      --green: #3B6D11; --green-bg: #EAF3DE;
      --amber: #854F0B; --amber-bg: #FAEEDA;
      --red: #A32D2D; --red-bg: #FCEBEB;
      --sidebar-w: 250px;
      --radius-lg: 14px;
    }

    body { font-family: 'Segoe UI', system-ui, sans-serif; background: var(--bg); color: var(--text); overflow: hidden; }

    .app { display: flex; height: 100vh; }

    /* Sidebar */
    .sidebar {
      width: var(--sidebar-w); background: var(--surface);
      border-right: 1px solid var(--border); display: flex; flex-direction: column;
    }
    .sidebar-brand { padding: 24px 20px; border-bottom: 1px solid var(--border); }
    .sidebar-brand h1 { font-size: 18px; font-weight: 700; color: var(--blue); }
    
    .sidebar-nav { padding: 15px 10px; flex: 1; }
    .nav-section { font-size: 10px; font-weight: 700; color: var(--text2); text-transform: uppercase; padding: 10px; letter-spacing: 1px; }
    .nav-item {
      display: flex; align-items: center; gap: 12px; padding: 10px;
      color: var(--text2); text-decoration: none; border-radius: 8px; margin-bottom: 4px; transition: 0.2s;
    }
    .nav-item:hover { background: var(--surface2); color: var(--text); }
    .nav-item.active { background: var(--blue-bg); color: var(--blue); font-weight: 600; }

    /* Main Content */
    .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
    
    .topbar {
      height: 60px; background: var(--surface); border-bottom: 1px solid var(--border);
      display: flex; align-items: center; justify-content: space-between; padding: 0 30px;
    }

    .content { padding: 30px; overflow-y: auto; }

    /* Header Tabel & Filter */
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    
    .btn {
      display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px;
      border-radius: 8px; font-size: 13px; font-weight: 500; text-decoration: none; transition: 0.2s; border: none; cursor: pointer;
    }
    .btn-primary { background: var(--blue); color: #fff; }
    
    /* Panel Tabel */
    .panel { background: var(--surface); border-radius: var(--radius-lg); border: 1px solid var(--border); overflow: hidden; }
    
    table { width: 100%; border-collapse: collapse; }
    th { text-align: left; background: #f9fafb; padding: 15px 20px; font-size: 11px; color: var(--text2); text-transform: uppercase; border-bottom: 1px solid var(--border); }
    td { padding: 15px 20px; border-bottom: 1px solid var(--border); font-size: 13px; vertical-align: middle; }
    tr:hover { background-color: #fcfcfc; }

    /* Status Pill */
    .pill { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; }
    .pill-siaga { background: var(--green-bg); color: var(--green); }
    .pill-tugas { background: var(--amber-bg); color: var(--amber); }

    .action-btn { color: var(--blue); text-decoration: none; margin-right: 15px; font-size: 13px; display: inline-flex; align-items: center; gap: 4px; }
    .action-btn:hover { text-decoration: underline; }
    .delete-btn { color: var(--red); }
  </style>
</head>
<body>

<div class="app">
  <aside class="sidebar">
    <div class="sidebar-brand">
      <h1>DAMKAR v.1</h1>
    </div>
    <nav class="sidebar-nav">
      <div class="nav-section">Menu Utama</div>
      <a href="index.php" class="nav-item">
        <i class="ti ti-layout-dashboard"></i> Dashboard
      </a>
      <a href="armada.php" class="nav-item active">
        <i class="ti ti-truck"></i> Armada
      </a>
      <a href="status_penanganan.php" class="nav-item">
        <i class="ti ti-shield-check"></i> Status Penanganan
      </a>
    </nav>
  </aside>

  <main class="main">
    <header class="topbar">
      <div class="topbar-left">
        <h2 style="font-size: 16px;">Manajemen Armada</h2>
      </div>
    </header>

    <div class="content">
      <div class="page-header">
        <div>
          <h1 style="font-size: 20px;">Daftar Unit Armada</h1>
          <p style="font-size: 13px; color: var(--text2);">Total 18 unit terdaftar dalam sistem</p>
        </div>
        <a href="tambah_armada.php" class="btn btn-primary">
          <i class="ti ti-plus"></i> Tambah Unit Baru
        </a>
      </div>

      <div class="panel">
        <table>
          <thead>
            <tr>
              <th>No</th>
              <th>Kode</th>
              <th>Jenis Kendaraan</th>
              <th>Merk / Model</th>
              <th>Tahun</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td><strong>DBK-01</strong></td>
              <td>Mobil Pemadam Besar</td>
              <td>Hino Ranger</td>
              <td>2019</td>
              <td><span class="pill pill-tugas">BERTUGAS</span></td>
              <td>
                <a href="#" class="action-btn"><i class="ti ti-edit"></i> Edit</a>
                <a href="#" class="action-btn delete-btn"><i class="ti ti-trash"></i> Hapus</a>
              </td>
            </tr>
            <tr>
              <td>2</td>
              <td><strong>DBK-02</strong></td>
              <td>Mobil Pemadam Sedang</td>
              <td>Isuzu Elf</td>
              <td>2021</td>
              <td><span class="pill pill-siaga">SIAGA</span></td>
              <td>
                <a href="#" class="action-btn"><i class="ti ti-edit"></i> Edit</a>
                <a href="#" class="action-btn delete-btn"><i class="ti ti-trash"></i> Hapus</a>
              </td>
            </tr>
            <tr>
              <td>3</td>
              <td><strong>RSC-01</strong></td>
              <td>Mobil Rescue / SAR</td>
              <td>Toyota Hilux</td>
              <td>2023</td>
              <td><span class="pill pill-siaga">SIAGA</span></td>
              <td>
                <a href="#" class="action-btn"><i class="ti ti-edit"></i> Edit</a>
                <a href="#" class="action-btn delete-btn"><i class="ti ti-trash"></i> Hapus</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</div>

</body>
</html>