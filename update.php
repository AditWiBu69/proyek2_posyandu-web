<?php
include_once("connection.php");

// 1. Ambil ID dari URL (sesuai action form kamu)
$id_anak = $_GET['id_anak'];

// 2. Ambil data dari Form
$nama_anak     = $_POST['nama_anak'];
$nama_ibu      = $_POST['nama_ibu'];
$tempat_lahir  = $_POST['tempat_lahir'];
$tanggal_lahir = $_POST['tanggal_lahir'];
$jenis_kelamin = $_POST['jenis_kelamin'];
$alamat        = $_POST['alamat'];

try {
    // 3. Siapkan Query Update
    $sql = "UPDATE t_anak SET 
            nama_anak = :nama_anak, 
            nama_ibu = :nama_ibu, 
            tempat_lahir = :tempat_lahir, 
            tanggal_lahir = :tanggal_lahir, 
            jenis_kelamin = :jenis_kelamin, 
            alamat = :alamat 
            WHERE id_anak = :id_anak";

    $statement = $conn->prepare($sql);

    // 4. Eksekusi Query
    $data = [
        ':nama_anak' => $nama_anak,
        ':nama_ibu' => $nama_ibu,
        ':tempat_lahir' => $tempat_lahir,
        ':tanggal_lahir' => $tanggal_lahir,
        ':jenis_kelamin' => $jenis_kelamin,
        ':alamat' => $alamat,
        ':id_anak' => $id_anak
    ];

    $execute = $statement->execute($data);

    // 5. Jika Berhasil, Tampilkan Pop Up SweetAlert
    if($execute) {
        $message = "Data anak berhasil diperbarui!";
        $status = "success";
    } else {
        $message = "Gagal memperbarui data!";
        $status = "error";
    }

} catch (PDOException $e) {
    $message = "Error: " . $e->getMessage();
    $status = "error";
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