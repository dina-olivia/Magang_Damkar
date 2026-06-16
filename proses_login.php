<?php
session_start();
require_once __DIR__ . '/config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$email = mysqli_real_escape_string($conn, trim($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header("Location: login.php?error=empty");
    exit;
}

$query = "SELECT * FROM user WHERE email = '$email' LIMIT 1";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 0) {
    header("Location: login.php?error=invalid");
    exit;
}

$user = mysqli_fetch_assoc($result);

// Verifikasi password (MD5 sesuai DB Anda)
if (md5($password) === $user['password']) {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nama']    = $user['nama'];
    $_SESSION['role']    = $user['role'];
    
    header("Location: index.php");
    exit;
} else {
    header("Location: login.php?error=invalid");
    exit;
}
?>