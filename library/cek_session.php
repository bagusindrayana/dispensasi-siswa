<?php
require_once __DIR__."/../config/database.php";
require_once __DIR__."/../config/database.php";
//cek session login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['login']) || !$_SESSION['user']){
    header("Location: ".base_url()."/pages/login.php");
    exit;
}
$user = $_SESSION['user'];
//cek id user dan username di database
$sql = "SELECT * FROM pengguna WHERE id = :id AND username = :username";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $user['id']);
$stmt->bindParam(':username', $user['username']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$user){
    header("Location: ".base_url()."/pages/login.php");
    exit;
}