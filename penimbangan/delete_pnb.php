<?php
include_once('connection_pnb.php');

$id_anak = $_GET['id_anak'];
$id_penimbangan = $_GET['id_penimbangan'];

$statement = $conn->prepare('DELETE FROM t_penimbangan WHERE id_penimbangan=:id_penimbangan');
$statement->execute([
    'id_penimbangan' => $id_penimbangan
]);

header('location: data_penimbangan.php?id_anak=' . $id_anak);
