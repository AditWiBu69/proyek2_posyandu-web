<?php
session_start();

// 1. Konfigurasi Koneksi
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "dbsipograf1"; 
$port = 3307; 

$koneksi = mysqli_connect($host, $user, $pass, $db, $port);

if (!$koneksi) {
    die("Koneksi Error: " . mysqli_connect_error());
}

// 2. Cek apakah user login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// 3. Proses Data Post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_user   = $_POST['id_user'];
    $id_jadwal = $_POST['id_jadwal'];

    // Validasi input tidak boleh kosong
    if (empty($id_user) || empty($id_jadwal)) {
        header("Location: home_user.php?status=gagal");
        exit();
    }

    // 4. Cek Duplikasi (Apakah user sudah daftar di jadwal ini sebelumnya?)
    $cek_query = mysqli_query($koneksi, "SELECT * FROM t_pendaftaran WHERE id_user = '$id_user' AND id_jadwal = '$id_jadwal'");
    
    if (mysqli_num_rows($cek_query) > 0) {
        // Jika sudah ada, kembalikan dengan status 'sudah_daftar'
        header("Location: home_user.php?status=sudah_daftar");
    } else {
        // 5. Simpan ke Database
        $insert = mysqli_query($koneksi, "INSERT INTO t_pendaftaran (id_user, id_jadwal) VALUES ('$id_user', '$id_jadwal')");
        
        if ($insert) {
            header("Location: home_user.php?status=sukses");
        } else {
            header("Location: home_user.php?status=gagal");
        }
    }
} else {
    // Jika akses langsung tanpa POST
    header("Location: home_user.php");
}
?>