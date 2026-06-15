<?php
require_once __DIR__ . '/../koneksi.php';

// Ensure $conn is available from koneksi.php
if (!isset($conn)) {
    die('Database connection ($conn) is not defined. Check koneksi.php');
}

$id = $_GET['id'];

$data = mysqli_query($conn, "SELECT * FROM sarpras WHERE id_sarpras='$id'");
$row = mysqli_fetch_array($data);

$kategori = mysqli_query($conn, "SELECT * FROM kategori");

if(isset($_POST['update'])){

    mysqli_query($conn, "UPDATE sarpras SET
        nama_barang='$_POST[nama_barang]',
        id_kategori='$_POST[id_kategori]',
        kondisi='$_POST[kondisi]',
        status='$_POST[status]',
        lokasi='$_POST[lokasi]',
        tahun='$_POST[tahun]'
        WHERE id_sarpras='$id'
    ");

    header("Location:index.php");
}
?>

<h2>Edit Sarpras</h2>

<form method="POST">

Nama Barang:
<input type="text" name="nama_barang" value="<?= $row['nama_barang']; ?>"><br><br>

Kategori:
<select name="id_kategori">
<?php while($k = mysqli_fetch_array($kategori)){ ?>
<option value="<?= $k['id_kategori']; ?>"
<?= $k['id_kategori']==$row['id_kategori']?'selected':''; ?>>
<?= $k['nama_kategori']; ?>
</option>
<?php } ?>
</select><br><br>

Kondisi:
<select name="kondisi">
<option <?= $row['kondisi']=="Baik"?'selected':''; ?>>Baik</option>
<option <?= $row['kondisi']=="Perbaikan"?'selected':''; ?>>Perbaikan</option>
<option <?= $row['kondisi']=="Rusak"?'selected':''; ?>>Rusak</option>
</select><br><br>

Status:
<select name="status">
<option <?= $row['status']=="Aktif"?'selected':''; ?>>Aktif</option>
<option <?= $row['status']=="Nonaktif"?'selected':''; ?>>Nonaktif</option>
</select><br><br>

Lokasi:
<input type="text" name="lokasi" value="<?= $row['lokasi']; ?>"><br><br>

Tahun:
<input type="text" name="tahun" value="<?= $row['tahun']; ?>"><br><br>

<button type="submit" name="update">Update</button>

</form>