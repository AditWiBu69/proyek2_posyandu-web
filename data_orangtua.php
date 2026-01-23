<?php
session_start();
include "koneksi.php"; // Pastikan file ini ada

// Cek koneksi
if (!isset($koneksi)) {
    die("Error: Variabel \$koneksi tidak ditemukan.");
}

// ==========================================
// LOGIKA TAMBAH DATA ORANG TUA
// ==========================================
if (isset($_POST['simpan_ortu'])) {
    // Amankan Input
    $nama_ibu   = mysqli_real_escape_string($koneksi, $_POST['nama_ibu']);
    $nama_ayah  = mysqli_real_escape_string($koneksi, $_POST['nama_ayah']);
    $alamat     = mysqli_real_escape_string($koneksi, $_POST['alamat_ortu']);
    $no_hp      = mysqli_real_escape_string($koneksi, $_POST['no_hp']);

    // Query Insert
    $sql_simpan = "INSERT INTO t_orangtua (nama_ibu, nama_ayah, alamat_ortu, no_hp) 
                   VALUES ('$nama_ibu', '$nama_ayah', '$alamat', '$no_hp')";
    
    $query_simpan = mysqli_query($koneksi, $sql_simpan);

    if ($query_simpan) {
        echo "<script>alert('Data Orang Tua Berhasil Disimpan!'); window.location.href='data_orangtua.php';</script>";
    } else {
        echo "<script>alert('Gagal Menyimpan: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Orang Tua - SiMona Admin</title>
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

  <style>
    body { background-color: #f0f2f5; font-family: 'Poppins', sans-serif; overflow-x: hidden; }
    .d-flex-wrapper { display: flex; width: 100%; min-height: 100vh; }
    
    /* Sidebar Styling (Sama seperti data_anak.php) */
    .sidebar { width: 280px; background: linear-gradient(180deg, #4e73df 0%, #224abe 100%); color: white; min-height: 100vh; position: sticky; top: 0; display: flex; flex-direction: column; flex-shrink: 0;}
    .sidebar-logo { padding: 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .sidebar-logo img { width: 60px; height: 60px; border-radius: 50%; background: white; padding: 3px; margin-bottom: 10px; }
    .sidebar-brand { font-size: 1.2rem; font-weight: 600; letter-spacing: 1px; display: block; }
    .sidebar-menu { padding: 20px 15px; flex-grow: 1; }
    .nav-link { color: rgba(255,255,255,0.8) !important; font-weight: 500; margin-bottom: 8px; border-radius: 10px; padding: 12px 15px; transition: all 0.3s; display: flex; align-items: center; }
    .nav-link i { width: 25px; text-align: center; margin-right: 10px; }
    .nav-link:hover, .nav-link.active { background-color: rgba(255,255,255,0.2); color: #fff !important; transform: translateX(5px); }
    .sidebar-footer { padding: 20px; border-top: 1px solid rgba(255,255,255,0.1); }
    .btn-logout { width: 100%; background-color: #dc3545; color: white !important; border: none; border-radius: 10px; padding: 10px; transition: 0.3s; }
    .btn-logout:hover { background-color: #bb2d3b; transform: scale(1.02); }

    /* Content */
    .main-content { flex-grow: 1; padding: 30px; width: 100%; }
    .card-custom { border: none; border-radius: 15px; box-shadow: 0 0 20px rgba(0,0,0,0.05); background: white; overflow: hidden; }
    .card-header-custom { background-color: white; border-bottom: 2px solid #f0f2f5; padding: 20px 25px; display: flex; justify-content: space-between; align-items: center; }
    
    @media (max-width: 768px) {
        .d-flex-wrapper { flex-direction: column; }
        .sidebar { width: 100%; min-height: auto; }
        .sidebar-menu { display: flex; flex-wrap: wrap; justify-content: center; gap: 5px;}
        .sidebar-footer { display: none; }
    }
  </style>
</head>

<body>

  <div class="d-flex-wrapper">
    
    <nav class="sidebar">
        <div class="sidebar-logo">
            <img src="img/Favicon.png" alt="Logo">
            <span class="sidebar-brand">SiMona ADMIN</span>
        </div>

        <div class="sidebar-menu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="data_anak.php">
                        <i class="fas fa-child"></i> Data Anak
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="data_orangtua.php">
                        <i class="fas fa-user-friends"></i> Data Orangtua
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="data_anak.php#galeri-admin">
                        <i class="fas fa-images"></i> Galeri
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_jadwal.php">
                        <i class="fas fa-calendar-alt"></i> Jadwal
                    </a>
                </li>
            </ul>
        </div>

        <div class="sidebar-footer d-none d-md-block">
            <a href="logout.php" class="btn btn-logout">
                <i class="fas fa-sign-out-alt me-2"></i> Keluar
            </a>
        </div>
    </nav>

    <div class="main-content">
        
        <div class="d-block d-md-none mb-4 text-center">
            <h5 class="fw-bold text-primary">Manajemen Orang Tua</h5>
            <hr>
        </div>

        <div class="card card-custom mb-4">
            <div class="card-header-custom">
                <h5 class="m-0 fw-bold text-success"><i class="fas fa-plus-circle me-2"></i>Tambah Data Orang Tua</h5>
            </div>
            <div class="card-body p-4">
                <form action="" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Ibu <span class="text-danger">*</span></label>
                            <input type="text" name="nama_ibu" class="form-control" required placeholder="Nama Lengkap Ibu">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Ayah</label>
                            <input type="text" name="nama_ayah" class="form-control" placeholder="Nama Lengkap Ayah">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Alamat Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="alamat_ortu" class="form-control" required placeholder="Jalan, RT/RW, Dusun">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">No. HP / WhatsApp</label>
                            <input type="number" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" name="simpan_ortu" class="btn btn-primary px-4 rounded-pill">
                                <i class="fas fa-save me-1"></i> Simpan Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-custom">
            <div class="card-header-custom">
                <h5 class="m-0 fw-bold text-primary"><i class="fas fa-table me-2"></i>Daftar Orang Tua Terdaftar</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="text-center py-3">No</th>
                                <th>Nama Ibu</th>
                                <th>Nama Ayah</th>
                                <th>Alamat</th>
                                <th>No. HP</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM t_orangtua ORDER BY id_orangtua DESC";
                            $query = mysqli_query($koneksi, $sql);
                            $no = 1;
                            
                            if(mysqli_num_rows($query) > 0):
                                while($row = mysqli_fetch_assoc($query)):
                            ?>
                            <tr>
                                <td class="text-center fw-bold text-muted"><?= $no++; ?></td>
                                <td class="fw-bold text-dark"><?= htmlspecialchars($row['nama_ibu']); ?></td>
                                <td><?= htmlspecialchars($row['nama_ayah']); ?></td>
                                <td class="small"><?= htmlspecialchars($row['alamat_ortu']); ?></td>
                                <td>
                                    <?php if(!empty($row['no_hp'])): ?>
                                        <span class="badge bg-success"><i class="fab fa-whatsapp"></i> <?= $row['no_hp']; ?></span>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                <a href="edit_orangtua.php?id_orangtua=<?= $row['id_orangtua']; ?>" class="btn btn-sm btn-warning text-white">
                                <i class="fas fa-pencil-alt"></i>
                                </a>
    
                                <a href="delete_orangtua.php?id_orangtua=<?= $row['id_orangtua']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">
                                <i class="fas fa-trash"></i>
                                </a>
                                </td>
                            </tr>
                            <?php 
                                endwhile; 
                            else:
                            ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada data orang tua.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>