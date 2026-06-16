<?php
include '../../config/koneksi.php';

if (isset($_GET['id']) && isset($conn)) {
    $id = $_GET['id'];

    // Gunakan prepared statement agar aman
    $stmt = $conn->prepare("DELETE FROM armada WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus!'); window.location='armada.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data.'); window.location='armada.php';</script>";
    }
    $stmt->close();
} else {
    header("Location: armada.php");
}
?>