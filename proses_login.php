<?php
session_start();
include_once __DIR__ . '/config/koneksi.php';

// Memastikan koneksi tersedia
if (!isset($conn)) {
    die('Database connection not established.');
}

define('BASE_URL', '/Magang/Magang_Damkar/');

// Mengambil dan membersihkan input
$email    = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

// Validasi jika input kosong
if (empty($email) || empty($password)) {
    header("Location: " . BASE_URL . "login.php?error=empty");
    exit;
}

// Mencegah SQL Injection
$email_safe = mysqli_real_escape_string($conn, $email);

// Query ke database
$query = mysqli_query($conn, "SELECT * FROM user WHERE email='$email_safe'");
$user  = mysqli_fetch_assoc($query);

if ($user) {
    // Logika Verifikasi Password:
    // 1. md5($password) === $user['password'] : Mendukung database lama Anda
    // 2. password_verify : Standar keamanan modern PHP
    
    $is_md5_match = (md5($password) === $user['password']);
    $is_hash_match = password_verify($password, $user['password']);

    if ($is_md5_match || $is_hash_match) {
        // Jika berhasil, buat sesi
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['nama']     = $user['nama'];
        $_SESSION['role']     = $user['role'];
        $_SESSION['login_at'] = time();

        header("Location: " . BASE_URL . "index.php");
        exit;
    }
}

// Jika user tidak ditemukan atau password salah
header("Location: " . BASE_URL . "login.php?error=invalid");
exit;
?>