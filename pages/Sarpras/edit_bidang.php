<?php 
// ensure correct include paths regardless of current working directory
require_once __DIR__ . '/../../config/koneksi.php'; 

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID Bidang tidak ditemukan!'); window.location.href='master_bidang.php';</script>";
    exit;
}

$id_bidang = $_GET['id'];

$query = mysqli_query($conn, 
"SELECT * FROM bidang WHERE id_bidang = '$id_bidang'");
$data  = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='master_bidang.php';</script>";
    exit;
}

if(isset($_POST['update'])){

    $nama_bidang = $_POST['nama_bidang'];
    $deskripsi   = $_POST['deskripsi'];
    $urutan      = $_POST['urutan'];

    mysqli_query($conn, "UPDATE bidang SET
        nama_bidang='$nama_bidang',
        deskripsi='$deskripsi',
        urutan='$urutan'
        WHERE id_bidang='$id_bidang'
    ");

    echo "<script>
        alert('Data berhasil diupdate!');
        window.location.href='master_bidang.php';
    </script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Bidang</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
:root {
    --fire-red: #b91c1c;
    --dark-sidebar: #0f172a;
    --sidebar-text: #94a3b8;
}

#sidebar {
    width: 280px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    background-color: var(--dark-sidebar);
    display: flex;
    flex-direction: column;
    z-index: 1000;
}

.sidebar-header {
    padding: 20px;
    background-color: var(--fire-red);
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.sidebar-content {
    flex-grow: 1;
    overflow-y: auto;
}

#sidebar a {
    color: var(--sidebar-text);
    text-decoration: none;
    padding: 12px 25px;
    display: flex;
    align-items: center;
}

#sidebar a:hover,
#sidebar a.active {
    background-color: #1e293b;
    color: white;
    border-left: 4px solid #ef4444;
}

body {
    background: #f0f2f5;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.main-content {
    margin-left: 280px;
    padding: 32px;
}

.form-card {
    background: #fff;
    border-radius: 16px;
    border: 1.5px solid #e5e7eb;
    padding: 28px;
    max-width: 600px;
    margin: 0 auto;
}

.btn-update {
    background: #f59e0b;
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 10px 20px;
    font-weight: 600;
}

.btn-update:hover {
    background: #d97706;
}
</style>
</head>
<body>

    <?php require_once __DIR__ . '/../../config/sidebar.php'; ?>

    <div class="main-content">
        <div class="form-card">
            <h3 class="mb-4" style="font-weight: 700; color: #1a1f2e;">Edit Data Bidang</h3>
            
            <form method="POST">
                <input type="hidden" name="id_bidang" value="<?= $data['id_bidang']; ?>">

                <div class="mb-3">
                    <label class="form-label" style="font-weight: 600;">Nama Bidang</label>
                    <input type="text" name="nama_bidang" class="form-control" value="<?= $data['nama_bidang']; ?>" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label" style="font-weight: 600;">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" required><?= $data['deskripsi']; ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label" style="font-weight: 600;">Urutan Tampilan</label>
                    <input type="number" name="urutan" class="form-control" value="<?= $data['urutan']; ?>" required>
                </div>

                <div class="d-flex gap-2 justify-content-end mt-4">
                    <a href="master_bidang.php" class="btn btn-light" style="border-radius: 10px;">Batal</a>
                    <button type="submit" name="update" class="btn btn-update">
    Update Data
</button>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
                </div>
            </form>
        </div>
    </div>

</body>
</html>