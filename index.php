<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/config/database.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/library/cek_session.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/pages/_partials/top.php";

$jumlahPengguna = 0;
$jumlahPengguna = $conn->query("SELECT COUNT(*) FROM pengguna")->fetchColumn();

$jumlahIzin = 0;
$jumlahIzin = $conn->query("SELECT COUNT(*) FROM izin")->fetchColumn();

$jumlahIzinDisetujui = 0;
$jumlahIzinDisetujui = $conn->query("SELECT COUNT(*) FROM izin WHERE status = 'disetujui'")->fetchColumn();
?>

<div class="container-fluid">
    <?php if ($user['rule'] == 'waka') {
        ?>
        <div class="row dash-row">
            <div class="col-xl-4">
                <div class="stats stats-primary">
                    <h3 class="stats-title"> Pengguna </h3>
                    <div class="stats-content">
                        <div class="stats-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="stats-data">
                            <div class="stats-number">
                                <?= $jumlahPengguna ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="stats stats-success ">
                    <h3 class="stats-title"> Pengajuan </h3>
                    <div class="stats-content">
                        <div class="stats-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="stats-data">
                            <div class="stats-number">
                                <?= $jumlahIzin ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="stats stats-danger">
                    <h3 class="stats-title"> Disetujui </h3>
                    <div class="stats-content">
                        <div class="stats-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div class="stats-data">
                            <div class="stats-number">
                                <?= $jumlahIzinDisetujui ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } else {
        ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            Selamat Datang Di Aplikasi Izin Dispensasi
                        </div>
                        <div class="card-body">
                            <p>
                                Aplikasi ini digunakan untuk mengajukan izin dispensasi siswa
                            </p>
                            <p>
                                Anda Login Sebagai :
                            </p>
                            <ul>
                                <li>Rule : <?=$user['rule']?></li>
                                <li>Nomor : <?=$user['nomor']?></li>
                                <li>Nama Lengkap : <?=$user['nama_lengkap']?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <?php
    }
    ?>

</div>
<?php
include_once "./pages/_partials/bottom.php";
?>