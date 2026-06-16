<?php
include '../../config/koneksi.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($conn instanceof mysqli) {
	$stmt = $conn->prepare("DELETE FROM bidang WHERE id_bidang = ?");
	if ($stmt) {
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->close();
	} else {
		// prepare failed
		die('Failed to prepare statement');
	}
} else {
	die('Database connection error');
}

header("Location: master_bidang.php");
?>