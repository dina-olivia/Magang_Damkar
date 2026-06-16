<?php
include '../../config/koneksi.php';

$id = $_GET['id'];

if (!$conn) {
    die('Database connection failed');
}

$query = mysqli_query($conn, "DELETE FROM sarpras WHERE id_sarpras='$id'");

if ($query) {
    echo "<script>
            alert('Data berhasil dihapus!');
            window.location='sarpras.php';
          </script>";
} else {
    echo "<script>
            alert('Data gagal dihapus!');
            window.location='sarpras.php';
          </script>";
}
?>