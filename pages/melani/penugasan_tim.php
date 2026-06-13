<?php 
// =========================================================================
// 1. PROSES LOGIKA BACKEND PHP JIKA FORM SUBMIT (HARUS DI PALING ATAS)
// =========================================================================
require_once __DIR__ . '/../../config/koneksi.php'; 

// Pastikan koneksi berhasil dibuat sebelum melanjutkan
if (!isset($conn) || !$conn) {
    die('Koneksi database tidak tersedia.');
}

// Memeriksa request metode POST secara global
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['laporan_kejadian_id'])) {
    $laporan_id = mysqli_real_escape_string($conn, $_POST['laporan_kejadian_id']);
    $nama_regu   = mysqli_real_escape_string($conn, $_POST['nama_regu']);

    // Mengatur nomor urut SPT otomatis secara dinamis (Contoh: SPT-2026-001)
    $tahun = date('Y');
    $query_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM spt");
    $data_count  = mysqli_fetch_assoc($query_count);
    $next_id     = $data_count['total'] + 1;
    $nomor_spt   = "SPT-" . $tahun . "-" . str_pad($next_id, 3, "0", STR_PAD_LEFT);

    // Query insert data tugas regu baru ke database
    $query_insert = "INSERT INTO spt (nomor_spt, laporan_kejadian_id, nama_regu, status) 
                     VALUES ('$nomor_spt', '$laporan_id', '$nama_regu', 'berangkat')";
    
    if (mysqli_query($conn, $query_insert)) {
        // Update otomatis status record di tabel laporan kejadian menjadi 'proses'
        mysqli_query($conn, "UPDATE laporan_kejadian SET status = 'proses' WHERE id = '$laporan_id'");
        
        echo "<script>alert('Tim Berhasil Ditugaskan!'); window.location='penugasan_tim.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal menugaskan tim: " . mysqli_error($conn) . "');</script>";
    }
}

// =========================================================================
// 2. QUERY UNTUK MENAMPILKAN DATA
// =========================================================================
// Ambil data laporan yang statusnya masih 'masuk' untuk pilihan di dropdown modal
$laporan_masuk = mysqli_query($conn, "SELECT * FROM laporan_kejadian WHERE status = 'masuk'");

// Ambil data SPT yang digabung (JOIN) dengan laporan_kejadian
$query_spt = "SELECT spt.*, laporan_kejadian.nomor_laporan, laporan_kejadian.lokasi, laporan_kejadian.jenis_kejadian 
              FROM spt 
              JOIN laporan_kejadian ON spt.laporan_kejadian_id = laporan_kejadian.id 
              ORDER BY spt.waktu_keberangkatan DESC";
$tampil_spt = mysqli_query($conn, $query_spt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penugasan Personil (SPT) - Damkar v.1</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    
    <style>
        [x-cloak] { display: none !important; }
        
        .collapse.sub-menu.show {
            display: block !important;
            height: auto !important;
            visibility: visible !important;
        }

        #sidebar .sub-menu a.active {
            background-color: rgba(229, 62, 62, 0.1) !important;
            color: #e53e3e !important;
            font-weight: 600;
            border-left: 3px solid #e53e3e;
        }
    </style>
</head>
<body class="bg-gray-50">

    <div id="sidebar" class="shadow">
        <div class="sidebar-header">
            <img src="/MAGANG/Magang_Damkar/assets/img/logo_damkar.png" alt="Logo" width="140" height="80">
            <span class="fw-bold ms-2">DAMKAR PADANG</span>
        </div>

        <div class="sidebar-content">
            <div class="nav flex-column mt-2">
                <a href="../../index.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
                
                <a href="#menuManajemenKejadian" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-megaphone"></i> Manajemen Kejadian</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuManajemenKejadian">
                    <a href="../input_laporan.php">Input Laporan</a>
                    <a href="../monitoring_kejadian.php">Monitoring Kejadian</a>
                    <a href="../detail_kejadian.php">Detail Kejadian</a>
                </div>

                <a href="#menuOperasional" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center" aria-expanded="true">
                    <span><i class="bi bi-clipboard-check"></i> Operasional</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu show" id="menuOperasional">
                    <a href="penugasan_tim.php" class="active">Penugasan Tim</a>
                    <a href="../monitoring_armada.php">Monitoring Armada</a>
                    <a href="../status_penanganan.php">Status Penanganan</a>
                    <a href="../riwayat_penugasan.php">Riwayat Penugasan</a>
                </div>

                <a href="#menuPersonil" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-people"></i> Personil</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuPersonil">
                    <a href="personil.php">Data Personil</a>
                    <a href="penempatan_pos.php">Penempatan Pos</a>
                    <a href="jadwal_piket.php">Jadwal Piket</a>
                    <a href="riwayat_tugas.php">Riwayat Tugas</a>
                </div>

                <a href="../armada.php"><i class="bi bi-truck"></i> Armada</a>

                <a href="#menuSarpras" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-tools"></i> Sarpras</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuSarpras">
                    <a href="../sarpras.php">Data Sarpras</a>
                    <a href="../master_bidang.php">Master Bidang</a>
                    <a href="../master_kategori.php">Master Kategori</a>
                </div>

                <a href="../dina/laporan.php"><i class="bi bi-file-earmark-text"></i> Laporan</a>
                <a href="../pengaturan.php"><i class="bi bi-gear"></i> Pengaturan</a>

                <a href="../../logout.php" class="mt-4 text-danger"><i class="bi bi-box-arrow-left"></i> Keluar</a>
            </div>
        </div>
    </div>

    <div id="main-content" x-data="{ modalTerbuka: false }">
        <div class="p-6">
            
            <div class="flex justify-end items-center mb-6 gap-4">
                <button class="text-gray-400 hover:text-gray-600"><i class="bi bi-moon text-lg"></i></button>
                <button class="text-gray-400 hover:text-gray-600 relative"><i class="bi bi-bell text-lg"></i></button>
                <div class="flex items-center gap-2 border-l pl-4 border-gray-200">
                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-bold"><i class="bi bi-person"></i></div>
                    <span class="text-xs font-semibold text-gray-700">Super Admin</span>
                    <i class="bi bi-chevron-down text-[10px] text-gray-400"></i>
                </div>
            </div>

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        <i class="bi bi-file-earmark-text text-red-600"></i> Penugasan Personil (SPT)
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">Manajemen tugas dan pergerakan regu di lapangan</p>
                </div>
                <button @click="modalTerbuka = true" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl font-medium shadow-sm transition flex items-center gap-2">
                    <span>+</span> Buat SPT Baru
                </button>
            </div>

            <div class="bg-white border border-red-600 rounded-2xl p-4 flex justify-between items-center shadow-sm mb-8">
                <div class="flex items-center gap-4">
                    <div class="bg-red-50 p-3.5 rounded-2xl text-red-600 text-2xl border border-red-100">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg font-bold text-gray-900">REGU BRAVO</h2>
                            <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wide">On Duty</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                            <i class="bi bi-clock"></i> Pagi (07:30 - 15:30) | Pos Pusat (Padang)
                        </p>
                        <p class="text-[11px] text-gray-400 mt-0.5">Komandan: A. Supriadi</p>
                    </div>
                </div>
                <div class="flex gap-8 text-right pr-4">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 tracking-wider">PERSONIL</p>
                        <p class="text-lg font-bold text-red-600">3 <span class="text-gray-400 font-normal text-xs">/ 15</span></p>
                        <p class="text-[9px] text-gray-400">Ready</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 tracking-wider">ARMADA</p>
                        <p class="text-lg font-bold text-orange-500">1 <span class="text-gray-400 font-normal text-xs">/ 4</span></p>
                        <p class="text-[9px] text-gray-400">Ready</p>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <h3 class="text-sm font-bold text-gray-800 flex items-center gap-1">
                    <i class="bi bi-geo-alt text-red-500"></i> Penugasan Lapangan Saat Ini
                </h3>
            </div>

            <div class="flex gap-4 mb-4">
                <div class="relative flex-1">
                    <i class="bi bi-search absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                    <input type="text" placeholder="Cari No. SPT atau Alamat Kejadian..." class="w-full bg-white border border-gray-100 rounded-xl pl-11 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20">
                </div>
                <select class="bg-white border border-gray-100 rounded-xl px-4 py-2.5 text-sm text-gray-600 focus:outline-none w-48">
                    <option>Semua Status</option>
                    <option>berangkat</option>
                    <option>tiba</option>
                    <option>selesai</option>
                </select>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-wider">
                            <th class="p-4 w-32">NO. SPT</th>
                            <th class="p-4">DETAIL KEJADIAN</th>
                            <th class="p-4">KEKUATAN TIM</th>
                            <th class="p-4">WAKTU</th>
                            <th class="p-4">STATUS</th>
                            <th class="p-4 text-center w-40">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700 divide-y divide-gray-50">
                        <?php if(mysqli_num_rows($tampil_spt) == 0): ?>
                            <tr>
                                <td colspan="6" class="p-12 text-center text-gray-400">
                                    Belum ada data penugasan lapangan saat ini.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php while($row = mysqli_fetch_assoc($tampil_spt)): ?>
                                <?php $kekuatan = ['total' => 0, 'danru' => '-']; ?>
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="p-4">
                                        <span class="bg-red-50 text-red-600 font-bold px-2.5 py-1 rounded-md text-xs border border-red-100">
                                            <?= $row['nomor_spt']; ?>
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <p class="font-bold text-gray-900 text-sm"><?= $row['lokasi']; ?></p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            <?= ucfirst($row['jenis_kejadian']); ?> <span class="text-gray-300 mx-1">|</span> Ref: <?= $row['nomor_laporan']; ?>
                                        </p>
                                    </td>
                                    
                                    <td class="p-4">
                                        <div class="text-xs text-gray-600 space-y-0.5">
                                            <p class="flex items-center gap-1">
                                                <i class="bi bi-people text-gray-400"></i> 
                                                <?= $kekuatan['total']; ?> Petugas (Danru: <?= $kekuatan['danru']; ?>)
                                            </p>
                                            <p class="flex items-center gap-1">
                                                <i class="bi bi-truck text-gray-400"></i> 
                                                1 Armada Unit (🚒 <?= $row['nama_regu']; ?>)
                                            </p>
                                        </div>
                                    </td>
                                    
                                    <td class="p-4 text-xs text-gray-500 whitespace-nowrap">
                                        <?= $row['waktu_keberangkatan']; ?>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold <?= $row['status'] == 'berangkat' ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-gray-100 text-gray-600' ?>">
                                            <?= $row['status']; ?>
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex items-center justify-center gap-3">
                                            <button class="text-gray-400 hover:text-red-600 text-base transition" title="Cetak Surat Perintah">
                                                <i class="bi bi-printer"></i>
                                            </button>
                                            <button class="bg-red-600 hover:bg-red-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg shadow-sm tracking-wide transition">
                                                Monitor
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="modalTerbuka" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" x-cloak x-transition>
            <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-xl" @click.away="modalTerbuka = false">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Buat Surat Perintah Tugas</h2>
                
                <form action="penugasan_tim.php" method="POST">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Laporan Kejadian</label>
                        <select name="laporan_kejadian_id" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-white focus:ring-2 focus:ring-red-500 focus:outline-none" required>
                            <option value="">-- Pilih Kejadian Aktif --</option>
                            <?php while($lap = mysqli_fetch_assoc($laporan_masuk)): ?>
                                <option value="<?= $lap['id']; ?>">
                                    <?= $lap['nomor_laporan']; ?> - <?= $lap['lokasi']; ?> (<?= $lap['jenis_kejadian']; ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tugaskan Regu</label>
                        <select name="nama_regu" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-white focus:ring-2 focus:ring-red-500 focus:outline-none" required>
                            <option value="">-- Pilih Regu Damkar --</option>
                            <option value="REGU ALPHA">REGU ALPHA</option>
                            <option value="REGU BRAVO">REGU BRAVO</option>
                            <option value="REGU CHARLIE">REGU CHARLIE</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" @click="modalTerbuka = false" class="px-4 py-2 border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-medium shadow-sm transition">Kirim Tim</button>
                    </div>
                </form>
            </div>
        </div>
    </div> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bundle.min.js"></script>
</body>
</html>