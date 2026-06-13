<?php
include '../../config/koneksi.php';

$filter_tipe = $_GET['filter_tipe'] ?? 'semua';
$where_clause = " WHERE 1=1 ";

if ($filter_tipe == 'harian') {
    $tgl = $_GET['tanggal'] ?? date('Y-m-d');
    $where_clause .= " AND DATE(tanggal) = '$tgl' ";
} elseif ($filter_tipe == 'bulanan') {
    $bulan = $_GET['bulan'] ?? date('m');
    $tahun = $_GET['tahun'] ?? date('Y');
    $where_clause .= " AND MONTH(tanggal) = '$bulan' AND YEAR(tanggal) = '$tahun' ";
} elseif ($filter_tipe == 'tahunan') {
    $tahun = $_GET['tahun'] ?? date('Y');
    $where_clause .= " AND YEAR(tanggal) = '$tahun' ";
}

$filename = "Laporan_Kejadian_Damkar_" . $filter_tipe . "_" . date('Ymd') . ".xls";

// Header untuk memaksa browser mendownload format Excel (.xls)
header("Content-Type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");

$result = mysqli_query($conn, "SELECT * FROM laporan_kejadian $where_clause ORDER BY id DESC");
?>

<h2>DATA REKAP LAPORAN KEJADIAN E-DAMKAR</h2>
<p>Filter Periode: <?= strtoupper($filter_tipe) ?></p>

<table border="1">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th>No</th>
            <th>No. Laporan</th>
            <th>Jenis Kejadian</th>
            <th>Lokasi</th>
            <th>Pelapor</th>
            <th>No. HP</th>
            <th>Tanggal</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['nomor_laporan'] ?></td>
                <td><?= strtoupper($row['jenis_kejadian']) ?></td>
                <td><?= $row['lokasi'] ?></td>
                <td><?= $row['pelapor'] ?></td>
                <td>'<?= $row['no_hp'] ?></td>
                <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                <td><?= strtoupper($row['status']) ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>