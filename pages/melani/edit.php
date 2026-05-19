<?php
require_once __DIR__ . '/../../config/koneksi.php';

// 1. Ambil data dari URL menggunakan null coalescing
$menu = $_GET['menu'] ?? '';
$id   = intval($_GET['id'] ?? 0);

// 2. Pengalihan otomatis jika mendeteksi keyword personil
if ($menu === 'personil') { 
    $menu = 'tbl_daftar'; 
}

// 3. VALIDASI: Pastikan parameter menu TIDAK kosong dan ID lebih dari 0
if (empty($menu) || $id <= 0) {
    echo "<script>alert('Akses tidak valid! Parameter menu atau ID salah.'); window.history.back();</script>";
    exit;
}

// 4. Jalankan Query dengan nama tabel yang sudah divalidasi
$result = mysqli_query($koneksi, "SELECT * FROM `$menu` WHERE id = $id");

if (!$result) {
    // Jika nama tabel ngawur / tidak ada di database, cegah fatal error
    echo "<script>alert('Terjadi kesalahan pada sistem data!'); window.history.back();</script>";
    exit;
}

$data = mysqli_fetch_assoc($result);

// 5. Validasi jika data tidak ditemukan di database
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.history.back();</script>";
    exit;
}

$nama_tampilan = ($menu === 'tbl_daftar') ? 'Personil' : ucfirst(str_replace('_', ' ', $menu));
?>