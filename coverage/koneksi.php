<?php
$host = "127.0.0.1"; 
$user = "root";
$pass = ""; 
$db   = "dbsipograf1"; 
$port = 3307; 

$koneksi = mysqli_connect($host, $user, $pass, $db, $port);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>