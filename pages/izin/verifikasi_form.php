<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/config/database.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/library/cek_session.php";
if($user['rule']=='guru'){
    http_response_code(404);
    echo "404 Not Found";
    die();
}
$title = "Ubah Izin";
include_once $_SERVER['DOCUMENT_ROOT'] . "/pages/_partials/top.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/actions/_models/Izin.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/actions/_models/Pengguna.php";
$modelIzin = new Izin();
$siswas = $modelIzin->rawQuery("SELECT * FROM pengguna WHERE rule = 'siswa'")->fetchAll();
$gurus = $modelIzin->rawQuery("SELECT * FROM pengguna WHERE rule = 'guru'")->fetchAll();
$_query = "SELECT izin.*,siswa.nomor,siswa.nama_lengkap as nama_siswa,guru.nama_lengkap as nama_guru FROM izin 
INNER JOIN pengguna as siswa ON siswa.id = izin.siswa_id 
INNER JOIN pengguna as guru ON guru.id = izin.guru_id  WHERE izin.id = :id";
$stmt = $conn->prepare($_query);
$stmt->bindParam(':id', $_GET['id']);
$stmt->execute();
$izin = $stmt->fetch();
if ($izin['status'] != 'pending') {
    $_SESSION['error'] = 'Izin tidak dapat diubah';
    header('Location: ' . base_url() . '/pages/izin/index.php');
    exit;
}
?>

<div class="container-fluid">
    <h1 class="dash-title">Verifikasi Izin</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card spur-card">
                <div class="card-header text-right">
                    <div class="spur-card-title"> Verifikasi Data Izin</div>
                </div>
                <div class="card-body ">
                    <form action="<?= base_url() . '/actions/izin_action.php?id='.$izin['id'] ?>" method="POST">
                        <ul>
                            <li>
                                Nomor(NIM/NIK) : <?= $izin['nomor'] ?>
                            </li>
                            <li>
                                Nama Siswa : <?= $izin['nama_siswa'] ?>
                            </li>
                            
                        </ul>
                        <hr>
                        <ul>
                            <li>
                                Kelas/Jurusan : <?= $izin['kelas_jurusan'] ?>
                            </li>
                            <li>
                                Guru : <?= $izin['nama_guru'] ?>
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
                                <?= $izin['tanggal'] ?>/<?= $izin['waktu'] ?>
                            </li>
                        </ul>
                        <hr>
                        <div class="form-group">
                            <label for="keterangan_status_diubah">Keterangan Tambahan <small class="text-danger">*</small></label>
                            <textarea class="form-control" required id="keterangan_status_diubah" name="keterangan_status_diubah" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success" name="disetujui">SETUJUI</button>
                        <button type="submit" class="btn btn-danger" name="ditolak">TOLAK</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/pages/_partials/bottom.php";
?>