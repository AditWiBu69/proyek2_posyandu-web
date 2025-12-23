<?php
session_start();

// 1. Konfigurasi Koneksi Database
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "dbsipograf1"; 
$port = 3307; 

$koneksi = mysqli_connect($host, $user, $pass, $db, $port);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 2. Cek apakah Admin sudah login (Keamanan)
// Sesuaikan $_SESSION['role'] dengan sistem login Anda jika ada
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// 3. Proses Hapus Data
if (isset($_GET['id'])) {
    // Ambil ID dari URL
    $id_daftar = $_GET['id'];

    // Mencegah SQL Injection sederhana
    $id_daftar = mysqli_real_escape_string($koneksi, $id_daftar);

    // Query Hapus
    $query = "DELETE FROM t_pendaftaran WHERE id_daftar = '$id_daftar'";
    $hasil = mysqli_query($koneksi, $query);

    // 4. Redirect (Pengalihan)
    if ($hasil) {
        // Jika berhasil, kembali ke halaman admin dengan status sukses
        header("Location: admin_lihat_pendaftar.php?status=hapus_sukses");
    } else {
        // Jika gagal query
        header("Location: admin_lihat_pendaftar.php?status=gagal");
    }
} else {
    // Jika file ini dibuka tanpa ID, kembalikan saja
    header("Location: admin_lihat_pendaftar.php");
}

exit();
?>