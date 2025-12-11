<?php
session_start();

// 1. Konfigurasi Koneksi Database
$host = "127.0.0.1"; // Gunakan IP 127.0.0.1
$user = "root";
$pass = "";
$db   = "dbsipograf1"; // Pastikan nama DB sama dengan di phpMyAdmin (dbsipograf atau dbsipograf1?)
$port = 3307; // <--- PENTING: Tambahkan Port 3307

// Tambahkan variabel $port di parameter ke-5
$koneksi = mysqli_connect($host, $user, $pass, $db, $port);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 2. Cek Keamanan
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

// 3. Ambil ID User yang sedang login
$username_session = $_SESSION['username'];

// PERBAIKAN: Ganti 'id' menjadi 'id_user' di SELECT dan di variabel array
$query_user = mysqli_query($koneksi, "SELECT id_user FROM masuk WHERE username = '$username_session'");
$data_user = mysqli_fetch_assoc($query_user);
$id_user_login = $data_user['id_user']; // <--- PENTING: Sesuaikan index array ini

// Variabel sapaan
$nama_pengguna = $username_session;
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
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .card-custom:hover { transform: translateY(-5px); }
        .section-header {
            color: #0d6efd;
            font-weight: bold;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
        }
        .gallery-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">SIPOGRAF USER</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#data-anak">Data Penimbangan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#jadwal">Jadwal</a></li>
                    <li class="nav-item"><a class="nav-link" href="#galeri">Galeri</a></li>
                    <li class="nav-item ms-3">
                        <a class="btn btn-warning btn-sm fw-bold mt-1" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero-banner text-center">
        <div class="container">
            <h2 class="display-5 fw-bold">Halo, Bunda <?php echo htmlspecialchars($nama_pengguna); ?>!</h2>
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
// Pastikan sesi sudah dimulai dan koneksi database $koneksi sudah ada

$username_session = $_SESSION['username'];

// Query "Sakti" untuk menghubungkan 3 tabel sekaligus
// User -> Anak -> Penimbangan
$query = "
    SELECT 
        tp.tgl_penimbangan,
        tp.umur,
        tp.berat_badan,
        tp.tinggi_badan,
        tp.keterangan,
        ta.nama_anak,
        ta.jenis_kelamin
    FROM 
        masuk m
    JOIN 
        t_anak ta ON m.username = ta.nama_ibu  -- HUBUNGKAN BERDASARKAN NAMA
    JOIN 
        t_penimbangan tp ON ta.id_anak = tp.id_anak 
    WHERE 
        m.username = '$username_session' 
    ORDER BY 
        tp.tgl_penimbangan DESC
";

$result = mysqli_query($koneksi, $query);

// Tampilkan Data
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Format Tanggal Indonesia
        $tanggal = date('d F Y', strtotime($row['tgl_penimbangan']));
        
        echo "<tr>";
        echo "<td>" . htmlspecialchars($tanggal) . "</td>";
        echo "<td>" . htmlspecialchars($row['nama_anak']) . "</td>";
        echo "<td>" . htmlspecialchars($row['umur']) . " Bulan</td>";
        echo "<td>" . htmlspecialchars($row['berat_badan']) . " Kg</td>";
        echo "<td>" . htmlspecialchars($row['tinggi_badan']) . " Cm</td>";
        echo "<td>" . htmlspecialchars($row['keterangan']) . "</td>";
        echo "</tr>";
    

                                }
                            } else {
                                echo '<tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data penimbangan untuk anak Anda.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <section id="jadwal" class="mb-5 pt-4">
            <h3 class="section-header">Jadwal Posyandu</h3>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card card-custom h-100 border-start border-5 border-info">
                        <div class="card-body">
                            <h5 class="card-title text-info fw-bold">Imunisasi Rutin</h5>
                            <hr>
                            <p class="mb-1"><strong>📅 Tanggal:</strong> 10 Desember 2024</p>
                            <p class="mb-1"><strong>🕗 Waktu:</strong> 08:00 - 11:00 WIB</p>
                            <p class="mb-0"><strong>📍 Tempat:</strong> Posyandu Mawar 1</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-custom h-100 border-start border-5 border-warning">
                        <div class="card-body">
                            <h5 class="card-title text-warning fw-bold">Penyuluhan Vitamin A</h5>
                            <hr>
                            <p class="mb-1"><strong>📅 Tanggal:</strong> 20 Desember 2024</p>
                            <p class="mb-1"><strong>🕗 Waktu:</strong> 09:00 - Selesai</p>
                            <p class="mb-0"><strong>📍 Tempat:</strong> Balai RW 05</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="galeri" class="mb-5 pt-4">
            <h3 class="section-header">Galeri Kegiatan</h3>
            <div class="row g-3">
                <div class="col-md-4 col-sm-6">
                    <img src="https://via.placeholder.com/400x300/6495ED/ffffff?text=Foto+Kegiatan+1" class="gallery-img shadow-sm" alt="Kegiatan 1">
                </div>
                <div class="col-md-4 col-sm-6">
                    <img src="https://via.placeholder.com/400x300/FF7F50/ffffff?text=Foto+Kegiatan+2" class="gallery-img shadow-sm" alt="Kegiatan 2">
                </div>
                <div class="col-md-4 col-sm-6">
                    <img src="https://via.placeholder.com/400x300/20B2AA/ffffff?text=Foto+Kegiatan+3" class="gallery-img shadow-sm" alt="Kegiatan 3">
                </div>
            </div>
        </section>

    </div>

    <footer class="bg-dark text-white text-center py-4 mt-auto">
        <div class="container">
            <p class="mb-0 small">&copy; 2024 SIPOGRAF - Sistem Informasi Posyandu.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>