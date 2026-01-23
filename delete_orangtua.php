<?php
include_once("koneksi.php");

// 1. Cek apakah ada ID di URL
if (isset($_GET['id_orangtua'])) {
    // Amankan input ID
    $id_orangtua = mysqli_real_escape_string($koneksi, $_GET['id_orangtua']);

    // 2. Cek Relasi (Opsional tapi Penting)
    // Cek apakah orang tua ini masih memiliki data anak?
    // Jika masih ada anak, biasanya tidak boleh dihapus (tergantung setting database/Foreign Key)
    $cek_anak = mysqli_query($koneksi, "SELECT * FROM t_anak WHERE id_orangtua = '$id_orangtua'");
    
    if (mysqli_num_rows($cek_anak) > 0) {
        $message = "Gagal! Data Orang Tua tidak bisa dihapus karena masih memiliki Data Anak yang terdaftar. Hapus data anaknya terlebih dahulu.";
        $status  = "error";
    } else {
        // 3. Buat Query Hapus
        $query = "DELETE FROM t_orangtua WHERE id_orangtua = '$id_orangtua'";
        $result = mysqli_query($koneksi, $query);

        // 4. Cek Hasil
        if ($result) {
            $message = "Data Orang Tua berhasil dihapus!";
            $status  = "success";
        } else {
            $message = "Gagal menghapus data. Error: " . mysqli_error($koneksi);
            $status  = "error";
        }
    }

} else {
    // Jika ID tidak ada di URL
    $message = "ID Orang Tua tidak ditemukan!";
    $status  = "error";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Hapus Orang Tua</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <script>
        Swal.fire({
            title: 'Status Hapus',
            text: '<?php echo $message; ?>',
            icon: '<?php echo $status; ?>',
            confirmButtonText: 'OK'
        }).then((result) => {
            // Setelah klik OK, kembali ke halaman data orangtua
            if (result.isConfirmed) {
                window.location.href = 'data_orangtua.php'; 
            }
        });
    </script>

</body>
</html>