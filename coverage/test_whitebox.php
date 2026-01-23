<?php
require 'fungsi_hitung.php';

// Variabel untuk menampung statistik pengujian
$total_test = 0;
$passed_test = 0;

function runTest($id, $deskripsi, $fungsi_aktual, $harapan) {
    global $total_test, $passed_test;
    $total_test++;
    
    // Normalisasi angka float untuk perbandingan (mengatasi presisi desimal)
    if (is_float($fungsi_aktual) || is_float($harapan)) {
        $is_passed = (abs($fungsi_aktual - $harapan) < 0.0001);
        $tampil_aktual = round($fungsi_aktual, 2);
    } else {
        $is_passed = ($fungsi_aktual === $harapan);
        $tampil_aktual = $fungsi_aktual;
    }

    if ($is_passed) {
        $passed_test++;
        $badge = '<span class="badge bg-success">PASSED ✅</span>';
    } else {
        $badge = '<span class="badge bg-danger">FAILED ❌</span>';
    }

    echo "<tr>
            <td>$id</td>
            <td>$deskripsi</td>
            <td><code>$harapan</code></td>
            <td><code>$tampil_aktual</code></td>
            <td class='text-center'>$badge</td>
          </tr>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Laporan Pengujian White Box</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card-test { box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: none; }
        .header-report { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem 0; margin-bottom: 2rem; }
    </style>
</head>
<body>

<div class="header-report text-center">
    <h1>🧪 Laporan Pengujian Sistem (White Box)</h1>
    <p>Verifikasi Logika Internal & Validasi Alur Data</p>
</div>

<div class="container mb-5">
    
    <div class="card card-test mb-4">
        <div class="card-header bg-white border-bottom-0 pt-4">
            <h5 class="fw-bold text-primary">Unit 1: Logika Perhitungan Coverage</h5>
            <p class="text-muted small">Menguji fungsi <code>hitungCoverage()</code>. Tujuan: Memastikan penanganan error matematika dan input.</p>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="10%">ID Path</th>
                            <th width="40%">Skenario Pengujian</th>
                            <th>Harapan (Expected)</th>
                            <th>Aktual (Actual)</th>
                            <th width="15%" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // PATH 1: Normal Operation
                        runTest("P1-01", "Hitung Normal (50 datang / 100 sasaran)", hitungCoverage(50, 100), 50.0);
                        
                        // PATH 2: Division by Zero
                        runTest("P1-02", "Cegah Error saat Sasaran Kosong (0)", hitungCoverage(10, 0), 0);
                        
                        // PATH 3: Input Validation
                        runTest("P1-03", "Input Negatif (Harus Return -1)", hitungCoverage(-5, 10), -1);

                        // PATH 4: Precision Check
                        runTest("P1-04", "Cek Presisi Desimal (1/3)", hitungCoverage(1, 3), 33.333333333333336);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card card-test mb-4">
        <div class="card-header bg-white border-bottom-0 pt-4">
            <h5 class="fw-bold text-success">Unit 2: Logika Penentuan Status</h5>
            <p class="text-muted small">Menguji fungsi <code>cekStatusKinerja()</code>. Tujuan: Memastikan setiap kategori 'If-Else' terpanggil.</p>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="table-success">
                        <tr>
                            <th width="10%">ID Path</th>
                            <th width="40%">Skenario Pengujian</th>
                            <th>Harapan (Expected)</th>
                            <th>Aktual (Actual)</th>
                            <th width="15%" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // PATH 1: Invalid Data
                        runTest("P2-01", "Input Error (-1)", cekStatusKinerja(-1), "DATA INVALID");

                        // PATH 2: Kurang (< 60)
                        runTest("P2-02", "Batas Bawah: Kinerja Kurang (59%)", cekStatusKinerja(59), "KURANG");

                        // PATH 3: Cukup (60 - 79)
                        runTest("P2-03", "Batas Tengah: Kinerja Cukup (75%)", cekStatusKinerja(75), "CUKUP");

                        // PATH 4: Baik (>= 80)
                        runTest("P2-04", "Batas Atas: Kinerja Baik (85%)", cekStatusKinerja(85), "BAIK");
                        
                        // Boundary Value Analysis (Tepat di batas angka)
                        runTest("P2-05", "Boundary: Tepat di angka 60 (Harus Cukup)", cekStatusKinerja(60), "CUKUP");
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php
    $score = ($passed_test / $total_test) * 100;
    $bar_color = ($score == 100) ? 'bg-success' : 'bg-warning';
    ?>
    <div class="card card-test">
        <div class="card-body">
            <h4 class="fw-bold">Ringkasan Hasil Pengujian</h4>
            <div class="row mt-3">
                <div class="col-md-6">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Jalur Logika (Test Cases)
                            <span class="badge bg-primary rounded-pill"><?= $total_test ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Jalur Valid (Passed)
                            <span class="badge bg-success rounded-pill"><?= $passed_test ?></span>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6 text-center">
                    <h5>Code Coverage Score</h5>
                    <h1 class="display-4 fw-bold <?= ($score==100)?'text-success':'text-warning' ?>"><?= $score ?>%</h1>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar <?= $bar_color ?>" role="progressbar" style="width: <?= $score ?>%"></div>
                    </div>
                    <p class="mt-2 text-muted small">
                        <?= ($score == 100) ? "Seluruh logika internal terverifikasi sesuai perancangan." : "Ada logika yang gagal. Periksa kode." ?>
                    </p>
                </div>
            </div>
            <div class="mt-4 text-center">
                <a href="index.php" class="btn btn-primary btn-lg">Masuk ke Sistem Utama</a>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>