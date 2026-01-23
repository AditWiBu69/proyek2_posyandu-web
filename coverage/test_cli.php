<?php
// Load logika perhitungan
require 'fungsi_hitung.php';

// --- Konfigurasi Warna Terminal (ANSI Code) ---
const COLOR_RESET = "\033[0m";
const COLOR_RED   = "\033[31m";
const COLOR_GREEN = "\033[32m";
const COLOR_YELLOW= "\033[33m";
const COLOR_BOLD  = "\033[1m";

// Variabel Statistik
$total_test = 0;
$passed_test = 0;

/**
 * Fungsi Pengujian Khusus Terminal
 */
function runTestCLI($id, $deskripsi, $hasil_aktual, $harapan) {
    global $total_test, $passed_test;
    $total_test++;

    // Cek hasil (Handle float precision)
    if (is_float($hasil_aktual) || is_float($harapan)) {
        $is_passed = (abs($hasil_aktual - $harapan) < 0.0001);
        $tampil_aktual = round($hasil_aktual, 2);
    } else {
        $is_passed = ($hasil_aktual === $harapan);
        $tampil_aktual = $hasil_aktual;
    }

    // Format Output
    $label_status = $is_passed 
        ? COLOR_GREEN . "[PASSED]" . COLOR_RESET 
        : COLOR_RED . "[FAILED]" . COLOR_RESET;
    
    // Hitung statistik
    if ($is_passed) $passed_test++;

    // Tampilkan baris hasil (str_pad agar rapi)
    echo str_pad($id, 8);
    echo str_pad(substr($deskripsi, 0, 40), 42); 
    echo str_pad("Exp: " . $harapan, 15);
    echo str_pad("Act: " . $tampil_aktual, 15);
    echo $label_status . "\n";
}

// --- HEADER ---
echo "\n";
echo COLOR_BOLD . "========================================================\n";
echo "   PENGUJIAN WHITE BOX - MODE TERMINAL (CLI) \n";
echo "========================================================" . COLOR_RESET . "\n\n";

// --- TEST CASES: UNIT 1 (PERHITUNGAN) ---
echo COLOR_YELLOW . ">> UNIT 1: Logika Perhitungan Coverage" . COLOR_RESET . "\n";
echo "ID      Skenario                                  Harapan        Aktual         Status\n";
echo "------- ----------------------------------------- -------------- -------------- --------\n";

runTestCLI("P1-01", "Hitung Normal (50/100)", hitungCoverage(50, 100), 50.0);
runTestCLI("P1-02", "Cegah Error Sasaran 0", hitungCoverage(10, 0), 0);
runTestCLI("P1-03", "Input Negatif", hitungCoverage(-5, 10), -1);
runTestCLI("P1-04", "Presisi Desimal (1/3)", hitungCoverage(1, 3), 33.333333333333336);

echo "\n";

// --- TEST CASES: UNIT 2 (KATEGORI) ---
echo COLOR_YELLOW . ">> UNIT 2: Logika Penentuan Status" . COLOR_RESET . "\n";
echo "ID      Skenario                                  Harapan        Aktual         Status\n";
echo "------- ----------------------------------------- -------------- -------------- --------\n";

runTestCLI("P2-01", "Input Error (-1)", cekStatusKinerja(-1), "DATA INVALID");
runTestCLI("P2-02", "Kinerja Kurang (<60)", cekStatusKinerja(59), "KURANG");
runTestCLI("P2-03", "Kinerja Cukup (60-79)", cekStatusKinerja(75), "CUKUP");
runTestCLI("P2-04", "Kinerja Baik (>=80)", cekStatusKinerja(85), "BAIK");
runTestCLI("P2-05", "Boundary Check (60)", cekStatusKinerja(60), "CUKUP");

// --- SUMMARY ---
echo "\n" . COLOR_BOLD . "========================================================" . COLOR_RESET . "\n";
echo "RINGKASAN HASIL:\n";
echo "Total Test : " . $total_test . "\n";
echo "Passed     : " . COLOR_GREEN . $passed_test . " ✅" . COLOR_RESET . "\n";
echo "Failed     : " . ($total_test - $passed_test > 0 ? COLOR_RED : COLOR_GREEN) . ($total_test - $passed_test) . COLOR_RESET . "\n";

$score = ($passed_test / $total_test) * 100;
echo "Score      : " . $score . "%\n";
echo "========================================================\n\n";
?>