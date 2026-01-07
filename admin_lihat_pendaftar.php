<?php
session_start();

// 1. Konfigurasi Koneksi Database
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

// Ambil nama admin untuk header
$admin_name = $_SESSION['nama_lengkap'] ?? "Administrator";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Data Pendaftar</title>
    
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

        /* --- STATS CARD STYLE --- */
        .stat-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border-left: 5px solid #0d6efd;
            transition: transform 0.2s;
            height: 100%;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }

        /* --- CARD & TABLE --- */
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
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .avatar-initial {
            width: 35px;
            height: 35px;
            background-color: #e9ecef;
            color: #0d6efd;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
        }

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
        <div class="sidebar-menu">
            <a href="data_anak.php" class="sidebar-link">
                <i class="fas fa-child"></i> Data Anak
            </a>
            <a href="admin_jadwal.php" class="sidebar-link">
                <i class="fas fa-calendar-alt"></i> Kelola Jadwal
            </a>
            <a href="#" class="sidebar-link active">
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
            <h5 class="m-0 fw-bold text-secondary">Data Peserta Kegiatan</h5>
            <div class="d-flex align-items-center">
                <div class="me-3 text-end d-none d-sm-block">
                    <span class="d-block fw-bold text-dark"><?= htmlspecialchars($admin_name); ?></span>
                    <small class="text-muted">Administrator</small>
                </div>
                <img src="https://ui-avatars.com/api/?name=Admin&background=0d6efd&color=fff" class="rounded-circle" width="40">
            </div>
        </div>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'hapus_sukses'): ?>
            <script>Swal.fire('Berhasil!', 'Data pendaftar telah dihapus.', 'success');</script>
        <?php elseif (isset($_GET['status']) && $_GET['status'] == 'gagal'): ?>
            <script>Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus.', 'error');</script>
        <?php endif; ?>

        <h6 class="fw-bold text-muted text-uppercase mb-3"><i class="fas fa-chart-pie me-2"></i>Rekapitulasi Pendaftar</h6>
        <div class="row mb-4">
            <?php
            // Query untuk menghitung jumlah pendaftar per jadwal (GROUP BY)
            $query_stats = "SELECT j.nama_kegiatan, j.tanggal, COUNT(p.id_daftar) as total_peserta
                            FROM t_jadwal j
                            LEFT JOIN t_pendaftaran p ON j.id_jadwal = p.id_jadwal
                            GROUP BY j.id_jadwal
                            HAVING total_peserta > 0
                            ORDER BY j.tanggal DESC";
            
            $result_stats = mysqli_query($koneksi, $query_stats);
            
            if (mysqli_num_rows($result_stats) > 0) {
                while($stat = mysqli_fetch_assoc($result_stats)) {
                    ?>
                    <div class="col-md-4 col-sm-6 mb-3">
                        <div class="stat-card d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted d-block mb-1"><?= date('d M Y', strtotime($stat['tanggal'])); ?></small>
                                <h6 class="fw-bold text-dark mb-0"><?= htmlspecialchars($stat['nama_kegiatan']); ?></h6>
                            </div>
                            <div class="text-end">
                                <h2 class="fw-bold text-primary mb-0"><?= $stat['total_peserta']; ?></h2>
                                <small class="text-muted">Peserta</small>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<div class="col-12"><div class="alert alert-info">Belum ada peserta yang mendaftar di kegiatan manapun.</div></div>';
            }
            ?>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card card-custom p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="fw-bold text-primary mb-1">Daftar Rincian Peserta</h4>
                            <p class="text-muted mb-0">Detail data orang tua/anak yang sudah booking jadwal.</p>
                        </div>
                        <a href="admin_jadwal.php" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali ke Jadwal
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama User / Ibu</th>
                                    <th>Kegiatan & Tanggal</th>
                                    <th>Waktu Mendaftar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Query JOIN 3 Tabel untuk detail
                                $query = "SELECT p.id_daftar, p.tgl_daftar, u.username, u.nama_lengkap, j.nama_kegiatan, j.tanggal
                                          FROM t_pendaftaran p
                                          JOIN masuk u ON p.id_user = u.id_user
                                          JOIN t_jadwal j ON p.id_jadwal = j.id_jadwal
                                          ORDER BY p.tgl_daftar DESC";
                                
                                $result = mysqli_query($koneksi, $query);
                                $no = 1;

                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $nama_tampil = !empty($row['nama_lengkap']) ? $row['nama_lengkap'] : $row['username'];
                                        $inisial = strtoupper(substr($nama_tampil, 0, 1));
                                        
                                        $tgl_kegiatan = date('d M Y', strtotime($row['tanggal']));
                                        $tgl_daftar = date('d/m/Y H:i', strtotime($row['tgl_daftar']));
                                        ?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-initial"><?= $inisial; ?></div>
                                                    <div>
                                                        <span class="d-block fw-bold text-dark"><?= htmlspecialchars($nama_tampil); ?></span>
                                                        <small class="text-muted">User ID: <?= htmlspecialchars($row['username']); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="d-block fw-bold text-primary"><?= htmlspecialchars($row['nama_kegiatan']); ?></span>
                                                <span class="badge bg-info text-dark mt-1"><i class="far fa-calendar me-1"></i> <?= $tgl_kegiatan; ?></span>
                                            </td>
                                            <td>
                                                <small class="text-muted"><i class="far fa-clock me-1"></i> <?= $tgl_daftar; ?> WIB</small>
                                            </td>
                                            <td>
                                                <a href="proses_admin_hapus_pendaftar.php?id=<?= $row['id_daftar']; ?>" 
                                                   class="btn btn-sm btn-outline-danger"
                                                   onclick="return confirm('Apakah Anda yakin ingin menghapus pendaftar ini? User harus mendaftar ulang jika dihapus.')">
                                                    <i class="fas fa-trash-alt me-1"></i> Hapus
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center py-5 text-muted'><i class='fas fa-user-slash fa-3x mb-3'></i><br>Belum ada pendaftar yang masuk.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>