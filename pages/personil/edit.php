<?php
require_once __DIR__ . '/../../config/koneksi.php';

/** @var mysqli $conn */
if (!isset($conn)) {
    die("Error: Koneksi database tidak ditemukan. Periksa kembali file koneksi.php Anda.");
}

// Matikan strict mode secara dinamis agar aman dari crash data truncated sewaktu-waktu
mysqli_query($conn, "SET sql_mode=''");

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
    $result = mysqli_query($conn, "SELECT * FROM `jadwal_piket` WHERE `tanggal`='" . mysqli_real_escape_string($conn, $tanggal) . "' AND `shift`='" . mysqli_real_escape_string($conn, $shift) . "'");
    if ($result) { $data = mysqli_fetch_assoc($result); }
} else {
    if ($id > 0) {
        $result = mysqli_query($conn, "SELECT * FROM `$menu` WHERE `id` = $id");
        if ($result) { $data = mysqli_fetch_assoc($result); }
    }
    
    // Cadangan jika ID kosong atau data tidak ketemu, ambil baris pertama agar form tidak blank
    if (empty($data)) {
        $result = mysqli_query($conn, "SELECT * FROM `$menu` LIMIT 1");
        if ($result) { $data = mysqli_fetch_assoc($result); }
    }
}

if (empty($data)) {
    header("Location: personil.php");
    exit;
}

$nama_tampilan = ucfirst(str_replace('_', ' ', $menu_url ?: $menu));

// 3. PROSES SIMPAN DATA (KETIKA TOMBOL SIMPAN DIKLIK)
if (isset($_POST['update'])) {
    // Ambil struktur asli opsi enum dari database
    $enum_db_values = [];
    $type_res = mysqli_query($conn, "SHOW COLUMNS FROM `$menu` LIKE 'status'");
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

            $parts[] = "`$kolom` = '" . mysqli_real_escape_string($conn, $val) . "'";
        }
    }

    if ($menu === 'jadwal_piket') {
        $old_tanggal = mysqli_real_escape_string($conn, $_POST['old_tanggal'] ?? $tanggal);
        $old_shift   = mysqli_real_escape_string($conn, $_POST['old_shift'] ?? $shift);
        $query = "UPDATE `jadwal_piket` SET " . implode(', ', $parts) . " WHERE `tanggal` = '$old_tanggal' AND `shift` = '$old_shift'";
    } else {
        $current_id = $data['id'] ?? $id;
        $query = "UPDATE `$menu` SET " . implode(', ', $parts) . " WHERE `id` = $current_id";
    }

    // --- LOGIKA REDIRECT SUBMENU UNIVERSAL ---
    if (mysqli_query($conn, $query)) {
        
        // Menentukan file tujuan redirect berdasarkan menu_url
        switch ($menu_url) {
            case 'tbl_daftar':
            case 'personil':
                $redirect_page = 'personil.php';
                break;
            case 'penempatan_pos':
            case 'penempatan_personil':
                $redirect_page = 'penempatan_pos.php';
                break;
            case 'jadwal_piket':
                $redirect_page = 'jadwal_piket.php';
                break;
            case 'riwayat_tugas':
                $redirect_page = 'riwayat_tugas.php';
                break;
            default:
                $redirect_page = !empty($menu_url) ? $menu_url . '.php' : 'personil.php';
                break;
        }

        echo "<script>
                alert('Data Berhasil Terupdate!'); 
                window.location.href='" . $redirect_page . "';
              </script>";
        exit;
    } else {
        echo "<script>alert('Gagal Update: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data <?= $nama_tampilan; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .edit-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            border-top: 4px solid #b91c1c;
            padding: 30px;
            max-width: 900px;
            margin: 0 auto;
        }
        .form-label {
            font-weight: 600;
            color: #334155;
            font-size: 0.9rem;
            text-transform: capitalize;
        }
        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #e2e8f0;
        }
        .form-control:focus, .form-select:focus {
            border-color: #b91c1c;
            box-shadow: 0 0 0 3px rgba(185, 28, 28, 0.1);
        }
        .btn-simpan {
            background: #b91c1c;
            color: white;
            padding: 10px 30px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .btn-simpan:hover {
            background: #991b1b;
            color: white;
            transform: translateY(-1px);
        }
        .btn-batal {
            background: #f1f5f9;
            color: #64748b;
            padding: 10px 30px;
            font-weight: 600;
            border-radius: 8px;
            margin-right: 10px;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div id="sidebar" class="shadow">
        <div class="sidebar-header">
            <img src="../../assets/img/logo_damkar.png" alt="Logo" width="40" height="40"
                onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/b/b0/Logo_Damkar.png'">
            <span class="fw-bold ms-2">DAMKAR PADANG</span>
        </div>

        <div class="sidebar-content">
            <div class="nav flex-column mt-2">
                <a href="../../index.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
                
                <a href="#menuManajemenKejadian" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-megaphone"></i> Manajemen Kejadian</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuManajemenKejadian">
                    <a href="../input_laporan.php">Input Laporan</a>
                    <a href="../monitoring_kejadian.php">Monitoring Kejadian</a>
                    <a href="../detail_kejadian.php">Detail Kejadian</a>
                    <a href="../timeline_kronologi.php">Timeline Kronologi</a>
                </div>

                <a href="#menuOperasional" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-clipboard-check"></i> Operasional</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuOperasional">
                    <a href="../penugasan_tim.php">Penugasan Tim</a>
                    <a href="../monitoring_armada.php">Monitoring Armada</a>
                    <a href="../status_penanganan.php">Status Penanganan</a>
                    <a href="../riwayat_penugasan.php">Riwayat Penugasan</a>
                </div>

                <a href="#menuPersonil" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-people"></i> Personil</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu show" id="menuPersonil">
                    <a href="personil.php" class="<?= ($menu_url == 'personil' || $menu_url == 'tbl_daftar') ? 'active' : '' ?>">Data Personil</a>
                    <a href="penempatan_pos.php" class="<?= ($menu_url == 'penempatan_pos') ? 'active' : '' ?>">Penempatan Pos</a>
                    <a href="jadwal_piket.php" class="<?= ($menu_url == 'jadwal_piket') ? 'active' : '' ?>">Jadwal Piket</a>
                    <a href="riwayat_tugas.php" class="<?= ($menu_url == 'riwayat_tugas') ? 'active' : '' ?>">Riwayat Tugas</a>
                </div>

                <a href="../armada.php"><i class="bi bi-truck"></i> Armada</a>

                <a href="#menuSarpras" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-tools"></i> Sarpras</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuSarpras">
                    <a href="../sarpras.php">Data Sarpras</a>
                    <a href="../master_bidang.php">Master Bidang</a>
                    <a href="../master_kategori.php">Master Kategori</a>
                </div>

                <a href="../dina/laporan.php"><i class="bi bi-file-earmark-text"></i> Laporan</a>
                <a href="../pengaturan.php"><i class="bi bi-gear"></i> Pengaturan</a>

                <a href="../../logout.php" class="mt-4 text-danger"><i class="bi bi-box-arrow-left"></i> Keluar</a>
            </div>
        </div>
    </div>

    <div id="main-content">
        <div class="mb-4">
            <h2 class="fw-bold m-0 text-uppercase">EDIT DATA <?= $nama_tampilan; ?></h2>
            <div class="text-muted small">Perbarui informasi pada tabel database secara dinamis.</div>
        </div>

        <div class="edit-card">
            <form action="" method="POST">
                <div class="row g-4">
                    <?php if ($menu === 'jadwal_piket'): ?>
                        <input type="hidden" name="old_tanggal" value="<?= htmlspecialchars($data['tanggal'] ?? ''); ?>">
                        <input type="hidden" name="old_shift" value="<?= htmlspecialchars($data['shift'] ?? ''); ?>">
                    <?php endif; ?>

                    <?php 
                    foreach ($data as $kolom => $nilai): 
                        if ($kolom === 'id') continue; 
                        $is_full = (strlen($nilai) > 40 || in_array($kolom, ['alamat_kejadian', 'lokasi', 'keterangan', 'alamat']));
                    ?>
                        <div class="<?= $is_full ? 'col-12' : 'col-md-6'; ?>">
                            <label class="form-label"><?= str_replace('_', ' ', $kolom); ?></label>
                            
                            <?php if ($kolom === 'status'): ?>
                                <select class="form-select" name="status">
                                    <?php
                                    $type_res = mysqli_query($conn, "SHOW COLUMNS FROM `$menu` LIKE 'status'");
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
                                        echo "<option value='Tidak Aktif' ".($clean_nilai === 'tidakaktif' || $clean_nilai === 'nonaktif' ? 'selected' : '').">Tidak Aktif</option>";
                                    }
                                    ?>
                                </select>

                            <?php elseif ($kolom === 'pos_penempatan'): ?>
                                <select class="form-select" name="pos_penempatan">
                                   <?php
                                    $jbt_res = mysqli_query($conn, "SHOW COLUMNS FROM `$menu` LIKE 'pos_penempatan'");
                                    $jbt_row = mysqli_fetch_assoc($jbt_res);
                                    
                                    if ($jbt_row && strpos($jbt_row['Type'], 'enum') !== false) {
                                        preg_match("/^enum\(\'(.*)\'\)$/", $jbt_row['Type'], $matches);
                                        $list_jabatan = explode("','", $matches[1]);
                                    } else {
                                        $list_jabatan = ['Pos Pusat', 'Pos Kuranji', 'Pos Bungus', 'Pos Koto Tangah'];
                                    }
                                    
                                    foreach ($list_jabatan as $jbt) {
                                        $selected = (strtolower(trim($nilai)) === strtolower($jbt)) ? 'selected' : '';
                                        echo "<option value='$jbt' $selected>$jbt</option>";
                                    }
                                    ?>
                                </select>

                            <?php elseif ($kolom === 'jabatan'): ?>
                                <select class="form-select" name="jabatan" required>
                                    <?php
                                    $jbt_res = mysqli_query($conn, "SHOW COLUMNS FROM `$menu` LIKE 'jabatan'");
                                    $jbt_row = mysqli_fetch_assoc($jbt_res);
                                    
                                    if ($jbt_row && strpos($jbt_row['Type'], 'enum') !== false) {
                                        preg_match("/^enum\(\'(.*)\'\)$/", $jbt_row['Type'], $matches);
                                        $list_jabatan = explode("','", $matches[1]);
                                    } else {
                                        $list_jabatan = ['Komandan Regu', 'Pengemudi', 'Petugas'];
                                    }
                                    
                                    foreach ($list_jabatan as $jbt) {
                                        $selected = (strtolower(trim($nilai)) === strtolower($jbt)) ? 'selected' : '';
                                        echo "<option value='$jbt' $selected>$jbt</option>";
                                    }
                                    ?>
                                </select>

                            <?php elseif (strpos($kolom, 'tanggal') !== false || strpos($kolom, 'tgl') !== false): ?>
                                <input type="date" class="form-control" name="<?= $kolom; ?>" value="<?= htmlspecialchars($nilai); ?>" required>
                            
                            <?php else: ?>
                                <input type="text" class="form-control" name="<?= $kolom; ?>" value="<?= htmlspecialchars($nilai); ?>" required>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-5 d-flex justify-content-end">
                    <a href="javascript:window.history.back();" class="btn-batal">Batal</a>
                    <button type="submit" name="update" class="btn btn-simpan">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>