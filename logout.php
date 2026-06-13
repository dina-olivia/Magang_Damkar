<?php
session_start();
include 'config/koneksi.php';

// Catat log sebelum logout
if (isset($_SESSION['user_id'])) {
    $uid = (int)$_SESSION['user_id'];
    $akt = mysqli_real_escape_string($conn, "Logout dari sistem");
    mysqli_query($conn, "INSERT INTO log_aktivitas (user_id, aktivitas) VALUES ($uid, '$akt')");
}

// Hapus semua data session
$_SESSION = [];

// Hapus cookie session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

header("Location: login.php?logout=1");
exit;
?>