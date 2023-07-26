<?php
include_once __DIR__ . "/../../config/database.php";
include_once __DIR__ . "/../../library/cek_session.php";
if ($user['rule'] != 'waka') {
    http_response_code(404);
    echo "404 Not Found";
    die();
}
$title = "Import Data Pengguna";
include_once __DIR__ . "/../../pages/_partials/top.php";
include_once __DIR__ . "/../../actions/_models/Pengguna.php";
?>

<div class="container-fluid">
    <h1 class="dash-title">Data Pengguna</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card spur-card">
                <div class="card-header text-right">
                    <div class="spur-card-title"> Import Data Pengguna</div>
                </div>
                <div class="card-body ">
                    <form action="<?= base_url() . '/actions/pengguna_action.php' ?>" method="POST" enctype="multipart/form-data">
                        <p class="text-danger">
                            <a href="<?= base_url() . '/assets/excel/format_import_data_pengguna.xlsx' ?>" class="text-danger">Download format import excel <i class="fas fa-file-excel"></i></a>
                        </p>
                        <div class="form-group">
                            <label for="file_excel">File Excel <small class="text-danger">*</small></label>
                            <input type="file" required class="form-control" id="file_excel" name="file_excel" placeholder="File Excel" onchange="checkfile(this);" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                        </div>
                        <button type="submit" class="btn btn-primary" name="import">Submit</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
<?php
include_once __DIR__ . "/../../pages/_partials/bottom.php";
?>

<script type="text/javascript" language="javascript">
function checkfile(sender) {
    var validExts = new Array(".xlsx", ".xls");
    var fileExt = sender.value;
    fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
    if (validExts.indexOf(fileExt) < 0) {
      alert("Invalid file selected, valid files are of " +
               validExts.toString() + " types.");
      return false;
    }
    else return true;
}
</script>