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

// 2. Cek Keamanan
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

// 3. Ambil ID User yang sedang login
$username_session = $_SESSION['username'];

// Ambil ID User saja (hapus nama_lengkap dari query)
$query_user = mysqli_query($koneksi, "SELECT id_user FROM masuk WHERE username = '$username_session'");
$data_user = mysqli_fetch_assoc($query_user);

// Simpan ID User
$id_user_login = $data_user['id_user']; 

// Karena kolom nama_lengkap tidak ada, gunakan username sebagai nama tampilan
$nama_lengkap_user = $username_session;

// Cek notifikasi status (jika ada redirect dari proses daftar)
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
            <h2 class="display-5 fw-bold">Halo, Bunda <?php echo htmlspecialchars($username_session); ?>!</h2>
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
                                JOIN t_anak ta ON m.username = ta.nama_ibu
                                JOIN t_penimbangan tp ON ta.id_anak = tp.id_anak 
                                WHERE m.username = '$username_session' 
                                ORDER BY tp.tgl_penimbangan DESC
                            ";
                            $result = mysqli_query($koneksi, $query);

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $tanggal = date('d F Y', strtotime($row['tgl_penimbangan']));
                                    echo "<tr>
                                        <td>" . htmlspecialchars($tanggal) . "</td>
                                        <td>" . htmlspecialchars($row['nama_anak']) . "</td>
                                        <td>" . htmlspecialchars($row['umur']) . " Bulan</td>
                                        <td>" . htmlspecialchars($row['berat_badan']) . " Kg</td>
                                        <td>" . htmlspecialchars($row['tinggi_badan']) . " Cm</td>
                                        <td>" . htmlspecialchars($row['keterangan']) . "</td>
                                    </tr>";
                                }
                            } else {
                                echo '<tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data penimbangan.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section id="jadwal" class="mb-5 pt-4">
            <h3 class="section-header">Jadwal Kegiatan Posyandu</h3>
            <p class="text-muted mb-4">Silakan klik tombol "Daftar Kegiatan" untuk melakukan pendaftaran online.</p>
            
            <div class="row g-4">
                <?php
                // Mengambil data jadwal dari database agar dinamis
$query_jadwal = mysqli_query($koneksi, "SELECT * FROM t_jadwal ORDER BY tanggal DESC");                
                if (mysqli_num_rows($query_jadwal) > 0) {
                    while ($jadwal = mysqli_fetch_assoc($query_jadwal)) {
                        $tgl_kegiatan = date('d F Y', strtotime($jadwal['tanggal']));
                        ?>
                        <div class="col-md-6">
                            <div class="card card-custom h-100 border-start border-5 border-info">
                                <div class="card-body">
                                    <h5 class="card-title text-primary fw-bold"><?= $jadwal['nama_kegiatan']; ?></h5>
                                    <hr>
                                    <p class="mb-1"><strong>📅 Tanggal:</strong> <?= $tgl_kegiatan; ?></p>
                                    <p class="mb-1"><strong>🕗 Waktu:</strong> <?= $jadwal['waktu']; ?></p>
                                    <p class="mb-3"><strong>📍 Tempat:</strong> <?= $jadwal['tempat']; ?></p>
                                    
                                    <button 
                                        type="button" 
                                        class="btn btn-info w-100 text-white fw-bold btn-daftar"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalDaftar"
                                        data-id="<?= $jadwal['id_jadwal']; ?>"
                                        data-kegiatan="<?= $jadwal['nama_kegiatan']; ?>"
                                        data-tanggal="<?= $tgl_kegiatan; ?>"
                                        data-lokasi="<?= $jadwal['tempat']; ?>">
                                        📝 Daftar Kegiatan Ini
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div class="col-12"><div class="alert alert-warning">Belum ada jadwal kegiatan yang tersedia.</div></div>';
                }
                ?>
            </div>
        </section>

        <section id="galeri" class="mb-5 pt-4">
    <h3 class="section-header">Galeri Kegiatan</h3>
    <div class="row g-3">
        <?php
        // Query ambil data galeri terbaru
        $query_galeri = mysqli_query($koneksi, "SELECT * FROM t_galeri ORDER BY id_galeri DESC LIMIT 6"); // Limit 6 foto
        
        if (mysqli_num_rows($query_galeri) > 0) {
            while ($foto = mysqli_fetch_assoc($query_galeri)) {
                ?>
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <img src="uploads/<?= htmlspecialchars($foto['nama_file']); ?>" 
                             class="gallery-img card-img-top" 
                             alt="<?= htmlspecialchars($foto['judul']); ?>"
                             style="height: 250px; object-fit: cover;">
                        <div class="card-body p-2 text-center bg-white">
                            <small class="text-muted fw-bold"><?= htmlspecialchars($foto['judul']); ?></small>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<div class="col-12 text-center text-muted">Belum ada dokumentasi kegiatan.</div>';
        }
        ?>
    </div>
</section>

    </div>

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
                            <label class="form-label fw-bold">Nama Pendaftar (Anda)</label>
                            <input type="text" class="form-control bg-light" value="<?= $nama_lengkap_user; ?>" readonly>
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
            // Tombol yang memicu modal
            const button = event.relatedTarget;
            
            // Ambil data dari atribut data-*
            const idJadwal = button.getAttribute('data-id');
            const namaKegiatan = button.getAttribute('data-kegiatan');
            const tanggal = button.getAttribute('data-tanggal');
            const lokasi = button.getAttribute('data-lokasi');

            // Isi nilai ke dalam input form modal
            document.getElementById('modal_id_jadwal').value = idJadwal;
            document.getElementById('modal_nama_kegiatan').value = namaKegiatan;
            document.getElementById('modal_tanggal').value = tanggal;
            document.getElementById('modal_lokasi').value = lokasi;
        });
    </script>
</body>
</html>