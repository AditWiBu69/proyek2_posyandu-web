<?php
// Konfigurasi Database
$host = "127.0.0.1"; 
$user = "root";
$pass = ""; 
$db   = "dbsipograf1"; 
$port = 3307; // Port disesuaikan

// Membuat koneksi menggunakan MySQLi (agar cocok dengan home.php)
$koneksi = mysqli_connect($host, $user, $pass, $db, $port);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>