<?php
require_once __DIR__ . "/../config/application.php";
include_once __DIR__ . "/../actions/_models/Izin.php";
include_once __DIR__ . "/../actions/_models/Pengguna.php";
include_once __DIR__ . "/../library/cek_session.php";
include_once __DIR__ . "/../library/include_library.php";
use Dompdf\Dompdf;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class IzinAction
{
    public $model;
    private $conn;

    public function __construct()
    {
        $this->model = new Izin();
        $this->conn = $GLOBALS['conn'];
    }

    public function createData()
    {
        $user = $GLOBALS['user'];
        if (!isset($_POST['guru_id']) || !isset($_POST['kelas_jurusan']) || !isset($_POST['keterangan'])) {
            $_SESSION['error'] = "Silahkan lengkapi semua inputan";
            //redirect back
            header("Location: ".base_url()."/pages/izin/tambah.php");
            exit;
        }



        $data = [

            'guru_id' => $_POST['guru_id'],
            'kelas_jurusan' => $_POST['kelas_jurusan'],
            'tanggal' => $_POST['tanggal'],
            'waktu' => $_POST['waktu'],
            'keterangan' => $_POST['keterangan'],

        ];

        if ($user['rule'] == 'siswa') {
            $data['siswa_id'] = $user['id'];
        } else {
            if (!isset($_POST['siswa_id'])) {
                $_SESSION['error'] = "Silahkan lengkapi semua inputan";
                //redirect back
                header("Location: ".base_url()."/pages/izin/tambah.php");
                exit;
            }
            $data['siswa_id'] = $_POST['siswa_id'];
        }

        $cekSiswa = new Pengguna();
        $cekSiswa = $cekSiswa->findById($data['siswa_id']);
        if ($cekSiswa['rule'] != 'siswa') {
            $_SESSION['error'] = "Siswa tidak ditemukan";
            //redirect back
            header("Location: ".base_url()."/pages/izin/tambah.php");
            exit;
        }

        try {
            $this->model->create($data);
            $_SESSION['success'] = "Izin berhasil di tambah";
            header("Location: ".base_url()."/pages/izin/index.php");
        } catch (\Throwable $th) {
            $_SESSION['error'] = "Izin gagal di tambah : " . $th->getMessage();
            //redirect back
            header("Location: ".base_url()."/pages/izin/tambah.php");
        }
    }

    public function editData()
    {
        $user = $GLOBALS['user'];
        if (!isset($_POST['guru_id']) || !isset($_POST['kelas_jurusan']) || !isset($_POST['keterangan'])) {
            $_SESSION['error'] = "Silahkan lengkapi semua inputan";
            //redirect back
            header("Location: ".base_url()."/pages/izin/edit.php?id=" . $_GET['id']);
            exit;
        }


        $oldData = $this->model->findById($_GET['id']);
        if ($user['rule'] == 'siswa' && $oldData['siswa_id'] != $user['id']) {
            http_response_code(405);
            echo "405 Method not allowed";
            die();
        } else if ($user['rule'] == 'guru') {
            http_response_code(405);
            echo "405 Method not allowed";
            die();
        }


        $data = [

            'guru_id' => $_POST['guru_id'],
            'kelas_jurusan' => $_POST['kelas_jurusan'],
            'tanggal' => $_POST['tanggal'],
            'waktu' => $_POST['waktu'],
            'keterangan' => $_POST['keterangan'],

        ];

        if ($user['rule'] == 'siswa') {
            $data['siswa_id'] = $user['id'];
        } else {
            if (!isset($_POST['siswa_id'])) {
                $_SESSION['error'] = "Silahkan lengkapi semua inputan";
                //redirect back
                header("Location: ".base_url()."/pages/izin/tambah.php");
                exit;
            }
            $data['siswa_id'] = $_POST['siswa_id'];
        }

        $cekSiswa = new Pengguna();
        $cekSiswa = $cekSiswa->findById($data['siswa_id']);
        if ($cekSiswa['rule'] != 'siswa') {
            $_SESSION['error'] = "Siswa tidak ditemukan";
            //redirect back
            header("Location: ".base_url()."/pages/izin/tambah.php");
            exit;
        }


        try {
            $this->model->update($_GET['id'], $data);
            $_SESSION['success'] = "Izin berhasil di ubah";
            header("Location: ".base_url()."/pages/izin/index.php");
        } catch (\Throwable $th) {
            $_SESSION['error'] = "Izin gagal di ubah : " . $th->getMessage();
            //redirect back
            header("Location: ".base_url()."/pages/izin/edit.php?id=" . $_GET['id']);
        }
    }

    //delete
    public function deleteData()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error'] = "Silahkan pilih data yang akan di hapus";
            //redirect back
            header("Location: ".base_url()."/pages/izin/index.php");
            exit;
        }

        try {
            $this->model->delete($_GET['id']);
            $_SESSION['success'] = "Izin berhasil di hapus";
            header("Location: ".base_url()."/pages/izin/index.php");
        } catch (\Throwable $th) {
            $_SESSION['error'] = "Izin gagal di hapus : " . $th->getMessage();
            //redirect back
            header("Location: ".base_url()."/pages/izin/index.php");
        }
    }

    //import file excel
    public function importData()
    {
        $file_mimes = array('application/vnd.ms-excel', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        if (isset($_FILES['file_excel']['name']) && in_array($_FILES['file_excel']['type'], $file_mimes)) {
            $arr_file = explode('.', $_FILES['file_excel']['name']);
            $extension = end($arr_file);

            if ('csv' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }

            $spreadsheet = $reader->load($_FILES['file_excel']['tmp_name']);

            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            $error = null;
            if (!empty($sheetData)) {
                for ($i = 1; $i < count($sheetData); $i++) {
                    $nama_siswa = $sheetData[$i][0];
                    $nama_guru = $sheetData[$i][1];
                    $kelas = $sheetData[$i][2];
                    $tanggal =  $sheetData[$i][3];
                    $waktu =  $sheetData[$i][4];
                    $keterangan =  $sheetData[$i][5];

                    if($nama_siswa != "" && $nama_siswa != null && !str_contains(strtolower($nama_siswa), 'nama')){
                        
                        $m_siswa = new Pengguna();
                        $cek_siswa = $m_siswa->rawQuery("select id from pengguna where nama_lengkap = '$nama_siswa' and rule = 'siswa'")->fetch();
                        if(!$cek_siswa){
                            $error .= "\n Siswa dengan nama $nama_siswa tidak ditemukan, ";
                            continue;
                        }
                        
                        $m_guru = new Pengguna();
                        $cek_guru = $m_guru->rawQuery("select id from pengguna where nama_lengkap = '$nama_guru' and rule = 'guru'")->fetch();
                        if(!$cek_guru){
                            $error .= "\n Guru dengan nama $nama_guru tidak ditemukan, ";
                            continue;
                        }

                        if(is_string($tanggal)){
                            $tanggal = date("Y-m-d", strtotime($tanggal));
                        } else {
                            $tanggal = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggal)->format('Y-m-d');
                            $tanggal = date("Y-m-d", strtotime($tanggal));
                        }

                        $data = [
                            'siswa_id' => $cek_siswa['id'],
                            'guru_id' => $cek_guru['id'],
                            'kelas_jurusan' => $kelas,
                            'tanggal' => $tanggal,
                            'waktu' => $waktu,
                            'keterangan' => $keterangan,
                        ];

                        try {
                            $this->model->create($data);
                        } catch (\Throwable $th) {
                            $error .= "\n Pengguna gagal di ubah : " . $th->getMessage();
                            
                        }

                        
                    }
                }
                $_SESSION['success'] = "File berhasil di import";
            }
            if($error != null){
                $_SESSION['error'] = $error;
            
                header("Location: " . base_url() . "/pages/izin/import.php");
            } else {
                $_SESSION['error'] = $error;
            
                header("Location: " . base_url() . "/pages/izin/index.php");
            }
        } else {
            $_SESSION['error'] = "Silahkan pilih file excel";
            //redirect back
            header("Location: " . base_url() . "/pages/izin/import.php");
            exit;
        }
    }
}

if (isset($_POST["create"])) {
    $izinAction = new IzinAction();
    $izinAction->createData();
} else if (isset($_POST["edit"]) && isset($_GET['id'])) {
    $izinAction = new IzinAction();
    $izinAction->editData();
} else if (isset($_POST["delete"]) && isset($_GET['id'])) {
    $izinAction = new IzinAction();
    $izinAction->deleteData();
} else if (isset($_POST["disetujui"]) && isset($_GET['id'])) {
    $izinAction = new IzinAction();
    try {
        $izinAction->model->update($_GET['id'], [
            'status' => 'disetujui',
            'keterangan_status_diubah' => $_POST['keterangan_status_diubah'],
            'waktu_status_diubah' => date("Y-m-d H:i:s"),
            'waka_id' => $user['id']
        ]);
        $_SESSION['success'] = "Izin berhasil di verifikasi";
        header("Location: ".base_url()."/pages/izin/index.php");
    } catch (\Throwable $th) {
        $_SESSION['error'] = "Izin gagal di verifikasi : " . $th->getMessage();
        //redirect back
        header("Location: ".base_url()."/pages/izin/verifikasi_form.php?id=" . $_GET['id']);
    }

} else if (isset($_POST["ditolak"]) && isset($_GET['id'])) {
    $izinAction = new IzinAction();
    try {
        $izinAction->model->update($_GET['id'], [
            'status' => 'ditolak',
            'keterangan_status_diubah' => $_POST['keterangan_status_diubah'],
            'waktu_status_diubah' => date("Y-m-d H:i:s"),
            'waka_id' => $user['id']
        ]);
        $_SESSION['success'] = "Izin berhasil di verifikasi";
        header("Location: ".base_url()."/pages/izin/index.php");
    } catch (\Throwable $th) {
        $_SESSION['error'] = "Izin gagal di verifikasi : " . $th->getMessage();
        //redirect back
        header("Location: ".base_url()."/pages/izin/verifikasi_form.php?id=" . $_GET['id']);
    }

} else if (isset($_GET['download-pdf']) && isset($_GET['id'])) {
    $modelIzin = new Izin();
    $izin = $modelIzin->rawQuery("SELECT izin.*,siswa.nomor,siswa.nama_lengkap as nama_siswa,guru.nama_lengkap as nama_guru,waka.nama_lengkap as nama_waka FROM izin 
INNER JOIN pengguna as siswa ON siswa.id = izin.siswa_id 
INNER JOIN pengguna as guru ON guru.id = izin.guru_id
INNER JOIN pengguna as waka ON waka.id = izin.waka_id
WHERE izin.id = " . $_GET['id'])->fetch();

    $file = "ijin_" . $izin['nomor'] . "_" . date("d_m_Y", strtotime($izin['tanggal'])) . "_" . $izin['id'] . ".pdf";
    ob_start();
    require __DIR__ . "/../pages/izin/download-pdf.php"; // the one you posted in your question
    $html = ob_get_clean();


    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $options = $dompdf->getOptions();
    $options->setIsRemoteEnabled(true);
    $dompdf->setOptions($options);
    //set paper size
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream($file);
} else if (isset($_POST["import"])) {
    $izinAction = new IzinAction();
    $izinAction->importData();
} else {
    //method not allowed
    http_response_code(405);
    echo "405 Method not allowed";
    die();
}