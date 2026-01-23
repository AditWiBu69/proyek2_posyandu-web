<?php
// Load file pendukung
require 'koneksi.php'; 
require 'fungsi_hitung.php';

// Cek koneksi
if (!isset($koneksi)) { die("Error: Variabel \$koneksi tidak ditemukan."); }

// --- LOGIC UTAMA ---

// 1. Filter Waktu
$bulan_pilih = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun_pilih = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

// 2. Query Total Sasaran (S)
$query_sasaran = "SELECT COUNT(*) as total_sasaran FROM t_anak";
$res_sasaran = mysqli_query($koneksi, $query_sasaran);
$row_sasaran = mysqli_fetch_assoc($res_sasaran);
$total_sasaran = $row_sasaran['total_sasaran'];

// 3. Query Jumlah Ditimbang (D)
$query_ditimbang = "SELECT COUNT(DISTINCT id_anak) as total_ditimbang 
                    FROM t_penimbangan 
                    WHERE MONTH(tgl_penimbangan) = '$bulan_pilih' 
                    AND YEAR(tgl_penimbangan) = '$tahun_pilih'";
$res_ditimbang = mysqli_query($koneksi, $query_ditimbang);
$row_ditimbang = mysqli_fetch_assoc($res_ditimbang);
$total_ditimbang = $row_ditimbang['total_ditimbang'];

// 4. Hitung Persentase (Menggunakan Fungsi dari fungsi_hitung.php)
$persentase = hitungCoverage($total_ditimbang, $total_sasaran);
$persentase_tampil = number_format($persentase, 1); // Format tampilan

// 5. Query Anak Belum Ditimbang (Sweeping)
$query_belum = "SELECT * FROM t_anak 
                WHERE id_anak NOT IN (
                    SELECT id_anak FROM t_penimbangan 
                    WHERE MONTH(tgl_penimbangan) = '$bulan_pilih' 
                    AND YEAR(tgl_penimbangan) = '$tahun_pilih'
                )";
$res_belum = mysqli_query($koneksi, $query_belum);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Coverage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-stat { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .icon-box { font-size: 2rem; opacity: 0.8; }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">📊 Monitoring Coverage</h2>
        <a href="test_whitebox.php" class="btn btn-outline-secondary btn-sm">Lihat Pengujian (Whitebox)</a>
    </div>

    <div class="card card-stat mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small text-muted">Bulan</label>
                    <select name="bulan" class="form-select">
                        <?php
                        $bulan_list = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                        foreach ($bulan_list as $k => $v) {
                            $sel = ($k == $bulan_pilih) ? 'selected' : '';
                            echo "<option value='$k' $sel>$v</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted">Tahun</label>
                    <select name="tahun" class="form-select">
                        <?php
                        for ($i = date('Y'); $i >= date('Y')-3; $i--) {
                            $sel = ($i == $tahun_pilih) ? 'selected' : '';
                            echo "<option value='$i' $sel>$i</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Filter Data</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card card-stat bg-white p-3 h-100 border-start border-4 border-info">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Total Sasaran (S)</p>
                        <h2 class="fw-bold mb-0"><?= $total_sasaran ?></h2>
                    </div>
                    <div class="icon-box text-info">👶</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stat bg-white p-3 h-100 border-start border-4 border-success">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Datang Ditimbang (D)</p>
                        <h2 class="fw-bold mb-0"><?= $total_ditimbang ?></h2>
                    </div>
                    <div class="icon-box text-success">⚖️</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stat bg-primary text-white p-3 h-100">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="mb-1 opacity-75">Capaian (D/S)</p>
                        <h2 class="fw-bold mb-0"><?= $persentase_tampil ?>%</h2>
                    </div>
                    <div class="icon-box">📈</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-stat">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 fw-bold text-danger">⚠️ Belum Hadir / Belum Ditimbang</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Nama Anak</th>
                        <th>JK</th>
                        <th>Tgl Lahir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($res_belum) > 0): ?>
                        <?php $no=1; while($row = mysqli_fetch_assoc($res_belum)): ?>
                        <tr>
                            <td class="ps-4"><?= $no++ ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($row['nama_anak']) ?></td>
                            <td><?= $row['jenis_kelamin'] ?></td>
                            <td><?= date('d M Y', strtotime($row['tanggal_lahir'])) ?></td>
                            <td><button class="btn btn-sm btn-outline-primary">Detail</button></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-4 text-muted">Semua anak sudah ditimbang! 🎉</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>