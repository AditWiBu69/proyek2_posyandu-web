<?php
session_start();

// 1. Konfigurasi Koneksi Database
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "dbsipograf1"; 
$port = 3307; 

$koneksi = mysqli_connect($host, $user, $pass, $db, $port);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 2. Cek Keamanan Login
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

// 3. Ambil Data User yang sedang login
$username_session = $_SESSION['username'];

// Ambil detail lengkap user dari tabel masuk
$query_user = mysqli_query($koneksi, "SELECT id_user, nama_lengkap FROM masuk WHERE username = '$username_session'");
$data_user = mysqli_fetch_assoc($query_user);

$id_user_login = $data_user['id_user']; 
$nama_lengkap_user = $data_user['nama_lengkap'];

// Cek notifikasi status (untuk alert setelah daftar)
$status_daftar = isset($_GET['status']) ? $_GET['status'] : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - User Posyandu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    <?php if ($status_daftar == 'sukses'): ?>
        <script>Swal.fire('Berhasil!', 'Anda berhasil mendaftar jadwal kegiatan.', 'success');</script>
    <?php elseif ($status_daftar == 'gagal'): ?>
        <script>Swal.fire('Gagal!', 'Terjadi kesalahan saat mendaftar.', 'error');</script>
    <?php elseif ($status_daftar == 'sudah_daftar'): ?>
        <script>Swal.fire('Info', 'Anda sudah terdaftar di kegiatan ini sebelumnya.', 'info');</script>
    <?php endif; ?>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">SiMona USER</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#data-anak">Data Penimbangan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#jadwal">Jadwal & Daftar</a></li>
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
            <h2 class="display-5 fw-bold">Halo, Bunda <?php echo htmlspecialchars($nama_lengkap_user); ?>!</h2>
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
                        <th>Nama Anak</th> <th>Usia</th>
                        <th>Berat (BB)</th>
                        <th>Tinggi (TB)</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
    <?php
    // PERBAIKAN QUERY: Menggunakan LEFT JOIN agar semua anak tetap muncul
    // meskipun belum ada data penimbangan
    $query = "
        SELECT 
            tp.tgl_penimbangan, 
            tp.umur, 
            tp.berat_badan, 
            tp.tinggi_badan, 
            tp.keterangan, 
            ta.nama_anak 
        FROM masuk m
        JOIN t_orangtua tor ON m.nama_lengkap = tor.nama_ibu
        JOIN t_anak ta ON tor.id_orangtua = ta.id_orangtua
        LEFT JOIN t_penimbangan tp ON ta.id_anak = tp.id_anak  -- Ganti jadi LEFT JOIN
        WHERE m.username = '$username_session' 
        ORDER BY ta.nama_anak ASC, tp.tgl_penimbangan DESC
    ";

    $result = mysqli_query($koneksi, $query);

    if (!$result) {
        die("Query Error: " . mysqli_error($koneksi));
    }

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Cek apakah ada data penimbangan atau null (karena LEFT JOIN)
            $ada_data = !empty($row['tgl_penimbangan']);

            // Format tampilan jika data kosong
            $tanggal = $ada_data ? date('d F Y', strtotime($row['tgl_penimbangan'])) : '-';
            $umur    = $ada_data ? htmlspecialchars($row['umur']) . " Bulan" : '-';
            $berat   = $ada_data ? htmlspecialchars($row['berat_badan']) . " Kg" : '<span class="badge bg-secondary">Belum ditimbang</span>';
            $tinggi  = $ada_data ? htmlspecialchars($row['tinggi_badan']) . " Cm" : '-';
            $ket     = $ada_data ? htmlspecialchars($row['keterangan']) : '-';

            echo "<tr>
                <td>" . $tanggal . "</td>
                <td class='fw-bold text-primary'>" . htmlspecialchars($row['nama_anak']) . "</td>
                <td>" . $umur . "</td>
                <td>" . $berat . "</td>
                <td>" . $tinggi . "</td>
                <td>" . $ket . "</td>
            </tr>";
        }
    } else {
        echo '<tr><td colspan="6" class="text-center py-4 text-muted">Data anak tidak ditemukan. Pastikan nama lengkap di akun sama dengan nama ibu di data orangtua.</td></tr>';
    }
    ?>
</tbody>
            </table>
        </div>
    </div>
</section>
        <section id="galeri" class="mb-5 pt-4">
    <h3 class="section-header">Galeri Kegiatan</h3>
    <div class="row g-3">
        <?php
        // Pastikan variabel $koneksi (mysqli) sudah tersedia dari file header/index Anda
        $query_galeri = mysqli_query($koneksi, "SELECT * FROM t_galeri ORDER BY id_galeri DESC LIMIT 6"); 
        
        if (mysqli_num_rows($query_galeri) > 0) {
            while ($foto = mysqli_fetch_assoc($query_galeri)) {
                ?>
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <img src="uploads/<?= htmlspecialchars($foto['nama_file']); ?>" 
                             class="gallery-img card-img-top" 
                             style="height: 200px; object-fit: cover;"
                             alt="<?= htmlspecialchars($foto['judul']); ?>">
                        
                        <div class="card-body p-3 text-center bg-white d-flex flex-column justify-content-center">
                            <h6 class="card-title fw-bold text-dark mb-1">
                                <?= htmlspecialchars($foto['judul']); ?>
                            </h6>
                            
                            <?php if (!empty($foto['keterangan'])): ?>
                                <p class="card-text small text-muted mb-0" style="font-size: 0.9rem;">
                                    <?= htmlspecialchars($foto['keterangan']); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<div class="col-12 text-center text-muted py-5">Belum ada dokumentasi kegiatan.</div>';
        }
        ?>
    </div>
</section>

    <div class="modal fade" id="modalDaftar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="proses_daftar.php" method="POST">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Konfirmasi Pendaftaran</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin mendaftar untuk kegiatan berikut?</p>
                        
                        <input type="hidden" name="id_jadwal" id="modal_id_jadwal">
                        <input type="hidden" name="id_user" value="<?= $id_user_login; ?>">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Pendaftar</label>
                            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($nama_lengkap_user); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Kegiatan</label>
                            <input type="text" class="form-control bg-light" id="modal_nama_kegiatan" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal</label>
                            <input type="text" class="form-control bg-light" id="modal_tanggal" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Lokasi</label>
                            <input type="text" class="form-control bg-light" id="modal_lokasi" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">✅ Ya, Saya Daftar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-4 mt-auto">
        <div class="container">
            <p class="mb-0 small">&copy; 2025 SiMona - Sistem Informasi Posyandu.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        const modalDaftar = document.getElementById('modalDaftar');
        modalDaftar.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const idJadwal = button.getAttribute('data-id');
            const namaKegiatan = button.getAttribute('data-kegiatan');
            const tanggal = button.getAttribute('data-tanggal');
            const lokasi = button.getAttribute('data-lokasi');

            document.getElementById('modal_id_jadwal').value = idJadwal;
            document.getElementById('modal_nama_kegiatan').value = namaKegiatan;
            document.getElementById('modal_tanggal').value = tanggal;
            document.getElementById('modal_lokasi').value = lokasi;
        });
    </script>
</body>
</html>