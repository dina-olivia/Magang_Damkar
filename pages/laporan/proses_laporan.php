<?php
// proses_laporan.php — menerima POST dari modal, simpan ke DB, redirect ke laporan.php

include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['simpan_laporan'])) {
    header("Location: laporan.php");
    exit;
}

// ── Ambil & bersihkan input ────────────────────────────────
$nomor_laporan = trim($_POST['nomor_laporan'] ?? '');
$tanggal = trim($_POST['tanggal'] ?? '');
$pelapor = trim($_POST['pelapor'] ?? '');
$no_hp = trim($_POST['no_hp'] ?? '');
$jenis_kejadian = trim($_POST['jenis_kejadian'] ?? '');
$lokasi = trim($_POST['lokasi'] ?? '');
$latitude = trim($_POST['latitude'] ?? '') ?: null;
$longitude = trim($_POST['longitude'] ?? '') ?: null;
$deskripsi = trim($_POST['deskripsi'] ?? '');
$status = 'masuk'; // status awal selalu 'masuk'

// ── Validasi field wajib ───────────────────────────────────
if (!$nomor_laporan || !$tanggal || !$pelapor || !$no_hp || !$jenis_kejadian || !$lokasi) {
    header("Location: laporan.php?error=" . urlencode("Semua field bertanda wajib harus diisi."));
    exit;
}

// ── Cek nomor laporan duplikat ─────────────────────────────
$cek = mysqli_prepare($conn, "SELECT id FROM laporan_kejadian WHERE nomor_laporan = ?");
mysqli_stmt_bind_param($cek, 's', $nomor_laporan);
mysqli_stmt_execute($cek);
mysqli_stmt_store_result($cek);

if (mysqli_stmt_num_rows($cek) > 0) {
    mysqli_stmt_close($cek);
    header("Location: laporan.php?error=" . urlencode("Nomor laporan sudah terdaftar. Silakan refresh dan coba lagi."));
    exit;
}
mysqli_stmt_close($cek);

// ── Simpan ke database ─────────────────────────────────────
$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO laporan_kejadian
       (nomor_laporan, tanggal, pelapor, no_hp, jenis_kejadian, lokasi, latitude, longitude, deskripsi, status)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    'ssssssssss',
    $nomor_laporan,
    $tanggal,
    $pelapor,
    $no_hp,
    $jenis_kejadian,
    $lokasi,
    $latitude,
    $longitude,
    $deskripsi,
    $status
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    // Redirect ke laporan.php — ORDER BY id DESC memastikan data terbaru muncul paling atas
    header("Location: laporan.php?success=1");
    exit;
} else {
    $err = mysqli_error($conn);
    mysqli_stmt_close($stmt);
    header("Location: laporan.php?error=" . urlencode("Gagal menyimpan ke database: " . $err));
    exit;
}
?>
<?php
// proses_laporan.php — menerima POST dari modal, simpan ke DB, redirect ke laporan.php

include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['simpan_laporan'])) {
    header("Location: laporan.php");
    exit;
}

// ── Ambil & bersihkan input ────────────────────────────────
$nomor_laporan = trim($_POST['nomor_laporan'] ?? '');
$tanggal = trim($_POST['tanggal'] ?? '');
$pelapor = trim($_POST['pelapor'] ?? '');
$no_hp = trim($_POST['no_hp'] ?? '');
$jenis_kejadian = trim($_POST['jenis_kejadian'] ?? '');
$lokasi = trim($_POST['lokasi'] ?? '');
$latitude = trim($_POST['latitude'] ?? '') ?: null;
$longitude = trim($_POST['longitude'] ?? '') ?: null;
$deskripsi = trim($_POST['deskripsi'] ?? '');
$status = 'masuk'; // status awal selalu 'masuk'

// ── Validasi field wajib ───────────────────────────────────
if (!$nomor_laporan || !$tanggal || !$pelapor || !$no_hp || !$jenis_kejadian || !$lokasi) {
    header("Location: laporan.php?error=" . urlencode("Semua field bertanda wajib harus diisi."));
    exit;
}

// ── Cek nomor laporan duplikat ─────────────────────────────
$cek = mysqli_prepare($conn, "SELECT id FROM laporan_kejadian WHERE nomor_laporan = ?");
mysqli_stmt_bind_param($cek, 's', $nomor_laporan);
mysqli_stmt_execute($cek);
mysqli_stmt_store_result($cek);

if (mysqli_stmt_num_rows($cek) > 0) {
    mysqli_stmt_close($cek);
    header("Location: laporan.php?error=" . urlencode("Nomor laporan sudah terdaftar. Silakan refresh dan coba lagi."));
    exit;
}
mysqli_stmt_close($cek);

// ── Simpan ke database ─────────────────────────────────────
$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO laporan_kejadian
       (nomor_laporan, tanggal, pelapor, no_hp, jenis_kejadian, lokasi, latitude, longitude, deskripsi, status)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    'ssssssssss',
    $nomor_laporan,
    $tanggal,
    $pelapor,
    $no_hp,
    $jenis_kejadian,
    $lokasi,
    $latitude,
    $longitude,
    $deskripsi,
    $status
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    // Redirect ke laporan.php — ORDER BY id DESC memastikan data terbaru muncul paling atas
    header("Location: laporan.php?success=1");
    exit;
} else {
    $err = mysqli_error($conn);
    mysqli_stmt_close($stmt);
    header("Location: laporan.php?error=" . urlencode("Gagal menyimpan ke database: " . $err));
    exit;
}
?>