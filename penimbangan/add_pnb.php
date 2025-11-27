<?php
include_once('connection_pnb.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_anak = $_POST['id_anak'];
    $tgl_penimbangan = $_POST['tgl_penimbangan'];
    $umur = $_POST['umur'];
    $berat_badan = $_POST['berat_badan'];
    $tinggi_badan = $_POST['tinggi_badan'];
    $keterangan = $_POST['keterangan'];
    $petugas = $_POST['petugas'];
    $posyandu = $_POST['posyandu'];

    $statement = $conn->prepare('INSERT INTO t_penimbangan (id_anak, tgl_penimbangan, umur, berat_badan, tinggi_badan, keterangan, petugas, posyandu)
                                            VALUES (:id_anak, :tgl_penimbangan, :umur, :berat_badan, :tinggi_badan, :keterangan, :petugas, :posyandu)');

    $result = $statement->execute([
        'id_anak' => $id_anak,
        'tgl_penimbangan' => $tgl_penimbangan,
        'umur' => $umur,
        'berat_badan' => $berat_badan,
        'tinggi_badan' => $tinggi_badan,
        'keterangan' => $keterangan,
        'petugas' => $petugas,
        'posyandu' => $posyandu
    ]);

    if ($result) {
        header('Location: data_penimbangan.php?id_anak=' . $id_anak);
        exit();
    } else {
        echo "Error: Gagal menambahkan data penimbangan.";
    }
}
?>