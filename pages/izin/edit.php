<?php
include_once __DIR__ . "/../../config/database.php";
include_once __DIR__ . "/../../library/cek_session.php";
if($user['rule']=='guru'){
    http_response_code(404);
    echo "404 Not Found";
    die();
}
$title = "Ubah Izin";
include_once __DIR__ . "/../../pages/_partials/top.php";
include_once __DIR__ . "/../../actions/_models/Izin.php";
include_once __DIR__ . "/../../actions/_models/Pengguna.php";
$modelIzin = new Izin();
$siswas = $modelIzin->rawQuery("SELECT * FROM pengguna WHERE rule = 'siswa'")->fetchAll();
$gurus = $modelIzin->rawQuery("SELECT * FROM pengguna WHERE rule = 'guru'")->fetchAll();

$izin = $modelIzin->findById($_GET['id']);
if ($izin['status'] != 'pending') {
    $_SESSION['error'] = 'Izin tidak dapat diubah';
    header('Location: ' . base_url() . '/pages/izin/index.php');
    exit;
}
?>

<div class="container-fluid">
    <h1 class="dash-title">Ubah Izin</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card spur-card">
                <div class="card-header text-right">
                    <div class="spur-card-title"> Ubah Data Izin</div>
                </div>
                <div class="card-body ">
                    <form action="<?= base_url() . '/actions/izin_action.php?id='.$izin['id'] ?>" method="POST">
                        <div class="form-group">
                            <label for="siswa_id">Siswa <small class="text-danger">*</small></label>
                            <select class="form-control" id="siswa_id" name="siswa_id">
                                <?php foreach ($siswas as $siswa): ?>
                                    <option value="<?= $siswa['id'] ?>" <?= $siswa['id']==$izin['siswa_id']?"selected":"" ?>><?= $siswa['nomor'] ?> - <?= $siswa['nama_lengkap'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="guru_id">Guru <small class="text-danger">*</small></label>
                            <select class="form-control" id="guru_id" name="guru_id">
                                <?php foreach ($gurus as $guru): ?>
                                    <option value="<?= $guru['id'] ?>" <?= $guru['id']==$izin['guru_id']?"selected":"" ?>><?= $guru['nomor'] ?> - <?= $guru['nama_lengkap'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kelas_jurusan">Kelas/Jurusan <small class="text-danger">*</small></label>
                            <input type="text" class="form-control" name="kelas_jurusan" minlength="5" required
                                id="kelas_jurusan" placeholder="Kelas/Jurusan..." value="<?=$izin['kelas_jurusan']?>">
                        </div>
                       
                        <div class="form-group">
                            <label for="tanggal">Tanggal <small class="text-danger">*</small></label>
                            <input type="date" class="form-control" min="<?=date("Y-m-d")?>" required id="tanggal" name="tanggal" required value="<?= $izin['tanggal'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="waktu">Waktu <small class="text-danger">*</small></label>
                            <input type="time" class="form-control" required id="waktu" name="waktu" required value="<?= date("H:i",strtotime($izin['waktu'])) ?>">
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan <small class="text-danger">*</small></label>
                            <textarea class="form-control" required id="keterangan" name="keterangan" required><?= $izin['keterangan'] ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" name="edit">Submit</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
<?php
include_once __DIR__ . "/../../pages/_partials/bottom.php";
?>