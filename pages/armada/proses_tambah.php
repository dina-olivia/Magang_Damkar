<?php
// Ensure correct include path and require the connection file
require_once __DIR__ . '/../../config/koneksi.php';

// Verify $conn is defined
if (!isset($conn) || !($conn instanceof mysqli)) {
    die('Database connection not available.');
}

// Ambil data dari form
$plat_no = $_POST['plat_no'];
$jenis   = $_POST['jenis'];
$merk    = $_POST['merk'];
$tahun   = $_POST['tahun'];
$status  = $_POST['status'];
$lokasi_kendaraan  = $_POST['lokasi_kendaraan'];

// Query insert
$sql = "INSERT INTO armada (plat_no, jenis, merk, tahun, status, lokasi_kendaraan) 
        VALUES ('$plat_no', '$jenis', '$merk', '$tahun', '$status', '$lokasi_kendaraan')";

if (mysqli_query($conn, $sql)) {
    // Berhasil → redirect ke halaman armada
    header("Location: armada.php?success=1");
    exit();
} else {
    // Gagal → tampilkan error
    echo "Error: " . mysqli_error($conn);
}
?>