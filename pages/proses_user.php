<?php

// Aktifkan tampilan error untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/koneksi.php';

// Pastikan variabel koneksi tersedia
if (!isset($conn)) {
    if (isset($koneksi)) {
        $conn = $koneksi;
    } elseif (isset($mysqli)) {
        $conn = $mysqli;
    } elseif (isset($connection)) {
        $conn = $connection;
    }
}

if (!isset($conn)) {
    die('Database connection tidak ditemukan. Periksa config/koneksi.php');
}

// Cek apakah form dikirim melalui tombol simpan_user
if (isset($_POST['simpan_user'])) {
    
    // Ambil data dari POST
    $nama     = $_POST['nama'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $role     = $_POST['role'];
    $opd_id   = $_POST['opd_id'];

    // Gunakan prepared statement untuk keamanan (mencegah SQL Injection)
    $stmt = $conn->prepare("INSERT INTO user (nama, email, password, role, opd_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nama, $email, $password, $role,  $opd_id);

    if ($stmt->execute()) {
        // Jika berhasil, kembali ke manajemen_user.php dengan flag sukses
        header("Location: manajemen_user.php?success=1");
        exit();
    } else {
        // Jika gagal, tampilkan error
        echo "Gagal menyimpan data: " . $stmt->error;
    }

    $stmt->close();
} else {
    // Jika user mengakses halaman ini tanpa klik tombol simpan_user
    echo "Akses ditolak. Silakan kembali ke halaman <a href='manajemen_user.php'>Manajemen User</a>.";
}
?>