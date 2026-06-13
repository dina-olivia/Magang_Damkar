<?php
$current_page = basename($_SERVER['PHP_SELF']);
$path = $_SERVER['PHP_SELF'];
$root_folder = '/Magang_Damkar';
$clean_path = str_replace($root_folder, '', $path);
$levels = substr_count($clean_path, '/');
$base_url = str_repeat('../', $levels);

$user_nama = $_SESSION['nama'] ?? 'Admin';
$inisial = strtoupper(substr($user_nama, 0, 1));
if (strpos($user_nama, ' ') !== false) {
    $parts = explode(' ', $user_nama);
    $inisial = strtoupper($parts[0][0] . end($parts)[0]);
}
?>

<div id="sidebar" class="shadow">

    <!-- Header -->
    <div class="sidebar-header">
        <img src="/Magang_Damkar/assets/img/logo_damkar.png" alt="Logo" width="100" height="70"
            style="border-radius:8px;object-fit:cover;"
            onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/b/b0/Logo_Damkar.png'">
        <div class="ms-2">
            <div class="fw-bold" style="font-size:15px;line-height:1.2">E-DAMKAR</div>
            <div style="font-size:11px;opacity:0.75">Kota Padang</div>
        </div>
    </div>

    <!-- Info user -->
    <div
        style="padding:14px 20px;border-bottom:1px solid rgba(255,255,255,0.08);display:flex;align-items:center;gap:10px;">
        <div style="width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,0.15);
                    display:flex;align-items:center;justify-content:center;
                    font-weight:700;font-size:14px;color:white;flex-shrink:0;">
            <?= htmlspecialchars($inisial) ?>
        </div>
        <div>
            <div style="font-size:13px;font-weight:600;color:white;">
                <?= htmlspecialchars($user_nama) ?>
            </div>
            <span style="background:#ef4444;color:white;font-size:10px;font-weight:700;
                         padding:2px 8px;border-radius:20px;">
                Admin
            </span>
        </div>
    </div>

    <div class="sidebar-content">
        <div class="nav flex-column mt-2">

            <!-- DASHBOARD -->
            <a href="<?= $base_url ?>index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <!-- MANAJEMEN KEJADIAN -->
            <a href="#menuManajemen" data-bs-toggle="collapse"
                class="d-flex justify-content-between align-items-center
               <?= in_array($current_page, ['input_laporan.php', 'monitoring_kejadian.php', 'detail_kejadian.php', 'timeline_kronologi.php']) ? 'active' : '' ?>">
                <span><i class="bi bi-megaphone"></i> Manajemen Kejadian</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse <?= in_array($current_page, ['input_laporan.php', 'monitoring_kejadian.php', 'detail_kejadian.php', 'timeline_kronologi.php']) ? 'show' : '' ?> sub-menu"
                id="menuManajemen">
                <a href="<?= $base_url ?>MAGANG_DAMKAR/pages/manajemen/input_laporan.php"
                    class="<?= $current_page == 'input_laporan.php' ? 'active' : '' ?>">Input Laporan</a>
                <a href="<?= $base_url ?>MAGANG_DAMKAR/pages/manajemen/monitoring_kejadian.php"
                    class="<?= $current_page == 'monitoring_kejadian.php' ? 'active' : '' ?>">Monitoring Kejadian</a>
                <a href="<?= $base_url ?>MAGANG_DAMKAR/pages/manajemen/detail_kejadian.php"
                    class="<?= $current_page == 'detail_kejadian.php' ? 'active' : '' ?>">Detail Kejadian</a>
                <a href="<?= $base_url ?>MAGANG_DAMKAR/pages/manajemen/timeline_kronologi.php"
                    class="<?= $current_page == 'timeline_kronologi.php' ? 'active' : '' ?>">Timeline Kronologi</a>
            </div>

            <!-- OPERASIONAL -->
            <a href="#menuOperasional" data-bs-toggle="collapse"
                class="d-flex justify-content-between align-items-center
               <?= in_array($current_page, ['penugasan_tim.php', 'monitoring_armada.php', 'status_penanganan.php', 'riwayat_penugasan.php']) ? 'active' : '' ?>">
                <span><i class="bi bi-clipboard-check"></i> Operasional</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse <?= in_array($current_page, ['penugasan_tim.php', 'monitoring_armada.php', 'status_penanganan.php', 'riwayat_penugasan.php']) ? 'show' : '' ?> sub-menu"
                id="menuOperasional">
                <a href="<?= $base_url ?>MAGANG_DAMKAR/pages/operasional/penugasan_tim.php"
                    class="<?= $current_page == 'penugasan_tim.php' ? 'active' : '' ?>">Penugasan Tim</a>
                <a href="<?= $base_url ?>MAGANG_DAMKAR/pages/operasional/monitoring_armada.php"
                    class="<?= $current_page == 'monitoring_armada.php' ? 'active' : '' ?>">Monitoring Armada</a>
                <a href="<?= $base_url ?>MAGANG_DAMKAR/pages/operasional/status_penanganan.php"
                    class="<?= $current_page == 'status_penanganan.php' ? 'active' : '' ?>">Status Penanganan</a>
                <a href="<?= $base_url ?>MAGANG_DAMKAR/pages/operasional/riwayat_penugasan.php"
                    class="<?= $current_page == 'riwayat_penugasan.php' ? 'active' : '' ?>">Riwayat Penugasan</a>
            </div>

            <!-- PERSONIL -->
            <a href="<?= $base_url ?>MAGANG_DAMKAR/pages/personil/personil.php"
                class="<?= $current_page == 'personil.php' ? 'active' : '' ?>">
                <i class="bi bi-people"></i> Personil
            </a>

            <!-- SARPRAS -->
            <a href="#menuSarpras" data-bs-toggle="collapse"
                class="d-flex justify-content-between align-items-center
               <?= in_array($current_page, ['sarpras.php', 'master_bidang.php', 'master_kategori.php']) ? 'active' : '' ?>">
                <span><i class="bi bi-tools"></i> Sarpras</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse <?= in_array($current_page, ['sarpras.php', 'master_bidang.php', 'master_kategori.php']) ? 'show' : '' ?> sub-menu"
                id="menuSarpras">
                <a href="<?= $base_url ?>MAGANG_DAMKAR/pages/sarpras.php"
                    class="<?= $current_page == 'sarpras.php' ? 'active' : '' ?>">Data Sarpras</a>
                <a href="<?= $base_url ?>MAGANG_DAMKAR/pages/master_bidang.php"
                    class="<?= $current_page == 'master_bidang.php' ? 'active' : '' ?>">Master Bidang</a>
                <a href="<?= $base_url ?>MAGANG_DAMKAR/pages/master_kategori.php"
                    class="<?= $current_page == 'master_kategori.php' ? 'active' : '' ?>">Master Kategori</a>
            </div>

            <!-- LAPORAN & ANALITIK -->
            <a href="#menuLaporan" data-bs-toggle="collapse"
                class="d-flex justify-content-between align-items-center
               <?= in_array($current_page, ['laporan_kejadian.php', 'rekap_statistik.php', 'cetak_export.php']) ? 'active' : '' ?>">
                <span><i class="bi bi-file-earmark-bar-graph"></i> Laporan & Analitik</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse <?= in_array($current_page, ['laporan_kejadian.php', 'rekap_statistik.php', 'cetak_export.php']) ? 'show' : '' ?> sub-menu"
                id="menuLaporan">
                <a href="<?= $base_url ?>MAGANG_DAMKAR/pages/laporan/laporan_kejadian.php"
                    class="<?= $current_page == 'laporan_kejadian.php' ? 'active' : '' ?>">Laporan Kejadian</a>
                <a href="<?= $base_url ?>MAGANG_DAMKAR/pages/laporan/rekap_statistik.php"
                    class="<?= $current_page == 'rekap_statistik.php' ? 'active' : '' ?>">Rekap Statistik</a>
                <a href="<?= $base_url ?>MAGANG_DAMKAR/pages/laporan/cetak_export.php"
                    class="<?= $current_page == 'cetak_export.php' ? 'active' : '' ?>">Cetak & Export</a>
            </div>

            <!-- MANAJEMEN USER -->
            <a href="<?= $base_url ?>MAGANG_DAMKAR/pages/manajemen_user.php"
                class="<?= $current_page == 'manajemen_user.php' ? 'active' : '' ?>">
                <i class="bi bi-person-gear"></i> Manajemen User
            </a>

            <!-- KELUAR -->
            <a href="/Magang_Damkar/logout.php" class="mt-3" style="color:#f87171 !important;"
                onclick="return confirm('Yakin ingin keluar?')">
                <i class="bi bi-box-arrow-left"></i> Keluar
            </a>

        </div>
    </div>
</div>