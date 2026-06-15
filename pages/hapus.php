<?php
// ensure correct path to koneksi.php and that it was loaded
require_once __DIR__ . '/../koneksi.php';

if (!isset($conn) || !$conn) {
	// stop if connection is not available
	http_response_code(500);
	exit('Database connection not available');
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id > 0) {
	$stmt = mysqli_prepare($conn, "DELETE FROM sarpras WHERE id_sarpras = ?");
	if ($stmt) {
		mysqli_stmt_bind_param($stmt, 'i', $id);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
}

header('Location: index.php');
?>