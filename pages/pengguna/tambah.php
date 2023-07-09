<?php
include_once __DIR__ . "/../../config/database.php";
include_once __DIR__ . "/../../library/cek_session.php";
if ($user['rule'] != 'waka') {
    http_response_code(404);
    echo "404 Not Found";
    die();
}
$title = "Tambah Pengguna";
include_once __DIR__ . "/../../pages/_partials/top.php";
include_once __DIR__ . "/../../actions/_models/Pengguna.php";
?>

<div class="container-fluid">
    <h1 class="dash-title">Tambah Pengguna</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card spur-card">
                <div class="card-header text-right">
                    <div class="spur-card-title"> Tambah Data Pengguna</div>
                </div>
                <div class="card-body ">
                    <form action="<?= base_url() . '/actions/pengguna_action.php' ?>" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <div class="row" style="
                            display:flex;
                            justify-content: center;
                            ">
                                <div class="col-md-3" style="text-align: center;">
                                    <label for="foto_profil">Foto Profil</label>
                                    <br>
                                    <img src="<?= base_url() ?>/assets/img/profile-placeholder.jpg" alt="User Profile"
                                        style="width: 200px;height: 200px;" id="img-preview">
                                    <br>
                                    <br>
                                    <input type="file" class="form-control" name="foto_profil" required id="foto_profil"
                                    accept="image/*" onchange="onFileChange(event)">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nomor">Nomor (NIK/NIP/NIM) <small class="text-danger">*</small></label>
                            <input type="text" class="form-control" name="nomor" required id="nomor"
                                placeholder="Nomor...">
                        </div>
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap <small class="text-danger">*</small></label>
                            <input type="text" class="form-control" name="nama_lengkap" required id="nama_lengkap"
                                placeholder="Nama Lengkap...">
                        </div>
                        <div class="form-group">
                            <label for="jenis_kelamin">Jenis Kelamin <small class="text-danger">*</small></label>
                            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin">
                                <option value="L">Laki-Laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="username">Username <small class="text-danger">*</small></label>
                            <input type="text" class="form-control" name="username" required id="username" minlength="5"
                                placeholder="Username...">
                        </div>
                        <div class="form-group">
                            <label for="password">Password <small class="text-danger">*</small></label>
                            <input type="password" class="form-control" name="password" minlength="5" required
                                id="password" placeholder="Password...">
                        </div>
                        <div class="form-group">
                            <label for="rule">Rule <small class="text-danger">*</small></label>
                            <select class="form-control" id="rule" name="rule">
                                <option value="waka">Waka</option>
                                <option value="guru">Guru</option>
                                <option value="siswa">Siswa</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="name@example.com">
                        </div>
                        <div class="form-group">
                            <label for="kontak">Kontak (WA/TELP)</label>
                            <input type="number" class="form-control" id="kontak" name="kontak" placeholder="08....">
                        </div>
                        <button type="submit" class="btn btn-primary" name="create">Submit</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
<?php
include_once __DIR__ . "/../../pages/_partials/bottom.php";
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