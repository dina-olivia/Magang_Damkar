<?php
// 1. Koneksi ke Database
$conn = null;
include '../config/koneksi.php';

// Cek apakah variabel $conn sudah benar
if (!isset($conn) || !$conn) {
    die("Koneksi gagal! Periksa file config/koneksi.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 2. Ambil data dari form (Sesuaikan name di form dengan variabel ini)
    // Gunakan mysqli_real_escape_string agar aman dari hacker
    $pelapor = mysqli_real_escape_string($conn, $_POST['nama_pelapor']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['kontak_pelapor']);
    $jenis_kejadian = mysqli_real_escape_string($conn, $_POST['jenis_kejadian']);
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi_kejadian']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['waktu_lapor']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['keterangan']); // Sesuai kolom 'deskripsi' di foto

    // 3. Query Insert (Nama kolom disamakan persis dengan gambar phpMyAdmin kamu)
    $query = "INSERT INTO laporan_kejadian (pelapor, no_hp, jenis_kejadian, lokasi, tanggal, status, deskripsi) 
              VALUES ('$pelapor', '$no_hp', '$jenis_kejadian', '$lokasi', '$tanggal', '$status', '$deskripsi')";

    $simpan = mysqli_query($conn, $query);

    // 4. Cek hasil
    if ($simpan) {
        echo "<script>alert('Laporan berhasil masuk ke database!'); window.location='laporan.php';</script>";
    } else {
        // Jika masih error, ini akan memunculkan pesan error yang jelas dari MySQL
        echo "Gagal simpan data. Error: " . mysqli_error($conn);
    }
}
?>