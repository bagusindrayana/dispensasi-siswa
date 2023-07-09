<?php
include_once __DIR__ . "/../../config/database.php";
include_once __DIR__ . "/../../library/cek_session.php";
$title = "Detail Izin";
include_once __DIR__ . "/../../pages/_partials/top.php";
include_once __DIR__ . "/../../actions/_models/Izin.php";
include_once __DIR__ . "/../../actions/_models/Pengguna.php";
$modelIzin = new Izin();
$siswas = $modelIzin->rawQuery("SELECT * FROM pengguna WHERE rule = 'siswa'")->fetchAll();
$gurus = $modelIzin->rawQuery("SELECT * FROM pengguna WHERE rule = 'guru'")->fetchAll();

$izin = $modelIzin->rawQuery("SELECT izin.*,siswa.nomor,siswa.nama_lengkap as nama_siswa,guru.nama_lengkap as nama_guru FROM izin 
INNER JOIN pengguna as siswa ON siswa.id = izin.siswa_id 
INNER JOIN pengguna as guru ON guru.id = izin.guru_id  WHERE izin.id = " . $_GET['id'])->fetch();
// if ($izin['status'] != 'pending') {
//     $_SESSION['error'] = 'Izin tidak dapat diubah';
//     header('Location: ' . base_url() . '/pages/izin/index.php');
//     exit;
// }
?>

<div class="container-fluid">
    <h1 class="dash-title">Detail Izin</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card spur-card">
                <div class="card-header text-right">
                    <div class="spur-card-title"> Detail Data Izin</div>
                </div>
                <div class="card-body ">
                    <ul>
                        <li>
                            Nomor(NIM/NIK) :
                            <?= $izin['nomor'] ?>
                        </li>
                        <li>
                            Nama Siswa :
                            <?= $izin['nama_siswa'] ?>
                        </li>

                    </ul>
                    <hr>
                    <ul>
                        <li>
                            Kelas/Jurusan :
                            <?= $izin['kelas_jurusan'] ?>
                        </li>
                        <li>
                            Guru :
                            <?= $izin['nama_guru'] ?>
                        </li>
                    </ul>
                    <hr>
                    <p>IZIN : </p>
                    <ul>
                        <li>
                            Keterangan :
                            <br>
                            <?= $izin['keterangan'] ?>
                        </li>
                        <li>
                            Tanggal/Waktu :
                            <br>
                            <?= $izin['tanggal'] ?> /
                            <?= $izin['waktu'] ?>
                        </li>
                    </ul>
                    <hr>
                    <p>Status : </p>
                    <?php if($izin['status']!='pending'){?>
                        <b>Izin sudah
                        <?php switch ($izin['status']) {
                            case "pending":
                                echo '<span class="text-warning">Pending</span>';
                                break;
                            case "disetujui":
                                echo '<span class="text-success">Disetujui</span>';
                                break;
                            case "ditolak":
                                echo '<span class="text-danger">Ditolak</span>';
                                break;
                        } ?> pada tanggal
                        <?= $izin['waktu_status_diubah'] ?>
                    </b>
                    <br>
                    <b>Dengan Keterangan : </b>
                    <br>
                    <i>
                        <?= $izin['keterangan_status_diubah'] ?>
                    </i>
                    <?php }else{?>
                        <span class="badge badge-warning">Pending</span>
                    <?php }?>
                </div>
            </div>
        </div>

    </div>
</div>
<?php
include_once __DIR__ . "/../../pages/_partials/bottom.php";
?>