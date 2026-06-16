<?php $page = 'dashboard'; ?>
<?php
require_once __DIR__ . '/../config/koneksi.php';

$current_page = basename($_SERVER['PHP_SELF']);
$path = $_SERVER['PHP_SELF'];
$root_folder = '/Magang_DAMKAR';
$clean_path = str_replace($root_folder, '', $path);
$levels = substr_count($clean_path, '/');
$base_url = ($levels > 1) ? str_repeat('../', $levels - 1) : '';

// Statistik armada dari database
$total_armada = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS n FROM armada"))['n'] ?? 0;
$armada_siap = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS n FROM armada WHERE status='Siap'"))['n'] ?? 0;
$armada_jalan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS n FROM armada WHERE status='Berangkat' OR status='Di Lokasi'"))['n'] ?? 0;
$result_armada = mysqli_query($conn, "SELECT * FROM armada ORDER BY id ASC");

// Laporan terkait jika ada ?id=
$data_laporan = null;
if (!empty($_GET['id'])) {
    $id_laporan = (int) $_GET['id'];
    $q = mysqli_prepare($conn, "SELECT * FROM laporan_kejadian WHERE id = ?");
    mysqli_stmt_bind_param($q, 'i', $id_laporan);
    mysqli_stmt_execute($q);
    $data_laporan = mysqli_fetch_assoc(mysqli_stmt_get_result($q));
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Armada - E-DAMKAR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= $base_url ?>assets/css/style.css">
    <style>
        body {
            background-color: #f4f5f7;
            font-family: 'Segoe UI', sans-serif;
        }

        /* Penyesuaian konten utama agar tidak tertutup sidebar samping */
        #main-content {
            padding: 25px;
            margin-left: 260px;
            min-height: 100vh;
        }

        .monitor-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .04);
            overflow: hidden;
            border: 1px solid #eef0f3;
        }

        .map-wrapper {
            position: relative;
            background-color: #e5e3df;
            height: 520px;
            width: 100%;
        }

        .map-controls {
            position: absolute;
            top: 15px;
            left: 15px;
            z-index: 10;
        }

        .map-btn {
            background: white;
            border: 1px solid #ccc;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 1px 5px rgba(0, 0, 0, .2);
            color: #333;
        }

        .map-btn:first-child {
            border-radius: 4px 4px 0 0;
            border-bottom: none;
        }

        .map-btn:last-child {
            border-radius: 0 0 4px 4px;
        }

        .live-badge {
            position: absolute;
            top: 15px;
            left: 55px;
            z-index: 10;
            background: rgba(255, 255, 255, .95);
            padding: 6px 12px;
            border-radius: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .1);
            font-size: .78rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            background: #0d6efd;
            border-radius: 50%;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(.9);
                opacity: 1
            }

            50% {
                transform: scale(1.3);
                opacity: .5
            }

            100% {
                transform: scale(.9);
                opacity: 1
            }
        }

        .dummy-map-bg {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: .85;
        }

        .map-marker {
            position: absolute;
            font-size: 2rem;
            transform: translate(-50%, -100%);
            cursor: pointer;
        }

        .unit-sidebar {
            border-left: 1px solid #eef0f3;
            height: 520px;
            overflow-y: auto;
            background: #fafbfc;
        }

        .unit-card {
            background: white;
            border: 1px solid #eef0f3;
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 12px;
            transition: all .2s;
            cursor: pointer;
            position: relative;
        }

        .unit-card:hover {
            border-color: #b6ceff;
            box-shadow: 0 2px 8px rgba(13, 110, 253, .05);
        }

        .badge-unit {
            font-size: .68rem;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 4px;
            position: absolute;
            top: 14px;
            right: 14px;
            text-transform: uppercase;
        }

        .bg-di-lokasi {
            background: #e6f4ea;
            color: #137333;
        }

        .bg-berangkat {
            background: #fef7e0;
            color: #b06000;
        }

        .bg-siap {
            background: #e8f0fe;
            color: #1a73e8;
        }

        .indicator-card {
            background: white;
            border-radius: 12px;
            padding: 16px 20px;
            border: 1px solid #eef0f3;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .02);
        }

        .indicator-title {
            font-size: .72rem;
            color: #8a92a6;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 6px;
        }

        .indicator-value {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1d2129;
            margin: 0;
        }

        .text-purple-value {
            color: #502ec3;
        }
    </style>
</head>

<body>

    <?php include '../config/sidebar.php'; ?>

    <div id="main-content">

        <div class="mb-3">
            <span style="font-size:.85rem;color:#6c757d;font-weight:600;letter-spacing:.5px">
                Pelacakan real time posisi unit pemadam di lapangan
            </span>
            <div class="d-flex justify-content-between align-items-center mt-1">
                <h4 class="fw-bold text-dark m-0">MONITORING ARMADA</h4>
                <?php if ($data_laporan): ?>
                    <span class="badge bg-danger px-3 py-2 small text-uppercase">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Kasus: <?= htmlspecialchars($data_laporan['jenis_kejadian']) ?>
                        (<?= htmlspecialchars($data_laporan['lokasi']) ?>)
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <div class="monitor-container mb-4">
            <div class="row g-0">
                <div class="col-lg-8 col-md-7">
                    <div class="map-wrapper">
                        <div class="map-controls">
                            <div class="map-btn">+</div>
                            <div class="map-btn">-</div>
                        </div>
                        <div class="live-badge">
                            <div class="pulse-dot"></div>
                            <span>LIVE TRACKING ACTIVE</span>
                            <span class="text-muted fw-normal">| <?= $armada_jalan ?> Units Out</span>
                        </div>
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6d/Padang_map.svg/1024px-Padang_map.svg.png"
                            class="dummy-map-bg" alt="Peta Padang">

                        <div class="map-marker" style="top:30%;left:45%;color:#0d6efd" title="Unit Pos Utama">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div class="map-marker" style="top:65%;left:48%;color:#dc3545" title="Lokasi Kejadian">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-5 unit-sidebar p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold text-muted small text-uppercase">Daftar Unit Armada</span>
                        <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-2 py-1"
                            style="font-size:.7rem"><?= $total_armada ?> Unit</span>
                    </div>

                    <?php if ($result_armada && mysqli_num_rows($result_armada) > 0):
                        while ($arm = mysqli_fetch_assoc($result_armada)):
                            $st = $arm['status'];
                            if ($st === 'Berangkat' || $st === 'Di Lokasi') {
                                $badge_class = $st === 'Di Lokasi' ? 'bg-di-lokasi' : 'bg-berangkat';
                                $icon_class = 'text-warning';
                            } else {
                                $badge_class = 'bg-siap';
                                $icon_class = 'text-primary';
                            }
                            ?>
                            <div class="unit-card">
                                <span class="badge-unit <?= $badge_class ?>"><?= htmlspecialchars($st) ?></span>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="fs-3 <?= $icon_class ?>">
                                        <i class="bi bi-<?= $arm['jenis'] === 'Rescue' ? 'shield-shaded' : 'truck' ?>"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold m-0"><?= htmlspecialchars($arm['kode_armada']) ?></h6>
                                        <small class="text-muted d-block mb-1" style="font-size:.75rem">
                                            <?= htmlspecialchars($arm['jenis']) ?> — <?= htmlspecialchars($arm['merk']) ?>
                                            <?= $arm['tahun'] ?>
                                        </small>
                                        <small class="text-dark d-block fw-medium">
                                            <i
                                                class="bi bi-geo-alt text-danger me-1"></i><?= htmlspecialchars($arm['pos_id']) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; else: ?>
                        <div class="text-center text-muted py-4" style="font-size:.85rem">
                            <i class="bi bi-truck" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px"></i>
                            Belum ada data armada
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-xl-3 col-md-6">
                <div class="indicator-card">
                    <div class="indicator-title">Armada Siap</div>
                    <div class="indicator-value text-success"><?= $armada_siap ?></div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="indicator-card">
                    <div class="indicator-title">Sedang Bertugas</div>
                    <div class="indicator-value text-purple-value"><?= $armada_jalan ?></div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="indicator-card">
                    <div class="indicator-title">Total Armada</div>
                    <div class="indicator-value text-danger"><?= $total_armada ?></div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="indicator-card">
                    <div class="indicator-title">Status GPS</div>
                    <div class="indicator-value" style="font-size:1.2rem;margin-top:6px">
                        <span
                            class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 small fw-bold">
                            <i class="bi bi-wifi me-1"></i> Sinyal Kuat
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center text-muted mt-5 mb-3" style="font-size:.75rem">
            &copy; 2026 — Dinas Kominfo Kota Padang
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>