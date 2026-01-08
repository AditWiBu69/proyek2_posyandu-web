<?php
session_start();
include 'koneksi.php'; // Pastikan file koneksi.php sudah ada

// 1. Cek Login
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

// 2. Ambil ID User
$username_session = $_SESSION['username'];
$query_user = mysqli_query($koneksi, "SELECT id_user, nama_lengkap FROM masuk WHERE username = '$username_session'");
$data_user = mysqli_fetch_assoc($query_user);
$id_user_login = $data_user['id_user'];
$nama_lengkap_user = $data_user['nama_lengkap'];

// 3. Cek Status Notifikasi (dari proses_daftar.php)
$status_daftar = isset($_GET['status']) ? $_GET['status'] : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Posyandu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #f8f9fa; }
        .card-jadwal {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .card-jadwal:hover { transform: translateY(-5px); }
        .date-badge {
            background-color: #e9ecef;
            color: #0d6efd;
            padding: 5px 10px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <?php if ($status_daftar == 'sukses'): ?>
        <script>Swal.fire('Berhasil!', 'Anda berhasil mendaftar kegiatan.', 'success');</script>
    <?php elseif ($status_daftar == 'gagal'): ?>
        <script>Swal.fire('Gagal!', 'Terjadi kesalahan sistem.', 'error');</script>
    <?php elseif ($status_daftar == 'sudah_daftar'): ?>
        <script>Swal.fire('Info', 'Anda sudah terdaftar di kegiatan ini.', 'info');</script>
    <?php endif; ?>

    <?php 
    // ... (Kode sebelumnya: Ambil Data User) ...
$id_user_login = $data_user['id_user']; 
$nama_lengkap_user = $data_user['nama_lengkap'];

// [BARU] Ambil data anak berdasarkan nama ibu (nama user login)
// Logikanya: User Login -> Nama Lengkap = Nama Ibu di t_orangtua -> Ambil anak-anaknya
$query_anak = "
    SELECT ta.id_anak, ta.nama_anak 
    FROM t_anak ta
    JOIN t_orangtua tor ON ta.id_orangtua = tor.id_orangtua
    WHERE tor.nama_ibu = '$nama_lengkap_user'
";
$result_anak = mysqli_query($koneksi, $query_anak);
$data_anak = [];
while ($row = mysqli_fetch_assoc($result_anak)) {
    $data_anak[] = $row;
}
    $page = 'jadwal'; 
    include 'navbar.php'; 
    ?>

    <div class="container py-5">
        <h3 class="mb-4 text-primary fw-bold border-bottom pb-2">📅 Jadwal Kegiatan Posyandu</h3>

        <div class="row g-4">
            <?php
            // QUERY DIPERBAIKI: Menggunakan kolom 'tanggal' (sesuai database t_jadwal)
            // Hanya menampilkan jadwal yang belum lewat (>= hari ini)
            $query_jadwal = "SELECT * FROM t_jadwal WHERE tanggal >= CURDATE() ORDER BY tanggal ASC";
            $result_jadwal = mysqli_query($koneksi, $query_jadwal);

            if (mysqli_num_rows($result_jadwal) > 0) {
                while ($jadwal = mysqli_fetch_assoc($result_jadwal)) {
                    // Format Tanggal Indonesia
                    $tgl = date('d F Y', strtotime($jadwal['tanggal']));
                    ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card card-jadwal h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="date-badge">🗓️ <?= $tgl; ?></span>
                                    <span class="badge bg-primary"><?= htmlspecialchars($jadwal['waktu']); ?></span>
                                </div>
                                <h5 class="card-title fw-bold text-dark"><?= htmlspecialchars($jadwal['nama_kegiatan']); ?></h5>
                                <p class="card-text text-muted small mb-2">
                                    📍 <?= htmlspecialchars($jadwal['tempat']); ?>
                                </p>
                                <p class="card-text">
                                    <?= htmlspecialchars($jadwal['deskripsi']); ?>
                                </p>
                            </div>
                            <div class="card-footer bg-white border-0 pb-3">
                                <button type="button" 
                                        class="btn btn-outline-primary w-100 fw-bold" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalDaftar"
                                        data-id="<?= $jadwal['id_jadwal']; ?>"
                                        data-kegiatan="<?= htmlspecialchars($jadwal['nama_kegiatan']); ?>"
                                        data-tanggal="<?= $tgl; ?>"
                                        data-lokasi="<?= htmlspecialchars($jadwal['tempat']); ?>">
                                    Daftar Kegiatan
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<div class="col-12"><div class="alert alert-info text-center">Belum ada jadwal kegiatan mendatang.</div></div>';
            }
            ?>
        </div>
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
                        <p>Lengkapi data di bawah ini untuk mendaftar:</p>
                        
                        <input type="hidden" name="id_jadwal" id="modal_id_jadwal">
                        <input type="hidden" name="id_user" value="<?= $id_user_login; ?>">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Ibu / Wali</label>
                            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($nama_lengkap_user); ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-primary">Pilih Nama Anak <span class="text-danger">*</span></label>
                            <select name="id_anak" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Anak --</option>
                                <?php if (!empty($data_anak)): ?>
                                    <?php foreach ($data_anak as $anak): ?>
                                        <option value="<?= $anak['id_anak']; ?>">
                                            <?= htmlspecialchars($anak['nama_anak']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>Data anak belum ada (Hubungi Admin)</option>
                                <?php endif; ?>
                            </select>
                            <div class="form-text small">Pilih anak yang akan mengikuti kegiatan ini.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Kegiatan</label>
                            <input type="text" class="form-control bg-light" id="modal_nama_kegiatan" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal & Lokasi</label>
                            <div class="input-group">
                                <input type="text" class="form-control bg-light" id="modal_tanggal" readonly>
                                <input type="text" class="form-control bg-light" id="modal_lokasi" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">✅ Daftar Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Script untuk oper data ke Modal
        const modalDaftar = document.getElementById('modalDaftar');
        modalDaftar.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            
            // Ambil data dari tombol
            const idJadwal = button.getAttribute('data-id');
            const namaKegiatan = button.getAttribute('data-kegiatan');
            const tanggal = button.getAttribute('data-tanggal');
            const lokasi = button.getAttribute('data-lokasi');

            // Isi ke dalam input modal
            document.getElementById('modal_id_jadwal').value = idJadwal;
            document.getElementById('modal_nama_kegiatan').value = namaKegiatan;
            document.getElementById('modal_tanggal').value = tanggal;
            document.getElementById('modal_lokasi').value = lokasi;
        });
    </script>
</body>
</html>