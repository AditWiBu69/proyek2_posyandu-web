<?php
// 1. Panggil file koneksi yang BENAR (koneksi.php)
include_once("koneksi.php");

// 2. Ambil ID dari URL
$id_anak = $_GET['id_anak'];

// 3. Ambil data dari Form (Perhatikan nama variabelnya)
$nama_anak     = $_POST['nama_anak'];

// PENTING: Kita ambil id_orangtua, BUKAN nama_ibu
// Karena di database kolomnya adalah id_orangtua
$id_orangtua   = $_POST['id_orangtua']; 

$tempat_lahir  = $_POST['tempat_lahir'];
$tanggal_lahir = $_POST['tanggal_lahir'];
$jenis_kelamin = $_POST['jenis_kelamin'];
$alamat        = $_POST['alamat'];

// 4. Proses Update menggunakan MySQLi (Sesuai koneksi.php)
// Kita update kolom id_orangtua, bukan nama_ibu
$query = "UPDATE t_anak SET 
            nama_anak     = '$nama_anak',
            id_orangtua   = '$id_orangtua',
            tempat_lahir  = '$tempat_lahir',
            tanggal_lahir = '$tanggal_lahir',
            jenis_kelamin = '$jenis_kelamin',
            alamat        = '$alamat'
          WHERE id_anak   = '$id_anak'";

$result = mysqli_query($koneksi, $query);

// 5. Cek Hasil Update
if ($result) {
    $message = "Data anak berhasil diperbarui!";
    $status  = "success";
} else {
    // Tampilkan error database jika gagal
    $message = "Gagal memperbarui data! Error: " . mysqli_error($koneksi);
    $status  = "error";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Update</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <script>
        Swal.fire({
            title: 'Pemberitahuan',
            text: '<?php echo $message; ?>',
            icon: '<?php echo $status; ?>',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect ke halaman daftar data setelah klik OK
                window.location.href = 'data_anak.php'; 
            }
        });
    </script>

</body>
</html>