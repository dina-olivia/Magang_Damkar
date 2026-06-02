<?php
include '../koneksi.php';

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM sarpras WHERE id_sarpras='$id'");

header("Location:index.php");
?>