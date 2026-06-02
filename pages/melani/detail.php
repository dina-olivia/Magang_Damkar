<?php
require_once __DIR__ . '/../../config/koneksi.php';

// 1. Ambil data dari URL dengan aman
$menu = $_GET['menu'] ?? '';
$id   = intval($_GET['id'] ?? 0);

// 2. Pengalihan otomatis jika mendeteksi keyword personil
if ($menu === 'personil') { 
    $menu = 'tbl_daftar'; 
}

// 3. VALIDASI AMAN: Cegah SQL kosong jika parameter URL tidak diisi
if (empty($menu) || $id <= 0) {
    echo "<script>alert('Akses tidak valid! Parameter menu atau ID salah.'); window.history.back();</script>";
    exit;
}

// 4. Jalankan Query dengan mengamankan nama tabel menggunakan backtick (`)
$result = mysqli_query($conn, "SELECT * FROM `$menu` WHERE id = $id");

// Jika nama tabel ngawur / tidak ada di database, tangani dengan elegan (bukan fatal error)
if (!$result) {
    echo "<script>alert('Terjadi kesalahan! Data menu tidak dikenali sistem.'); window.history.back();</script>";
    exit;
}

$data = mysqli_fetch_assoc($result);

// 5. Validasi jika ID data memang tidak ada di database
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.history.back();</script>";
    exit;
}

// Menentukan judul halaman berdasarkan nama tabel
$nama_tampilan = ($menu === 'tbl_daftar') ? 'Personil' : ucfirst(str_replace('_', ' ', $menu));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail <?= $nama_tampilan; ?></title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; padding: 30px; display: flex; justify-content: center; }
        .modal-box { width: 100%; max-width: 600px; background: #ffffff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.15); overflow: hidden; }
        .modal-header { background: #343a40; color: white; padding: 15px 20px; font-size: 18px; font-weight: bold; display: flex; align-items: center; }
        .modal-header span { margin-right: 10px; }
        .modal-body { padding: 20px; }
        .detail-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .detail-table tr { border-bottom: 1px solid #eceeef; }
        .detail-table tr:last-child { border-bottom: none; }
        .detail-table td { padding: 14px 10px; font-size: 14px; color: #495057; }
        .detail-table td.label { font-weight: 600; color: #333333; text-transform: capitalize; width: 35%; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .badge-aktif { background-color: #d4edda; color: #155724; }
        .badge-non { background-color: #f8d7da; color: #721c24; }
        .modal-footer { display: flex; justify-content: flex-end; padding: 15px 20px; background-color: #fafafa; border-top: 1px solid #eceeef; }
        .btn-back { padding: 9px 18px; background-color: #6c757d; color: #ffffff; border-radius: 6px; font-size: 14px; font-weight: 600; text-decoration: none; transition: background 0.2s; }
        .btn-back:hover { background-color: #5a6268; }
    </style>
</head>
<body>

<div class="modal-box">
    <div class="modal-header">
        <span>📋</span> Detail Informasi <?= $nama_tampilan; ?>
    </div>
    
    <div class="modal-body">
        <table class="detail-table">
            <?php foreach ($data as $kolom => $nilai) : ?>
                <tr>
                    <td class="label"><?= str_replace('_', ' ', $kolom); ?></td>
                    <td>
                        <?php 
                        // Deteksi otomatis jika kolom tersebut mengatur status aktif/non-aktif
                        if (strpos($kolom, 'status') !== false) {
                            $nilai_bersih = strtolower(trim($nilai));
                            if ($nilai_bersih === 'aktif' || $nilai_bersih === '1') {
                                echo "<span class='badge badge-aktif'>Aktif</span>";
                            } else {
                                echo "<span class='badge badge-non'>Non-Aktif</span>";
                            }
                        } else {
                            echo htmlspecialchars($nilai);
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="modal-footer">
        <a href="#" onclick="window.history.back(); return false;" class="btn-back">Kembali</a>
    </div>
</div>

</body>
</html>