<?php
include '../../config/koneksi.php';

// Memastikan variabel $conn sudah siap digunakan
if (!isset($conn)) {
    die("Error: Variabel koneksi database \$conn tidak ditemukan. Periksa kembali file koneksi.php Anda.");
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personil - DAMKAR Padang</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">

    <style>
        .custom-modal-overlay {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            background: rgba(0, 0, 0, 0.6) !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            z-index: 9999 !important;
            display: none !important; 
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .custom-modal-overlay.open {
            display: flex !important;
            opacity: 1 !important;
        }

        .custom-modal-box {
            background: white !important;
            border-radius: 12px !important;
            width: 550px !important;
            max-width: 90% !important;
            overflow: hidden !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3) !important;
            transform: translateY(-50px);
            transition: transform 0.3s ease;
        }

        .custom-modal-overlay.open .custom-modal-box {
            transform: translateY(0) !important;
        }
    </style>
</head>

<body>

    <div id="sidebar" class="shadow">
        <div class="sidebar-header">
            <img src="../../assets/img/logo_damkar.png" alt="Logo" width="140" height="80"
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
                    <a href="personil.php" class="active">Data Personil</a>
                    <a href="penempatan_pos.php">Penempatan Pos</a>
                    <a href="jadwal_piket.php">Jadwal Piket</a>
                    <a href="riwayat_tugas.php">Riwayat Tugas</a>
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

        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h2 class="fw-bold m-0 text-uppercase">PERSONIL DAMKAR</h2>
                <div class="text-muted small">Sistem Informasi Manajemen Personil Pemadam Kebakaran</div>
            </div>
            <button class="btn btn-danger fw-bold px-4 rounded-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-circle me-2"></i>Tambah Personil
            </button>
        </div>

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

        <div class="main-card">
            <div class="main-card-header">
                <h6><i class="bi bi-people me-2 text-danger"></i>Daftar Personil</h6>
                <div class="d-flex gap-2 filter-bar flex-wrap">
                    <input type="text" id="searchInput" class="form-control" style="width:220px" placeholder="🔍  Cari nama / NIP...">
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

                        $result = mysqli_query($conn, "SELECT * FROM tbl_daftar ORDER BY nip ASC");

                        if ($result && mysqli_num_rows($result) > 0):
                            $i = 0;
                            while ($row = mysqli_fetch_assoc($result)):
                                $nama_personil = !empty($row['nama_personil']) ? $row['nama_personil'] : (!empty($row['nama']) ? $row['nama'] : 'Tanpa Nama');
                                $inisial   = strtoupper(substr($nama_personil, 0, 1));
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
                                            <?= $inisial; ?>
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark"><?= htmlspecialchars($nama_personil) ?></div>
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
                                        <a href="hapus.php?menu=tbl_daftar&id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data <?= htmlspecialchars($nama_personil) ?>?')">
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
    function filterTable() {
        let searchText = document.getElementById('searchInput').value.toLowerCase();
        let jabatanFilter = document.getElementById('filterJabatan').value.toLowerCase();
        let statusFilter = document.getElementById('filterStatus').value.toLowerCase();
        let rows = document.querySelectorAll('#tabelPersonil tbody tr');
        
        rows.forEach(row => {
            if(row.querySelector('.empty-state')) return;
            
            let cells = row.getElementsByTagName('td');
            if (cells.length < 7) return;

            let nip = cells[0].innerText.toLowerCase();
            let nama = cells[1].innerText.toLowerCase();
            let jabatan = cells[2].innerText.toLowerCase().trim();
            let status = cells[6].innerText.toLowerCase().trim();
            
            let matchesSearch = nip.includes(searchText) || nama.includes(searchText);
            let matchesJabatan = jabatanFilter === "" || jabatan === jabatanFilter;
            let matchesStatus = statusFilter === "" || status === statusFilter;
            
            if (matchesSearch && matchesJabatan && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    document.getElementById('searchInput').addEventListener('keyup', filterTable);
    document.getElementById('filterJabatan').addEventListener('change', filterTable);
    document.getElementById('filterStatus').addEventListener('change', filterTable);
    </script>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
    $nip           = mysqli_real_escape_string($conn, $_POST['nip']);
    $nama          = mysqli_real_escape_string($conn, $_POST['nama']);
    $jabatan       = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $telepon       = mysqli_real_escape_string($conn, $_POST['telepon']);
    $email         = mysqli_real_escape_string($conn, $_POST['email']);
    $status        = mysqli_real_escape_string($conn, $_POST['status']);

    $query_insert = "INSERT INTO tbl_daftar (nip, nama_personil, jabatan, tanggal_lahir, telepon, email, status) 
                     VALUES ('$nip', '$nama', '$jabatan', '$tanggal_lahir', '$telepon', '$email', '$status')";

    if (mysqli_query($conn, $query_insert)) {
        echo "<script>
                alert('Data personil baru berhasil disimpan!');
                window.location.href = 'personil.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Gagal menyimpan data: " . mysqli_error($conn) . "');
              </script>";
    }
}
?>