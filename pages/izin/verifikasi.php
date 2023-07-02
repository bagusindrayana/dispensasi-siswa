<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/config/database.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/library/cek_session.php";
if($user['rule']=='guru'){
    http_response_code(404);
    echo "404 Not Found";
    die();
}
$title = "Izin";
include_once $_SERVER['DOCUMENT_ROOT'] . "/pages/_partials/top.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/actions/_models/Izin.php";

$model = new Izin();
$query = "SELECT izin.*,siswa.nama_lengkap as nama_siswa,guru.nama_lengkap as nama_guru FROM izin 
INNER JOIN pengguna as siswa ON siswa.id = izin.siswa_id 
INNER JOIN pengguna as guru ON guru.id = izin.guru_id 
WHERE (siswa.nama_lengkap LIKE :keyword OR guru.nama_lengkap LIKE :keyword OR izin.tanggal LIKE :keyword OR izin.keterangan LIKE :keyword) AND izin.status = 'pending'
ORDER BY izin.id DESC ";
$izins = $model->paginationAndSearch(10, $_GET['search'] ?? '',$query);
?>

<div class="container-fluid">
    <h1 class="dash-title">Verifikasi Izin Pending</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card spur-card">
                <div class="card-header">
                    <form class="searchbox" action="#!">
                        <a href="/" class="searchbox-toggle"> <i class="fas fa-arrow-left"></i> </a>
                        <button type="submit" class="searchbox-submit"> <i class="fas fa-search"></i> </button>
                        <input type="text" name="search" class="searchbox-input" placeholder="type to search"
                            value="<?= $_GET['search'] ?? '' ?>">
                    </form>
                   
                </div>
                <div class="card-body ">
                <table class="table table-in-card">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Siswa</th>
                                <th scope="col">Kelas/Jurusan</th>
                                <th scope="col">Tanggal/Waktu</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($izins as $izin) {
                                ?>
                                <tr>
                                    <th scope="row">
                                        <?= $no++ ?>
                                    </th>
                                    <td>
                                        <?= $izin['nama_siswa'] ?>
                                    </td>
                                    <td>
                                        <?= $izin['kelas_jurusan'] ?>
                                    </td>
                                    <td>
                                        <?= $izin['tanggal'] ?>/<?= $izin['waktu'] ?>
                                    </td>
                                    <td>
                                        <?= $izin['keterangan'] ?>
                                    </td>
                                    <td>
                                        <?php switch ($izin['status']) {
                                            case "pending":
                                                echo '<span class="badge badge-warning">Pending</span>';
                                                break;
                                            case "disetujui":
                                                echo '<span class="badge badge-success">Disetujui</span>';
                                                break;
                                            case "ditolak":
                                                echo '<span class="badge badge-danger">Ditolak</span>';
                                                break;
                                        }?>

                                    </td>
                                    <td>
                                        <a href="<?= base_url() . '/pages/izin/verifikasi_form.php?id=' . $izin['id'] ?>"
                                            class="btn btn-success"><i class="fas fa-check"></i> Verifikasi</a>
                                        
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7">
                                    <?= $model->html_pagination ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/pages/_partials/bottom.php";
?>