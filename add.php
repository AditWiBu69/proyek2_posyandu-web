<?php
include 'koneksi.php';

// 1. Ambil data dari form
$nama_anak     = $_POST['nama_anak'];
$id_orangtua   = $_POST['id_orangtua']; 
$tempat_lahir  = $_POST['tempat_lahir'];
$tanggal_lahir = $_POST['tanggal_lahir'];
$jenis_kelamin = $_POST['jenis_kelamin'];
$alamat        = $_POST['alamat'];

// 2. Siapkan Query SQL (Gaya MySQLi)
// Jangan pakai :titik_dua, tapi langsung masukkan variabel PHP dengan tanda kutip
$query = "INSERT INTO t_anak (nama_anak, id_orangtua, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat) 
          VALUES ('$nama_anak', '$id_orangtua', '$tempat_lahir', '$tanggal_lahir', '$jenis_kelamin', '$alamat')";

// 3. Eksekusi Query menggunakan mysqli_query
$result = mysqli_query($koneksi, $query);

// 4. Cek Hasil
if ($result) {
    $message = "Data berhasil ditambahkan!";
    $status  = "success";
} else {
    $message = "Gagal menambahkan data. Error: " . mysqli_error($koneksi);
    $status  = "error";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Tambah Data</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <script>
        Swal.fire({
            title: 'Status',
            text: '<?php echo $message; ?>',
            icon: '<?php echo $status; ?>',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect ke halaman data_anak.php
                window.location.href = 'data_anak.php';
            }
        });
    </script>
</body>
</html>