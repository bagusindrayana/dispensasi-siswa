<?php
$host = "localhost";
$port = "3306";
$username = "root";
$password = "";
$database = "db_dispensasi_siswa";

//db pdo
$conn = null;
try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}