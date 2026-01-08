<?php
session_start();

// 1. Konfigurasi Koneksi (Sesuai settingan Anda)
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "dbsipograf1"; 
$port = 3307; 

$koneksi = mysqli_connect($host, $user, $pass, $db, $port);

if (!$koneksi) {
    die("Koneksi Error: " . mysqli_connect_error());
}

// 2. Cek Login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// 3. Proses Data Post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Tangkap semua data dari form modal
    $id_user   = $_POST['id_user'];
    $id_jadwal = $_POST['id_jadwal'];
    
    // [PENTING] Tangkap id_anak. Gunakan isset untuk menghindari error jika kosong
    $id_anak   = isset($_POST['id_anak']) ? $_POST['id_anak'] : '';

    // Validasi: Pastikan Anak dipilih!
    if (empty($id_user) || empty($id_jadwal) || empty($id_anak)) {
        // Jika anak belum dipilih, kembalikan dengan error
        header("Location: jadwal.php?status=gagal"); 
        exit();
    }

    // 4. Cek Duplikasi (Cek apakah ANAK ini sudah terdaftar di jadwal ini?)
    // Logika diubah: Kita cek berdasarkan id_anak, bukan user.
    // Karena satu ibu bisa punya 2 anak, anak A sudah daftar, anak B belum.
    $cek_query = mysqli_query($koneksi, "SELECT * FROM t_pendaftaran WHERE id_anak = '$id_anak' AND id_jadwal = '$id_jadwal'");
    
    if (mysqli_num_rows($cek_query) > 0) {
        // Jika anak tersebut sudah terdaftar
        header("Location: jadwal.php?status=sudah_daftar");
    } else {
        // 5. Simpan ke Database (QUERY DIPERBAIKI)
        // Menambahkan kolom id_anak ke dalam INSERT
        $query_insert = "INSERT INTO t_pendaftaran (id_user, id_jadwal, id_anak) VALUES ('$id_user', '$id_jadwal', '$id_anak')";
        $insert = mysqli_query($koneksi, $query_insert);
        
        if ($insert) {
            header("Location: jadwal.php?status=sukses");
        } else {
            // Jika masih error, tampilkan pesan error asli mysql untuk debugging
            echo "Gagal menyimpan: " . mysqli_error($koneksi);
            // header("Location: jadwal.php?status=gagal");
        }
    }
} else {
    // Jika akses langsung tanpa POST
    header("Location: jadwal.php");
}
?>