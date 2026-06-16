<?php
// PERBAIKAN: Naik 2 tingkat folder agar sukses membaca file koneksi.php
include '../../config/koneksi.php';

// Pastikan ada parameter ID laporan yang dikirim melalui URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: laporan_kejadian.php?error=ID laporan tidak valid.");
    exit;
}

$id_laporan = intval($_GET['id']);
$waktu_sekarang = date('Y-m-d H:i:s');

// Mulai Database Transaction agar perubahan di kedua tabel aman & sinkron
mysqli_begin_transaction($conn);

try {
    // 1. Update status laporan_kejadian menjadi 'selesai'
    $stmt_laporan = mysqli_prepare($conn, "UPDATE laporan_kejadian SET status = 'selesai' WHERE id = ?");
    mysqli_stmt_bind_param($stmt_laporan, "i", $id_laporan);
    mysqli_stmt_execute($stmt_laporan);

    // 2. Update waktu_selesai dan status di tabel penugasan yang berhubungan dengan laporan ini
    // Mengubah status penugasan dari 'dalam penanganan' menjadi 'selesai'
    $status_tugas_baru = 'selesai';
    $stmt_penugasan = mysqli_prepare($conn, "UPDATE penugasan SET waktu_selesai = ?, status = ? WHERE laporan_id = ? AND status = 'dalam penanganan'");
    mysqli_stmt_bind_param($stmt_penugasan, "ssi", $waktu_sekarang, $status_tugas_baru, $id_laporan);
    mysqli_stmt_execute($stmt_penugasan);

    // Jika kedua query di atas sukses, terapkan ke database
    mysqli_commit($conn);

    header("Location: laporan_kejadian.php?success=Laporan berhasil diselesaikan dan waktu penanganan telah dicatat.");
    exit;

} catch (Exception $e) {
    // Jika salah satu proses gagal, batalkan semua perubahan
    mysqli_rollback($conn);
    header("Location: laporan_kejadian.php?error=Gagal menyelesaikan laporan: " . urlencode($e->getMessage()));
    exit;
}
?>