<?php
require_once __DIR__ . '/../../config/koneksi.php';

// Matikan strict mode secara dinamis agar aman dari crash data truncated sewaktu-waktu
mysqli_query($koneksi, "SET sql_mode=''");

// 1. AMBIL PARAMETER URL
$menu_url = $_GET['menu'] ?? '';
$id       = intval($_GET['id'] ?? 0);
$tanggal  = $_GET['tanggal'] ?? '';
$shift    = $_GET['shift'] ?? '';

// SINKRONISASI NAMA PARAMETER URL KE NAMA TABEL DATABASE ASLI
$menu = $menu_url;
if ($menu === 'personil' || $menu === 'tbl_daftar') { 
    $menu = 'tbl_daftar'; 
} elseif ($menu === 'penempatan_pos' || $menu === 'penempatan_personil') {
    $menu = 'penempatan_pos'; 
}

// 2. AMBIL DATA LAMA UNTUK DIEDIT
$data = [];
if ($menu === 'jadwal_piket' && !empty($tanggal) && !empty($shift)) {
    $result = mysqli_query($koneksi, "SELECT * FROM `jadwal_piket` WHERE `tanggal`='$tanggal' AND `shift`='$shift'");
    if ($result) { $data = mysqli_fetch_assoc($result); }
} else {
    if ($id > 0) {
        $result = mysqli_query($koneksi, "SELECT * FROM `$menu` WHERE `id` = $id");
        if ($result) { $data = mysqli_fetch_assoc($result); }
    }
    
    // Cadangan jika ID kosong atau data tidak ketemu, ambil baris pertama agar form tidak blank
    if (empty($data)) {
        $result = mysqli_query($koneksi, "SELECT * FROM `$menu` LIMIT 1");
        if ($result) { $data = mysqli_fetch_assoc($result); }
    }
}

if (empty($data)) {
    die("<div style='color:white; background:#c92a2a; padding:20px; font-family:sans-serif; text-align:center; border-radius:8px; margin:20px;'>
            <strong>Pemberitahuan Sistem:</strong> Data atau Tabel database untuk menu <u>`$menu`</u> tidak ditemukan atau masih kosong.
         </div>");
}

$nama_tampilan = ucfirst(str_replace('_', ' ', $menu_url ?: $menu));

// 3. PROSES SIMPAN DATA (KETIKA TOMBOL SIMPAN DIKLIK)
if (isset($_POST['update'])) {
    // Ambil struktur asli opsi enum dari database
    $enum_db_values = [];
    $type_res = mysqli_query($koneksi, "SHOW COLUMNS FROM `$menu` LIKE 'status'");
    $type_row = mysqli_fetch_assoc($type_res);
    if ($type_row && strpos($type_row['Type'], 'enum') !== false) {
        preg_match("/^enum\(\'(.*)\'\)$/", $type_row['Type'], $matches);
        $enum_db_values = explode("','", $matches[1]);
    }

    $parts = [];
    foreach ($data as $kolom => $nilai_lama) {
        if (in_array($kolom, ['id', 'old_tanggal', 'old_shift'])) {
            continue;
        }

        if (isset($_POST[$kolom])) {
            $val = trim($_POST[$kolom]);

            // VALIDASI DAN SINKRONISASI ENUM STATUS
            if ($kolom === 'status' && !empty($enum_db_values)) {
                $matched = false;
                foreach ($enum_db_values as $db_val) {
                    $clean_val = str_replace('-', '', strtolower($val));
                    $clean_db  = str_replace('-', '', strtolower($db_val));
                    
                    if ($clean_val === $clean_db || strtolower($val) === strtolower($db_val)) {
                        $val = $db_val; 
                        $matched = true;
                        break;
                    }
                }
                if (!$matched) { $val = $enum_db_values[0]; }
            }

            $parts[] = "`$kolom` = '" . mysqli_real_escape_string($koneksi, $val) . "'";
        }
    }

    if ($menu === 'jadwal_piket') {
        $old_tanggal = mysqli_real_escape_string($koneksi, $_POST['old_tanggal'] ?? $tanggal);
        $old_shift   = mysqli_real_escape_string($koneksi, $_POST['old_shift'] ?? $shift);
        $query = "UPDATE `jadwal_piket` SET " . implode(', ', $parts) . " WHERE `tanggal` = '$old_tanggal' AND `shift` = '$old_shift'";
    } else {
        $current_id = $data['id'] ?? $id;
        $query = "UPDATE `$menu` SET " . implode(', ', $parts) . " WHERE `id` = $current_id";
    }

    // --- LOGIKA REDIRECT SUBMENU UNIVERSAL ---
    if (mysqli_query($koneksi, $query)) {
        
        // MAPPING: Menentukan parameter ?page= sesuai nama file/tujuan submenu kamu
        switch ($menu_url) {
            case 'tbl_daftar':
            case 'personil':
                $page_target = 'personil';
                break;
            case 'penempatan_pos':
            case 'penempatan_personil':
                $page_target = 'penempatan_pos';
                break;
            case 'jadwal_piket':
                $page_target = 'jadwal_piket';
                break;
            case 'laporan_kejadian':
                $page_target = 'laporan_kejadian';
                break;
            case 'armada':
                $page_target = 'armada';
                break;
            default:
                // Jika ada submenu baru nanti, dia otomatis mengarah ke nama menu itu sendiri
                $page_target = !empty($menu_url) ? $menu_url : $menu;
                break;
        }

        echo "<script>
                alert('Data Berhasil Terupdate!'); 
                window.location.href='../../index.php?page=" . $page_target . "';
              </script>";
        exit;
    } else {
        echo "<script>alert('Gagal Update: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data <?= $nama_tampilan; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background-color: #555; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .modal { background: #fff; width: 100%; max-width: 800px; border-radius: 8px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
        .header { background: #c92a2a; color: #fff; padding: 15px 20px; font-size: 18px; font-weight: 600; }
        .body { padding: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .full { grid-column: span 2; }
        label { font-size: 13px; font-weight: 600; color: #333; margin-bottom: 5px; display: block; text-transform: capitalize; }
        .control { width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; outline: none; background: #fff; }
        .control:focus { border-color: #c92a2a; }
        .footer { padding: 15px 20px; background: #f8f9fa; text-align: right; }
        .btn { padding: 10px 25px; border-radius: 4px; border: none; font-size: 14px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-batal { background: #e9ecef; color: #495057; margin-right: 10px; }
        .btn-simpan { background: #c92a2a; color: #fff; }
    </style>
</head>
<body>

<div class="modal">
    <div class="header">Edit Data <?= $nama_tampilan; ?></div>
    
    <form action="" method="POST">
        <div class="body">
            <?php if ($menu === 'jadwal_piket'): ?>
                <input type="hidden" name="old_tanggal" value="<?= htmlspecialchars($data['tanggal'] ?? ''); ?>">
                <input type="hidden" name="old_shift" value="<?= htmlspecialchars($data['shift'] ?? ''); ?>">
            <?php endif; ?>

            <?php 
            foreach ($data as $kolom => $nilai): 
                if ($kolom === 'id') continue; 
                $is_full = (strlen($nilai) > 40 || in_array($kolom, ['alamat_kejadian', 'lokasi', 'keterangan', 'alamat']));
            ?>
                <div class="<?= $is_full ? 'full' : ''; ?>">
                    <label><?= str_replace('_', ' ', $kolom); ?></label>
                    
                    <?php if ($kolom === 'status'): ?>
                        <select class="control" name="status">
                            <?php
                            $type_res = mysqli_query($koneksi, "SHOW COLUMNS FROM `$menu` LIKE 'status'");
                            $type_row = mysqli_fetch_assoc($type_res);
                            
                            if ($type_row && strpos($type_row['Type'], 'enum') !== false) {
                                preg_match("/^enum\(\'(.*)\'\)$/", $type_row['Type'], $matches);
                                $enum_values = explode("','", $matches[1]);
                                
                                foreach ($enum_values as $value) {
                                    $selected = (str_replace('-', '', strtolower(trim($nilai))) === str_replace('-', '', strtolower($value))) ? 'selected' : '';
                                    echo "<option value='$value' $selected>$value</option>";
                                }
                            } else {
                                $clean_nilai = str_replace('-', '', strtolower(trim($nilai)));
                                echo "<option value='Aktif' ".($clean_nilai === 'aktif' ? 'selected' : '').">Aktif</option>";
                                echo "<option value='Non-Aktif' ".($clean_nilai === 'nonaktif' || $clean_nilai === 'tidakaktif' ? 'selected' : '').">Non-Aktif</option>";
                            }
                            ?>
                        </select>

                    <?php elseif ($kolom === 'pos_penempatan'): ?>
                        <select class="control" name="pos_penempatan">
                           <?php
                            // Menggunakan pengecekan dinamis apakah tipe data kolom pos penempatan di database berbentuk ENUM
                            $jbt_res = mysqli_query($koneksi, "SHOW COLUMNS FROM `$menu` LIKE 'pos_penempatan'");
                            $jbt_row = mysqli_fetch_assoc($jbt_res);
                            
                            if ($jbt_row && strpos($jbt_row['Type'], 'enum') !== false) {
                                // JIKA DI DATABASE BERBENTUK ENUM: Ambil otomatis pilihan opsinya dari database
                                preg_match("/^enum\(\'(.*)\'\)$/", $jbt_row['Type'], $matches);
                                $list_jabatan = explode("','", $matches[1]);
                            } else {
                                // JIKA BUKAN ENUM: Masukkan daftar pilihan manual di bawah ini sebagai cadangan (Silakan edit array ini)
                                $list_jabatan = ['Pos Pusat', 'Pos Kuranji', 'Pos Bungus', 'Pos Koto Tangah'];
                            }
                            
                            foreach ($list_jabatan as $jbt) {
                                $selected = (strtolower(trim($nilai)) === strtolower($jbt)) ? 'selected' : '';
                                echo "<option value='$jbt' $selected>$jbt</option>";
                            }
                            ?>
                        </select>

                    <?php elseif ($kolom === 'jabatan'): ?>
                        <select class="control" name="jabatan" required>
                            <?php
                            // Menggunakan pengecekan dinamis apakah tipe data kolom jabatan di database berbentuk ENUM
                            $jbt_res = mysqli_query($koneksi, "SHOW COLUMNS FROM `$menu` LIKE 'jabatan'");
                            $jbt_row = mysqli_fetch_assoc($jbt_res);
                            
                            if ($jbt_row && strpos($jbt_row['Type'], 'enum') !== false) {
                                // JIKA DI DATABASE BERBENTUK ENUM: Ambil otomatis pilihan opsinya dari database
                                preg_match("/^enum\(\'(.*)\'\)$/", $jbt_row['Type'], $matches);
                                $list_jabatan = explode("','", $matches[1]);
                            } else {
                                // JIKA BUKAN ENUM: Masukkan daftar pilihan manual di bawah ini sebagai cadangan (Silakan edit array ini)
                                $list_jabatan = ['Komandan Regu', 'Pengemudi', 'Petugas'];
                            }
                            
                            foreach ($list_jabatan as $jbt) {
                                $selected = (strtolower(trim($nilai)) === strtolower($jbt)) ? 'selected' : '';
                                echo "<option value='$jbt' $selected>$jbt</option>";
                            }
                            ?>
                        </select>

                    <?php elseif (strpos($kolom, 'tanggal') !== false || strpos($kolom, 'tgl') !== false): ?>
                        <input type="date" class="control" name="<?= $kolom; ?>" value="<?= htmlspecialchars($nilai); ?>" required>
                    
                    <?php else: ?>
                        <input type="text" class="control" name="<?= $kolom; ?>" value="<?= htmlspecialchars($nilai); ?>" required>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="footer">
            <a href="javascript:window.history.back();" class="btn btn-batal">Batal</a>
            <button type="submit" name="update" class="btn btn-simpan">Simpan Data</button>
        </div>
    </form>
</div>

</body>
</html>