<?php
include '../config/koneksi.php';

// Ambil data dari form
$plat_no = $_POST['plat_no'];
$jenis   = $_POST['jenis'];
$merk    = $_POST['merk'];
$tahun   = $_POST['tahun'];
$status  = $_POST['status'];

// Query insert
$sql = "INSERT INTO armada (plat_no, jenis, merk, tahun, status) 
        VALUES ('$plat_no', '$jenis', '$merk', '$tahun', '$status')";

if (mysqli_query($conn, $sql)) {
    // Berhasil → redirect ke halaman armada
    header("Location: armada.php?success=1");
    exit();
} else {
    // Gagal → tampilkan error
    echo "Error: " . mysqli_error($conn);
}
?>