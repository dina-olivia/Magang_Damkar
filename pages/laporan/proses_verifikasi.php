<?php
include 'koneksi.php';

if (isset($_POST['verifikasi_laporan'])) {
    $id_laporan = $_POST['id_laporan'];
    $status_verifikasi = $_POST['status_verifikasi']; // Nilai: 'Valid' atau 'Palsu'
    $catatan_operator = mysqli_real_escape_string($koneksi, $_POST['catatan_operator']);

    if ($status_verifikasi == 'Valid') {
        // Jika valid, status laporan naik ke 'Proses/Pengerahan Armada'
        $query = "UPDATE laporan SET 
                  status_laporan = 'Dalam Penanganan', 
                  verifikasi = 'Valid',
                  catatan = '$catatan_operator' 
                  WHERE id_laporan = '$id_laporan'";
    } else {
        // Jika laporan palsu, langsung diarsipkan sebagai laporan ditolak
        $query = "UPDATE laporan SET 
                  status_laporan = 'Ditolak (Palsu)', 
                  verifikasi = 'Palsu',
                  catatan = '$catatan_operator' 
                  WHERE id_laporan = '$id_laporan'";
    }

    $eksekusi = mysqli_query($koneksi, $query);

    if ($eksekusi) {
        echo "<script>alert('Laporan berhasil diverifikasi!'); window.location='index.php?page=laporan';</script>";
    } else {
        echo "<script>alert('Gagal memproses verifikasi.'); window.location='index.php?page=laporan';</script>";
    }
}
?>