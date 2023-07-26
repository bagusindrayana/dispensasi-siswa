<?php

include_once __DIR__ . "/../../config/database.php";
include_once __DIR__ . "/../../library/cek_session.php";
$title = "Izin";
include_once __DIR__ . "/../../pages/_partials/top.php";
include_once __DIR__ . "/../../actions/_models/Izin.php";

$model = new Izin();
if ($user['rule'] == 'waka') {
    $izins = $model->paginationAndSearch(10, $_GET['search'] ?? '');
} else if ($user['rule'] == 'guru') {
    $query = "SELECT izin.*,siswa.nama_lengkap as nama_siswa,guru.nama_lengkap as nama_guru FROM izin 
    INNER JOIN pengguna as siswa ON siswa.id = izin.siswa_id 
    INNER JOIN pengguna as guru ON guru.id = izin.guru_id 
    WHERE (siswa.nama_lengkap LIKE :keyword OR guru.nama_lengkap LIKE :keyword OR izin.tanggal LIKE :keyword OR izin.keterangan LIKE :keyword) AND izin.status = 'pending' AND izin.guru_id = " . $user['id'] . "
    ORDER BY izin.id DESC ";
    $izins = $model->paginationAndSearch(10, $_GET['search'] ?? '', $query);
} else if ($user['rule'] == 'siswa') {
    $query = "SELECT izin.*,siswa.nama_lengkap as nama_siswa,guru.nama_lengkap as nama_guru FROM izin 
    INNER JOIN pengguna as siswa ON siswa.id = izin.siswa_id 
    INNER JOIN pengguna as guru ON guru.id = izin.guru_id 
    WHERE (siswa.nama_lengkap LIKE :keyword OR guru.nama_lengkap LIKE :keyword OR izin.tanggal LIKE :keyword OR izin.keterangan LIKE :keyword) AND izin.status = 'pending' AND izin.siswa_id = " . $user['id'] . "
    ORDER BY izin.id DESC ";
    $izins = $model->paginationAndSearch(10, $_GET['search'] ?? '', $query);
}


?>

<div class="container-fluid">
    <h1 class="dash-title">Data Izin</h1>
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
                    <?php if ($user['rule'] == 'waka' || $user['rule'] == 'siswa') { ?>
                    <a href="<?= base_url() . '/pages/izin/tambah.php' ?>" class="btn btn-info m-1"><i
                            class="fas fa-plus"></i> Tambah Izin</a>
                            <a href="<?= base_url() . '/pages/izin/import.php' ?>" class="btn btn-success m-1"><i
                            class="fas fa-file-excel"></i> Import Excel</a>
                    <?php } ?>
                </div>
                <div class="card-body table-responsive">
                    <table class="table">
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
                                        <?= $izin['tanggal'] ?> /
                                        <?= date("H:i",strtotime($izin['waktu'])) ?>
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
                                        } ?>

                                    </td>
                                    <td>
                                        <?php if ($izin['status'] == 'pending') { ?>
                                            <?php if ($user['rule'] == 'waka' || $user['rule'] == 'siswa') { ?>
                                                <a href="<?= base_url() . '/pages/izin/edit.php?id=' . $izin['id'] ?>"
                                                    class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                                <a href="#" onclick="confirmDelete('delete-<?= $izin['id'] ?>')"
                                                    class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</a>
                                                <form action="<?= base_url() . '/actions/izin_action.php?id=' . $izin['id'] ?>"
                                                    id="delete-<?= $izin['id'] ?>" method="POST">
                                                    <input type="hidden" name="delete" value="true">
                                                </form>
                                            <?php } else {
                                                ?>
                                                <a href="<?= base_url() . '/pages/izin/detail.php?id=' . $izin['id'] ?>"
                                                    class="btn btn-info"><i class="fas fa-edit"></i> Detail</a>
                                                <?php
                                            } ?>
                                        <?php } else { ?>
                                            <a href="<?= base_url() . '/pages/izin/detail.php?id=' . $izin['id'] ?>"
                                                    class="btn btn-info"><i class="fas fa-edit"></i> Detail</a>
                                                    <a href="<?= base_url() . '/actions/izin_action.php?download-pdf=true&id=' . $izin['id'] ?>"
                                                    class="btn btn-info" target="_blank"><i class="fas fa-file"></i> Download</a>
                                                <br>
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
                                        <?php } ?>
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
include_once __DIR__ . "/../../pages/_partials/bottom.php";
?>