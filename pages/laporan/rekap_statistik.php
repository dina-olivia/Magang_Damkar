<?php 
$page = 'rekap_statistik'; 
include '../../config/koneksi.php'; // Pastikan path koneksi Anda benar [cite: 3]

// ── 1. Mengatur Filter Tahun ─────────────────────────────────
$filter_tahun = $_GET['tahun'] ?? date('Y');

// ── 2. Query Mengambil Data Akumulasi Per Bulan ───────────────
// Mengambil data dari tabel rekap_kejadian sesuai struktur dokumen 
$query = "SELECT rk.bulan, rk.jumlah_kebakaran, rk.jumlah_rescue, rk.kerugian 
          FROM rekap_kejadian rk 
          WHERE rk.tahun = '$filter_tahun' 
          ORDER BY rk.bulan ASC";
$result = mysqli_query($conn, $query);

// ── 3. Mempersiapkan Wadah Data untuk Grafik & Tabel ──────────
$months_name = [
    '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun',
    '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
];

// Inisialisasi data 12 bulan kosong agar grafik tetap rapi walau data belum terisi
$kebakaran_chart = array_fill_keys(array_keys($months_name), 0);
$rescue_chart = array_fill_keys(array_keys($months_name), 0);
$tabel_data = [];

$total_kebakaran = 0;
$total_rescue = 0;
$total_kerugian = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $bln = $row['bulan'];
    
    // Masukkan ke array grafik [cite: 82, 83]
    $kebakaran_chart[$bln] = (int)$row['jumlah_kebakaran'];
    $rescue_chart[$bln] = (int)$row['jumlah_rescue'];
    
    // Hitung total akumulasi tahunan [cite: 82, 83, 84]
    $total_kebakaran += $row['jumlah_kebakaran'];
    $total_rescue += $row['jumlah_rescue'];
    $total_kerugian += $row['kerugian'];
    
    // Simpan untuk looping tabel
    $tabel_data[$bln] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Statistik Analitik - E-DAMKAR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body { background-color: #f8f9fa; }
        .stat-box {
            background: #fff; padding: 25px; border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03); border-left: 5px solid #dee2e6;
        }
        .border-kebakaran { border-left-color: #dc3545; }
        .border-rescue { border-left-color: #0dcaf0; }
        .border-kerugian { border-left-color: #ffc107; }
        
        @media print {
            .sidebar, #filter-box, .btn-print-group { display: none !important; }
            #main-content { margin-left: 0 !important; padding: 0 !important; }
            .card { border: 1px solid #ddd !important; box-shadow: none !important; }
        }
    </style>
</head>
<body>

    <div id="main-content" class="p-4">
        
        <header class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold m-0 text-uppercase"><i class="bi bi-graph-up-arrow text-danger me-2"></i>Rekap Statistik Kejadian</h2>
                [cite_start]<p class="text-muted m-0">Visualisasi Data Tren Kebakaran, Operasi Rescue & Estimasi Kerugian Kota Padang [cite: 82, 83, 84]</p>
            </div>
            <div class="btn-print-group">
                <button onclick="window.print()" class="btn btn-dark shadow-sm px-3">
                    <i class="bi bi-printer me-2"></i> Cetak Statistik
                </button>
            </div>
        </header>

        <div class="card border-0 shadow-sm rounded-4 p-3 mb-4" id="filter-box">
            <form method="GET" action="" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Periode Tahun Analitik</label>
                    <select name="tahun" class="form-select fw-semibold">
                        <?php
                        $start_year = 2020;
                        $end_year = date('Y');
                        for ($y = $end_year; $y >= $start_year; $y--) {
                            $selected = ($filter_tahun == $y) ? 'selected' : '';
                            echo "<option value='$y' $selected>$y</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="bi bi-funnel"></i> Filter</button>
                </div>
            </form>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-box border-kebakaran">
                    <span class="text-muted text-uppercase small fw-bold d-block mb-1">Total Insiden Kebakaran</span>
                    <h2 class="fw-extrabold m-0 text-danger"><?= $total_kebakaran ?> <small class="fs-6 text-muted fw-normal">Kasus</small></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box border-rescue">
                    <span class="text-muted text-uppercase small fw-bold d-block mb-1">Total Penyelamatan (Rescue)</span>
                    <h2 class="fw-extrabold m-0 text-info"><?= $total_rescue ?> <small class="fs-6 text-muted fw-normal">Aksi</small></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box border-kerugian">
                    <span class="text-muted text-uppercase small fw-bold d-block mb-1">Total Nilai Kerugian</span>
                    <h2 class="fw-extrabold m-0 text-warning">Rp <?= number_format($total_kerugian, 0, ',', '.') ?></h2>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                    <h5 class="fw-bold text-dark mb-4"><i class="bi bi-pie-chart-fill me-2 text-primary"></i>Grafik Batang Perbandingan Tren Kejadian</h5>
                    <div style="position: relative; height:320px;">
                        <canvas id="statistikChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-xl-5">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="m-0 fw-bold text-dark"><i class="bi bi-list-ol me-2 text-success"></i>Angka Indikator Bulanan</h5>
                    </div>
                    <div class="table-responsive" style="max-height: 330px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0 text-center small">
                            <thead class="table-light text-uppercase sticky-top">
                                <tr>
                                    <th class="text-start ps-3 py-3">Bulan</th>
                                    <th><i class="bi bi-fire text-danger"></i></th>
                                    <th><i class="bi bi-life-preserver text-info"></i></th>
                                    <th class="text-end pe-3">Kerugian</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($months_name as $code => $name): 
                                    $kebakaran = $tabel_data[$code]['jumlah_kebakaran'] ?? 0;
                                    $rescue = $tabel_data[$code]['jumlah_rescue'] ?? 0;
                                    $kerugian = $tabel_data[$code]['kerugian'] ?? 0;
                                ?>
                                <tr>
                                    <td class="text-start ps-3 fw-bold"><?= $name ?></td>
                                    <td class="text-danger fw-semibold"><?= $kebakaran ?></td>
                                    <td class="text-info fw-semibold"><?= $rescue ?></td>
                                    <td class="text-end pe-3 text-muted">Rp <?= number_format($kerugian, 0, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        const ctx = document.getElementById('statistikChart').getContext('2d');
        
        // Data PHP dikonversi ke Array Javascript via JSON Encode
        const labelBulan = <?= json_encode(array_values($months_name)) ?>;
        const dataKebakaran = <?= json_encode(array_values($kebakaran_chart)) ?>;
        const dataRescue = <?= json_encode(array_values($rescue_chart)) ?>;

        new Chart(ctx, {
            type: 'bar', // Menggunakan Grafik Batang (Bar Chart)
            data: {
                labels: labelBulan,
                datasets: [
                    {
                        label: 'Kebakaran',
                        data: dataKebakaran,
                        backgroundColor: '#dc3545',
                        borderRadius: 6
                    },
                    {
                        label: 'Rescue / Penyelamatan',
                        data: dataRescue,
                        backgroundColor: '#0dcaf0',
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    </script>
</body>
</html>