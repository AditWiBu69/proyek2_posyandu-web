<?php
// Konfigurasi Database
$servername = "127.0.0.1"; // Gunakan IP ini
$username = "root";
$password = ""; 
$dbname = "dbsipograf1"; 
$port = 3307; // <--- PORT SUDAH DIUBAH KE 3307

try {
    // Perhatikan bagian 'port=$port' di dalam string koneksi di bawah ini
    $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
    
    // Set error mode agar jika ada masalah ketahuan
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
    die();
}
?>