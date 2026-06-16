<?php
include '../../config/koneksi.php';

$id = $_GET['id'];

// ensure we have a valid connection and a valid id
if (!isset($id) || $id === '') {
	header("Location: sarpras.php");
	exit;
}

// cast to int to avoid SQL injection for numeric id; adjust if id is non-numeric
$id = (int) $id;

if ($conn instanceof mysqli) {
	$stmt = mysqli_prepare($conn, "DELETE FROM hydrant WHERE id_hydrant = ?");
	if ($stmt) {
		mysqli_stmt_bind_param($stmt, 'i', $id);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
} else {
	// connection not available
	die('Database connection error');
}

header("Location: sarpras.php");
?>