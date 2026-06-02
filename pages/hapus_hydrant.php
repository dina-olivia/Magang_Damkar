<?php
include '../config/koneksi.php';

$id = $_GET['id'];

mysqli_query($koneksi, "DELETE FROM hydrant WHERE id_hydrant='$id'");

header("Location: sarpras.php");
?>