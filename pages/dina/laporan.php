<?php include '../../config/koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Log Laporan Penanganan - E-DAMKAR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            margin: 0;
        }

        #main-content {
            flex: 1;
            /* Ini kunci agar konten mengisi ruang di kanan sidebar */
            padding: 25px;
            background-color: #f8f9fa;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border: none;
        }

        .btn-purple {
            background-color: #6f42c1;
            color: white;
            border: none;
        }

        .btn-purple:hover {
            background-color: #59359a;
            color: white;
        }
    </style>
</head>

<body class="d-flex">

    <?php include '../../config/sidebar.php'; ?>

    <div id="main-content">

        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h4 class="fw-bold mb-1">Log Laporan Penanganan</h4>
                <p class="text-muted small">Manajemen verifikasi laporan kejadian dari masyarakat secara real-time.</p>
            </div>
            <button class="btn btn-purple shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalLaporanBaru">
                <i class="bi bi-plus-lg me-2"></i> Buat Laporan Baru
            </button>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <p class="small text-muted mb-1">Total Laporan</p>
                    <h3 class="fw-bold">0</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <p class="small text-muted mb-1">Menunggu Verifikasi</p>
                    <h3 class="fw-bold">0</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <p class="small text-muted mb-1">Selesai Ditangani</p>
                    <h3 class="fw-bold">0</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center border-dashed" style="border-style: dashed;">
                    <i class="bi bi-file-earmark-arrow-down text-primary mb-1"></i><br>
                    <a href="#" class="text-decoration-none small fw-bold">Export Excel</a>
                </div>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0"
                        placeholder="Cari lokasi, pelapor, atau deskripsi...">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select">
                    <option>Semua Status</option>
                </select>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <table class="table mb-0">
                <thead class="table-light small text-uppercase">
                    <tr>
                        <th class="py-3 px-4">Lokasi & Kejadian</th>
                        <th>Pelapor & Kontak</th>
                        <th>Waktu Lapor</th>
                        <th>Status</th>
                        <th>Aksi Ops</th>
                    </tr>
                </thead>
            </table>
            <div class="py-5 text-center bg-white">
                <i class="bi bi-database-exclamation text-light" style="font-size: 3rem;"></i>
                <h6 class="fw-bold text-muted mt-2">DATABASE KOSONG</h6>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <div class="modal fade" id="modalLaporanBaru" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                <div class="modal-header bg-danger text-white" style="border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title fw-bold"><i class="bi bi-megaphone me-2"></i> Buat Laporan Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form action="proses_laporan.php" method="POST">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Nama Pelapor</label>
                                <input type="text" name="nama_pelapor" class="form-control bg-light border-0"
                                    placeholder="Contoh: Budi Santoso" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Kontak (WA/Telp)</label>
                                <input type="text" name="kontak_pelapor" class="form-control bg-light border-0"
                                    placeholder="08xxxxxx" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold small text-muted">Jenis Kejadian</label>
                                <select name="jenis_kejadian" class="form-select bg-light border-0" required>
                                    <option value="" selected disabled>Pilih Kejadian...</option>
                                    <option value="Kebakaran Lahan">Kebakaran Lahan</option>
                                    <option value="Kebakaran Pemukiman">Kebakaran Pemukiman</option>
                                    <option value="Penyelamatan">Penyelamatan / Rescue</option>
                                    <option value="Lain-lain">Lain-lain</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold small text-muted">Lokasi Kejadian</label>
                                <textarea name="lokasi_kejadian" class="form-control bg-light border-0" rows="3"
                                    placeholder="Masukkan alamat lengkap lokasi kejadian..." required></textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Waktu Kejadian</label>
                                <input type="datetime-local" name="waktu_lapor" class="form-control bg-light border-0"
                                    value="<?= date('Y-m-d\TH:i'); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Status Awal</label>
                                <input type="text" class="form-control bg-light border-0" value="Menunggu Verifikasi"
                                    readonly>
                                <input type="hidden" name="status" value="Menunggu Verifikasi">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-purple shadow-sm px-4">Simpan Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>