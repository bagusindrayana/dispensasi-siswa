<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config/application.php";
require_once $_SERVER['DOCUMENT_ROOT']."/config/database.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class LoginAction {
    public function __construct() {
        $this->conn = $GLOBALS['conn'];
    }
    public function login() {
        if(!isset($_POST['username']) || !isset($_POST['password'])){
            $_SESSION['error'] = "Silahkan lengkapi form login";
            header("Location: ".base_url()."/pages/login.php");
        }
        $username = $_POST['username'];
        $password = $_POST['password'];
        //cek login with password_verify
        $sql = "SELECT * FROM  pengguna  WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        if(!$stmt->rowCount()){
            $_SESSION['error'] = "Username atau password salah";
            header("Location: ".base_url()."/pages/login.php");
            exit;
        }
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        //cek password
        if(!password_verify($password, $result['password'])){
            //set session error message
            $_SESSION['error'] = "Username atau password salah";
            header("Location: ".base_url()."/pages/login.php");
            exit;
        }
        
        
        if($result) {
            $_SESSION['login'] = true;
            $_SESSION['user'] = [
                "nomor"=>$result['nomor'],
                "nama_lengkap"=>$result['nama_lengkap'],
                "id"=>$result['id'],
                "username"=>$result['username'],
                "rule"=>$result['rule'],
            ];
            header("Location: ".base_url()."/index.php");
        } else {
            header("Location: ".base_url()."/pages/login.php");
        }
    }
    public function logout() {
        session_destroy();
        header("Location: ".base_url()."/pages/login.php");
    }
}

$loginAction = new LoginAction();
if(isset($_POST['login'])){
    $loginAction->login();
} else if(isset($_POST['logout'])){
    $loginAction->logout();
}