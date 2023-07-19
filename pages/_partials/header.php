<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/actions/_models/Izin.php";

$_model = new Izin();
$_query = "SELECT izin.*,siswa.nama_lengkap as nama_siswa,guru.nama_lengkap as nama_guru FROM izin 
INNER JOIN pengguna as siswa ON siswa.id = izin.siswa_id 
INNER JOIN pengguna as guru ON guru.id = izin.guru_id WHERE izin.status = 'pending' ORDER BY izin.id DESC ";
$notifIzins = $_model->rawQuery($_query)->fetchAll();
?>
<header class="dash-toolbar">
    <a href="#!" class="menu-toggle">
        <i class="fas fa-bars"></i>
    </a>
    <a href="#!" class="searchbox-toggle">
        <i class="fas fa-search"></i>
    </a>
    <!-- <form class="searchbox" action="#!">
        <a href="#!" class="searchbox-toggle"> <i class="fas fa-arrow-left"></i> </a>
        <button type="submit" class="searchbox-submit"> <i class="fas fa-search"></i> </button>
        <input type="text" class="searchbox-input" placeholder="type to search">
    </form> -->
    <div class="tools">
        <!-- <a href="https://github.com/HackerThemes/spur-template" target="_blank" class="tools-item">
            <i class="fab fa-github"></i>
        </a> -->
        <?php if ($user['rule'] == "waka" || $user['rule'] == "guru") { ?>
            <div class="dropdown tools-item">
                <a href="#" class="" id="notifikasiIzin" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    <i class="tools-item-count">
                        <?= count($notifIzins) ?>
                    </i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notifikasiIzin" style="min-width:300px;">
                    <p class="m-1">Izin Yang Perlu Di Verifikasi</p>
                    <div class="dropdown-divider"></div>
                    <?php
                    foreach ($notifIzins as $notif) {
                        ?>

                        <a href="<?=base_url()?><?=$user['rule']=='waka'?'/pages/izin/verifikasi_form.php':'/pages/izin/detail.php'?>?id=<?=$notif['id']?>">
                            <div class="notification">
                                <div class="notification-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="notification-text">
                                    <span class="text">
                                        <?= $notif['nama_siswa'] ?> Mengajukan Izin
                                    </span>
                                    <span class="date">
                                        <?= $notif['tanggal'] ?>
                                    </span>
                                </div>
                            </div>
                        </a>
                        <?php
                    }
                    ?>

                </div>
            </div>
        <?php } ?>

        <div class="dropdown tools-item">
            <a href="#" class="" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                <a class="dropdown-item" href="<?=base_url()?>/pages/profil.php">Profile</a>
                <a class="dropdown-item" href="#" onclick="document.getElementById('formLogout').submit()">Logout</a>
                <form action="/actions/login_action.php" id="formLogout" style="display: none;" method="POST">
                    <input type="hidden" name="logout" value="true">
                </form>
            </div>
        </div>
    </div>
</header>