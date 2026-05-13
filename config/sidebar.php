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
        <img src="<?= $base_url ?>assets/img/logo_damkar.png" alt="Logo" width="140" height="80"
            onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/b/b0/Logo_Damkar.png'">
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

             <a href="#menuPersonil" data-bs-toggle="collapse"
                class="d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clipboard-check"></i> Personil</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse <?= in_array($current_page, ['personil.php', 'penempatan_pos.php', 'jadwal_piket.php', 'riwayat_tugas.php']) ? 'show' : '' ?> sub-menu"
                id="menuPersonil">
                <a href="<?= $base_url ?>pages/personil.php"
                    class="<?= $current_page == 'personil.php' ? 'active' : '' ?>">Data Personil</a>
                <a href="<?= $base_url ?>pages/penempatan_pos.php"
                    class="<?= $current_page == 'penempatan_pos.php' ? 'active' : '' ?>">Penempatan Pos</a>
                <a href="<?= $base_url ?>pages/jadwal_piket.php"
                    class="<?= $current_page == 'jadwal_piket.php' ? 'active' : '' ?>">Jadwal Piket</a>
                <a href="<?= $base_url ?>pages/riwayat_tugas.php"
                    class="<?= $current_page == 'riwayat_tugas.php' ? 'active' : '' ?>">Riwayat Tugas</a>

            <a href="<?= $base_url ?>pages/armada.php" class="<?= $current_page == 'armada.php' ? 'active' : '' ?>">
                <i class="bi bi-truck"></i> Armada
            </a>

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