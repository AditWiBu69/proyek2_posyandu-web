<?php
/**
 * UNIT LOGIKA 1: Menghitung Persentase
 * Logika: Validasi Negatif -> Validasi Nol -> Hitung
 */
function hitungCoverage($jumlah_datang, $total_sasaran) {
    // Jalur 1: Input tidak valid (Negatif)
    if ($jumlah_datang < 0 || $total_sasaran < 0) {
        return -1; // Kode error internal
    }

    // Jalur 2: Mencegah pembagian nol
    if ($total_sasaran == 0) {
        return 0; 
    }

    // Jalur 3: Normal
    $hasil = ($jumlah_datang / $total_sasaran) * 100;
    return $hasil;
}

/**
 * UNIT LOGIKA 2: Menentukan Status Kinerja (Kategorisasi)
 * Logika: If Berjenjang (Ladder If)
 */
function cekStatusKinerja($persentase) {
    // Jalur 1: Data Error
    if ($persentase < 0) {
        return "DATA INVALID";
    }
    // Jalur 2: Kinerja Rendah (< 60%)
    elseif ($persentase < 60) {
        return "KURANG";
    }
    // Jalur 3: Kinerja Sedang (60% - 79%)
    elseif ($persentase < 80) {
        return "CUKUP";
    }
    // Jalur 4: Kinerja Tinggi (>= 80%)
    else {
        return "BAIK";
    }
}
?>