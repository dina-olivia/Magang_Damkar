<?php
session_start();
include 'config/koneksi.php';

// Hanya terima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Validasi kosong
if (empty($email) || empty($password)) {
    header("Location: login.php?error=empty");
    exit;
}

// Cari user berdasarkan email
$email_safe = mysqli_real_escape_string($conn, $email);
$query = "SELECT * FROM user WHERE email = '$email_safe' LIMIT 1";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    header("Location: login.php?error=invalid&email=" . urlencode($email));
    exit;
}

$user = mysqli_fetch_assoc($result);

// ── Verifikasi password ──────────────────────────────────────
// Mendukung password_hash (bcrypt) maupun MD5 (legacy)
$password_valid = false;

if (password_verify($password, $user['password_hash'])) {
    // Modern: bcrypt
    $password_valid = true;

    // Upgrade otomatis jika masih MD5 di DB (tidak perlu tapi aman)
} elseif (md5($password) === $user['password_hash']) {
    // Legacy: MD5 — upgrade ke bcrypt sekaligus
    $new_hash = password_hash($password, PASSWORD_DEFAULT);
    $new_hash_safe = mysqli_real_escape_string($conn, $new_hash);
    mysqli_query($conn, "UPDATE user SET password_hash='$new_hash_safe' WHERE id={$user['id']}");
    $password_valid = true;
}

if (!$password_valid) {
    header("Location: login.php?error=invalid&email=" . urlencode($email));
    exit;
}

// ── Buat session ────────────────────────────────────────────
session_regenerate_id(true); // Cegah session fixation

$_SESSION['user_id'] = $user['id'];
$_SESSION['nama'] = $user['nama'];
$_SESSION['email'] = $user['email'];
$_SESSION['role'] = $user['role'];   // 'admin' atau 'petugas'
$_SESSION['opd_id'] = $user['opd_id'];
$_SESSION['login_at'] = time();

// ── Catat log aktivitas ─────────────────────────────────────
$uid = (int) $user['id'];
$akt = mysqli_real_escape_string($conn, "Login ke sistem");
mysqli_query($conn, "INSERT INTO log_aktivitas (user_id, aktivitas) VALUES ($uid, '$akt')");

// ── Redirect berdasarkan role ───────────────────────────────
header("Location: index.php");
exit;
?>