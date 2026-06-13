<?php
include '../config/koneksi.php';

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM bidang WHERE id_bidang='$id'");

header("Location: master_bidang.php");
?>