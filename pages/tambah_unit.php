<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tambah Unit – Damkar v.1</title>
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" />

  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --bg: #f4f5f7;
      --surface: #ffffff;
      --border: rgba(0,0,0,.1);
      --text: #1a1b1e;
      --text2: #6b7280;
      --blue: #185FA5;
      --sidebar-w: 250px;
      --radius-lg: 14px;
    }

    body { font-family: 'Segoe UI', system-ui, sans-serif; background: var(--bg); color: var(--text); overflow: hidden; }
    .app { display: flex; height: 100vh; }

    /* Sidebar Konsisten */
    .sidebar { width: var(--sidebar-w); background: var(--surface); border-right: 1px solid var(--border); display: flex; flex-direction: column; }
    .sidebar-brand { padding: 24px 20px; border-bottom: 1px solid var(--border); }
    .sidebar-brand h1 { font-size: 18px; font-weight: 700; color: var(--blue); }
    .sidebar-nav { padding: 15px 10px; flex: 1; }
    .nav-item { display: flex; align-items: center; gap: 12px; padding: 10px; color: var(--text2); text-decoration: none; border-radius: 8px; margin-bottom: 4px; }
    .nav-item.active { background: #E6F1FB; color: var(--blue); font-weight: 600; }

    /* Main Area */
    .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
    .topbar { height: 60px; background: var(--surface); border-bottom: 1px solid var(--border); display: flex; align-items: center; padding: 0 30px; }
    .content { padding: 30px; overflow-y: auto; display: flex; justify-content: center; }

    /* Form Card */
    .form-card { background: var(--surface); width: 100%; max-width: 600px; padding: 30px; border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .form-header { margin-bottom: 25px; }
    .form-header h2 { font-size: 20px; font-weight: 600; }
    
    .form-group { margin-bottom: 18px; }
    .form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px; }
    
    .form-control {
      width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border);
      font-size: 14px; color: var(--text); transition: border-color 0.2s;
    }
    .form-control:focus { outline: none; border-color: var(--blue); }

    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }

    .btn-group { display: flex; gap: 10px; margin-top: 10px; }
    .btn { padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; transition: 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
    .btn-save { background: var(--blue); color: #fff; }
    .btn-cancel { background: #f0f1f5; color: var(--text2); }
    .btn:hover { opacity: 0.9; }
  </style>
</head>
<body>

<div class="app">
  <aside class="sidebar">
    <div class="sidebar-brand"><h1>DAMKAR v.1</h1></div>
    <nav class="sidebar-nav">
      <a href="index.php" class="nav-item"><i class="ti ti-layout-dashboard"></i> Dashboard</a>
      <a href="armada.php" class="nav-item active"><i class="ti ti-truck"></i> Armada</a>
      <a href="status_penanganan.php" class="nav-item"><i class="ti ti-shield-check"></i> Status Penanganan</a>
    </nav>
  </aside>

  <main class="main">
    <header class="topbar"><h2 style="font-size: 16px;">Unit Baru</h2></header>

    <div class="content">
      <div class="form-card">
        <div class="form-header">
          <h2>Tambah Unit Armada</h2>
          <p style="font-size: 13px; color: var(--text2);">Masukkan detail kendaraan operasional baru</p>
        </div>

        <form action="proses_tambah.php" method="POST">
          <div class="form-group">
            <label for="kode">Kode Unit</label>
            <input type="text" id="kode" name="kode" class="form-control" placeholder="Contoh: DBK-05" required>
          </div>

          <div class="form-group">
            <label for="jenis">Jenis Kendaraan</label>
            <select id="jenis" name="jenis" class="form-control" required>
              <option value="">-- Pilih Jenis --</option>
              <option value="Mobil Pemadam Besar">Mobil Pemadam Besar</option>
              <option value="Mobil Pemadam Sedang">Mobil Pemadam Sedang</option>
              <option value="Mobil Rescue">Mobil Rescue / SAR</option>
              <option value="Mobil Tangki Suply">Mobil Tangki Suply</option>
            </select>
          </div>

          <div class="form-group">
            <label for="merk">Merk / Model</label>
            <input type="text" id="merk" name="merk" class="form-control" placeholder="Contoh: Hino Ranger / Isuzu Elf" required>
          </div>

          <div class="grid-2">
            <div class="form-group">
              <label for="tahun">Tahun Pengadaan</label>
              <input type="number" id="tahun" name="tahun" class="form-control" placeholder="Contoh: 2024" required>
            </div>
            <div class="form-group">
              <label for="kapasitas">Kapasitas Air (Liter)</label>
              <input type="text" id="kapasitas" name="kapasitas" class="form-control" placeholder="Contoh: 5000">
            </div>
          </div>

          <div class="form-group">
            <label for="status">Status Awal</label>
            <select id="status" name="status" class="form-control">
              <option value="siaga">Siaga (Ready)</option>
              <option value="perbaikan">Dalam Perbaikan</option>
            </select>
          </div>

          <div class="btn-group">
            <button type="submit" class="btn btn-save"><i class="ti ti-device-floppy"></i> Simpan Unit</button>
            <a href="armada.php" class="btn btn-cancel">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </main>
</div>

</body>
</html>