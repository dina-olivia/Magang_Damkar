<?php
include '../config/koneksi.php';

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM hydrant WHERE id_hydrant='$id'");

header("Location: sarpras.php");
?>