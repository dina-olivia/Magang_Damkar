<?php
/**
 * config/auth.php
 * Sertakan file ini di AWAL setiap halaman yang butuh login.
 *
 * Cara pakai:
 *   include '../config/auth.php';              // halaman di /pages/
 *   include '../../config/auth.php';           // halaman di /pages/laporan/
 *   require_role('admin');                     // tambahkan ini jika halaman khusus admin
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hitung base_url dinamis berdasarkan kedalaman direktori
if (!isset($base_url)) {
    $path = $_SERVER['PHP_SELF'];
    $root_folder = '/Magang_DAMKAR';   // sesuaikan nama folder project kamu
    $clean_path = str_replace($root_folder, '', $path);
    $levels = substr_count(ltrim($clean_path, '/'), '/');
    $base_url = $levels > 0 ? str_repeat('../', $levels) : '';
}

// ── Cek apakah sudah login ──────────────────────────────────
if (!isset($_SESSION['user_id'])) {
    header("Location: " . $base_url . "login.php");
    exit;
}

// ── Session timeout: 8 jam ─────────────────────────────────
$timeout = 8 * 60 * 60;
if (isset($_SESSION['login_at']) && (time() - $_SESSION['login_at']) > $timeout) {
    session_destroy();
    header("Location: " . $base_url . "login.php?error=timeout");
    exit;
}
$_SESSION['login_at'] = time(); // refresh timer

// ── Helper function: cek role ───────────────────────────────
/**
 * Panggil require_role('admin') di halaman yang hanya boleh diakses admin.
 * Petugas yang mencoba akses akan diarahkan ke dashboard dengan pesan error.
 */
function require_role(string $required_role): void
{
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $required_role) {
        global $base_url;
        header("Location: " . $base_url . "index.php?error=akses_ditolak");
        exit;
    }
}

/**
 * Cek apakah user yang sedang login adalah admin.
 */
function is_admin(): bool
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Ambil nama user yang sedang login.
 */
function current_user_name(): string
{
    return $_SESSION['nama'] ?? 'Pengguna';
}

/**
 * Ambil role user yang sedang login.
 */
function current_role(): string
{
    return $_SESSION['role'] ?? '';
}
?>