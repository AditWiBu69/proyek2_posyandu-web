<?php
session_start();
include "koneksi.php";

// Cek koneksi
if (!isset($koneksi)) {
    die("Error: Variabel \$koneksi tidak ditemukan.");
}

// ==========================================
// 1. LOGIKA AMBIL DATA (Berdasarkan ID di URL)
// ==========================================
if (isset($_GET['id_orangtua'])) {
    $id_orangtua = mysqli_real_escape_string($koneksi, $_GET['id_orangtua']);
    
    // Query ambil data lama
    $sql_get = "SELECT * FROM t_orangtua WHERE id_orangtua = '$id_orangtua'";
    $query_get = mysqli_query($koneksi, $sql_get);
    $data_lama = mysqli_fetch_assoc($query_get);

    // Jika data tidak ditemukan (misal ID diubah manual di URL)
    if (!$data_lama) {
        echo "<script>alert('Data orang tua tidak ditemukan!'); window.location.href='data_orangtua.php';</script>";
        exit;
    }
} else {
    // Jika tidak ada ID di URL, kembalikan ke halaman data
    header("Location: data_orangtua.php");
    exit;
}

// ==========================================
// 2. LOGIKA UPDATE DATA (Saat tombol Simpan ditekan)
// ==========================================
if (isset($_POST['btn_update_ortu'])) {
    // Ambil data dari form & amankan
    $id_edit    = $_POST['id_orangtua_hidden']; // ID dari input hidden
    $nama_ibu   = mysqli_real_escape_string($koneksi, $_POST['nama_ibu']);
    $nama_ayah  = mysqli_real_escape_string($koneksi, $_POST['nama_ayah']);
    $alamat     = mysqli_real_escape_string($koneksi, $_POST['alamat_ortu']);
    $no_hp      = mysqli_real_escape_string($koneksi, $_POST['no_hp']);

    // Query Update
    $sql_update = "UPDATE t_orangtua SET 
                    nama_ibu = '$nama_ibu',
                    nama_ayah = '$nama_ayah',
                    alamat_ortu = '$alamat',
                    no_hp = '$no_hp'
                   WHERE id_orangtua = '$id_edit'";
    
    $query_update = mysqli_query($koneksi, $sql_update);

    if ($query_update) {
        echo "<script>alert('Data Orang Tua Berhasil Diperbarui!'); window.location.href='data_orangtua.php';</script>";
    } else {
        echo "<script>alert('Gagal Memperbarui: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Orang Tua - SiMona Admin</title>
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

  <style>
    body { background-color: #f0f2f5; font-family: 'Poppins', sans-serif; overflow-x: hidden; }
    .d-flex-wrapper { display: flex; width: 100%; min-height: 100vh; }
    
    /* Sidebar Styling */
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
        
        <div class="card card-custom mb-4 col-lg-8 mx-auto">
            <div class="card-header-custom bg-warning bg-opacity-10">
                <h5 class="m-0 fw-bold text-warning text-dark"><i class="fas fa-pencil-alt me-2"></i>Edit Data Orang Tua</h5>
                <a href="data_orangtua.php" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
            </div>
            <div class="card-body p-4">
                
                <form action="" method="POST">
                    <input type="hidden" name="id_orangtua_hidden" value="<?= $data_lama['id_orangtua']; ?>">

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Nama Ibu <span class="text-danger">*</span></label>
                            <input type="text" name="nama_ibu" class="form-control" required 
                                   value="<?= htmlspecialchars($data_lama['nama_ibu']); ?>">
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Nama Ayah</label>
                            <input type="text" name="nama_ayah" class="form-control" 
                                   value="<?= htmlspecialchars($data_lama['nama_ayah']); ?>">
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea name="alamat_ortu" class="form-control" rows="3" required><?= htmlspecialchars($data_lama['alamat_ortu']); ?></textarea>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label fw-bold">No. HP / WhatsApp</label>
                            <input type="number" name="no_hp" class="form-control" 
                                   value="<?= htmlspecialchars($data_lama['no_hp']); ?>">
                        </div>

                        <div class="col-12 d-flex justify-content-between mt-4">
                             <a href="data_orangtua.php" class="btn btn-light border">Batal</a>
                            <button type="submit" name="btn_update_ortu" class="btn btn-warning text-dark fw-bold px-4">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>