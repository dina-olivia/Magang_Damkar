<?php
include '../config/koneksi.php';

// Memastikan parameter ID terkirim lewat URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Menjalankan query hapus data
    $sql = "DELETE FROM armada WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Data armada berhasil dihapus!');
                window.location.href = 'armada.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data: " . mysqli_error($conn) . "');
                window.location.href = 'armada.php';
              </script>";
    }
} else {
    // Jika diakses tanpa parameter ID, lempar kembali ke halaman utama
    header("Location: armada.php");
    exit;
}
?>