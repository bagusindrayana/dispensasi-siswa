<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/actions/_models/Pengguna.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class PenggunaAction {
    private $model;
    private $conn;

    public function __construct() {
        $this->model = new Pengguna();
        $this->conn = $GLOBALS['conn'];
    }

    public function createData() {
        if(!isset($_POST['nama_lengkap']) || !isset($_POST['rule']) || !isset($_POST['username']) || !isset($_POST['password'])) {
            $_SESSION['error'] = "Silahkan lengkapi semua inputan";
            //redirect back
            header("Location: ../pages/pengguna/tambah.php");
            exit;
        }

        //cek username
        $sql = "SELECT * FROM  pengguna  WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $_POST['username']);
        $stmt->execute();
        if($stmt->rowCount()){
            $_SESSION['error'] = "Username sudah pernah digunakan";
            header("Location: ../pages/pengguna/index.php");
            exit;
        }

        $data = [
            'nomor' => $_POST['nomor'],
            'nama_lengkap' => $_POST['nama_lengkap'],
            'rule' => $_POST['rule'],
            'email' => $_POST['email'],
            'kontak' => $_POST['kontak'],
            'username' => $_POST['username'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
        ];

        try {
            $this->model->create($data);
            $_SESSION['success'] = "Pengguna berhasil di tambah";
            header("Location: ../pages/pengguna/index.php");
        } catch (\Throwable $th) {
            $_SESSION['error'] = "Pengguna gagal di tambah : ".$th->getMessage();
            //redirect back
            header("Location: ../pages/pengguna/tambah.php");
        }
    }

    public function editData() {
        if(!isset($_POST['nama_lengkap']) || !isset($_POST['rule']) || !isset($_POST['username'])) {
            $_SESSION['error'] = "Silahkan lengkapi semua inputan";
            //redirect back
            header("Location: ../pages/pengguna/edit.php?id=".$_GET['id']);
            exit;
        }
        $sql = "SELECT * FROM  pengguna  WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        $oldData = $stmt->fetch(PDO::FETCH_ASSOC);
        if($_POST['username'] != $oldData['username']){
            //cek username
            $sql = "SELECT * FROM  pengguna  WHERE username = :username";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $_POST['username']);
            $stmt->execute();
            if($stmt->rowCount()){
                $_SESSION['error'] = "Username sudah pernah digunakan";
                header("Location: ../pages/pengguna/edit.php?id=".$_GET['id']);
                exit;
            }
        }
        

        $data = [
            'nomor' => $_POST['nomor'],
            'nama_lengkap' => $_POST['nama_lengkap'],
            'rule' => $_POST['rule'],
            'email' => $_POST['email'],
            'kontak' => $_POST['kontak'],
            'username' => $_POST['username'],
            
        ];

        if(isset($_POST['password_baru']) && $_POST['password_baru'] != ""){
            $data['password'] = password_hash($_POST['password_baru'], PASSWORD_DEFAULT);
        }

        try {
            $this->model->update($_GET['id'],$data);
            $_SESSION['success'] = "Pengguna berhasil di ubah";
            header("Location: ../pages/pengguna/index.php");
        } catch (\Throwable $th) {
            $_SESSION['error'] = "Pengguna gagal di ubah : ".$th->getMessage();
            //redirect back
            header("Location: ../pages/pengguna/edit.php");
        }
    }

    //delete
    public function deleteData() {
        if(!isset($_GET['id'])) {
            $_SESSION['error'] = "Silahkan pilih data yang akan di hapus";
            //redirect back
            header("Location: ../pages/pengguna/index.php");
            exit;
        }

        try {
            $this->model->delete($_GET['id']);
            $_SESSION['success'] = "Pengguna berhasil di hapus";
            header("Location: ../pages/pengguna/index.php");
        } catch (\Throwable $th) {
            $_SESSION['error'] = "Pengguna gagal di hapus : ".$th->getMessage();
            //redirect back
            header("Location: ../pages/pengguna/index.php");
        }
    }
}

if(isset($_POST["create"])) {
    $penggunaAction = new PenggunaAction();
    $penggunaAction->createData();
} else if(isset($_POST["edit"]) && isset($_GET['id'])) {
    $penggunaAction = new PenggunaAction();
    $penggunaAction->editData();
} else if(isset($_POST["delete"]) && isset($_GET['id'])) {
    $penggunaAction = new PenggunaAction();
    $penggunaAction->deleteData();
} else {
    //method not allowed
    http_response_code(405);
    echo "405 Method not allowed";
    die();
}

