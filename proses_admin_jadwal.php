<?php
session_start();

// Koneksi Database
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "dbsipograf1";
$port = 3307;

$koneksi = mysqli_connect($host, $user, $pass, $db, $port);

if (!$koneksi) {
    die("Koneksi Error");
}

// ------------------------------------------
// PROSES HAPUS (DELETE)
// ------------------------------------------
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $id = $_GET['id'];
    
    // Hapus data pendaftaran terkait dulu agar tidak error (Opsional, tergantung setting foreign key)
    mysqli_query($koneksi, "DELETE FROM t_pendaftaran WHERE id_jadwal = '$id'");

    // Hapus Jadwal
    $query = "DELETE FROM t_jadwal WHERE id_jadwal = '$id'";
    
    if (mysqli_query($koneksi, $query)) {
        header("Location: admin_jadwal.php?pesan=hapus");
    } else {
        header("Location: admin_jadwal.php?pesan=gagal");
    }
    exit();
}

// ------------------------------------------
// PROSES TAMBAH & EDIT (CREATE & UPDATE)
// ------------------------------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $aksi = $_POST['aksi_form'];
    
    $nama_kegiatan = mysqli_real_escape_string($koneksi, $_POST['nama_kegiatan']);
    $tanggal       = $_POST['tanggal'];
    $waktu         = mysqli_real_escape_string($koneksi, $_POST['waktu']);
    $tempat        = mysqli_real_escape_string($koneksi, $_POST['tempat']);
    $deskripsi     = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    if ($aksi == 'tambah') {
        // --- QUERY INSERT ---
        $query = "INSERT INTO t_jadwal (nama_kegiatan, tanggal, waktu, tempat, deskripsi) 
                  VALUES ('$nama_kegiatan', '$tanggal', '$waktu', '$tempat', '$deskripsi')";
    } elseif ($aksi == 'edit') {
        // --- QUERY UPDATE ---
        $id_jadwal = $_POST['id_jadwal'];
        $query = "UPDATE t_jadwal SET 
                  nama_kegiatan = '$nama_kegiatan',
                  tanggal       = '$tanggal',
                  waktu         = '$waktu',
                  tempat        = '$tempat',
                  deskripsi     = '$deskripsi'
                  WHERE id_jadwal = '$id_jadwal'";
    }

    // Eksekusi Query
    if (mysqli_query($koneksi, $query)) {
        header("Location: admin_jadwal.php?pesan=sukses");
    } else {
        // Tampilkan error sql jika gagal (untuk debugging)
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>