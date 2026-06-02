<?php require_once __DIR__ . '/../../config/koneksi.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personil - DAMKAR Padang</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <link rel="stylesheet" href="../../assets/css/style.css">
    
</head>
<body>

<div id="sidebar" class="shadow">
        <div class="sidebar-header">
            <img src="../../assets/img/logo_damkar.png" alt="Logo" width="140" height="80"
                onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/b/b0/Logo_Damkar.png'">
            <span class="fw-bold ms-2">DAMKAR PADANG</span>
        </div>

    <div class="sidebar-content">
        <div class="nav-section">

            <a href="../../index.php" class="nav-top-link">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

           <a class="nav-top-link" data-bs-toggle="collapse" href="#menuManajemenKejadian" aria-expanded="false">
                <i class="bi bi-clipboard-check"></i> Manajemen Kejadian
                <i class="bi bi-chevron-down chevron"></i>
            </a>
            <div class="collapse sub-menu" id="menuManajemenKejadian">
                <a href="input_laporan.php">Input Laporan</a>
                <a href="monitoring_kejadian.php">Monitoring Kejadian</a>
                <a href="detail_kejadian.php">Detail Kejadian</a>
                <a href="timeline_kronologi.php">Timeline Kronologi</a>
            </div>
            <a class="nav-top-link" data-bs-toggle="collapse" href="#menuOperasional" aria-expanded="false">
                <i class="bi bi-clipboard-check"></i> Operasional
                <i class="bi bi-chevron-down chevron"></i>
            </a>
            <div class="collapse sub-menu" id="menuOperasional">
                <a href="penugasan_tim.php">Penugasan Tim</a>
                <a href="monitoring_armada.php">Monitoring Armada</a>
                <a href="status_penanganan.php">Status Penanganan</a>
                <a href="riwayat_penugasan.php">Riwayat Penugasan</a>
            </div>

            <a class="nav-top-link active" data-bs-toggle="collapse" href="#menuPersonil" aria-expanded="true">
                <i class="bi bi-people"></i> Personil
                <i class="bi bi-chevron-down chevron"></i>
            </a>
            <div class="collapse show sub-menu" id="menuPersonil">
                <a href="personil.php" class="active">Data Personil</a>
                <a href="penempatan_pos.php">Penempatan Pos</a>
                <a href="jadwal_piket.php">Jadwal Piket</a>
                <a href="riwayat_tugas.php">Riwayat Tugas</a>
            </div>

            <a href="armada.php" class="nav-top-link">
                <i class="bi bi-truck"></i> Armada
            </a>

            <a class="nav-top-link" data-bs-toggle="collapse" href="#menuSarpras" aria-expanded="false">
                <i class="bi bi-tools"></i> Sarpras
                <i class="bi bi-chevron-down chevron"></i>
            </a>
            <div class="collapse sub-menu" id="menuSarpras">
                <a href="sarpras.php">Data Sarpras</a>
                <a href="master_bidang.php">Master Bidang</a>
            </div>

            <a href="laporan.php" class="nav-top-link">
                <i class="bi bi-file-earmark-text"></i> Laporan
            </a>

            <a href="pengaturan.php" class="nav-top-link">
                <i class="bi bi-gear"></i> Pengaturan
            </a>

            <a href="../logout.php" class="nav-top-link mt-2" style="color:#f87171;">
                <i class="bi bi-box-arrow-left"></i> Keluar
            </a>

        </div>
    </div>
</div>

<div id="main-content">

    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="page-header-title"><h2>PERSONIL DAMKAR</h2></div>
            <div class="page-header-sub">Sistem Informasi Manajemen Personil Pemadam Kebakaran</div>
        </div>
        <button class="btn btn-danger px-4 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle me-2"></i>Tambah Personil
        </button>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="stat-label">Total Personil</div>
                <div class="stat-value">
                    <?php
                    $r = isset($koneksi) && $koneksi ? mysqli_query($koneksi, "SELECT COUNT(*) as c FROM tbl_daftar") : false;
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
                    $r = isset($koneksi) && $koneksi ? mysqli_query($koneksi, "SELECT COUNT(*) as c FROM tbl_daftar WHERE status='Aktif'") : false;
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
                    $r = isset($koneksi) && $koneksi ? mysqli_query($koneksi, "SELECT COUNT(*) as c FROM tbl_daftar WHERE jabatan='Komandan Regu'") : false;
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
                    $r = isset($koneksi) && $koneksi ? mysqli_query($koneksi, "SELECT COUNT(*) as c FROM tbl_daftar WHERE status='Tidak Aktif'") : false;
                    echo ($r ? mysqli_fetch_assoc($r)['c'] : 0);
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card">
        <div class="main-card-header">
            <h6><i class="bi bi-people me-2 text-danger"></i>Daftar Personil</h6>
            <div class="d-flex gap-2 filter-bar flex-wrap">
                <input type="text" id="searchInput" class="form-control" style="width:220px" placeholder="&#128269;  Cari nama / NIP...">
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

                    $result = $koneksi ? mysqli_query($koneksi, "SELECT * FROM tbl_daftar ORDER BY nip ASC") : false;

                    if ($result && mysqli_num_rows($result) > 0):
                        $i = 0;
                        while ($row = mysqli_fetch_assoc($result)):
                            $inisial   = !empty($row['nama']) ? strtoupper(substr($row['nama'], 0, 1)) : '?';
                            $warna     = $colors[$i % count($colors)];
                            $tgl       = ($row['tanggal_lahir'] != '0000-00-00' && !empty($row['tanggal_lahir'])) ? date('d M Y', strtotime($row['tanggal_lahir'])) : '-';
                            $umur      = ($row['tanggal_lahir'] != '0000-00-00' && !empty($row['tanggal_lahir'])) ? date('Y') - date('Y', strtotime($row['tanggal_lahir'])) : '-';
                            [$bg, $tc] = $badge_map[$row['jabatan']] ?? ['bg-secondary-subtle', 'text-secondary'];
                            $i++;
                    ?>
                        <tr>
                            <td class="fw-semibold text-muted small"><?= htmlspecialchars($row['nip']) ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-circle" style="background:<?= $warna ?>">
                                      <?= $inisial = strtoupper(substr($row['nama_personil'] ?? '', 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark"><?= htmlspecialchars($row['nama_personil']) ?></div>
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
                                    <a href="detail.php?menu=tbl_daftar&id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="edit.php?menu=tbl_daftar&id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="hapus.php?menu=tbl_daftar&id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data <?= htmlspecialchars($row['nama_personil'] ?? '') ?>?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
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

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background:var(--fire-red)">
                <h5 class="modal-title text-white fw-bold">
                    <i class="bi bi-person-plus me-2"></i>Tambah Personil
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">NIP</label>
                            <input type="text" name="nip" class="form-control" placeholder="PK-YYYY-XXX" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" placeholder="Nama lengkap personil" required>
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
                            <input type="text" name="telepon" class="form-control" placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="nama@damkar.go.id">
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
                    <button type="submit" name="simpan" class="btn btn-danger fw-semibold">
                        <i class="bi bi-save me-1"></i>Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Search & Filter JavaScript
    const searchInput   = document.getElementById('searchInput');
    const filterJabatan = document.getElementById('filterJabatan');
    const filterStatus  = document.getElementById('filterStatus');

    function doFilter() {
        const kw  = searchInput.value.toLowerCase();
        const jab = filterJabatan.value.toLowerCase();
        const sta = filterStatus.value.toLowerCase();

        document.querySelectorAll('#tabelPersonil tbody tr').forEach(row => {
            if (row.querySelector('.empty-state')) return; // Lewati baris jika data kosong
            
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
</script>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
    // Ambil data form dan amankan dari SQL Injection
    $nip           = mysqli_real_escape_string($koneksi, $_POST['nip']);
    $nama          = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jabatan       = mysqli_real_escape_string($koneksi, $_POST['jabatan']);
    $tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $telepon       = mysqli_real_escape_string($koneksi, $_POST['telepon']);
    $email         = mysqli_real_escape_string($koneksi, $_POST['email']);
    $status        = mysqli_real_escape_string($koneksi, $_POST['status']);

    // Query INSERT disesuaikan dengan struktur tabel tbl_daftar Anda
    $query = "INSERT INTO tbl_daftar (nip, nama_personil, jabatan, tanggal_lahir, telepon, email, status) 
              VALUES ('$nip', '$nama', '$jabatan', '$tanggal_lahir', '$telepon', '$email', '$status')";

    if (mysqli_query($koneksi, $query)) {
        // Jika berhasil, munculkan alert sukses dan redirect ke halaman ini sendiri agar form ter-reset
        echo "<script>
                alert('Data personil baru berhasil ditambahkan!');
                window.location.href = 'personil.php';
              </script>";
        exit;
    } else {
        // Jika gagal, munculkan alert error tanpa me-redirect halaman agar tahu letak salahnya
        echo "<script>
                alert('Gagal menambahkan data: " . mysqli_error($koneksi) . "');
              </script>";
    }
}
?>