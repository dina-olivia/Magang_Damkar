<?php 
include '../../config/koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personil - DAMKAR Padang</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root { 
            --fire-red: #b91c1c; 
            --dark-sidebar: #0f172a; 
            --sidebar-text: #94a3b8;
        }
        
        body { background-color: #f1f5f9; margin: 0; padding: 0; display: flex; font-family: 'Segoe UI', sans-serif; }

        /* ===== SIDEBAR ===== */
        #sidebar {
            width: 280px; height: 100vh; position: fixed; left: 0; top: 0;
            background-color: var(--dark-sidebar); display: flex; flex-direction: column; z-index: 1000;
        }

        .sidebar-header {
            padding: 16px 20px;
            background-color: var(--fire-red);
            color: white;
            display: flex;
            align-items: center;
            flex-shrink: 0;
            border-left: 4px solid rgba(255,255,255,0.3);
        }

        .sidebar-content { flex-grow: 1; overflow-y: auto; overflow-x: hidden; }
        .sidebar-content::-webkit-scrollbar { width: 4px; }
        .sidebar-content::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }

        /* Nav item wrapper */
        .nav-section { padding: 6px 0; }

        /* Top-level link */
        #sidebar .nav-top-link {
            color: var(--sidebar-text);
            text-decoration: none;
            padding: 13px 22px;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
            font-weight: 500;
            border-left: 4px solid transparent;
            transition: 0.2s;
            cursor: pointer;
        }

        #sidebar .nav-top-link i { margin-right: 13px; font-size: 1.15rem; }

        #sidebar .nav-top-link:hover {
            background-color: #1e293b;
            color: #fff;
            border-left-color: #ef4444;
        }

        #sidebar .nav-top-link.active {
            background-color: #1e293b;
            color: #fff;
            border-left-color: #ef4444;
        }

        /* Sub-menu */
        .sub-menu { background-color: #1a2236; }

        .sub-menu a {
            color: #64748b;
            text-decoration: none;
            padding: 10px 22px 10px 52px;
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            border-left: 4px solid transparent;
            transition: 0.2s;
        }

        .sub-menu a i { margin-right: 10px; font-size: 0.85rem; }

        .sub-menu a:hover {
            background-color: #1e293b;
            color: #cbd5e1;
            border-left-color: #ef4444;
        }

        .sub-menu a.active {
            background-color: #1e293b;
            color: #fff;
            font-weight: 600;
            border-left-color: #ef4444;
        }

        /* Chevron */
        .chevron { margin-left: auto; font-size: 0.8rem; transition: transform 0.25s; }
        .nav-top-link[aria-expanded="true"] .chevron { transform: rotate(180deg); }

        /* ===== MAIN CONTENT ===== */
        #main-content {
            margin-left: 280px;
            padding: 36px 40px;
            width: calc(100% - 280px);
            min-height: 100vh;
        }

        /* Page header */
        .page-header-title {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            line-height: 1.1;
        }

        .page-header-sub { color: #64748b; font-size: 0.95rem; margin-top: 4px; }

        /* Stat Cards — mirip dashboard */
        .stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 24px 28px;
            border: 1px solid #e2e8f0;
            border-bottom: 4px solid var(--fire-red);
            transition: box-shadow 0.2s;
        }

        .stat-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,0.08); }

        .stat-label {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
            margin-top: 6px;
        }

        /* Main card */
        .main-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .main-card-header {
            padding: 18px 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .main-card-header h6 {
            font-weight: 700;
            color: #0f172a;
            font-size: 1rem;
            margin: 0;
        }

        .main-card-body { padding: 20px 24px; }

        /* Table */
        .table thead th {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: #94a3b8;
            border-bottom: 2px solid #f1f5f9;
            padding: 10px 14px;
            background: #f8fafc;
        }

        .table tbody td {
            padding: 13px 14px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
            color: #334155;
        }

        .table tbody tr:hover { background: #f8fafc; }

        /* Avatar */
        .avatar-circle {
            width: 38px; height: 38px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 14px;
            color: #fff; flex-shrink: 0;
        }

        /* Badge jabatan */
        .badge-jabatan {
            font-size: 11px; font-weight: 600;
            padding: 4px 10px; border-radius: 20px;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #94a3b8;
        }

        .empty-state i { font-size: 3rem; margin-bottom: 12px; display: block; }
        .empty-state p { font-size: 0.95rem; }

        /* Search/filter controls */
        .filter-bar .form-control,
        .filter-bar .form-select {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            font-size: 0.875rem;
            padding: 8px 12px;
            background: #f8fafc;
        }

        .filter-bar .form-control:focus,
        .filter-bar .form-select:focus {
            border-color: var(--fire-red);
            box-shadow: 0 0 0 3px rgba(185,28,28,0.1);
        }
    </style>
</head>
<body>

<!-- ===== SIDEBAR ===== -->
<div id="sidebar" class="shadow">

    <!-- Header -->
    <div class="sidebar-header">
        <img src="../assets/img/logo-damkar.png" alt="Logo" width="36" height="36"
             onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/b/b0/Logo_Damkar.png'"
             class="rounded">
        <span class="fw-bold ms-2 fs-6">DAMKAR PADANG</span>
    </div>

    <div class="sidebar-content">
        <div class="nav-section">

            <!-- Dashboard -->
            <a href="../index.php" class="nav-top-link">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <!-- Manajemen Kejadian -->
            <a href="manajemen_kejadian.php" class="nav-top-link">
                <i class="bi bi-megaphone"></i> Manajemen Kejadian
            </a>

            <!-- Operasional (collapsed) -->
            <a class="nav-top-link" data-bs-toggle="collapse" href="#menuOperasional"
               aria-expanded="false">
                <i class="bi bi-clipboard-check"></i> Operasional
                <i class="bi bi-chevron-down chevron"></i>
            </a>
            <div class="collapse sub-menu" id="menuOperasional">
                <a href="penugasan_tim.php"><i class="bi bi-dot"></i> Penugasan Tim</a>
                <a href="monitoring_armada.php"><i class="bi bi-dot"></i> Monitoring Armada</a>
                <a href="status_penanganan.php"><i class="bi bi-dot"></i> Status Penanganan</a>
                <a href="riwayat_penugasan.php"><i class="bi bi-dot"></i> Riwayat Penugasan</a>
            </div>

            <!-- Personil (aktif & expand) -->
            <a class="nav-top-link active" data-bs-toggle="collapse" href="#menuPersonil"
               aria-expanded="true">
                <i class="bi bi-people"></i> Personil
                <i class="bi bi-chevron-down chevron"></i>
            </a>
            <div class="collapse show sub-menu" id="menuPersonil">
                <a href="personil.php" class="active"><i class="bi bi-dot"></i> Data Personil</a>
                <a href="penempatan_pos.php"><i class="bi bi-dot"></i> Penempatan Pos</a>
                <a href="jadwal_piket.php"><i class="bi bi-dot"></i> Jadwal Piket</a>
                <a href="riwayat_tugas.php"><i class="bi bi-dot"></i> Riwayat Tugas</a>
            </div>

            <!-- Armada -->
            <a href="armada.php" class="nav-top-link">
                <i class="bi bi-truck"></i> Armada
            </a>

            <!-- Sarpras (collapsed) -->
            <a class="nav-top-link" data-bs-toggle="collapse" href="#menuSarpras"
               aria-expanded="false">
                <i class="bi bi-tools"></i> Sarpras
                <i class="bi bi-chevron-down chevron"></i>
            </a>
            <div class="collapse sub-menu" id="menuSarpras">
                <a href="sarpras.php"><i class="bi bi-dot"></i> Data Sarpras</a>
                <a href="master_bidang.php"><i class="bi bi-dot"></i> Master Bidang</a>
            </div>

            <!-- Laporan -->
            <a href="laporan.php" class="nav-top-link">
                <i class="bi bi-file-earmark-text"></i> Laporan
            </a>

            <!-- Pengaturan -->
            <a href="pengaturan.php" class="nav-top-link">
                <i class="bi bi-gear"></i> Pengaturan
            </a>

            <!-- Keluar -->
            <a href="../logout.php" class="nav-top-link mt-2" style="color:#f87171;">
                <i class="bi bi-box-arrow-left"></i> Keluar
            </a>

        </div>
    </div>
</div>

<!-- ===== MAIN CONTENT ===== -->
<div id="main-content">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="page-header-title">PERSONIL DAMKAR</div>
            <div class="page-header-sub">Sistem Informasi Manajemen Personil Pemadam Kebakaran</div>
        </div>
        <button class="btn btn-danger px-4 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle me-2"></i>Tambah Personil
        </button>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="stat-label">Total Personil</div>
                <div class="stat-value">
                    <?php
                    $r = mysqli_query($conn, "SELECT COUNT(*) as c FROM tbl_daftar");
                    echo ($r ? mysqli_fetch_assoc($r)['c'] : 0);
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="stat-label">Personil Aktif</div>
                <div class="stat-value">
                    <?php
                    $r = mysqli_query($conn, "SELECT COUNT(*) as c FROM tbl_daftar WHERE status='Aktif'");
                    echo ($r ? mysqli_fetch_assoc($r)['c'] : 0);
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="stat-label">Komandan Regu</div>
                <div class="stat-value">
                    <?php
                    $r = mysqli_query($conn, "SELECT COUNT(*) as c FROM tbl_daftar WHERE jabatan='Komandan Regu'");
                    echo ($r ? mysqli_fetch_assoc($r)['c'] : 0);
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="stat-label">Tidak Aktif</div>
                <div class="stat-value">
                    <?php
                    $r = mysqli_query($conn, "SELECT COUNT(*) as c FROM tbl_daftar WHERE status='Tidak Aktif'");
                    echo ($r ? mysqli_fetch_assoc($r)['c'] : 0);
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel -->
    <div class="main-card">
        <div class="main-card-header">
            <h6><i class="bi bi-people me-2 text-danger"></i>Daftar Personil</h6>
            <div class="d-flex gap-2 filter-bar flex-wrap">
                <input type="text" id="searchInput" class="form-control" style="width:220px"
                       placeholder="&#128269;  Cari nama / NIP...">
                <select id="filterJabatan" class="form-select" style="width:170px">
                    <option value="">Semua Jabatan</option>
                    <option value="Komandan Regu">Komandan Regu</option>
                    <option value="Pengemudi">Pengemudi</option>
                    <option value="Petugas">Petugas</option>
                </select>
                <select id="filterStatus" class="form-select" style="width:150px">
                    <option value="">Semua Status</option>
                    <option value="Aktif">Aktif</option>
                    <option value="Tidak Aktif">Tidak Aktif</option>
                </select>
            </div>
        </div>

        <div class="main-card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0" id="tabelPersonil">
                    <thead>
                        <tr>
                            <th>NIP</th>
                            <th>Nama Personil</th>
                            <th>Jabatan</th>
                            <th>Telepon</th>
                            <th>Email</th>
                            <th>Tgl Lahir</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $colors = ['#e53935','#1d4ed8','#16a34a','#d97706','#7c3aed','#0891b2','#db2777'];
                    $badge_map = [
                        'Komandan Regu' => ['bg-danger-subtle',   'text-danger'],
                        'Pengemudi'     => ['bg-primary-subtle',  'text-primary'],
                        'Petugas'       => ['bg-success-subtle',  'text-success'],
                    ];

                    $result = mysqli_query($koneksi, "SELECT * FROM tbl_daftar ORDER BY nip ASC");

                    if ($result && mysqli_num_rows($result) > 0):
                        $i = 0;
                        while ($row = mysqli_fetch_assoc($result)):
                            $inisial   = strtoupper(substr($row['nama'], 0, 1));
                            $warna     = $colors[$i % count($colors)];
                            $tgl       = date('d M Y', strtotime($row['tanggal_lahir']));
                            $umur      = date('Y') - date('Y', strtotime($row['tanggal_lahir']));
                            [$bg, $tc] = $badge_map[$row['jabatan']] ?? ['bg-secondary-subtle', 'text-secondary'];
                            $i++;
                    ?>
                        <tr>
                            <td class="fw-semibold text-muted small"><?= htmlspecialchars($row['nip']) ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-circle" style="background:<?= $warna ?>">
                                        <?= $inisial ?>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark"><?= htmlspecialchars($row['nama']) ?></div>
                                        <div class="text-muted" style="font-size:11px">
                                            Umur <?= $umur ?> tahun
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-jabatan <?= $bg ?> <?= $tc ?>">
                                    <?= htmlspecialchars($row['jabatan']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($row['telepon']) ?></td>
                            <td class="text-muted"><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= $tgl ?></td>
                            <td>
                                <?php if ($row['status'] === 'Aktif'): ?>
                                    <span class="badge bg-success-subtle text-success fw-semibold">
                                        <i class="bi bi-check-circle me-1"></i>Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary fw-semibold">
                                        <i class="bi bi-dash-circle me-1"></i>Tidak Aktif
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="detail_personil.php?id=<?= $row['id'] ?>"
                                       class="btn btn-sm btn-outline-secondary" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="edit_personil.php?id=<?= $row['id'] ?>"
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus"
                                            onclick="hapus(<?= $row['id'] ?>, '<?= htmlspecialchars($row['nama']) ?>')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php
                        endwhile;
                    else:
                    ?>
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="bi bi-people"></i>
                                    <strong class="d-block text-dark mb-1">Belum Ada Data Personil</strong>
                                    <p>Klik tombol "Tambah Personil" untuk menambahkan data.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ===== MODAL TAMBAH ===== -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background:var(--fire-red)">
                <h5 class="modal-title text-white fw-bold">
                    <i class="bi bi-person-plus me-2"></i>Tambah Personil
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="proses/tambah_personil.php" method="POST">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">NIP</label>
                            <input type="text" name="nip" class="form-control"
                                   placeholder="PK-YYYY-XXX" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control"
                                   placeholder="Nama lengkap personil" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Jabatan</label>
                            <select name="jabatan" class="form-select" required>
                                <option value="">-- Pilih Jabatan --</option>
                                <option value="Komandan Regu">Komandan Regu</option>
                                <option value="Pengemudi">Pengemudi</option>
                                <option value="Petugas">Petugas</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Telepon</label>
                            <input type="text" name="telepon" class="form-control"
                                   placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Email</label>
                            <input type="email" name="email" class="form-control"
                                   placeholder="nama@damkar.go.id">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="Aktif">Aktif</option>
                                <option value="Tidak Aktif">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-semibold">
                        <i class="bi bi-save me-1"></i>Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Search & Filter
    const searchInput   = document.getElementById('searchInput');
    const filterJabatan = document.getElementById('filterJabatan');
    const filterStatus  = document.getElementById('filterStatus');

    function doFilter() {
        const kw  = searchInput.value.toLowerCase();
        const jab = filterJabatan.value.toLowerCase();
        const sta = filterStatus.value.toLowerCase();

        document.querySelectorAll('#tabelPersonil tbody tr').forEach(row => {
            const txt  = row.innerText.toLowerCase();
            const jabC = row.cells[2]?.innerText.toLowerCase() ?? '';
            const staC = row.cells[6]?.innerText.toLowerCase() ?? '';
            row.style.display =
                txt.includes(kw) &&
                (jab === '' || jabC.includes(jab)) &&
                (sta === '' || staC.includes(sta))
                ? '' : 'none';
        });
    }

    searchInput.addEventListener('input',    doFilter);
    filterJabatan.addEventListener('change', doFilter);
    filterStatus.addEventListener('change',  doFilter);

    function hapus(id, nama) {
        if (confirm('Hapus personil "' + nama + '"?')) {
            window.location.href = 'proses/hapus_personil.php?id=' + id;
        }
    }
</script>
</body>
</html>