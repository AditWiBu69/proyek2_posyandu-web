<?php
include 'koneksi.php';

// 1. Ambil data dari form (perhatikan nama variabelnya)
$nama_anak     = $_POST['nama_anak'];
$id_orangtua   = $_POST['id_orangtua']; // <-- Dulu $_POST['nama_ibu'], sekarang jadi id_orangtua
$tempat_lahir  = $_POST['tempat_lahir'];
$tanggal_lahir = $_POST['tanggal_lahir'];
$jenis_kelamin = $_POST['jenis_kelamin'];
$alamat        = $_POST['alamat'];

// 2. Siapkan Query SQL
// Ganti kolom 'nama_ibu' menjadi 'id_orangtua'
$statement = $conn->prepare('INSERT INTO t_anak (nama_anak, id_orangtua, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat) VALUES (:nama_anak, :id_orangtua, :tempat_lahir, :tanggal_lahir, :jenis_kelamin, :alamat)');

// 3. Eksekusi Query
$result = $statement->execute([
    'nama_anak'     => $nama_anak,
    'id_orangtua'   => $id_orangtua, // <-- Mapping parameter juga diganti
    'tempat_lahir'  => $tempat_lahir,
    'tanggal_lahir' => $tanggal_lahir,
    'jenis_kelamin' => $jenis_kelamin,
    'alamat'        => $alamat
]);

// Cek jika data berhasil disimpan
if ($result) {
    echo "
    <script>
        alert('Data berhasil ditambahkan!');
        window.location.href = 'data_anak.php';
    </script>
    ";
} else {
    echo "
    <script>
        alert('Gagal menambahkan data.');
        window.location.href = 'data_anak.php';
    </script>
    ";
}
?>