<?php
include_once("koneksi.php");

// 1. Cek apakah ada ID di URL
if (isset($_GET['id_anak'])) {
    $id_anak = $_GET['id_anak'];

    // 2. Buat Query Hapus (Gaya MySQLi)
    // Langsung hapus berdasarkan ID
    $query = "DELETE FROM t_anak WHERE id_anak = '$id_anak'";
    
    // 3. Eksekusi Query
    $result = mysqli_query($koneksi, $query);

    // 4. Cek Hasil
    if ($result) {
        $message = "Data berhasil dihapus!";
        $status  = "success";
    } else {
        $message = "Gagal menghapus data. Error: " . mysqli_error($koneksi);
        $status  = "error";
    }

} else {
    // Jika ID tidak ada di URL
    $message = "ID Anak tidak ditemukan!";
    $status  = "error";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Hapus</title>
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
            // Setelah klik OK, kembali ke halaman data anak
            if (result.isConfirmed) {
                window.location.href = 'data_anak.php'; 
            }
        });
    </script>

</body>
</html>