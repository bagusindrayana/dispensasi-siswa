<?php
include_once __DIR__ . "/../config/application.php";
include_once __DIR__ . "/../actions/_models/Pengguna.php";
include_once __DIR__ . "/../library/include_library.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class PenggunaAction
{
    private $model;
    private $conn;

    public function __construct()
    {
        $this->model = new Pengguna();
        $this->conn = $GLOBALS['conn'];
    }

    public function createData()
    {
        if (!isset($_POST['nama_lengkap']) || !isset($_POST['rule']) || !isset($_POST['username']) || !isset($_POST['password'])) {
            $_SESSION['error'] = "Silahkan lengkapi semua inputan";
            //redirect back
            header("Location: " . base_url() . "/pages/pengguna/tambah.php");
            exit;
        }

        //cek username
        $sql = "SELECT * FROM  pengguna  WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $_POST['username']);
        $stmt->execute();
        if ($stmt->rowCount()) {
            $_SESSION['error'] = "Username sudah pernah digunakan";
            header("Location: " . base_url() . "/pages/pengguna/index.php");
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
            'jenis_kelamin' => $_POST['jenis_kelamin'],
        ];

        //validate $_FILES['foto_profil']
        if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['name'] != "") {
            $target_dir = __DIR__ . "/../assets/images/pengguna/";
            //check dir
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $target_file = $target_dir . basename($_FILES["foto_profil"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $check = getimagesize($_FILES["foto_profil"]["tmp_name"]);
            if ($check === false) {
                $_SESSION['error'] = "File yang di upload bukan gambar";
                //redirect back
                header("Location: " . base_url() . "/pages/pengguna/tambah.php");
                exit;
            }
            if ($_FILES["foto_profil"]["size"] > 5000000) {
                $_SESSION['error'] = "Ukuran file terlalu besar";
                //redirect back
                header("Location: " . base_url() . "/pages/pengguna/tambah.php");
                exit;
            }
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                $_SESSION['error'] = "Format file tidak di dukung";
                //redirect back
                header("Location: " . base_url() . "/pages/pengguna/tambah.php");
                exit;
            }
            $data['foto_profil'] = basename($_FILES["foto_profil"]["name"]);
            //upload file
            if (!move_uploaded_file($_FILES["foto_profil"]["tmp_name"], $target_file)) {
                $_SESSION['error'] = "File gagal di upload";
                //redirect back
                header("Location: " . base_url() . "/pages/pengguna/tambah.php");
                exit;
            }
        }

        try {
            $this->model->create($data);
            $_SESSION['success'] = "Pengguna berhasil di tambah";
            header("Location: " . base_url() . "/pages/pengguna/index.php");
        } catch (\Throwable $th) {
            $_SESSION['error'] = "Pengguna gagal di tambah : " . $th->getMessage();
            //redirect back
            header("Location: " . base_url() . "/pages/pengguna/tambah.php");
        }
    }

    public function editData()
    {
        if (!isset($_POST['nama_lengkap']) || !isset($_POST['rule']) || !isset($_POST['username'])) {
            $_SESSION['error'] = "Silahkan lengkapi semua inputan";
            //redirect back
            header("Location: " . base_url() . "/pages/pengguna/edit.php?id=" . $_GET['id']);
            exit;
        }
        $sql = "SELECT * FROM  pengguna  WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        $oldData = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($_POST['username'] != $oldData['username']) {
            //cek username
            $sql = "SELECT * FROM  pengguna  WHERE username = :username";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $_POST['username']);
            $stmt->execute();
            if ($stmt->rowCount()) {
                $_SESSION['error'] = "Username sudah pernah digunakan";
                header("Location: " . base_url() . "/pages/pengguna/edit.php?id=" . $_GET['id']);
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
            'jenis_kelamin' => $_POST['jenis_kelamin'],
        ];

        if (isset($_POST['password_baru']) && $_POST['password_baru'] != "") {
            $data['password'] = password_hash($_POST['password_baru'], PASSWORD_DEFAULT);
        }

        //validate $_FILES['foto_profil']
        if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['name'] != "") {
            $target_dir = __DIR__ . "/../assets/images/pengguna/";
            //check dir
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $target_file = $target_dir . basename($_FILES["foto_profil"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $check = getimagesize($_FILES["foto_profil"]["tmp_name"]);
            if ($check === false) {
                $_SESSION['error'] = "File yang di upload bukan gambar";
                //redirect back
                header("Location: " . base_url() . "/pages/pengguna/edit.php?id=" . $_GET['id']);
                exit;
            }
            if ($_FILES["foto_profil"]["size"] > 5000000) {
                $_SESSION['error'] = "Ukuran file terlalu besar";
                //redirect back
                header("Location: " . base_url() . "/pages/pengguna/edit.php?id=" . $_GET['id']);
                exit;
            }
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                $_SESSION['error'] = "Format file tidak di dukung";
                //redirect back
                header("Location: " . base_url() . "/pages/pengguna/edit.php?id=" . $_GET['id']);
                exit;
            }
            $data['foto_profil'] = basename($_FILES["foto_profil"]["name"]);
            //upload file
            if (!move_uploaded_file($_FILES["foto_profil"]["tmp_name"], $target_file)) {
                $_SESSION['error'] = "File gagal di upload";
                //redirect back
                header("Location: " . base_url() . "/pages/pengguna/edit.php?id=" . $_GET['id']);
                exit;
            }
        }

        try {
            $this->model->update($_GET['id'], $data);
            $_SESSION['success'] = "Pengguna berhasil di ubah";
            header("Location: " . base_url() . "/pages/pengguna/index.php");
        } catch (\Throwable $th) {
            $_SESSION['error'] = "Pengguna gagal di ubah : " . $th->getMessage();
            //redirect back
            header("Location: " . base_url() . "/pages/pengguna/edit.php?id=" . $_GET['id']);
        }
    }

    //delete
    public function deleteData()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error'] = "Silahkan pilih data yang akan di hapus";
            //redirect back
            header("Location: " . base_url() . "/pages/pengguna/index.php");
            exit;
        }

        try {
            $data = $this->model->findById($_GET['id']);
            //check if foto_profil is not empty and exist and delete
            if ($data['foto_profil'] != null && $data['foto_profil'] != "" && file_exists(__DIR__ . "/../assets/images/pengguna/" . $data['foto_profil'])) {
                unlink(__DIR__ . "/../assets/images/pengguna/" . $data['foto_profil']);
            }
            $this->model->delete($_GET['id']);
            $_SESSION['success'] = "Pengguna berhasil di hapus";
            header("Location: " . base_url() . "/pages/pengguna/index.php");
        } catch (\Throwable $th) {
            $_SESSION['error'] = "Pengguna gagal di hapus : " . $th->getMessage();
            //redirect back
            header("Location: " . base_url() . "/pages/pengguna/index.php");
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
                    
                    $nomor = str_replace(",", ".", $sheetData[$i][0]);
                    $nama_lengkap = $sheetData[$i][1];
                    $jenis_kelamin = $sheetData[$i][2];
                    $username = $sheetData[$i][3];
                    $password = $sheetData[$i][4];
                    $rule = $sheetData[$i][5];
                    $email = $sheetData[$i][6];
                    $kontak = $sheetData[$i][7];

                    //check if username contain whitespace
                    if (preg_match('/\s/', $username)) {
                        $error .= "\n Username tidak boleh mengandung spasi";
                        continue;
                    }

                    if($nomor != "" && $nomor != null && strpos(strtolower($nomor), 'nomor') === false){
                        $data = [
                            'nomor' => $nomor,
                            'nama_lengkap' => $nama_lengkap,
                            'rule' => strtolower($rule),
                            'email' => $email,
                            'kontak' => $kontak,
                            'username' => $username,
                            'password' => password_hash($password, PASSWORD_DEFAULT),
                            'jenis_kelamin' => $jenis_kelamin,
                        ];

                        if(strlen($password) < 5){
                            $error .= "\n Password minimal 5 karakter";
                            continue;
                        } else {
                            try {
                                $this->model->create($data);
                            } catch (\Throwable $th) {
                                $error .= "\n Pengguna gagal di ubah : " . $th->getMessage();
                                
                            }
                        }

                        
                    }
                }
                $_SESSION['success'] = "File berhasil di import";
            }
            if($error != null){
                $_SESSION['error'] = $error;
            
                header("Location: " . base_url() . "/pages/pengguna/import.php");
            } else {
                $_SESSION['error'] = $error;
            
                header("Location: " . base_url() . "/pages/pengguna/index.php");
            }
        } else {
            $_SESSION['error'] = "Silahkan pilih file excel";
            //redirect back
            header("Location: " . base_url() . "/pages/pengguna/import.php");
            exit;
        }
    }


}

if (isset($_POST["create"])) {
    $penggunaAction = new PenggunaAction();
    $penggunaAction->createData();
} else if (isset($_POST["edit"]) && isset($_GET['id'])) {
    $penggunaAction = new PenggunaAction();
    $penggunaAction->editData();
} else if (isset($_POST["delete"]) && isset($_GET['id'])) {
    $penggunaAction = new PenggunaAction();
    $penggunaAction->deleteData();
} else if (isset($_POST["import"])) {
    $penggunaAction = new PenggunaAction();
    $penggunaAction->importData();
}  else {
    //method not allowed
    http_response_code(405);
    echo "405 Method not allowed";
    die();
}