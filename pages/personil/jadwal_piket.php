<?php require_once __DIR__ . '/../../config/koneksi.php';

// STATISTICS
$total_jadwal   = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM jadwal_piket"));
$jumlah_shift   = mysqli_num_rows(mysqli_query($conn, "SELECT DISTINCT shift FROM jadwal_piket WHERE shift != ''"));
$total_personil = mysqli_num_rows(mysqli_query($conn, "SELECT DISTINCT nama_personil FROM jadwal_piket"));

// DISTRIBUSI BOX
$query_distribusi = mysqli_query($conn, "
    SELECT 
        shift,
        COUNT(id) as jumlah
    FROM jadwal_piket
    WHERE shift IN ('Pagi', 'Siang', 'Malam')
    GROUP BY shift
");

// QUERY UTAMA
$query = mysqli_query($conn, "
    SELECT 
        jp.tanggal, 
        jp.shift, 
        jp.jam_kerja,
        GROUP_CONCAT(CONCAT('<div class=\"personil-row\"><i class=\"bi bi-shield-fill-check text-danger me-2\"></i>', tbl_daftar.nama_personil, ' <span class=\"text-muted small\">(NIP: ', tbl_daftar.nip, ')</span></div>') SEPARATOR '') AS daftar_personil,
        COUNT(jp.id) AS jumlah_piket
    FROM jadwal_piket jp
    INNER JOIN tbl_daftar ON jp.nama_personil = tbl_daftar.nama_personil 
    GROUP BY jp.tanggal, jp.shift, jp.jam_kerja
    ORDER BY jp.tanggal DESC, jp.shift ASC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Piket - E-DAMKAR Kota Padang</title>

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
            /* Kondisi Awal: Tersembunyi Total */
            display: none !important; 
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        /* Saat berkelas .open, override display-none menjadi flex */
        .custom-modal-overlay.open {
            display: flex !important;
            opacity: 1 !important;
        }

        .custom-modal-box {
            background: white !important;
            border-radius: 12px !important;
            width: 500px !important;
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
                    <a href="personil.php">Data Personil</a>
                    <a href="penempatan_pos.php">Penempatan Pos</a>
                    <a href="jadwal_piket.php" class="active">Jadwal Piket</a>
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
        <header class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold m-0 text-uppercase">Jadwal Piket Personil</h2>
                <p class="text-muted m-0">Kelola Pengaturan Shift Dan Tugas Piket Anggota Damkar</p>
            </div>
            <div class="text-end">
                <span class="badge bg-danger mb-1">SIAGA 1</span>
                <div class="fw-bold small"><?php echo date('d M Y | H:i'); ?> WIB</div>
            </div>
        </header>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card card-custom shadow-sm p-4 bg-white text-center">
                    <h6 class="text-muted text-uppercase small fw-bold">Total Data Piket</h6>
                    <h2 class="fw-bold m-0 text-dark"><?= $total_jadwal; ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom shadow-sm p-4 bg-white text-center">
                    <h6 class="text-muted text-uppercase small fw-bold">Jumlah Shift</h6>
                    <h2 class="fw-bold m-0 text-dark"><?= $jumlah_shift; ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom shadow-sm p-4 bg-white text-center">
                    <h6 class="text-muted text-uppercase small fw-bold">Personil Terlibat</h6>
                    <h2 class="fw-bold m-0 text-dark"><?= $total_personil; ?></h2>
                </div>
            </div>
        </div>

        <div class="card card-custom shadow-sm p-4 bg-white mb-5">
            <h5 class="fw-bold text-dark mb-4">Distribusi Total Personil per Shift</h5>
            <div class="row g-3">
                <?php 
                if(mysqli_num_rows($query_distribusi) > 0) {
                    while($d = mysqli_fetch_array($query_distribusi)){ 
                ?>
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded-3 text-center border">
                            <p class="text-muted small text-uppercase fw-bold mb-1"><?= htmlspecialchars($d['shift']); ?></p>
                            <h3 class="fw-bold m-0 text-danger"><?= $d['jumlah']; ?> <span class="fs-6 fw-normal text-muted">Anggota</span></h3>
                        </div>
                    </div>
                <?php 
                    }
                } else {
                    echo "<div class='col-12 text-center py-3 text-muted'>Belum ada data distribusi shift.</div>";
                }
                ?>
            </div>
        </div>

        <div class="card card-custom shadow-sm p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold text-dark m-0">Daftar Jadwal Piket Anggota</h5>
                <div class="d-flex gap-2">
                    <div class="search-bar">
                        <i class="bi bi-search text-muted"></i>
                        <input type="text" id="searchInput" placeholder="Cari tanggal atau shift...">
                    </div>
                    <button class="btn btn-danger fw-bold px-3 py-2 d-flex align-items-center gap-2 rounded-3" onclick="openModal()">
                        <i class="bi bi-calendar-plus"></i> Tambah Anggota Piket
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tabelJadwal">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Shift</th>
                            <th>Jam Kerja</th>
                            <th style="width: 40%;">Personil Piket</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(mysqli_num_rows($query) > 0) {
                            while($data = mysqli_fetch_array($query)){ 
                        ?>
                        <tr>
                            <td class="fw-semibold"><?= date('d M Y', strtotime($data['tanggal'])); ?></td>
                            <td><span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1"><?= htmlspecialchars($data['shift']); ?></span></td>
                            <td><i class="bi bi-clock me-1 text-muted"></i> <?= htmlspecialchars($data['jam_kerja']); ?></td>
                            <td>
                                <div class="personil-container">
                                    <?= $data['daftar_personil']; ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge-jumlah"><?= $data['jumlah_piket']; ?> Orang</span>
                            </td>
                            <td class="text-center action-links">
                                <a href="edit.php?menu=jadwal_piket&tanggal=<?= $data['tanggal']; ?>&shift=<?= $data['shift']; ?>" class="btn btn-sm btn-outline-primary border-0" title="Edit"><i class="bi bi-pencil-square fs-5"></i></a>
                                <a href="hapus.php?menu=jadwal_piket&tanggal=<?= $data['tanggal']; ?>&shift=<?= $data['shift']; ?>" class="btn btn-sm btn-outline-danger border-0" title="Hapus" onclick="return confirm('Hapus seluruh jadwal pada tanggal dan shift ini?')"><i class="bi bi-trash fs-5"></i></a>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else { 
                        ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-people text-light d-block mb-2" style="font-size: 3rem;"></i>
                                Belum Ada Data Jadwal Piket Personil.
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="custom-modal-overlay" id="modalTambah">
        <div class="custom-modal-box">
            <div class="bg-danger text-white p-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 fw-bold"><i class="bi bi-calendar-plus me-2"></i>Tambah Anggota Piket</h5>
                <button type="button" class="btn-close btn-close-white" onclick="closeModal()"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Tanggal Piket</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Shift</label>
                        <select name="shift" class="form-select" required>
                            <option value="">-- Pilih Shift --</option>
                            <option value="Pagi">Pagi</option>
                            <option value="Siang">Siang</option>
                            <option value="Malam">Malam</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Jam Kerja</label>
                        <input type="text" name="jam_kerja" class="form-control" value="08:00 - 08:00" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Nama Personil</label>
                        <select name="nama_personil" class="form-select" required>
                            <option value="">-- Pilih Anggota Damkar --</option>
                            <?php
                            $ambil_personil = mysqli_query($conn, "SELECT nama_personil, nip FROM tbl_daftar ORDER BY nama_personil ASC");
                            while ($personil = mysqli_fetch_assoc($ambil_personil)) {
                                echo "<option value='".htmlspecialchars($personil['nama_personil'])."'>".htmlspecialchars($personil['nama_personil'])." - (NIP: ".htmlspecialchars($personil['nip']).")</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer p-3 bg-light border-top d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary fw-semibold px-4" onclick="closeModal()">Batal</button>
                    <button type="submit" name="simpan_jadwal" class="btn btn-danger fw-semibold px-4"><i class="bi bi-floppy me-2"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function openModal() { 
            document.getElementById('modalTambah').classList.add('open'); 
        }
        
        function closeModal() { 
            document.getElementById('modalTambah').classList.remove('open'); 
        }

        // Menutup modal jika user klik area abu-abu di luar kotak form
        window.onclick = function(event) {
            const overlay = document.getElementById('modalTambah');
            if (event.target == overlay) {
                closeModal();
            }
        }

        // Live Search Realtime
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('input', function() {
            const keyword = this.value.toLowerCase();
            const rows = document.querySelectorAll('#tabelJadwal tbody tr');
            rows.forEach(row => {
                if(row.cells.length === 1) return; 
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(keyword) ? '' : 'none';
            });
        });
    </script>
</body>

</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_jadwal'])) {
    $tanggal        = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $shift          = mysqli_real_escape_string($conn, $_POST['shift']);
    $jam_kerja      = mysqli_real_escape_string($conn, $_POST['jam_kerja']);
    $nama_personil  = mysqli_real_escape_string($conn, $_POST['nama_personil']);

    $cek_ganda = mysqli_query($conn, "SELECT * FROM jadwal_piket WHERE tanggal='$tanggal' AND shift='$shift' AND nama_personil='$nama_personil'");
    if(mysqli_num_rows($cek_ganda) > 0) {
        echo "<script>alert('Personil tersebut sudah terdaftar di shift dan tanggal ini!');</script>";
    } else {
        $query_insert = "INSERT INTO jadwal_piket (tanggal, shift, jam_kerja, nama_personil) VALUES ('$tanggal', '$shift', '$jam_kerja', '$nama_personil')";
        if (mysqli_query($conn, $query_insert)) {
            echo "<script>alert('Anggota berhasil ditambahkan ke jadwal piket!'); window.location.href = 'jadwal_piket.php';</script>";
            exit;
        }
    }
}
?>