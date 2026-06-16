<?php
// config/koneksi.php
$host = "localhost";
$user = "root";
$pass = "";
$db = "app_damkar";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set timezone agar waktu otomatis sesuai dengan lokasi Indonesia
date_default_timezone_set('Asia/Jakarta');
//
// --- 1. LOGIKA GENERATE NOMOR LAPORAN ---
$tgl_prefix = date('Ymd');
$check_lp = mysqli_query($conn, "SELECT nomor_laporan FROM laporan_kejadian WHERE nomor_laporan LIKE 'LP-$tgl_prefix-%' ORDER BY nomor_laporan DESC LIMIT 1");
$data_lp = mysqli_fetch_assoc($check_lp);

if ($data_lp) {
    $urut = (int) substr($data_lp['nomor_laporan'], -3) + 1;
} else {
    $urut = 1;
}
$no_lp_baru = "LP-" . $tgl_prefix . "-" . str_pad($urut, 3, "0", STR_PAD_LEFT);

// --- 2. PROSES SIMPAN DATA ---
if (isset($_POST['simpan'])) {
    $nomor_laporan = mysqli_real_escape_string($conn, $_POST['nomor_laporan']);

    // Logika Waktu
    $tanggal = !empty($_POST['tanggal']) ? $_POST['tanggal'] : date('Y-m-d H:i:s');
    $tanggal = mysqli_real_escape_string($conn, $tanggal);

    $pelapor = mysqli_real_escape_string($conn, $_POST['pelapor']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $jenis_kejadian = mysqli_real_escape_string($conn, $_POST['jenis_kejadian']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $status = 'Masuk';

    // Query INSERT tanpa latitude & longitude
    $query = "INSERT INTO laporan_kejadian (nomor_laporan, tanggal, pelapor, no_hp, lokasi, jenis_kejadian, deskripsi, status) 
              VALUES ('$nomor_laporan', '$tanggal', '$pelapor', '$no_hp', '$lokasi', '$jenis_kejadian', '$deskripsi', '$status')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Laporan Berhasil Disimpan!'); window.location='monitoring_kejadian.php';</script>";
    } else {
        echo "Error MySQL: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Laporan Kejadian - DAMKAR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">

    <style>
        :root {
            --primary-red: #dc3545;
            --soft-bg: #f4f7f6;
        }

        body {
            background-color: var(--soft-bg);
            font-family: 'Segoe UI', sans-serif;
        }

        .main-content {
            margin-left: 280px;
            transition: 0.3s;
        }

        .form-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border: none;
        }

        .card-header-gradient {
            background: linear-gradient(135deg, var(--primary-red), #ff4d5a);
            padding: 25px;
            color: white;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            padding: 12px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-red);
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.1);
            outline: none;
        }

        .section-title {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--primary-red);
            border-left: 4px solid var(--primary-red);
            padding-left: 10px;
            margin: 30px 0 20px 0;
            font-weight: bold;
        }

        .btn-save {
            background: var(--primary-red);
            border: none;
            padding: 12px 35px;
            border-radius: 12px;
            font-weight: 700;
            transition: 0.3s;
            color: white;
        }

        .btn-save:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
            background: #c82333;
            color: white;
        }

        @media (max-width: 992px) {
            .main-content {
                margin-left: 0;
            }
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
                <div class="collapse sub-menu show" id="menuManajemenKejadian">
                    <a href="input_laporan.php">Input Laporan</a>
                    <a href="monitoring_kejadian.php">Monitoring Kejadian</a>
                </div>

                <!-- Operasional (Aktif & Terbuka Otomatis) -->
                <a href="#menuOperasional" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center" aria-expanded="true">
                    <span><i class="bi bi-clipboard-check"></i> Operasional</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuOperasional">
                    <a href="../operasional/penugasan_tim.php" class="active">Penugasan Tim</a>
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
                    <a href="../laporan/laporan_kejadian.php">Laporan Kejadian</a>
                    <a href="../laporan/rekap_statistik.php">Rekap Statistik & Analisis</a>
                    <a href="../laporan/cetak_export.php">Cetak & Export Dokumen</a>
                </div>
                
                <a href="../pengaturan.php"><i class="bi bi-gear"></i> Pengaturan</a>
                <a href="../../logout.php" class="mt-4 text-danger"><i class="bi bi-box-arrow-left"></i> Keluar</a>
            </div>
        </div>
    </div>

        <div class="main-content flex-grow-1 p-4">
            <div class="container-fluid">
                <div class="form-card mx-auto" style="max-width: 900px;">
                    <div class="card-header-gradient d-flex justify-content-between align-items-center">
                        <div>
                            
                            <h4 class="m-0 fw-bold"><i class="bi bi-megaphone-fill me-2"></i> INPUT KEJADIAN BARU</h4>
                            <small class="opacity-75">Sistem Pelaporan DAMKAR</small>
                        </div>
                        <div class="badge bg-white text-danger p-2 px-3 rounded-pill fw-bold">
                            ID: <?= $no_lp_baru ?>
                        </div>
                    </div>

                    <div class="card-body p-5">
                        <form action="" method="POST">
                            <div class="section-title">Informasi Dasar</div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Nomor Laporan</label>
                                    <input type="text" name="nomor_laporan" class="form-control fw-bold text-danger"
                                        value="<?= $no_lp_baru ?>" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Waktu Kejadian</label>
                                    <input type="datetime-local" name="tanggal" id="waktu_kejadian" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Jenis Kejadian</label>
                                    <select name="jenis_kejadian" class="form-select" required>
                                        <option value="">-- Pilih Jenis --</option>
                                        <option value="Kebakaran">Kebakaran</option>
                                        <option value="Banjir">Banjir</option>
                                        <option value="Rescue">Rescue (Penyelamatan)</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                            </div>

                            <div class="section-title">Data Pelapor & Lokasi</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Pelapor</label>
                                    <input type="text" name="pelapor" class="form-control"
                                        placeholder="Nama Lengkap Pelapor" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">No. HP Pelapor</label>
                                    <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx"
                                        required>
                                </div>
                                <div class="col-12 mt-3">
                                    <label class="form-label">Alamat Lengkap Kejadian</label>
                                    <input type="text" name="lokasi" class="form-control"
                                        placeholder="Contoh: Jl. Merdeka No. 10, RT 01/RW 02..." required>
                                </div>
                            </div>

                            <div class="section-title">Detail Kronologi</div>
                            <div class="row">
                                <div class="col-12">
                                    <textarea name="deskripsi" class="form-control" rows="5"
                                        placeholder="Tuliskan deskripsi singkat kejadian di sini..."></textarea>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-3 mt-5">
                                <a href="monitoring_kejadian.php"
                                    class="btn btn-light px-4 border rounded-pill">Batal</a>
                                <button type="submit" name="simpan" class="btn btn-save shadow">
                                    <i class="bi bi-check2-circle me-1"></i> Simpan Laporan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-fill datetime-local
        window.onload = function () {
            const now = new Date();
            const tzOffset = now.getTimezoneOffset() * 60000;
            const localISOTime = (new Date(now - tzOffset)).toISOString().slice(0, 16);
            document.getElementById('waktu_kejadian').value = localISOTime;
        };
    </script>
</body>

</html>