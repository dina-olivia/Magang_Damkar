<?php
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

// ── Statistik ──────────────────────────────────────────────
$row_admin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM user WHERE role='admin'"));
$row_operator = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM user WHERE role='operator'"));
$row_danru = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM user WHERE role='danru'"));

// ── Flash message (Perbaikan pencegahan Undefined Index Notice) ──────────────────────────
$flash_success = isset($_GET['success']) && $_GET['success'] == '1';
$flash_error = isset($_GET['error']) ? $_GET['error'] : '';

// ── Data user ──────────────────────────────────────────────
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
    <link rel="stylesheet" href="<?= $base_url ?>assets/css/style.css">
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
            background-color: #60cf10;
            color: white;
            border: none;
        }

        .btn-hijau:hover {
            background-color: #4fb30d;
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

    <?php include '../config/sidebar.php'; ?>

    <div id="main-content" class="p-4" style="margin-left: 260px;"> <?php if ($flash_success): ?>
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
                <p class="text-muted m-0">Pengaturan hak akses akun personel, operator komando, dan administrator.</p>
            </div>
            <button class="btn btn-hijau shadow-sm px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalUserBaru">
                <i class="bi bi-person-plus me-2"></i> Tambah Pengguna Baru
            </button>
        </header>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <p class="small text-muted text-uppercase fw-bold mb-1">Administrator</p>
                        <h2 class="fw-bold m-0 text-danger"><?= (int) $row_admin['total'] ?> <span class="fs-6 fw-normal text-muted">Akun</span></h2>
                    </div>
                    <div class="fs-1 text-danger-subtle"><i class="bi bi-shield-lock-fill"></i></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <p class="small text-muted text-uppercase fw-bold mb-1">Operator Posko</p>
                        <h2 class="fw-bold m-0 text-primary"><?= (int) $row_operator['total'] ?> <span class="fs-6 fw-normal text-muted">Akun</span></h2>
                    </div>
                    <div class="fs-1 text-primary-subtle"><i class="bi bi-headset"></i></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <p class="small text-muted text-uppercase fw-bold mb-1">Komandan Regu</p>
                        <h2 class="fw-bold m-0 text-warning"><?= (int) $row_danru['total'] ?> <span class="fs-6 fw-normal text-muted">Akun</span></h2>
                    </div>
                    <div class="fs-1 text-warning-subtle"><i class="bi bi-person-badge-fill"></i></div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-uppercase">
                        <tr>
                            <th class="py-3 px-4">Pengguna</th>
                            <th>Username</th>
                            <th>No. Telp / HP</th>
                            <th>Hak Akses (Role)</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result_users) > 0):
                            while ($u = mysqli_fetch_assoc($result_users)):
                                $role_badge = match ($u['role']) {
                                    'admin' => 'bg-danger-subtle text-danger',
                                    'danru' => 'bg-warning-subtle text-dark',
                                    default => 'bg-primary-subtle text-primary'
                                };
                                ?>
                                <tr>
                                    <td class="py-3 px-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-circle">
                                                <?= strtoupper(substr($u['nama_lengkap'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <span class="fw-bold text-dark d-block"><?= htmlspecialchars($u['nama_lengkap']) ?></span>
                                                <small class="text-muted"><?= htmlspecialchars($u['email'] ?? '-') ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-secondary">@<?= htmlspecialchars($u['username']) ?></span>
                                    </td>
                                    <td>
                                        <small class="fw-medium text-dark"><?= htmlspecialchars($u['no_hp'] ?: '-') ?></small>
                                    </td>
                                    <td>
                                        <span class="badge <?= $role_badge ?> px-3 py-2 small text-uppercase rounded">
                                            <?= htmlspecialchars($u['role']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group gap-2">
                                            <a href="edit_user.php?id=<?= $u['id'] ?>" class="btn btn-light btn-sm rounded-3 border" title="Edit User">
                                                <i class="bi bi-pencil-square text-primary"></i>
                                            </a>
                                            <a href="proses_hapus_user.php?id=<?= $u['id'] ?>" class="btn btn-light btn-sm rounded-3 border" onclick="return confirm('Yakin hapus akun <?= htmlspecialchars($u['nama_lengkap']) ?>?')" title="Hapus User">
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

    </div><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
                                <label class="form-label fw-bold small text-muted">Nama Lengkap Personel</label>
                                <input type="text" name="nama_lengkap" class="form-control bg-light border-0" placeholder="Masukkan nama lengkap" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small text-muted">Username</label>
                                <input type="text" name="username" class="form-control bg-light border-0" placeholder="Contoh: operator_padang" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small text-muted">Email</label>
                                <input type="email" name="email" class="form-control bg-light border-0" placeholder="Contoh: nama@damkar.go.id">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small text-muted">Kata Sandi (Password)</label>
                                <input type="password" name="password" class="form-control bg-light border-0" placeholder="Minimal 6 karakter" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small text-muted">No. HP Aktif</label>
                                <input type="text" name="no_hp" class="form-control bg-light border-0" placeholder="Contoh: 0822xxxxxxxx">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small text-muted">Hak Akses / Jabatan</label>
                                <select name="role" class="form-select bg-light border-0" required>
                                    <option value="" selected disabled>Pilih Hak Akses...</option>
                                    <option value="admin">Administrator (Full Control)</option>
                                    <option value="operator">Operator Posko Komando</option>
                                    <option value="danru">Komandan Regu (Danru)</option>
                                </select>
                            </div>
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
        // Auto-close alert message setelah 4 detik
        document.querySelectorAll('.alert').forEach(function (el) {
            setTimeout(function () {
                var alertInstance = bootstrap.Alert.getOrCreateInstance(el);
                if (alertInstance) alertInstance.close();
            }, 4000);
        });
    </script>

</body>
</html>