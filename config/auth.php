<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ── Base URL menggunakan path absolut ──────────────────────
$root_folder = '/Magang/Magang_Damkar';  // ← sesuai URL browser

if (!isset($base_url)) {
    $path       = $_SERVER['PHP_SELF'];
    $clean_path = str_replace($root_folder, '', $path);
    $levels     = substr_count(ltrim($clean_path, '/'), '/');
    $base_url   = $levels > 0 ? str_repeat('../', $levels) : '';
}

// ── Cek apakah sudah login ─────────────────────────────────
if (!isset($_SESSION['user_id'])) {
    // Hindari redirect loop: jangan redirect kalau sudah di login.php
    $current = basename($_SERVER['PHP_SELF']);
    if ($current !== 'login.php') {
        header("Location: " . $base_url . "login.php");
        exit;
    }
}

// ── Session timeout: 8 jam ─────────────────────────────────
$timeout = 8 * 60 * 60;
if (isset($_SESSION['login_at']) && (time() - $_SESSION['login_at']) > $timeout) {
    session_destroy();
    header("Location: " . $base_url . "login.php?error=timeout");
    exit;
}
if (isset($_SESSION['user_id'])) {
    $_SESSION['login_at'] = time();
}

// ── Helper functions ───────────────────────────────────────
function require_role(string $required_role): void
{
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $required_role) {
        global $base_url;
        header("Location: " . $base_url . "index.php?error=akses_ditolak");
        exit;
    }
}

function is_admin(): bool
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function current_user_name(): string
{
    return $_SESSION['nama'] ?? 'Pengguna';
}

function current_role(): string
{
    return $_SESSION['role'] ?? '';
}
?>