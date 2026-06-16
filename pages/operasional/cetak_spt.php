<?php
include '../../config/koneksi.php';

// Pastikan koneksi database tersedia dari file koneksi.php
if (!isset($conn) || !($conn instanceof mysqli)) {
    // Jika $conn belum di-set, coba buat koneksi langsung jika konfigurasi tersedia
    if (defined('DB_HOST') && defined('DB_USER') && defined('DB_PASS') && defined('DB_NAME')) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_errno) {
            die("Koneksi database gagal: " . $conn->connect_error);
        }
    } else {
        die("Koneksi database gagal. Pastikan file koneksi.php mengatur \$conn sebagai mysqli connection.");
    }
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Query dengan penanganan jika relasi tidak ditemukan
    $query = "SELECT spt.*, laporan_kejadian.lokasi, laporan_kejadian.jenis_kejadian 
              FROM spt 
              LEFT JOIN laporan_kejadian ON spt.laporan_kejadian_id = laporan_kejadian.id 
              WHERE spt.id = '$id'";
    
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
    } else {
        die("Data SPT tidak ditemukan.");
    }
} else {
    die("ID SPT tidak disertakan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Perintah Tugas - <?= htmlspecialchars($data['nomor_spt']); ?></title>
    <style>
        body { font-family: 'Times New Roman', serif; padding: 20px; line-height: 1.5; color: #000; }
        
        /* Kop Surat Bersih */
        .kop-surat { text-align: center; border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-surat h2 { margin: 0; text-transform: uppercase; font-size: 20px; }
        .kop-surat p { margin: 2px 0; font-size: 14px; }
        
        /* Judul Surat */
        .judul-surat { text-align: center; font-weight: bold; margin: 30px 0; }
        .garis-judul { border-bottom: 1px solid #000; width: 250px; margin: 5px auto; }
        
        .isi-surat { margin-top: 20px; }
        table.detail-tugas { width: 100%; margin-bottom: 30px; }
        table.detail-tugas td { padding: 5px 0; vertical-align: top; }
        .label { width: 200px; font-weight: bold; }

        .tanda-tangan { margin-top: 50px; float: right; width: 300px; text-align: center; }
        /* CSS UNTUK MENGHILANGKAN HEADER & FOOTER DARI BROWSER SAAT PRINT */
            @media print {
                @page {
            margin: 0; /* Menghilangkan margin default browser */
            size: auto;
                }
                body {
                    margin: 1.5cm; /* Memberikan margin manual agar tidak terpotong */
                }
            }
    </style>
</head>
<body onload="window.print()">

    <div class="kop-surat">
        <h2>PEMERINTAH KOTA PADANG</h2>
        <h2 style="font-size: 18px;">DINAS PEMADAM KEBAKARAN</h2>
        <p>Jl. Damkar No. 1, Kota Padang, Sumatera Barat</p>
    </div>

    <div class="judul-surat">
        SURAT PERINTAH TUGAS
        <div class="garis-judul"></div>
        Nomor: <?= htmlspecialchars($data['nomor_spt']); ?>
    </div>

    <div class="isi-surat">
        <p>Dengan ini memerintahkan kepada regu di bawah ini untuk melaksanakan tugas operasional:</p>
        
        <table class="detail-tugas">
            <tr><td class="label">Nama Regu</td><td>: <?= htmlspecialchars($data['nama_regu']); ?></td></tr>
            <tr><td class="label">Lokasi Kejadian</td><td>: <?= htmlspecialchars($data['lokasi'] ?? 'Data tidak tersedia'); ?></td></tr>
            <tr><td class="label">Jenis Kejadian</td><td>: <?= ucfirst(htmlspecialchars($data['jenis_kejadian'] ?? 'Data tidak tersedia')); ?></td></tr>
            <tr><td class="label">Waktu Keberangkatan</td><td>: <?= date('d F Y, H:i:s', strtotime($data['waktu_keberangkatan'])); ?></td></tr>
        </table>
    </div>

    <div class="tanda-tangan">
        <p>Padang, <?= date('d F Y'); ?></p>
        <p>Kepala Dinas Damkar Kota Padang</p>
        <br><br><br>
        <p><strong>( __________________________ )</strong></p>
    </div>

</body>
</html>