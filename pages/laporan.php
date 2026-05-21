<?php $page = 'dashboard'; ?>
<?php
include '../config/koneksi.php';

$current_page = basename($_SERVER['PHP_SELF']);
$path = $_SERVER['PHP_SELF'];
$root_folder = '/Magang_DAMKAR';
$clean_path = str_replace($root_folder, '', $path);
$levels = substr_count($clean_path, '/');
$base_url = ($levels > 1) ? str_repeat('../', $levels - 1) : '';

// ── Statistik ─────────────────────────────────────────────
$row_total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM laporan_kejadian"));
$row_masuk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS masuk  FROM laporan_kejadian WHERE status = 'masuk'"));
$row_selesai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS selesai FROM laporan_kejadian WHERE status = 'selesai'"));

// ── Flash message ─────────────────────────────────────────
$flash_success = isset($_GET['success']) && $_GET['success'] == '1';
$flash_error = $_GET['error'] ?? '';

// ── Data tabel — terbaru di atas ──────────────────────────
$result_tabel = mysqli_query($conn, "SELECT * FROM laporan_kejadian ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Laporan Penanganan - E-DAMKAR</title>
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
    </style>
</head>

<body>

    <?php include '../config/sidebar.php'; ?>


    <div id="main-content">

        <?php if ($flash_success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong>Berhasil!</strong> Laporan baru telah disimpan dan tampil di tabel.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($flash_error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Gagal!</strong> <?= htmlspecialchars($flash_error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <header class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold m-0 text-uppercase">Log Laporan Penanganan</h2>
                <p class="text-muted m-0">Sistem Informasi Manajemen Kebakaran &amp; Penyelamatan</p>
            </div>
            <button class="btn btn-hijau shadow-sm px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalLaporanBaru">
                <i class="bi bi-plus-lg me-2"></i> Buat Laporan Baru
            </button>
        </header>

        <!-- Statistik -->
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <h6 class="text-muted text-uppercase small fw-bold mb-1">Total Laporan</h6>
                    <h2 class="fw-bold m-0"><?= (int) $row_total['total'] ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <h6 class="text-muted text-uppercase small fw-bold mb-1">Menunggu Verifikasi</h6>
                    <h2 class="fw-bold m-0 text-warning"><?= (int) $row_masuk['masuk'] ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <h6 class="text-muted text-uppercase small fw-bold mb-1">Selesai Ditangani</h6>
                    <h2 class="fw-bold m-0 text-success"><?= (int) $row_selesai['selesai'] ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center d-flex flex-column justify-content-center align-items-center"
                    style="border: 2px dashed #dee2e6; min-height: 105px; cursor:pointer"
                    onclick="window.location='export_laporan.php'">
                    <i class="bi bi-file-earmark-arrow-down text-primary fs-3 mb-1"></i>
                    <span class="text-decoration-none small fw-bold text-primary">Export Excel</span>
                </div>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="row g-2 mb-4">
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control border-start-0"
                        placeholder="Cari lokasi, pelapor, atau deskripsi...">
                </div>
            </div>
            <div class="col-md-3">
                <select id="filterStatus" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="masuk">Masuk</option>
                    <option value="proses">Proses</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
        </div>

        <!-- Tabel -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-uppercase">
                        <tr>
                            <th class="py-3 px-4">Lokasi &amp; Kejadian</th>
                            <th>Pelapor &amp; Kontak</th>
                            <th>Waktu Lapor</th>
                            <th>Status</th>
                            <th class="text-center">Aksi Ops</th>
                        </tr>
                    </thead>
                    <tbody id="tabelBody">
                        <?php if (mysqli_num_rows($result_tabel) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result_tabel)):
                                $st = strtolower($row['status']);
                                if ($st === 'selesai')
                                    $badge = 'bg-success text-white';
                                elseif ($st === 'proses')
                                    $badge = 'bg-warning text-dark';
                                else
                                    $badge = 'bg-danger text-white'; // masuk
                                ?>
                                <tr>
                                    <td class="py-3 px-4">
                                        <span class="fw-bold text-danger text-uppercase d-block mb-1">
                                            <?= htmlspecialchars($row['jenis_kejadian']) ?>
                                        </span>
                                        <small class="text-muted d-block text-wrap" style="max-width:280px">
                                            <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($row['lokasi']) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="fw-semibold d-block"><?= htmlspecialchars($row['pelapor']) ?></span>
                                        <small class="text-muted">
                                            <i
                                                class="bi bi-whatsapp text-success me-1"></i><?= htmlspecialchars($row['no_hp'] ?? '-') ?>
                                        </small>
                                    </td>
                                    <td>
                                        <small class="fw-medium text-dark">
                                            <?= date('d M Y', strtotime($row['tanggal'])) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge <?= $badge ?> rounded-pill px-3 py-2 small text-uppercase">
                                            <?= htmlspecialchars($row['status']) ?>
                                        </span>
                                    </td>
                                    <td>

                                        <?php if ($row['status'] == 'menunggu'): ?>
                                            <a href="proses.php?id=<?= $row['id'] ?>" class="btn btn-warning">Proses</a>

                                        <?php elseif ($row['status'] == 'proses'): ?>
                                            <a href="selesai.php?id=<?= $row['id'] ?>" class="btn btn-success">Selesai</a>

                                        <?php else: ?>
                                            <button class="btn btn-secondary" disabled>Selesai</button>
                                        <?php endif; ?>

                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr id="emptyRow">
                                <td colspan="5" class="py-5 text-center bg-white">
                                    <i class="bi bi-database-exclamation text-light" style="font-size:3rem"></i>
                                    <h6 class="fw-bold text-muted mt-2">DATABASE KOSONG</h6>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div><!-- /main-content -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Modal Buat Laporan Baru -->
    <div class="modal fade" id="modalLaporanBaru" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius:15px">
                <div class="modal-header bg-danger text-white" style="border-radius:15px 15px 0 0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-megaphone me-2"></i> Buat Laporan Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="proses_laporan.php" method="POST">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Nomor Laporan</label>
                                <input type="text" name="nomor_laporan" class="form-control bg-light border-0"
                                    value="LPK-<?= date('dmYHis') ?>" readonly required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Tanggal Kejadian</label>
                                <input type="date" name="tanggal" class="form-control bg-light border-0"
                                    value="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Nama Pelapor</label>
                                <input type="text" name="pelapor" class="form-control bg-light border-0"
                                    placeholder="Masukkan nama pelapor" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">No. HP / WhatsApp</label>
                                <input type="text" name="no_hp" class="form-control bg-light border-0"
                                    placeholder="Contoh: 08123456789" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small text-muted">Jenis Kejadian</label>
                                <select name="jenis_kejadian" class="form-select bg-light border-0" required>
                                    <option value="" selected disabled>Pilih Jenis Kejadian...</option>
                                    <option value="kebakaran">Kebakaran</option>
                                    <option value="rescue">Penyelamatan / Rescue</option>
                                    <option value="banjir">Banjir</option>
                                    <option value="lainnya">Lain-lain</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small text-muted">Lokasi Kejadian (Alamat)</label>
                                <textarea name="lokasi" class="form-control bg-light border-0" rows="2"
                                    placeholder="Masukkan alamat lengkap lokasi kejadian..." required></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Latitude <span
                                        class="text-muted fw-normal">(opsional)</span></label>
                                <input type="text" name="latitude" class="form-control bg-light border-0"
                                    placeholder="Contoh: -0.123456">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Longitude <span
                                        class="text-muted fw-normal">(opsional)</span></label>
                                <input type="text" name="longitude" class="form-control bg-light border-0"
                                    placeholder="Contoh: 100.112345">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small text-muted">Deskripsi / Kronologi</label>
                                <textarea name="deskripsi" class="form-control bg-light border-0" rows="3"
                                    placeholder="Tuliskan detail kejadian..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="simpan_laporan" class="btn btn-hijau shadow-sm px-4">
                            <i class="bi bi-save me-1"></i> Simpan Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // ── Search & Filter real-time ────────────────────────────
        const searchInput = document.getElementById('searchInput');
        const filterStatus = document.getElementById('filterStatus');
        const tabelBody = document.getElementById('tabelBody');

        function filterTabel() {
            const keyword = searchInput.value.toLowerCase();
            const status = filterStatus.value.toLowerCase();
            const rows = tabelBody.querySelectorAll('tr:not(#emptyRow):not(#noResultRow)');
            let visible = 0;

            rows.forEach(function (row) {
                const teks = row.innerText.toLowerCase();
                const badge = row.querySelector('.badge');
                const statusRow = badge ? badge.innerText.toLowerCase().trim() : '';
                const cocok = teks.includes(keyword) && (status === '' || statusRow === status);
                row.style.display = cocok ? '' : 'none';
                if (cocok) visible++;
            });

            let noResult = document.getElementById('noResultRow');
            if (visible === 0 && rows.length > 0) {
                if (!noResult) {
                    noResult = document.createElement('tr');
                    noResult.id = 'noResultRow';
                    noResult.innerHTML = '<td colspan="5" class="py-4 text-center text-muted">'
                        + '<i class="bi bi-search me-2"></i>Tidak ada data yang cocok.</td>';
                    tabelBody.appendChild(noResult);
                }
            } else if (noResult) {
                noResult.remove();
            }
        }

        searchInput.addEventListener('input', filterTabel);
        filterStatus.addEventListener('change', filterTabel);

        // ── Auto-dismiss alert setelah 4 detik ───────────────────
        document.querySelectorAll('.alert').forEach(function (el) {
            setTimeout(function () {
                bootstrap.Alert.getOrCreateInstance(el).close();
            }, 4000);
        });
    </script>

</body>

</html>