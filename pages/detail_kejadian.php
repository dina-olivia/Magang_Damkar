<?php
// --- KONEKSI DATABASE ---
$host = "localhost";
$user = "root";
$pass = "";
$db   = "app_damkar"; 

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if (!isset($_GET['no_lp']) || empty($_GET['no_lp'])) {
    echo "<script>alert('Silahkan pilih laporan terlebih dahulu!'); window.location='monitoring_kejadian.php';</script>";
    exit;
}

$no_lp = mysqli_real_escape_string($conn, $_GET['no_lp']);
$query = mysqli_query($conn, "SELECT * FROM laporan_kejadian WHERE nomor_laporan = '$no_lp'");
$data  = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='monitoring_kejadian.php';</script>";
    exit;
}
$lokasi    = isset($data['lokasi']) ? $data['lokasi'] : 'Lokasi tidak spesifik';
$deskripsi = isset($data['deskripsi']) ? $data['deskripsi'] : 'Tidak ada rincian tambahan.';

// --- PERBAIKAN DI SINI ---
$status       = strtolower($data['status']);
$waktu_awal   = date('H:i', strtotime($data['tanggal'])); // Jam Laporan Masuk (Contoh: 10:00)
$jam_dasar    = date('G', strtotime($data['tanggal']));   // Ambil angka jam saja (0-23)
$menit_dasar  = date('i', strtotime($data['tanggal']));   // Ambil angka menit saja (0-59)
?>

<!DOCTYPE html>
<html lang="id"> 
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan #<?= $no_lp ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <style>
        :root { --damkar-red: #e63946; --damkar-dark: #9f0925; }
        body { background-color: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .main-wrapper { margin-left: 260px; padding: 40px; }
        
        /* Judul Besar Sesuai Request */
        .title-large { font-weight: 700; font-size: 2.5rem; color: #dc3545; letter-spacing: -1px; }
        
        /* Tombol Kembali Kapsul Merah di Kanan */
        .btn-kembali {
            background-color: var(--damkar-red);
            color: white;
            padding: 12px 35px;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            transition: 0.3s;
            border: none;
        }
        .btn-kembali:hover {
            background-color: #c1272d;
            color: white;
            transform: translateX(-5px);
            box-shadow: 0 10px 20px rgba(248, 2, 23, 0.3);
        }

        .card-main { border: none; border-radius: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.05); background: white; overflow: hidden; }
        .hero-info { background: linear-gradient(135deg, #a00428 0%, #457b9d 100%); color: white; padding: 40px; }
        
        /* Timeline Styling with Time Label */
        .timeline { position: relative; padding-left: 20px; }
        .timeline:before { content: ""; position: absolute; left: 4px; top: 0; bottom: 0; width: 2px; background: #e2e8f0; }
        .timeline-item { position: relative; margin-bottom: 30px; padding-left: 30px; }
        .timeline-marker { 
            position: absolute; left: -4px; top: 0; width: 18px; height: 18px; 
            border-radius: 50%; background: #cbd5e1; border: 3px solid white; z-index: 2; 
        }
        .marker-active { background: #22c55e; box-shadow: 0 0 10px rgba(34, 197, 94, 0.4); }
        .time-badge { font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 2px; }
        
        .info-grid { background: #f1f5f9; border-radius: 20px; padding: 25px; }
        
        @media (max-width: 992px) { .main-wrapper { margin-left: 0; padding: 20px; } }
    </style>
</head>
<body>

    <?php include '../config/sidebar.php'; ?>

    <div class="main-wrapper w-100">
        <div class="container-fluid">
            
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h1 class="title-large m-0">DETAIL PENANGANAN</h1>
                    <p class="text-muted fw-medium">Informasi lengkap laporan kejadian di lapangan</p>
                </div>
                <a href="monitoring_kejadian.php" class="btn-kembali">
                    <i class="bi bi-arrow-left me-2"></i> Kembali
                </a>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card-main mb-4">
                        <div class="hero-info">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-white text-primary mb-2 rounded-pill px-3 py-2 fw-bold" style="font-size: 0.7rem;">
                                        STATUS: <?= strtoupper($data['status']) ?>
                                    </span>
                                    <h2 class="fw-bold m-0"><?= strtoupper($data['jenis_kejadian']) ?></h2>
                                    <small class="opacity-75">ID Laporan: <?= $no_lp ?></small>
                                </div>
                                <i class="bi bi-shield-fire" style="font-size: 4rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                        
                        <div class="card-body p-4 p-md-5">
                            <div class="row g-4 mb-4">
                                <div class="col-md-4">
                                    <div class="info-grid">
                                        <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 0.65rem;">Pelapor</small>
                                        <div class="fw-bold"><?= $data['pelapor'] ?></div>
                                        <div class="text-danger small fw-bold"><?= $data['no_hp'] ?></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-grid">
                                        <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 0.65rem;">Lokasi TKP</small>
                                        <div class="fw-bold"><?= $lokasi ?></div>
                                        <div class="text-muted small">Kota Padang</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-grid">
                                        <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 0.65rem;">Tanggal Kejadian</small>
                                        <div class="fw-bold"><?= date('d F Y', strtotime($data['tanggal'])) ?></div>
                                        <div class="text-muted small">Pukul <?= $waktu_awal ?> WIB</div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 rounded-4" style="background: #fff5f5; border: 1px dashed #fecaca;">
                                <h6 class="fw-bold text-danger mb-2"><i class="bi bi-chat-square-text-fill me-2"></i>Deskripsi Kronologi</h6>
                                <p class="m-0 text-dark" style="line-height: 1.8; text-align: justify;">
                                    <?= $deskripsi ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card-main p-4">
                        <h5 class="fw-bold mb-4 d-flex align-items-center">
                            <i class="bi bi-clock-history text-danger me-2"></i> Progres Lapangan
                        </h5>
                        
                        <div class="timeline" id="timeline-container" 
     data-jam="<?= $jam_dasar ?>" 
     data-menit="<?= $menit_dasar ?>" 
     data-status="<?= $status ?>">
    
    <div class="timeline-item">
        <div class="timeline-marker marker-active"></div>
        <span class="time-badge"><?= $waktu_awal ?> WIB</span>
        <h6 class="fw-bold mb-1">Laporan Masuk</h6>
        <p class="small text-muted mb-0">Informasi diterima oleh sistem Pusdalops.</p>
    </div>

    <div class="timeline-item">
        <div class="timeline-marker <?= ($status == 'proses' || $status == 'selesai') ? 'marker-active' : '' ?>"></div>
        <span class="time-badge" id="waktu-proses">--:--</span>
        <h6 class="fw-bold mb-1 <?= ($status == 'masuk') ? 'text-muted' : '' ?>">Penanganan</h6>
        <p class="small text-muted mb-0">Unit armada terdekat dikerahkan ke lokasi.</p>
    </div>

    <div class="timeline-item mb-0">
        <div class="timeline-marker <?= ($status == 'selesai') ? 'marker-active' : '' ?>"></div>
        <span class="time-badge" id="waktu-selesai">--:--</span>
        <h6 class="fw-bold mb-1 <?= ($status != 'selesai') ? 'text-muted' : '' ?>">Selesai</h6>
        <p class="small text-muted mb-0">Kondisi dinyatakan aman (Hijau).</p>
    </div>
</div>

                    </div>
                </div>
            </div>

        </div>
    </div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const container = document.getElementById('timeline-container');
    const status = container.getAttribute('data-status');
    
    // Ambil angka jam dan menit murni dari PHP
    const jamAwal = parseInt(container.getAttribute('data-jam'), 10);
    const menitAwal = parseInt(container.getAttribute('data-menit'), 10);
    
    // Fungsi untuk menghitung penambahan menit dengan aman (menghindari error overload 60 menit)
    const hitungWaktuOtomatis = (menitTambah) => {
        let totalMenit = menitAwal + menitTambah;
        let jamBaru = jamAwal + Math.floor(totalMenit / 60);
        let menitBaru = totalMenit % 60;
        
        // Jika jam melewati pukul 23:59 malam, reset kembali ke 00
        jamBaru = jamBaru % 24; 
        
        // Format string agar selalu 2 digit (contoh: "05" bukan "5")
        const strJam = String(jamBaru).padStart(2, '0');
        const strMenit = String(menitBaru).padStart(2, '0');
        
        return `${strJam}:${strMenit} WIB`;
    };

    const elProses = document.getElementById('waktu-proses');
    const elSelesai = document.getElementById('waktu-selesai');

    // Tampilkan jam otomatis hanya jika status database sesuai
    if (status === 'proses' || status === 'selesai') {
        elProses.innerText = hitungWaktuOtomatis(5); // Otomatis tambah 5 Menit
    } else {
        elProses.innerText = "--:--";
    }

    if (status === 'selesai') {
        elSelesai.innerText = hitungWaktuOtomatis(45); // Otomatis tambah 45 Menit
    } else {
        elSelesai.innerText = "--:--";
    }
});
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>