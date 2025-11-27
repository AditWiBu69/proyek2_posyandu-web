<?php
include_once('connection.php');

// Pastikan parameter id_anak tersedia
if (!isset($_GET['id_anak'])) {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='data_anak.php';</script>";
    exit;
}

$id_anak = $_GET['id_anak'];

// FITUR PENTING: Mendapatkan nama file ini secara otomatis
// Ini menggantikan 'nama_file_ini.php' agar link tidak error
$current_file = basename($_SERVER['PHP_SELF']);

// Cek apakah user sudah mengonfirmasi penghapusan via parameter GET
if (isset($_GET['konfirmasi']) && $_GET['konfirmasi'] === 'yes') {
    
    try {
        // Eksekusi Hapus
        $statement = $conn->prepare('DELETE FROM t_anak WHERE id_anak=:id_anak');
        $result = $statement->execute([
            'id_anak' => $id_anak
        ]);

        // Cek apakah query benar-benar berhasil menghapus baris
        if ($result) {
            echo "<script>alert('Data berhasil dihapus!'); window.location.href='data_anak.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus data (Query Error).'); window.location.href='data_anak.php';</script>";
        }

    } catch (PDOException $e) {
        // Menangkap jika ada error database (misalnya data sedang dipakai di tabel lain)
        $error_msg = addslashes($e->getMessage());
        echo "<script>alert('Gagal menghapus! Error: $error_msg'); window.location.href='data_anak.php';</script>";
    }
    
} else {
    // Jika belum ada konfirmasi, munculkan Pop Up Javascript
    // Perhatikan variabel $current_file di bawah ini
    echo "<script>
        var yakin = confirm('Apakah Anda yakin ingin menghapus data ini?');
        if (yakin) {
            // Jika YES, panggil ulang file INI ($current_file) dengan tambahan parameter konfirmasi=yes
            window.location.href = '$current_file?id_anak=$id_anak&konfirmasi=yes';
        } else {
            // Jika NO, kembalikan ke halaman data
            window.location.href = 'data_anak.php';
        }
    </script>";
}
?>