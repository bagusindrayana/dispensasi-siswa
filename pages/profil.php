<?php
include_once __DIR__ . "/../config/database.php";
include_once __DIR__ . "/../library/cek_session.php";

$title = "Profil Pengguna";
include_once __DIR__ . "/../pages/_partials/top.php";
include_once __DIR__ . "/../actions/_models/Pengguna.php";

$model = new Pengguna();
$pengguna = $model->findById($user['id']);
?>

<div class="container-fluid">
    <h1 class="dash-title">Profil Pengguna</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card spur-card">
                <div class="card-header text-right">
                    <div class="spur-card-title"> Profil Data Pengguna</div>
                </div>
                <div class="card-body ">
                    <form action="<?= base_url() . '/actions/profil_action.php' ?>?id=<?= $user['id'] ?>" method="POST"
                        enctype="multipart/form-data">
                        <div class="form-group">
                            <div class="row" style="
                            display:flex;
                            justify-content: center;
                            ">
                                <div class="col-md-3" style="text-align: center;">
                                    <label for="foto_profil">Foto Profil</label>
                                    <br>
                                    <img src="<?= ($pengguna['foto_profil'] != null) ? base_url() . '/assets/images/pengguna/' . $pengguna['foto_profil'] : base_url() . '/assets/img/profile-placeholder.jpg' ?>"
                                        alt="User Profile" style="width: 200px;height: 200px;" id="img-preview">
                                    <br>
                                    <br>
                                    <input type="file" class="form-control" name="foto_profil" id="foto_profil"
                                        accept="image/*" onchange="onFileChange(event)">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php if ($user['rule'] == 'waka'): ?>
                                <label for="nomor">Nomor (NISN) <small class="text-danger">*</small></label>
                            <?php else: ?>
                                <label for="nomor">Nomor (NIK/NIP) <small class="text-danger">*</small></label>
                            <?php endif ?>
                            <input type="text" class="form-control" name="nomor" required id="nomor"
                                placeholder="Nomor..." value="<?= $pengguna['nomor'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap <small class="text-danger">*</small></label>
                            <input type="text" class="form-control" name="nama_lengkap" required id="nama_lengkap"
                                placeholder="Nama Lengkap..." value="<?= $pengguna['nama_lengkap'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="jenis_kelamin">Jenis Kelamin <small class="text-danger">*</small></label>
                            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin">
                                <option value="L" <?= ($pengguna['jenis_kelamin'] == 'L') ? 'selected' : '' ?>>Laki-Laki
                                </option>
                                <option value="P" <?= ($pengguna['jenis_kelamin'] == 'P') ? 'selected' : '' ?>>Perempuan
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="username">Username <small class="text-danger">*</small></label>
                            <input type="text" class="form-control" name="username" required id="username" minlength="5"
                                placeholder="Username..." value="<?= $pengguna['username'] ?>">
                        </div>


                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="name@example.com" value="<?= $pengguna['email'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="kontak">Kontak (WA/TELP)</label>
                            <input type="number" class="form-control" id="kontak" name="kontak" placeholder="08...."
                                value="<?= $pengguna['kontak'] ?>">
                        </div>

                        <hr>
                        <div class="form-group">
                            <label for="password_baru">Password Baru <small class="text-info">kosongkan jika tidak
                                    diubah</small></label>
                            <input type="password" class="form-control" name="password_baru" minlength="5"
                                id="password_baru" placeholder="Password...">
                        </div>
                        <button type="submit" class="btn btn-primary" name="edit">Submit</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
<?php
include_once __DIR__ . "/../pages/_partials/bottom.php";
?>

<script>
    const imgPreview = document.getElementById("img-preview");
    function onFileChange(event) {

        imgPreview.src = URL.createObjectURL(event.target.files[0]);
        imgPreview.onload = function () {
            URL.revokeObjectURL(imgPreview.src) // free memory
        }
    }
</script>