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
        <img src="/MAGANG/Magang_Damkar/assets/img/logo_damkar.png" alt="Logo" width="140" height="80">
        <span class="fw-bold ms-2">DAMKAR PADANG</span>
    </div>

    <div class="sidebar-content">
        <div class="nav flex-column mt-2">

            <a href="<?= $base_url ?>index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <a href="<?= $base_url ?>pages/manajemen_kejadian.php"
                class="<?= $current_page == 'manajemen_kejadian.php' ? 'active' : '' ?>">
                <i class="bi bi-megaphone"></i> Manajemen Kejadian
            </a>
           <div class="collapse <?= in_array($current_page, ['input_laporan.php', 'monitoring_kejadian.php', 'detail_penanganan.php', 'timeline_kronologi.php']) ? 'show' : '' ?> sub-menu" id="menuManajemen">

            <a href="/MAGANG/Magang_Damkar/pages/input_laporan.php"
            class="<?= $current_page == 'input_laporan.php' ? 'active' : '' ?>">Input Laporan</a>
            
            <a href="/MAGANG/Magang_Damkar/pages/monitoring_kejadian.php"
            class="<?= $current_page == 'monitoring_kejadian.php' ? 'active' : '' ?>">Monitoring Kejadian</a>
            
            <a href="/MAGANG/Magang_Damkar/pages/monitoring_kejadian.php"
            class="<?= $current_page == 'detail_kejadian.php' ? 'active' : '' ?>">Detail Kejadian</a>
        </div>

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

            <a href="<?= $base_url ?>pages/personil.php" class="<?= $current_page == 'personil.php' ? 'active' : '' ?>">
                <i class="bi bi-people"></i> Personil
            </a>

            <a href="<?= $base_url ?>pages/armada.php" class="<?= $current_page == 'armada.php' ? 'active' : '' ?>">
                <i class="bi bi-truck"></i> Armada
            </a>

             <a href="/MAGANG/Magang_Damkar/pages/armada.php"
            class="<?= $current_page == 'armada.php' ? 'active' : '' ?>">Armada</a>

           

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

            <a href="<?= $base_url ?>pages/dina/laporan.php"
                class="<?= $current_page == 'laporan.php' ? 'active' : '' ?>">
                <i class="bi bi-file-earmark-text"></i> Laporan
            </a>

            <a href="<?= $base_url ?>pages/pengaturan.php"
                class="<?= $current_page == 'pengaturan.php' ? 'active' : '' ?>">
                <i class="bi bi-gear"></i> Pengaturan
            </a>

            <a href="<?= $base_url ?>logout.php" class="mt-4 text-danger">
                <i class="bi bi-box-arrow-left"></i> Keluar
            </a>
        </div>
    </div>
</div>