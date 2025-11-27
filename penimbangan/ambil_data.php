<?php
include_once("connection_pnb.php");

if (isset($_GET['id_anak'])) {
    $selectedIdAnak = $_GET['id_anak'];

    // Buat query SQL untuk mengambil data sesuai dengan ID Anak yang dipilih
    $query = $conn->query("SELECT usia, berat_badan FROM t_penimbangan WHERE id_anak = '$selectedIdAnak'");

    $dataUsiaBeratBadan = array();

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $usia = $row['usia'];
        $berat_badan = $row['berat_badan'];

        $dataUsiaBeratBadan[$usia] = $berat_badan;
    }

    header('Content-Type: application/json');
    echo json_encode($dataUsiaBeratBadan);
}
?>