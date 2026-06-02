<?php
// proses_selesai.php — ubah status laporan dari 'proses' menjadi 'selesai'

include '../config/koneksi.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id > 0) {
    $stmt = mysqli_prepare($conn, "UPDATE laporan_kejadian SET status = 'selesai' WHERE id = ? AND status = 'proses'");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: laporan.php?success=1");
} else {
    header("Location: laporan.php");
}
exit;
?>