<div class="dash-nav dash-nav-dark">
    <header>
        <a href="#!" class="menu-toggle">
            <i class="fas fa-bars"></i>
        </a>

        <a href="<?=base_url()?>/" class="text-center">
            <img src="<?= $logo_url ?>" alt="Logo Sekolah" style="width:75px;">
            <br>
            <?= @$app_name ?>
        </a>
    </header>
    <nav class="dash-nav-list">
        <a href="<?=base_url()?>/" class="dash-nav-item">
            <i class="fas fa-home"></i> Dashboard </a>
        <?php if ($user['rule'] == "waka"): ?>
            <div class="dash-nav-dropdown">
                <a href="#!" class="dash-nav-item dash-nav-dropdown-toggle">
                    <i class="fas fa-users"></i> Pengguna </a>
                <div class="dash-nav-dropdown-menu">
                    <a href="<?=base_url()?>/pages/pengguna/tambah.php" class="dash-nav-dropdown-item">Tambah Pengguna</a>
                    <a href="<?=base_url()?>/pages/pengguna/index.php" class="dash-nav-dropdown-item">Data Pengguna</a>
                </div>
            </div>
        <?php endif ?>
        <?php if ($user['rule'] == "waka"): ?>
            <a href="<?=base_url()?>/pages/izin/verifikasi.php" class="dash-nav-item">
                <i class="fas fa-tasks"></i> Verifikasi Data Izin </a>
        <?php endif ?>
        <div class="dash-nav-dropdown">
            <a href="#!" class="dash-nav-item dash-nav-dropdown-toggle">
                <i class="fas fa-book"></i> Izin </a>
            <div class="dash-nav-dropdown-menu">
                <?php if ($user['rule'] != "guru"): ?>
                    <a href="<?=base_url()?>/pages/izin/tambah.php" class="dash-nav-dropdown-item">Tambah Izin</a>
                <?php endif ?>
                <a href="<?=base_url()?>/pages/izin/index.php" class="dash-nav-dropdown-item">Data Izin</a>
            </div>
        </div>

    </nav>
</div>