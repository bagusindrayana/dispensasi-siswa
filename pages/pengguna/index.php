<?php
include_once __DIR__ . "/../../config/database.php";
include_once __DIR__ . "/../../library/cek_session.php";
if($user['rule']!='waka'){
    http_response_code(404);
    echo "404 Not Found";
    die();
}
$title = "Pengguna";
include_once __DIR__ . "/../../pages/_partials/top.php";
include_once __DIR__ . "/../../actions/_models/Pengguna.php";

$model = new Pengguna();
$penggunas = $model->paginationAndSearch(10, $_GET['search'] ?? '');
?>

<div class="container-fluid">
    <h1 class="dash-title">Data Pengguna</h1>
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
                    <a href="<?= base_url() . '/pages/pengguna/tambah.php' ?>" class="btn btn-info m-1"><i
                            class="fas fa-plus"></i> Tambah Pengguna</a>
                            <a href="<?= base_url() . '/pages/pengguna/import.php' ?>" class="btn btn-success m-1"><i
                            class="fas fa-file-excel"></i> Import Excel</a>
                </div>
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nomor</th>
                                <th scope="col">Nama Lengkap</th>
                                <th scope="col">Rule</th>
                                <th scope="col">Email</th>
                                <th scope="col">Kontak</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($penggunas as $pengguna) {
                                ?>
                                <tr>
                                    <th scope="row">
                                        <?= $no++ ?>
                                    </th>
                                    <td>
                                        <?= $pengguna['nomor'] ?>
                                    </td>
                                    <td>
                                        <?= $pengguna['nama_lengkap'] ?>
                                    </td>
                                    <td>
                                        <?= $pengguna['rule'] ?>
                                    </td>
                                    <td>
                                        <?= $pengguna['email'] ?>
                                    </td>
                                    <td>
                                        <?= $pengguna['kontak'] ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url() . '/pages/pengguna/edit.php?id=' . $pengguna['id'] ?>"
                                            class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                        <?php if ($pengguna['id'] != $user['id']): ?>
                                            <a href="#" onclick="confirmDelete('delete-<?= $pengguna['id'] ?>')"
                                                class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</a>
                                            <form
                                                action="<?= base_url() . '/actions/pengguna_action.php?id=' . $pengguna['id'] ?>"
                                                id="delete-<?= $pengguna['id'] ?>" method="POST">
                                                <input type="hidden" name="delete" value="true">
                                            </form>
                                        <?php endif; ?>
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