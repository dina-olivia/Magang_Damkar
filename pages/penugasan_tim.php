<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penugasan Personil (SPT) - Damkar v.1</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased flex" x-data="{ modalTerbuka: false }">

    <?php 
    // Memanggil file koneksi database Anda
    require_once __DIR__ . '/../config/koneksi.php'; 

    // Pastikan koneksi berhasil dibuat sebelum query
    if (!isset($conn) || !$conn) {
        die('Koneksi database tidak tersedia.');
    }

    // Ambil data laporan yang statusnya masih 'masuk' untuk pilihan di dropdown modal
    $laporan_masuk = mysqli_query($conn, "SELECT * FROM laporan_kejadian WHERE status = 'masuk'");

    // Ambil data SPT yang digabung (JOIN) dengan laporan_kejadian agar info lokasi muncul di tabel
    $query_spt = "SELECT spt.*, laporan_kejadian.nomor_laporan, laporan_kejadian.lokasi, laporan_kejadian.jenis_kejadian 
                  FROM spt 
                  JOIN laporan_kejadian ON spt.laporan_kejadian_id = laporan_kejadian.id 
                  ORDER BY spt.waktu_keberangkatan DESC";
    $tampil_spt = mysqli_query($conn, $query_spt);
    ?>

    <aside class="w-64 bg-[#111827] text-gray-400 min-h-screen flex flex-col flex-shrink-0 border-r border-gray-800">
        <div class="bg-[#c21807] p-4 flex items-center gap-3">
            <img src="../assets/img/logo_damkar.png" alt="Logo" width="140" height="80">
            <div>
                <h2 class="text-white font-extrabold text-sm tracking-wider leading-tight">DAMKAR</h2>
                <h2 class="text-white font-extrabold text-sm tracking-wider leading-tight">PADANG</h2>
            </div>
        </div>

        <nav class="flex-1 p-3 space-y-1 text-xs overflow-y-auto" x-data="{ menuKejadian: false, menuOperasional: true, menuPersonil: false }">
            
            <a href="../index.php" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg hover:bg-gray-800 hover:text-white transition">
                <i class="bi bi-speedometer2 text-base"></i> Dashboard
            </a>

            <div>
                <button @click="menuKejadian = !menuKejadian" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg hover:bg-gray-800 hover:text-white transition">
                    <span class="flex items-center gap-2.5">
                        <i class="bi bi-megaphone text-base"></i> Manajemen Kejadian
                    </span>
                    <i class="bi bi-chevron-down text-[10px]" :class="menuKejadian ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="menuKejadian" x-cloak class="pl-8 mt-1 space-y-1 bg-gray-900/40 rounded-lg py-1">
                    <a href="#" class="block px-3 py-2 hover:text-white">Input Laporan</a>
                    <a href="#" class="block px-3 py-2 hover:text-white">Monitoring Kejadian</a>
                    <a href="#" class="block px-3 py-2 hover:text-white">Detail Kejadian</a>
                    <a href="#" class="block px-3 py-2 hover:text-white">Timeline Kronologi</a>
                </div>
            </div>

            <div>
                <button @click="menuOperasional = !menuOperasional" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg hover:bg-gray-800 hover:text-white transition">
                    <span class="flex items-center gap-2.5">
                        <i class="bi bi-clipboard-check text-base"></i> Operasional
                    </span>
                    <i class="bi bi-chevron-down text-[10px]" :class="menuOperasional ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="menuOperasional" class="pl-8 mt-1 space-y-1 bg-gray-900/40 rounded-lg py-1">
                    <a href="penugasan.php" class="block px-3 py-2 text-white bg-blue-600/20 font-semibold rounded-md">Penugasan Tim</a>
                    <a href="#" class="block px-3 py-2 hover:text-white">Monitoring Armada</a>
                    <a href="#" class="block px-3 py-2 hover:text-white">Status Penanganan</a>
                    <a href="#" class="block px-3 py-2 hover:text-white">Riwayat Penugasan</a>
                </div>
            </div>

            <div>
                <button @click="menuPersonil = !menuPersonil" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg hover:bg-gray-800 hover:text-white transition">
                    <span class="flex items-center gap-2.5">
                        <i class="bi bi-people text-base"></i> Personil
                    </span>
                    <i class="bi bi-chevron-down text-[10px]" :class="menuPersonil ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="menuPersonil" x-cloak class="pl-8 mt-1 space-y-1 bg-gray-900/40 rounded-lg py-1">
                    <a href="#" class="block px-3 py-2 hover:text-white">Data Personil</a>
                    <a href="#" class="block px-3 py-2 hover:text-white">Penempatan Pos</a>
                    <a href="#" class="block px-3 py-2 hover:text-white">Jadwal Piket</a>
                </div>
            </div>

            <a href="../logout.php" class="flex items-center gap-2.5 px-3 py-2.5 text-red-400 rounded-lg hover:bg-red-950/30 transition">
                <i class="bi bi-box-arrow-left text-base"></i> Keluar
            </a>
        </nav>
    </aside>

    <main class="flex-1 p-6 min-h-screen overflow-x-hidden">
        
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
                    <i class="bi bi-file-earmark-text text-blue-600"></i> Penugasan Personil (SPT)
                </h1>
                <p class="text-sm text-gray-500 mt-1">Manajemen tugas dan pergerakan regu di lapangan</p>
            </div>
            <button @click="modalTerbuka = true" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-medium shadow-sm transition flex items-center gap-2">
                <span>+</span> Buat SPT Baru
            </button>
        </div>

        <div class="bg-white border border-blue-600 rounded-2xl p-4 flex justify-between items-center shadow-sm mb-8">
            <div class="flex items-center gap-4">
                <div class="bg-blue-50 p-3.5 rounded-2xl text-blue-600 text-2xl border border-blue-100">
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
                    <p class="text-lg font-bold text-blue-600">3 <span class="text-gray-400 font-normal text-xs">/ 15</span></p>
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
                <input type="text" placeholder="Cari No. SPT atau Alamat Kejadian..." class="w-full bg-white border border-gray-100 rounded-xl pl-11 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20">
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
                    <?php endif; ?>

                    <?php while($row = mysqli_fetch_assoc($tampil_spt)): ?>
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-4">
                            <span class="bg-blue-50 text-blue-600 font-bold px-2.5 py-1 rounded-md text-xs border border-blue-100">
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
                                <p class="flex items-center gap-1"><i class="bi bi-people text-gray-400"></i> 4 Petugas (Danru: A. Supriadi)</p>
                                <p class="flex items-center gap-1"><i class="bi bi-truck text-gray-400"></i> 1 Armada Unit (🚒 <?= $row['nama_regu']; ?>)</p>
                            </div>
                        </td>
                        <td class="p-4 text-xs text-gray-500 whitespace-nowrap">
                            <?= $row['waktu_keberangkatan']; ?>
                        </td>
                        <td class="p-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold <?= $row['status'] == 'berangkat' ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-gray-100 text-gray-600' ?>">
                                <?= $row['status']; ?>
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center justify-center gap-3">
                                <button class="text-gray-400 hover:text-gray-600 text-base" title="Cetak Surat Perintah">
                                    <i class="bi bi-printer"></i>
                                </button>
                                <button class="bg-gray-950 hover:bg-gray-800 text-white text-xs font-medium px-3 py-1.5 rounded-lg shadow-sm tracking-wide transition">
                                    Monitor
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div x-show="modalTerbuka" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" x-cloak x-transition>
            <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-xl" @click.away="modalTerbuka = false">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Buat Surat Perintah Tugas</h2>
                
                <form action="penugasan.php" method="POST">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Laporan Kejadian</label>
                        <select name="laporan_kejadian_id" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
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
                        <select name="nama_regu" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                            <option value="">-- Pilih Regu Damkar --</option>
                            <option value="REGU ALPHA">REGU ALPHA</option>
                            <option value="REGU BRAVO">REGU BRAVO</option>
                            <option value="REGU CHARLIE">REGU CHARLIE</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" @click="modalTerbuka = false" class="px-4 py-2 border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50">Batal</button>
                        <button type="submit" name="kirim_tim" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium shadow-sm">Kirim Tim</button>
                    </div>
                </form>
            </div>
        </div>

    </main>

</body>
</html>

<?php
// =========================================================================
// PROSES LOGIKA BACKEND PHP JIKA FORM SUBMIT (LETAK DI BAWAH SEKALI)
// =========================================================================
if (isset($_POST['kirim_tim'])) {
    $laporan_id = $_POST['laporan_kejadian_id'];
    $nama_regu   = $_POST['nama_regu'];

    // 1. Mengatur nomor urut SPT otomatis secara dinamis (Contoh: SPT-2026-001)
    $tahun = date('Y');
    $query_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM spt");
    $data_count  = mysqli_fetch_assoc($query_count);
    $next_id     = $data_count['total'] + 1;
    $nomor_spt   = "SPT-" . $tahun . "-" . str_pad($next_id, 3, "0", STR_PAD_LEFT);

    // 2. Query insert data tugas regu baru ke database
    $query_insert = "INSERT INTO spt (nomor_spt, laporan_kejadian_id, nama_regu, status) 
                     VALUES ('$nomor_spt', '$laporan_id', '$nama_regu', 'berangkat')";
    
    if (mysqli_query($conn, $query_insert)) {
        // 3. Update otomatis status record di tabel laporan kejadian menjadi 'diproses'
        mysqli_query($conn, "UPDATE laporan_kejadian SET status = 'diproses' WHERE id = '$laporan_id'");
        
        echo "<script>alert('Tim Berhasil Ditugaskan!'); window.location='penugasan.php';</script>";
    } else {
        echo "<script>alert('Gagal menugaskan tim: " . mysqli_error($conn) . "');</script>";
    }
}
?>