<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/actions/_models/Izin.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/actions/_models/Pengguna.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/library/cek_session.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class IzinAction {
    public $model;
    private $conn;

    public function __construct() {
        $this->model = new Izin();
        $this->conn = $GLOBALS['conn'];
    }

    public function createData() {
        $user = $GLOBALS['user'];
        if(!isset($_POST['guru_id']) || !isset($_POST['kelas_jurusan']) || !isset($_POST['keterangan'])) {
            $_SESSION['error'] = "Silahkan lengkapi semua inputan";
            //redirect back
            header("Location: ../pages/izin/tambah.php");
            exit;
        }

       

        $data = [
            
            'guru_id' => $_POST['guru_id'],
            'kelas_jurusan' => $_POST['kelas_jurusan'],
            'tanggal' => $_POST['tanggal'],
            'waktu' => $_POST['waktu'],
            'keterangan' => $_POST['keterangan'],
            
        ];
       
        if($user['rule']=='siswa'){
            $data['siswa_id'] = $user['id'];
        } else {
            if(!isset($_POST['siswa_id'])) {
                $_SESSION['error'] = "Silahkan lengkapi semua inputan";
                //redirect back
                header("Location: ../pages/izin/tambah.php");
                exit;
            }
            $data['siswa_id'] = $_POST['siswa_id'];
        }

        $cekSiswa = new Pengguna();
        $cekSiswa = $cekSiswa->findById($data['siswa_id']);
        if($cekSiswa['rule'] != 'siswa'){
            $_SESSION['error'] = "Siswa tidak ditemukan";
            //redirect back
            header("Location: ../pages/izin/tambah.php");
            exit;
        }

        try {
            $this->model->create($data);
            $_SESSION['success'] = "Izin berhasil di tambah";
            header("Location: ../pages/izin/index.php");
        } catch (\Throwable $th) {
            $_SESSION['error'] = "Izin gagal di tambah : ".$th->getMessage();
            //redirect back
            header("Location: ../pages/izin/tambah.php");
        }
    }

    public function editData() {
        $user = $GLOBALS['user'];
        if(!isset($_POST['guru_id']) || !isset($_POST['kelas_jurusan']) || !isset($_POST['keterangan'])) {
            $_SESSION['error'] = "Silahkan lengkapi semua inputan";
            //redirect back
            header("Location: ../pages/izin/edit.php?id=".$_GET['id']);
            exit;
        }
        

        $oldData = $this->model->findById($_GET['id']);
        if($user['rule'] == 'siswa' && $oldData['siswa_id'] != $user['id']){
            http_response_code(405);
            echo "405 Method not allowed";
            die();
        } else if($user['rule'] == 'guru'){
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

        if($user['rule']=='siswa'){
            $data['siswa_id'] = $user['id'];
        } else {
            if(!isset($_POST['siswa_id'])) {
                $_SESSION['error'] = "Silahkan lengkapi semua inputan";
                //redirect back
                header("Location: ../pages/izin/tambah.php");
                exit;
            }
            $data['siswa_id'] = $_POST['siswa_id'];
        }

        $cekSiswa = new Pengguna();
        $cekSiswa = $cekSiswa->findById($data['siswa_id']);
        if($cekSiswa['rule'] != 'siswa'){
            $_SESSION['error'] = "Siswa tidak ditemukan";
            //redirect back
            header("Location: ../pages/izin/tambah.php");
            exit;
        }


        try {
            $this->model->update($_GET['id'],$data);
            $_SESSION['success'] = "Izin berhasil di ubah";
            header("Location: ../pages/izin/index.php");
        } catch (\Throwable $th) {
            $_SESSION['error'] = "Izin gagal di ubah : ".$th->getMessage();
            //redirect back
            header("Location: ../pages/izin/edit.php?id=".$_GET['id']);
        }
    }

    //delete
    public function deleteData() {
        if(!isset($_GET['id'])) {
            $_SESSION['error'] = "Silahkan pilih data yang akan di hapus";
            //redirect back
            header("Location: ../pages/izin/index.php");
            exit;
        }

        try {
            $this->model->delete($_GET['id']);
            $_SESSION['success'] = "Izin berhasil di hapus";
            header("Location: ../pages/izin/index.php");
        } catch (\Throwable $th) {
            $_SESSION['error'] = "Izin gagal di hapus : ".$th->getMessage();
            //redirect back
            header("Location: ../pages/izin/index.php");
        }
    }
}

if(isset($_POST["create"])) {
    $izinAction = new IzinAction();
    $izinAction->createData();
} else if(isset($_POST["edit"]) && isset($_GET['id'])) {
    $izinAction = new IzinAction();
    $izinAction->editData();
} else if(isset($_POST["delete"]) && isset($_GET['id'])) {
    $izinAction = new IzinAction();
    $izinAction->deleteData();
} else if(isset($_POST["disetujui"]) && isset($_GET['id'])) {
    $izinAction = new IzinAction();
    try {
        $izinAction->model->update($_GET['id'],[
            'status' => 'disetujui',
            'keterangan_status_diubah'=>$_POST['keterangan_status_diubah'],
            'waktu_status_diubah'=>date("Y-m-d H:i:s"),
            'waka_id'=>$user['id']
        ]);
        $_SESSION['success'] = "Izin berhasil di verifikasi";
        header("Location: ../pages/izin/index.php");
    } catch (\Throwable $th) {
        $_SESSION['error'] = "Izin gagal di verifikasi : ".$th->getMessage();
        //redirect back
        header("Location: ../pages/izin/verifikasi_form.php?id=".$_GET['id']);
    }
    
} else if(isset($_POST["ditolak"]) && isset($_GET['id'])) {
    $izinAction = new IzinAction();
    try {
        $izinAction->model->update($_GET['id'],[
            'status' => 'ditolak',
            'keterangan_status_diubah'=>$_POST['keterangan_status_diubah'],
            'waktu_status_diubah'=>date("Y-m-d H:i:s"),
            'waka_id'=>$user['id']
        ]);
        $_SESSION['success'] = "Izin berhasil di verifikasi";
        header("Location: ../pages/izin/index.php");
    } catch (\Throwable $th) {
        $_SESSION['error'] = "Izin gagal di verifikasi : ".$th->getMessage();
        //redirect back
        header("Location: ../pages/izin/verifikasi_form.php?id=".$_GET['id']);
    }
    
}  else {
    //method not allowed
    http_response_code(405);
    echo "405 Method not allowed";
    die();
}

