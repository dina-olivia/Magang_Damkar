<?php
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Tangkap data dari form tambah_bidang
    $nama_bidang = mysqli_real_escape_string($koneksi, $_POST['nama_bidang']);
    $deskripsi   = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $urutan      = mysqli_real_escape_string($koneksi, $_POST['urutan']);

    // Query untuk memasukkan data ke tabel 'bidang'
    $query = "INSERT INTO bidang (nama_bidang, deskripsi, urutan) VALUES ('$nama_bidang', '$deskripsi', '$urutan')";
    
    if (mysqli_query($koneksi, $query)) {
        // Jika berhasil, langsung alihkan kembali ke halaman utama master bidang
        echo "<script>alert('Data bidang berhasil ditambahkan!'); window.location.href='master_bidang.php';</script>";
    } else {
        // Jika gagal
        echo "<script>alert('Gagal menambahkan data: " . mysqli_error($koneksi) . "'); window.location.href='tambah_bidang.php';</script>";
    }
} else {
    header('Location: master_bidang.php');
}
?>