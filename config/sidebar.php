<?php echo "";
$current_page = basename($_SERVER['PHP_SELF']);
$path = $_SERVER['PHP_SELF'];
$root_folder = '/Magang_DAMKAR';
$clean_path = str_replace($root_folder, '', $path);
$levels = substr_count($clean_path, '/');
$base_url = str_repeat('../', $levels);
?>

<div id="sidebar" class="shadow">
    <div class="sidebar-header">
        <img src="/Magang_Damkar/assets/img/logo_damkar.png" alt="Logo" width="140" height="80">
        <span class="fw-bold ms-2">DAMKAR PADANG</span> 
    </div>

    <div class="sidebar-content">
        <div class="nav flex-column mt-2">

            <!-- DASHBOARD -->
            <a href="<?= $base_url ?index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <!-- MANAJEMEN -->
            <a href="#menuManajemen" data-bs-toggle="collapse"
                class="d-flex justify-content-between align-items-center">
                <span><i class="bi bi-megaphone"></i> Manajemen Kejadian</span>
                <i class="bi bi-chevron-down small"></i>
            </a>

            <div class="collapse <?= in_array($current_page, ['input_laporan.php', 'monitoring_kejadian.php', 'detail_kejadian.php', 'timeline_kronologi.php']) ? 'show' : '' ?> sub-menu"
                id="menuManajemen">

                <a href="<?= $base_url ?>pages/input_laporan.php"
                    class="<?= $current_page == 'input_laporan.php' ? 'active' : '' ?>">Input Laporan</a>

                <a href="<?= $base_url ?>pages/monitoring_kejadian.php"
                    class="<?= $current_page == 'monitoring_kejadian.php' ? 'active' : '' ?>">Monitoring Kejadian</a>

                <a href="<?= $base_url ?>pages/detail_kejadian.php"
                    class="<?= $current_page == 'detail_kejadian.php' ? 'active' : '' ?>">Detail Kejadian</a>

                <a href="<?= $base_url ?>pages/timeline_kronologi.php"
                    class="<?= $current_page == 'timeline_kronologi.php' ? 'active' : '' ?>">Timeline Kronologi</a>
            </div>

            <!-- OPERASIONAL -->
            <a href="#menuOperasional" data-bs-toggle="collapse"
                class="d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clipboard-check"></i> Operasional</span>
                <i class="bi bi-chevron-down small"></i>
            </a>

            <div class="collapse <?= in_array($current_page, ['penugasan_tim.php', 'monitoring_armada.php', 'status_penanganan.php', 'riwayat_penugasan.php']) ? 'show' : '' ?> sub-menu"
                id="menuOperasional">

                <a href="<?= $base_url ?>pages/penugasan_tim.php"
                    class="<?= $current_page == 'penugasan_tim.php' ? 'active' : '' ?>">Penugasan Tim</a>

                <a href="<?= $base_url ?>pages/monitoring_armada.php"
                    class="<?= $current_page == 'monitoring_armada.php' ? 'active' : '' ?>">Monitoring Armada</a>

                <a href="<?= $base_url ?>pages/status_penanganan.php"
                    class="<?= $current_page == 'status_penanganan.php' ? 'active' : '' ?>">Status Penanganan</a>

                <a href="<?= $base_url ?>pages/riwayat_penugasan.php"
                    class="<?= $current_page == 'riwayat_penugasan.php' ? 'active' : '' ?>">Riwayat Penugasan</a>
            </div>

            <a href="<?= $base_url ?>pages/melani/personil.php"
                class="<?= $current_page == 'personil.php' ? 'active' : '' ?>">
                <i class="bi bi-people"></i> Personil
            </a>
        </div>

        <!-- SARPRAS -->
        <a href="#menuSarpras" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
            <span><i class="bi bi-tools"></i> Sarpras</span>
            <i class="bi bi-chevron-down small"></i>
        </a>

        <div class="collapse <?= in_array($current_page, ['sarpras.php', 'master_bidang.php', 'master_kategori.php']) ? 'show' : '' ?> sub-menu"
            id="menuSarpras">

            <a href="<?= $base_url ?>pages/sarpras.php"
                class="<?= $current_page == 'sarpras.php' ? 'active' : '' ?>">Data Sarpras</a>

            <a href="<?= $base_url ?>pages/master_bidang.php"
                class="<?= $current_page == 'master_bidang.php' ? 'active' : '' ?>">Master Bidang</a>

            <a href="<?= $base_url ?>pages/master_kategori.php"
                class="<?= $current_page == 'master_kategori.php' ? 'active' : '' ?>">Master Kategori</a>
        </div>

        <a href="#menuLaporan" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
            <span><i class="bi bi-file-earmark-bar-graph"></i> Laporan & Analitik</span>
            <i class="bi bi-chevron-down small"></i>
        </a>

        <div class="collapse <?= in_array($current_page, ['laporan_kejadian.php', 'rekap_statistik.php', 'export_excel.php']) ? 'show' : '' ?> sub-menu"
            id="menuLaporan">

            <a href="<?= $base_url ?>pages/laporan_kejadian.php"
                class="<?= $current_page == 'laporan_kejadian.php' ? 'active' : '' ?>">Laporan Kejadian</a>

            <a href="<?= $base_url ?>pages/rekap_statistik.php"
                class="<?= $current_page == 'rekap_statistik.php' ? 'active' : '' ?>">Rekap Statistik</a>

            <a href="<?= $base_url ?>pages/cetak_export.php"
                class="<?= $current_page == 'cetak_export.php' ? 'active' : '' ?>">Cetak & Export</a>

        </div>

        <a href="<?= $base_url ?>pages/manajemen_user.php"
            class="<?= $current_page == 'manajemen_user.php' ? 'active' : '' ?>">
            <i class="bi bi-person"></i> Manajemen User
        </a>

        <a href="<?= $base_url ?>logout.php" class="mt-4 text-danger">
            <i class="bi bi-box-arrow-left"></i> Keluar
        </a>

    </div>
</div>
</div>