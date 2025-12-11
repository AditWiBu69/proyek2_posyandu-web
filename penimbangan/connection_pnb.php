<?php
// Konfigurasi Database
$servername = "127.0.0.1";
$username = "root";
$password = ""; 
$dbname = "dbsipograf1"; 
$port = 3307; // Pastikan Port 3307

try {
    $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
    die();
}
?>