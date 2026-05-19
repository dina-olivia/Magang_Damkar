<?php
require_once __DIR__ . '/../../config/koneksi.php';

$menu = $_GET['menu'] ?? '';
$redirect = $menu . '.php'; // Halaman kembali otomatis sesuai nama menu awal

// 1. Pengalihan khusus untuk menu personil (Tabel menggunakan NIP string)
if ($menu === 'personil') { 
    $menu  = 'tbl_daftar'; 
    $nip   = mysqli_real_escape_string($koneksi, $_GET['nip'] ?? '');
    $where = "nip = '$nip'";
}
// 2. Pengalihan khusus untuk menu jadwal_piket (Menggunakan kombinasi Tanggal & Shift)
else if ($menu === 'jadwal_piket') {
    $tgl   = mysqli_real_escape_string($koneksi, $_GET['tanggal'] ?? '');
    $shift = mysqli_real_escape_string($koneksi, $_GET['shift'] ?? '');
    $where = "tanggal = '$tgl' AND shift = '$shift'";
}
// 3. Menu lainnya secara otomatis menggunakan ID (Angka)
else {
    $id    = intval($_GET['id'] ?? 0);
    $where = "id = $id";
}

// EKSEKUSI UTAMA (Mengecek apakah nama tabel menu dan parameter WHERE-nya sudah siap)
if (!empty($menu) && !empty($where) && ($menu === 'tbl_daftar' || $menu === 'jadwal_piket' || intval($_GET['id'] ?? 0) > 0)) {
    
    // Query DELETE otomatis menggunakan variabel $where yang fleksibel
    $query = mysqli_query($koneksi, "DELETE FROM $menu WHERE $where");
    
    if ($query) {
        // Jika dari tbl_daftar, kembalikan ke personil.php. Jika lainnya, sesuai nama tabel/menu.
        $halaman_kembali = ($menu === 'tbl_daftar') ? 'personil.php' : $redirect;
        echo "<script>alert('Data berhasil dihapus!'); window.location.href = '$halaman_kembali';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal menghapus data!'); window.history.back();</script>";
        exit;
    }
} else {
    echo "<script>alert('Akses tidak valid!'); window.history.back();</script>";
    exit;
}
?>