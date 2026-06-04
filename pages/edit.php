<?php
include '../config/koneksi.php';

// Mengambil data lama berdasarkan ID yang dikirim melalui URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = mysqli_query($conn, "SELECT * FROM armada WHERE id = '$id'");
    $data = mysqli_fetch_array($query);

    if (!$data) {
        echo "<script>alert('Data tidak ditemukan!'); window.location.href='armada.php';</script>";
        exit;
    }
} else {
    header("Location: armada.php");
    exit;
}

// Proses update data ketika tombol Simpan ditekan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $plat_no = $_POST['plat_no'];
    $jenis_kendaraan = $_POST['jenis_kendaraan'];
    $merek_kendaraan = $_POST['merek_kendaraan'];
    $tahun_kendaraan = $_POST['tahun_kendaraan'];
    $status_kendaraan = $_POST['status_kendaraan'];
    $lokasi_kendaraan = $_POST['lokasi_kendaraan'];

    $sql = "UPDATE armada SET 
            plat_no = '$plat_no', 
            jenis = '$jenis_kendaraan', 
            merk = '$merek_kendaraan', 
            tahun = '$tahun_kendaraan', 
            status = '$status_kendaraan',
            lokasi_kendaraan = '$lokasi_kendaraan'
            WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Data armada berhasil diperbarui!'); window.location.href='armada.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Armada – Damkar v.1</title>
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" />

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
      --blue: #185FA5; --blue-bg: #E6F1FB;
      --sidebar-w: 260px;
      --radius-lg: 14px;
      
      --sidebar-bg: #111c34;
      --sidebar-brand-bg: #b91c1c;
      --sidebar-text: #94a3b8;
      --sidebar-text-hover: #ffffff;
      --sidebar-item-active: #1e293b;
    }

    body { font-family: 'Segoe UI', system-ui, sans-serif; background: var(--bg); color: var(--text); overflow: hidden; }
    .app { display: flex; height: 100vh; }

    /* Sidebar Styles */
    .sidebar { width: var(--sidebar-w); background: var(--sidebar-bg); display: flex; flex-direction: column; box-shadow: 2px 0 10px rgba(0,0,0,0.1); }
    .sidebar-brand { padding: 24px 20px; background: var(--sidebar-brand-bg); text-align: center; }
    .sidebar-brand h1 { font-size: 16px; font-weight: 800; color: #ffffff; letter-spacing: 1px; line-height: 1.3; }
    .sidebar-nav { padding: 15px 0; flex: 1; overflow-y: auto; }
    .nav-item { display: flex; align-items: center; justify-content: space-between; padding: 12px 20px; color: var(--sidebar-text); text-decoration: none; font-size: 13px; transition: 0.2s; }
    .nav-item-content { display: flex; align-items: center; gap: 12px; }
    .nav-item:hover { background: rgba(255,255,255,0.05); color: var(--sidebar-text-hover); }
    .nav-item.active { background: var(--sidebar-item-active); color: #ffffff; border-left: 4px solid #ef4444; }
    .submenu { background: rgba(0, 0, 0, 0.2); padding-left: 15px; }
    .submenu .nav-item { padding: 10px 20px; font-size: 12.5px; }

    /* Main & Form Panel */
    .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
    .topbar { height: 60px; background: var(--surface); border-bottom: 1px solid var(--border); display: flex; align-items: center; padding: 0 30px; }
    .content { padding: 30px; overflow-y: auto; }
    .page-header { margin-bottom: 20px; }
    
    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 8px; font-size: 13px; font-weight: 500; text-decoration: none; transition: 0.2s; border: none; cursor: pointer; }
    .btn-primary { background: var(--blue); color: #fff; }
    .btn-secondary { background: var(--surface2); color: var(--text); border: 1px solid var(--border); }
    .btn-secondary:hover { background: #e2e8f0; }
    
    .panel { background: var(--surface); border-radius: var(--radius-lg); border: 1px solid var(--border); padding: 30px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); max-width: 700px; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; }
    .form-control { width: 100%; padding: 10px 14px; font-size: 14px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; transition: 0.2s; color: var(--text); }
    .form-control:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(24, 95, 165, 0.15); }
    .form-actions { display: flex; gap: 12px; margin-top: 25px; border-top: 1px solid var(--border); padding-top: 20px; }
  </style>
</head>
<body>

<div class="app">
  <aside class="sidebar">
    <div class="sidebar-brand">
      <h1>DAMKAR<br>PADANG</h1>
    </div>
    <nav class="sidebar-nav">
      <a href="index.php" class="nav-item">
        <div class="nav-item-content"><i class="ti ti-layout-dashboard"></i> <span>Dashboard</span></div>
      </a>
      <a href="#" class="nav-item"><div class="nav-item-content"><i class="ti ti-alert-circle"></i> <span>Manajemen Kejadian</span></div></a>
      <a href="#" class="nav-item"><div class="nav-item-content"><i class="ti ti-clipboard-list"></i> <span>Operasional</span></div><i class="ti ti-chevron-down" style="font-size: 12px;"></i></a>
      <a href="#" class="nav-item"><div class="nav-item-content"><i class="ti ti-users"></i> <span>Personil</span></div><i class="ti ti-chevron-down" style="font-size: 12px;"></i></a>
      <div>
        <a href="#" class="nav-item active">
          <div class="nav-item-content"><i class="ti ti-truck"></i> <span>Armada</span></div>
          <i class="ti ti-chevron-down" style="font-size: 12px;"></i>
        </a>
        <div class="submenu">
          <a href="armada.php" class="nav-item active"><div class="nav-item-content" style="padding-left: 10px;"><span>Armada</span></div></a>
          <a href="status_penanganan.php" class="nav-item"><div class="nav-item-content" style="padding-left: 10px;"><span>Status Penanganan</span></div></a>
        </div>
      </div>
      <a href="#" class="nav-item"><div class="nav-item-content"><i class="ti ti-tool"></i> <span>Sarpras</span></div><i class="ti ti-chevron-down" style="font-size: 12px;"></i></a>
      <a href="#" class="nav-item"><div class="nav-item-content"><i class="ti ti-file-text"></i> <span>Laporan</span></div></a>
      <a href="#" class="nav-item"><div class="nav-item-content"><i class="ti ti-settings"></i> <span>Pengaturan</span></div></a>
    </nav>
  </aside>

  <main class="main">
    <header class="topbar">
      <div class="topbar-left"><h2 style="font-size: 16px; font-weight: 600;">Manajemen Armada</h2></div>
    </header>

    <div class="content">
      <div class="page-header">
        <div>
          <h1 style="font-size: 20px; font-weight: 700;">Edit Data Unit</h1>
          <p style="font-size: 13px; color: #64748b; margin-top: 4px;">Ubah rincian informasi teknis armada pemadam kebakaran</p>
        </div>
      </div>

      <div class="panel">
        <form action="" method="post">
          <div class="form-group">
            <label for="plat_no">Nomor Plat (Kode Unit)</label>
            <input type="text" id="plat_no" class="form-control" name="plat_no" value="<?php echo $data['plat_no']; ?>" required>
          </div>
          
          <div class="form-group">
            <label for="jenis_kendaraan">Jenis Kendaraan</label>
            <input type="text" id="jenis_kendaraan" class="form-control" name="jenis_kendaraan" value="<?php echo $data['jenis']; ?>" required>
          </div>
          
          <div class="form-group">
            <label for="merek_kendaraan">Merek / Model Kendaraan</label>
            <input type="text" id="merek_kendaraan" class="form-control" name="merek_kendaraan" value="<?php echo $data['merk']; ?>" required>
          </div>
          
          <div class="form-group">
            <label for="tahun_kendaraan">Tahun Kendaraan</label>
            <input type="number" id="tahun_kendaraan" class="form-control" name="tahun_kendaraan" value="<?php echo $data['tahun']; ?>" required>
          </div>
          
          <div class="form-group">
            <label for="status_kendaraan">Status Kendaraan</label>
            <select id="status_kendaraan" class="form-control" name="status_kendaraan">
              <option value="Siaga" <?php if($data['status'] == 'Siaga' || $data['status'] == 'SIAP') echo 'selected'; ?>>Siaga</option>
              <option value="Tugas" <?php if($data['status'] == 'Tugas' || $data['status'] == 'DIGUNAKAN') echo 'selected'; ?>>Tugas</option>
              <option value="Perbaikan" <?php if($data['status'] == 'Perbaikan') echo 'selected'; ?>>Perbaikan</option>
              <option value="Rusak" <?php if($data['status'] == 'Rusak') echo 'selected'; ?>>Rusak</option>
            </select>
          </div>
          
          <div class="form-group">
            <label for="lokasi_kendaraan">Lokasi Pos / Kendaraan</label>
            <input type="text" id="lokasi_kendaraan" class="form-control" name="lokasi_kendaraan" value="<?php echo isset($data['lokasi_kendaraan']) ? $data['lokasi_kendaraan'] : ''; ?>" required>
          </div>
          
          <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy"></i> Simpan Perubahan</button>
            <a href="armada.php" class="btn btn-secondary">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </main>
</div>

</body>
</html>