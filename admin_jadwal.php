<?php
session_start();

// 1. Konfigurasi Koneksi
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "dbsipograf1";
$port = 3307;

$koneksi = mysqli_connect($host, $user, $pass, $db, $port);

// 2. Cek Keamanan Admin
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil nama admin untuk ditampilkan di header
$admin_name = $_SESSION['nama_lengkap'] ?? "Administrator";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Kelola Jadwal</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* --- SIDEBAR STYLE --- */
        .sidebar {
            min-height: 100vh;
            width: 260px;
            background: linear-gradient(180deg, #0d6efd 0%, #004494 100%);
            color: #fff;
            position: fixed;
            transition: all 0.3s;
            z-index: 999;
        }
        
        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu {
            padding: 20px 10px;
        }

        .sidebar-link {
            display: block;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: all 0.2s;
        }

        .sidebar-link:hover, .sidebar-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
            transform: translateX(5px);
        }

        .sidebar-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* --- CONTENT WRAPPER --- */
        .main-content {
            margin-left: 260px;
            padding: 20px;
            transition: all 0.3s;
        }

        /* --- NAVBAR --- */
        .top-navbar {
            background-color: #fff;
            padding: 15px 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        /* --- CARD STYLE --- */
        .card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            background-color: #fff;
        }

        .table-custom thead th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
            border-bottom: 2px solid #e9ecef;
        }

        /* --- RESPONSIVE --- */
        @media (max-width: 768px) {
            .sidebar { margin-left: -260px; }
            .sidebar.active { margin-left: 0; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-heartbeat me-2"></i> SiMona Admin
        </div>
            </a>
            <a href="data_anak.php" class="sidebar-link">
                <i class="fas fa-child"></i> Data Anak
            </a>
            <a href="#" class="sidebar-link active">
                <i class="fas fa-calendar-alt"></i> Kelola Jadwal
            </a>
            <a href="admin_lihat_pendaftar.php" class="sidebar-link">
                <i class="fas fa-users"></i> Pendaftar
            </a>
            <a href="logout.php" class="sidebar-link mt-5 text-warning">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <div class="main-content">
        
        <div class="top-navbar">
            <button class="btn btn-outline-primary d-md-none" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <h5 class="m-0 fw-bold text-secondary">Manajemen Jadwal Kegiatan</h5>
            <div class="d-flex align-items-center">
                <div class="me-3 text-end d-none d-sm-block">
                    <span class="d-block fw-bold text-dark"><?= htmlspecialchars($admin_name); ?></span>
                    <small class="text-muted">Administrator</small>
                </div>
                <img src="https://ui-avatars.com/api/?name=Admin&background=0d6efd&color=fff" class="rounded-circle" width="40">
            </div>
        </div>

        <?php if (isset($_GET['pesan'])): ?>
            <?php if ($_GET['pesan'] == 'sukses'): ?>
                <script>Swal.fire('Berhasil!', 'Data jadwal berhasil disimpan.', 'success');</script>
            <?php elseif ($_GET['pesan'] == 'hapus'): ?>
                <script>Swal.fire('Berhasil!', 'Data jadwal berhasil dihapus.', 'success');</script>
            <?php elseif ($_GET['pesan'] == 'gagal'): ?>
                <script>Swal.fire('Gagal!', 'Terjadi kesalahan sistem.', 'error');</script>
            <?php endif; ?>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-custom p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="fw-bold text-primary mb-1">Daftar Jadwal Posyandu</h4>
                            <p class="text-muted mb-0">Kelola jadwal kegiatan rutin bulanan di sini.</p>
                        </div>
                        <button type="button" class="btn btn-primary px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalJadwal" onclick="resetForm()">
                            <i class="fas fa-plus-circle me-2"></i> Tambah Jadwal
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="25%">Nama Kegiatan</th>
                                    <th width="15%">Tanggal</th>
                                    <th width="20%">Waktu & Tempat</th>
                                    <th>Deskripsi</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $query = mysqli_query($koneksi, "SELECT * FROM t_jadwal ORDER BY tanggal DESC");
                                
                                if (mysqli_num_rows($query) > 0) {
                                    while ($row = mysqli_fetch_assoc($query)) {
                                        $tgl = date('d M Y', strtotime($row['tanggal']));
                                        ?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td class="fw-bold text-dark"><?= htmlspecialchars($row['nama_kegiatan']); ?></td>
                                            <td><span class="badge bg-info text-dark"><i class="far fa-calendar me-1"></i> <?= $tgl; ?></span></td>
                                            <td>
                                                <small class="d-block text-muted"><i class="far fa-clock me-1"></i> <?= htmlspecialchars($row['waktu']); ?></small>
                                                <small class="d-block fw-bold"><i class="fas fa-map-marker-alt me-1 text-danger"></i> <?= htmlspecialchars($row['tempat']); ?></small>
                                            </td>
                                            <td class="text-muted small"><?= mb_strimwidth($row['deskripsi'], 0, 50, "..."); ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-warning" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalJadwal"
                                                    onclick="editData(
                                                        '<?= $row['id_jadwal']; ?>',
                                                        '<?= addslashes($row['nama_kegiatan']); ?>',
                                                        '<?= $row['tanggal']; ?>',
                                                        '<?= addslashes($row['waktu']); ?>',
                                                        '<?= addslashes($row['tempat']); ?>',
                                                        '<?= addslashes($row['deskripsi']); ?>'
                                                    )">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                
                                                <a href="proses_admin_jadwal.php?aksi=hapus&id=<?= $row['id_jadwal']; ?>" 
                                                   class="btn btn-sm btn-outline-danger ms-1"
                                                   onclick="return confirm('Yakin ingin menghapus jadwal ini? Data pendaftar juga mungkin akan hilang.')">
                                                   <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center py-5 text-muted'><i class='fas fa-inbox fa-3x mb-3'></i><br>Belum ada data jadwal kegiatan.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalJadwal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="proses_admin_jadwal.php" method="POST">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold" id="modalTitle"><i class="fas fa-plus-circle me-2"></i> Tambah Jadwal</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <input type="hidden" name="id_jadwal" id="id_jadwal">
                        <input type="hidden" name="aksi_form" id="aksi_form" value="tambah">

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase">Nama Kegiatan</label>
                            <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control" placeholder="Contoh: Imunisasi Campak" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-uppercase">Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-uppercase">Waktu</label>
                                <input type="text" name="waktu" id="waktu" class="form-control" placeholder="08:00 - 11:00" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase">Tempat / Lokasi</label>
                            <input type="text" name="tempat" id="tempat" class="form-control" placeholder="Nama Posyandu / Balai Desa" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" placeholder="Tambahkan catatan untuk peserta..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle Sidebar pada Mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });

        // Fungsi Reset Form
        function resetForm() {
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus-circle me-2"></i> Tambah Jadwal Baru';
            document.getElementById('aksi_form').value = "tambah";
            document.getElementById('id_jadwal').value = "";
            document.getElementById('nama_kegiatan').value = "";
            document.getElementById('tanggal').value = "";
            document.getElementById('waktu').value = "";
            document.getElementById('tempat').value = "";
            document.getElementById('deskripsi').value = "";
        }

        // Fungsi Edit Data
        function editData(id, nama, tgl, waktu, tempat, deskripsi) {
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit me-2"></i> Edit Jadwal';
            document.getElementById('aksi_form').value = "edit";
            document.getElementById('id_jadwal').value = id;
            document.getElementById('nama_kegiatan').value = nama;
            document.getElementById('tanggal').value = tgl;
            document.getElementById('waktu').value = waktu;
            document.getElementById('tempat').value = tempat;
            document.getElementById('deskripsi').value = deskripsi;
        }
    </script>
</body>
</html>