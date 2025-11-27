<?php
include_once('connection_pnb.php');
$id_penimbangan = $_GET['id_penimbangan'];
$id_anak = $_POST['id_anak'];
$tgl_penimbangan = $_POST['tgl_penimbangan'];
$umur = $_POST['umur'];
$berat_badan = $_POST['berat_badan'];
$tinggi_badan = $_POST['tinggi_badan'];
$keterangan = $_POST['keterangan'];
$petugas = $_POST['petugas'];
$posyandu = $_POST['posyandu'];

$statement = $conn->prepare('UPDATE t_penimbangan SET id_anak=:id_anak, 
                                            tgl_penimbangan=:tgl_penimbangan,
                                            umur=:umur,
                                            berat_badan=:berat_badan,
                                            tinggi_badan=:tinggi_badan,
                                            keterangan=:keterangan,
                                            petugas=:petugas,
                                            posyandu=:posyandu
                                            WHERE id_penimbangan=:id_penimbangan');

$statement->execute([
    'id_penimbangan' => $id_penimbangan,
    'id_anak' => $id_anak,
    'tgl_penimbangan' => $tgl_penimbangan,
    'umur' => $umur,
    'berat_badan' => $berat_badan,
    'tinggi_badan' => $tinggi_badan,
    'keterangan' => $keterangan,
    'petugas' => $petugas,
    'posyandu' => $posyandu
]);

header('location: data_penimbangan.php?id_anak=' . $id_anak);
