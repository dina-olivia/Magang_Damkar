0<?php
$config_path = __DIR__ . '/../config/koneksi.php';
if (!file_exists($config_path)) {
    die('Database configuration file not found.');
}
require_once $config_path;
if (!isset($conn)) {
    die('Database connection not established.');
}

$current_page = basename($_SERVER['PHP_SELF']);
$path = $_SERVER['PHP_SELF'];
$root_folder = '/Magang_DAMKAR';
$clean_path = str_replace($root_folder, '', $path);
$levels = substr_count($clean_path, '/');
$base_url = ($levels > 1) ? str_repeat('../', $levels - 1) : '';

// ── Statistik (Menghitung total seluruh user aktif) ───────────────────
$row_total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM user"));

// ── Flash message ───────────────────────────────────────────────────
$flash_success = isset($_GET['success']) && $_GET['success'] == '1';
$flash_error = isset($_GET['error']) ? $_GET['error'] : '';

// ── Data user ───────────────────────────────────────────────────────
$result_users = mysqli_query($conn, "SELECT * FROM user ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - E-DAMKAR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
        }

        .btn-hijau {
            background-color: #b43b19;
            color: white;
            border: none;
        }

        .btn-hijau:hover {
            background-color: #c72828;
            color: white;
        }

        .avatar-circle {
            width: 40px;
            height: 40px;
            background: #e9ecef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #495057;
        }
    </style>
</head>

<body>

    <div id="sidebar" class="shadow">
        <div class="sidebar-header">
            <img src="../assets/img/logo_damkar.png" alt="Logo" width="140" height="80"
                onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/b/b0/Logo_Damkar.png'">
            <span class="fw-bold ms-2">DAMKAR PADANG</span>
        </div>

        <div class="sidebar-content">
            <div class="nav flex-column mt-2">
                <a href="../index.php"><i class="bi bi-speedometer2"></i> Dashboard</a>

                <!-- Manajemen Kejadian -->
                <a href="#menuManajemenKejadian" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-megaphone"></i> Manajemen Kejadian</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuManajemenKejadian">
                    <a href="../manajemen/input_laporan.php">Input Laporan</a>
                    <a href="../manajemen/monitoring_kejadian.php">Monitoring Kejadian</a>
                    <a href="../manajemen/detail_kejadian.php">Detail Kejadian</a>
                </div>

                <!-- Operasional (Aktif & Terbuka Otomatis) -->
                <a href="#menuOperasional" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-clipboard-check"></i> Operasional</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuOperasional">
                    <a href="../operasional/penugasan_tim.php">Penugasan Tim</a>
                    <a href="../operasional/monitoring_armada.php">Monitoring Armada</a>
                    <a href="../operasional/status_penanganan.php">Status Penanganan</a>
                    <a href="../operasional/riwayat_penugasan.php">Riwayat Penugasan</a>
                </div>

                <!-- Personil -->
                <a href="#menuPersonil" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-people"></i> Personil</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuPersonil">
                    <a href="../personil/personil.php">Data Personil</a>
                    <a href="../personil/penempatan_pos.php">Penempatan Pos</a>
                    <a href="../personil/jadwal_piket.php">Jadwal Piket</a>
                    <a href="../personil/riwayat_tugas.php">Riwayat Tugas</a>
                </div>

                 <!-- Armada -->
                <a href="#menuArmada" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-truck"></i> Armada</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuArmada">
                    <a href="../armada/armada.php">Data Armada</a>
                </div>

                <!-- Sarpras -->
                <a href="#menuSarpras" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-tools"></i> Sarpras</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuSarpras">
                    <a href="../Sarpras/sarpras.php">Data Sarpras</a>
                    <a href="../Sarpras/master_bidang.php">Master Bidang</a>
                    <a href="../Sarpras/master_kategori.php">Master Kategori</a>
                </div>

                <!-- Laporan & Analitik -->
                <a href="#menuLaporan" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-file-earmark-text"></i> Laporan & Analitik</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse sub-menu" id="menuLaporan">
                    <a href="laporan_kejadian.php">Laporan Kejadian</a>
                    <a href="rekap_statistik.php">Rekap Statistik & Analisis</a>
                    <a href="cetak_export.php">Cetak & Export Dokumen</a>
                </div>
                
                <a href="manajemen_user.php"><i class="bi bi-gear"></i> Manajemen User</a>
                <a href="../logout.php" class="mt-4 text-danger"><i class="bi bi-box-arrow-left"></i> Keluar</a>
            </div>
        </div>
    </div>

    <div id="main-content" class="p-4" style="margin-left: 260px;">

        <?php if ($flash_success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong>Berhasil!</strong> Data pengguna telah disimpan.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($flash_error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Gagal!</strong> <?= htmlspecialchars($flash_error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <header class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold m-0 text-uppercase">Manajemen User / Pengguna</h2>
                <p class="text-muted m-0">Pengaturan data akun login personel DAMKAR.</p>
            </div>
            <button class="btn btn-hijau shadow-sm px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalUserBaru">
                <i class="bi bi-person-plus me-2"></i> Tambah Pengguna Baru
            </button>
        </header>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <p class="small text-muted text-uppercase fw-bold mb-1">Total Pengguna Terdaftar</p>
                        <h2 class="fw-bold m-0 text-primary"><?= (int) $row_total['total'] ?> <span
                                class="fs-6 fw-normal text-muted">Akun</span></h2>
                    </div>
                    <div class="fs-1 text-primary-subtle"><i class="bi bi-people-fill"></i></div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-uppercase">
                        <tr>
                            <th class="py-3 px-4">Nama Pengguna</th>
                            <th>Email Login</th>
                            <th>OPD ID</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result_users) > 0):
                            while ($u = mysqli_fetch_assoc($result_users)):
                                $inisial = strtoupper(substr($u['nama'] ?? 'U', 0, 1));
                                ?>
                                <tr>
                                    <td class="py-3 px-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-circle">
                                                <?= $inisial ?>
                                            </div>
                                            <div>
                                                <span
                                                    class="fw-bold text-dark d-block"><?= htmlspecialchars($u['nama']) ?></span>
                                                <small class="text-muted">ID: <?= htmlspecialchars($u['id']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-medium text-dark"><?= htmlspecialchars($u['email']) ?></span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-secondary-subtle text-secondary border px-2 py-1"><?= htmlspecialchars($u['opd_id'] ?? '-') ?></span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-success-subtle text-success px-3 py-2 small text-uppercase rounded">
                                            <?= htmlspecialchars($u['status'] ?? 'aktif') ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group gap-2">
                                            <a href="edit_user.php?id=<?= $u['id'] ?>"
                                                class="btn btn-light btn-sm rounded-3 border" title="Edit User">
                                                <i class="bi bi-pencil-square text-primary"></i>
                                            </a>
                                            <a href="proses_hapus_user.php?id=<?= $u['id'] ?>"
                                                class="btn btn-light btn-sm rounded-3 border"
                                                onclick="return confirm('Yakin hapus akun <?= htmlspecialchars($u['nama']) ?>?')"
                                                title="Hapus User">
                                                <i class="bi bi-trash3 text-danger"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; else: ?>
                            <tr>
                                <td colspan="5" class="py-5 text-center bg-white">
                                    <i class="bi bi-people text-light" style="font-size:3rem"></i>
                                    <h6 class="fw-bold text-muted mt-2">BELUM ADA DATA PENGGUNA</h6>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <div class="modal fade" id="modalUserBaru" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius:15px">
                <div class="modal-header bg-dark text-white" style="border-radius:15px 15px 0 0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-person-lock me-2"></i> Registrasi Akun Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="proses_user.php" method="POST">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold small text-muted">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" placeholder="Nama lengkap" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small text-muted">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small text-muted">Kata Sandi</label>
                                <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small text-muted">OPD ID</label>
                                <input type="text" name="opd_id" class="form-control" value="DAMKAR-PDG" readonly>
                            </div>
                            <input type="hidden" name="role" value="petugas">
                            <input type="hidden" name="status" value="aktif">
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="simpan_user" class="btn btn-hijau shadow-sm px-4">
                            <i class="bi bi-save me-1"></i> Simpan Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.alert').forEach(function (el) {
            setTimeout(function () {
                var alertInstance = bootstrap.Alert.getOrCreateInstance(el);
                if (alertInstance) alertInstance.close();
            }, 4000);
        });
    </script>

</body>

</html>