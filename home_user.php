<?php
session_start();
include 'koneksi.php';

// Cek Keamanan Login
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

// Ambil Data User
$username_session = $_SESSION['username'];
$query_user = mysqli_query($koneksi, "SELECT id_user, nama_lengkap FROM masuk WHERE username = '$username_session'");
$data_user = mysqli_fetch_assoc($query_user);
$nama_lengkap_user = $data_user['nama_lengkap'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - User Posyandu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .hero-banner {
            background: linear-gradient(135deg, #0d6efd 0%, #0099ff 100%);
            color: white;
            padding: 50px 20px;
            border-radius: 0 0 20px 20px;
            margin-bottom: 40px;
        }
        .card-custom {
            border: none; border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .card-custom:hover { transform: translateY(-5px); }
        .section-header {
            color: #0d6efd; font-weight: bold;
            margin-bottom: 25px; text-transform: uppercase;
            border-bottom: 2px solid #e9ecef; padding-bottom: 10px;
        }
    </style>
</head>
<body>

    <?php 
    $page = 'home'; // Penanda halaman aktif
    include 'navbar.php'; 
    ?>

    <div class="hero-banner text-center">
        <div class="container">
            <h2 class="display-5 fw-bold">Halo, Bunda <?= htmlspecialchars($nama_lengkap_user); ?>!</h2>
            <p class="lead">Selamat datang di Dashboard Monitoring Kesehatan Anak.</p>
        </div>
    </div>

    <div class="container">
        
        <section id="data-anak" class="mb-5 pt-4">
            <h3 class="section-header">Riwayat Penimbangan Anak</h3>
            <div class="card card-custom bg-white p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Anak</th>
                                <th>Usia</th>
                                <th>Berat (BB)</th>
                                <th>Tinggi (TB)</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "
                                SELECT tp.tgl_penimbangan, tp.umur, tp.berat_badan, tp.tinggi_badan, tp.keterangan, ta.nama_anak 
                                FROM masuk m
                                JOIN t_orangtua tor ON m.nama_lengkap = tor.nama_ibu
                                JOIN t_anak ta ON tor.id_orangtua = ta.id_orangtua
                                LEFT JOIN t_penimbangan tp ON ta.id_anak = tp.id_anak 
                                WHERE m.username = '$username_session' 
                                ORDER BY ta.nama_anak ASC, tp.tgl_penimbangan DESC
                            ";

                            $result = mysqli_query($koneksi, $query);

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $ada_data = !empty($row['tgl_penimbangan']);
                                    $tanggal = $ada_data ? date('d F Y', strtotime($row['tgl_penimbangan'])) : '-';
                                    $umur    = $ada_data ? htmlspecialchars($row['umur']) . " Bulan" : '-';
                                    $berat   = $ada_data ? htmlspecialchars($row['berat_badan']) . " Kg" : '<span class="badge bg-secondary">Belum ditimbang</span>';
                                    $tinggi  = $ada_data ? htmlspecialchars($row['tinggi_badan']) . " Cm" : '-';
                                    $ket     = $ada_data ? htmlspecialchars($row['keterangan']) : '-';

                                    echo "<tr>
                                        <td>{$tanggal}</td>
                                        <td class='fw-bold text-primary'>" . htmlspecialchars($row['nama_anak']) . "</td>
                                        <td>{$umur}</td>
                                        <td>{$berat}</td>
                                        <td>{$tinggi}</td>
                                        <td>{$ket}</td>
                                    </tr>";
                                }
                            } else {
                                echo '<tr><td colspan="6" class="text-center py-4 text-muted">Data anak tidak ditemukan. Pastikan nama akun sesuai dengan nama ibu di data posyandu.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

    </div>

    <footer class="bg-dark text-white text-center py-4 mt-auto">
        <div class="container">
            <p class="mb-0 small">&copy; 2025 SiMona - Sistem Informasi Posyandu.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>