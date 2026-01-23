<?php
session_start();

// 1. Hubungkan ke database
include "koneksi.php";

// Cek koneksi
if (!isset($koneksi)) {
    die("Error: Variabel \$koneksi tidak ditemukan. Pastikan file koneksi.php benar.");
}

// ==========================================
// LOGIKA PHP UNTUK GALERI (UPLOAD & HAPUS)
// ==========================================

// A. Proses Upload
if (isset($_POST['upload_foto'])) {
    $judul      = mysqli_real_escape_string($koneksi, $_POST['judul_galeri']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan_galeri']);
    
    // File handling
    $nama_file = $_FILES['file_gambar']['name'];
    $tmp_file  = $_FILES['file_gambar']['tmp_name'];
    $ekstensi  = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
    
    // Rename agar unik
    $nama_baru = "galeri_" . time() . "." . $ekstensi;
    $tujuan    = "uploads/" . $nama_baru; 

    // Validasi
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (in_array($ekstensi, $allowed)) {
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        if (move_uploaded_file($tmp_file, $tujuan)) {
            $sql = "INSERT INTO t_galeri (judul, keterangan, nama_file) VALUES ('$judul', '$keterangan', '$nama_baru')";
            $simpan = mysqli_query($koneksi, $sql);
            
            if ($simpan) {
                echo "<script>alert('Foto berhasil diupload!'); window.location.href='data_anak.php#galeri-admin';</script>";
            } else {
                echo "<script>alert('Error Database: " . mysqli_error($koneksi) . "');</script>";
            }
        } else {
            echo "<script>alert('Gagal mengupload file ke folder uploads.');</script>";
        }
    } else {
        echo "<script>alert('Format file harus JPG, PNG, atau GIF.');</script>";
    }
}

// B. Proses Hapus
if (isset($_GET['hapus_galeri'])) {
    $id_hapus = mysqli_real_escape_string($koneksi, $_GET['hapus_galeri']);
    
    // Ambil nama file dulu
    $cek_sql = "SELECT nama_file FROM t_galeri WHERE id_galeri = '$id_hapus'";
    $cek_query = mysqli_query($koneksi, $cek_sql);
    $data_img = mysqli_fetch_assoc($cek_query);
    
    if ($data_img) {
        // Hapus file fisik
        $path = "uploads/" . $data_img['nama_file'];
        if (file_exists($path)) {
            unlink($path);
        }
        
        // Hapus dari database
        $del_sql = "DELETE FROM t_galeri WHERE id_galeri = '$id_hapus'";
        $del = mysqli_query($koneksi, $del_sql);
        
        if($del) {
            echo "<script>alert('Foto dihapus.'); window.location.href='data_anak.php#galeri-admin';</script>";
        } else {
             echo "<script>alert('Gagal menghapus database.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Anak & Galeri - SiMona Admin</title>
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

  <style>
    body { background-color: #f0f2f5; font-family: 'Poppins', sans-serif; overflow-x: hidden; }
    
    /* Layout Wrapper */
    .d-flex-wrapper { display: flex; width: 100%; min-height: 100vh; }

    /* Sidebar Styling */
    .sidebar { width: 280px; background: linear-gradient(180deg, #4e73df 0%, #224abe 100%); color: white; flex-shrink: 0; min-height: 100vh; position: sticky; top: 0; display: flex; flex-direction: column; }
    .sidebar-logo { padding: 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .sidebar-logo img { width: 60px; height: 60px; border-radius: 50%; background: white; padding: 3px; margin-bottom: 10px; }
    .sidebar-brand { font-size: 1.2rem; font-weight: 600; letter-spacing: 1px; display: block; }
    .sidebar-menu { padding: 20px 15px; flex-grow: 1; }
    
    .nav-link { color: rgba(255,255,255,0.8) !important; font-weight: 500; margin-bottom: 8px; border-radius: 10px; padding: 12px 15px; transition: all 0.3s; display: flex; align-items: center; }
    .nav-link i { width: 25px; text-align: center; margin-right: 10px; }
    .nav-link:hover, .nav-link.active { background-color: rgba(255,255,255,0.2); color: #fff !important; transform: translateX(5px); }

    .sidebar-footer { padding: 20px; border-top: 1px solid rgba(255,255,255,0.1); }
    .btn-logout { width: 100%; background-color: #dc3545; color: white !important; border: none; border-radius: 10px; padding: 10px; text-align: center; transition: 0.3s; }
    .btn-logout:hover { background-color: #bb2d3b; transform: scale(1.02); }

    /* Main Content Styling */
    .main-content { flex-grow: 1; padding: 30px; width: 100%; }

    /* Card Custom */
    .card-custom { border: none; border-radius: 15px; box-shadow: 0 0 20px rgba(0,0,0,0.05); background: white; overflow: hidden; }
    .card-header-custom { background-color: white; border-bottom: 2px solid #f0f2f5; padding: 20px 25px; display: flex; justify-content: space-between; align-items: center; }
    .table thead { background-color: #f8f9fa; color: #495057; }
    .table th { font-weight: 600; border-top: none; }
    .table-hover tbody tr:hover { background-color: #f1f3f9; }
    .action-btn { margin: 0 2px; border-radius: 5px; }

    /* Responsiveness */
    @media (max-width: 768px) {
        .d-flex-wrapper { flex-direction: column; }
        .sidebar { width: 100%; min-height: auto; position: relative; }
        .sidebar-menu { display: flex; flex-wrap: wrap; gap: 5px; justify-content: center; }
        .nav-link { margin-bottom: 0; font-size: 0.9rem; }
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
                    <a class="nav-link active" href="data_anak.php">
                        <i class="fas fa-child"></i> Data Anak
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="data_orangtua.php">
                        <i class="fas fa-user-friends"></i> Data Orangtua
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#galeri-admin">
                        <i class="fas fa-images"></i> Galeri
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_jadwal.php">
                        <i class="fas fa-calendar-alt"></i> Jadwal
                    </a>
                </li>
                
                <li class="nav-item d-md-none mt-2">
                    <a class="nav-link bg-danger text-white justify-content-center" href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Keluar
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
            <h5 class="fw-bold text-primary">Dashboard Admin</h5>
            <hr>
        </div>

        <div class="card card-custom mb-5">
            <div class="card-header-custom">
                <h4 class="m-0 fw-bold text-primary"><i class="fas fa-table me-2"></i>Data Anak Posyandu</h4>
                <a href="create.php" class="btn btn-primary rounded-pill shadow-sm">
                    <i class="fas fa-plus me-1"></i> Tambah Data
                </a>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                      <thead class="text-secondary">
                        <tr>
                          <th class="text-center py-3">No</th> 
                          <th>Nama Anak</th>
                          <th>Nama Ibu</th>
                          <th>TTL</th> 
                          <th class="text-center">L/P</th>
                          <th>Alamat</th>
                          <th class="text-center">Penimbangan</th>
                          <th class="text-center">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php 
                      $sql = "SELECT t_anak.*, t_orangtua.nama_ibu 
                              FROM t_anak 
                              LEFT JOIN t_orangtua ON t_anak.id_orangtua = t_orangtua.id_orangtua 
                              ORDER BY t_anak.id_anak DESC";
                      $query = mysqli_query($koneksi, $sql); 
                      ?>

                      <?php if (mysqli_num_rows($query) > 0): ?>
                        <?php
                        $no = 1; 
                        while ($row = mysqli_fetch_assoc($query)):
                          $tgl_lahir = date('d-m-Y', strtotime($row['tanggal_lahir']));
                        ?>
                          <tr>
                            <td class="text-center text-muted fw-bold"><?php echo $no++; ?></td>
                            <td class="fw-bold text-dark"><?php echo htmlspecialchars($row['nama_anak']); ?></td>
                            <td class="text-secondary"><?php echo htmlspecialchars($row['nama_ibu']); ?></td>
                            <td>
                                <small class="d-block fw-bold"><?php echo htmlspecialchars($row['tempat_lahir']); ?></small>
                                <small class="text-muted"><?php echo $tgl_lahir; ?></small>
                            </td>
                            <td class="text-center">
                                <?php if($row['jenis_kelamin'] == 'Laki-laki' || $row['jenis_kelamin'] == 'L') { ?>
                                    <span class="badge bg-info text-dark">L</span>
                                <?php } else { ?>
                                    <span class="badge bg-danger bg-opacity-75">P</span>
                                <?php } ?>
                            </td>
                            <td class="small text-muted"><?php echo htmlspecialchars($row['alamat']); ?></td>
                            <td class="text-center">
                              <a href="penimbangan/data_penimbangan.php?id_anak=<?php echo $row['id_anak']; ?>" class="btn btn-sm btn-outline-primary action-btn">
                                <i class="fas fa-weight"></i> Data
                              </a>
                            </td>
                            <td class="text-center">
                              <a href="edit.php?id_anak=<?php echo $row['id_anak']; ?>" class="btn btn-sm btn-warning text-white action-btn" title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                              </a>
                              <a href="delete.php?id_anak=<?php echo $row['id_anak']; ?>" class="btn btn-sm btn-danger action-btn" onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus">
                                <i class="fas fa-trash"></i>
                              </a>
                            </td>
                          </tr>
                        <?php endwhile; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="8" class="text-center py-5 text-muted">Belum ada data anak.</td>
                        </tr>
                      <?php endif; ?>
                      </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="galeri-admin" class="card card-custom">
            <div class="card-header-custom bg-white">
                <h4 class="m-0 fw-bold text-success"><i class="fas fa-images me-2"></i>Manajemen Galeri Kegiatan</h4>
            </div>
            <div class="card-body p-4">
                
                <form action="" method="POST" enctype="multipart/form-data" class="row g-3 mb-4 border-bottom pb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Judul Kegiatan</label>
                        <input type="text" name="judul_galeri" class="form-control" required placeholder="Contoh: Imunisasi Masal">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Keterangan Foto</label>
                        <input type="text" name="keterangan_galeri" class="form-control" placeholder="Contoh: Peserta balita usia 2 tahun..">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Pilih Foto (JPG/PNG)</label>
                        <input type="file" name="file_gambar" class="form-control" required accept="image/*">
                    </div>
                    
                    <div class="col-md-1 d-grid">
                        <label class="form-label text-white">.</label>
                        <button type="submit" name="upload_foto" class="btn btn-success"><i class="fas fa-upload"></i></button>
                    </div>
                </form>

                <h6 class="fw-bold mb-3 text-secondary">Daftar Foto Terupload:</h6>
                <div class="row g-3">
                    <?php
                    $sql_galeri = "SELECT * FROM t_galeri ORDER BY id_galeri DESC";
                    $q_galeri = mysqli_query($koneksi, $sql_galeri);
                    
                    if (mysqli_num_rows($q_galeri) > 0) {
                        while ($g = mysqli_fetch_assoc($q_galeri)) {
                    ?>
                        <div class="col-xl-2 col-md-3 col-6 text-center">
                            <div class="border p-2 rounded bg-light h-100 position-relative d-flex flex-column">
                                <img src="uploads/<?= $g['nama_file']; ?>" class="img-fluid rounded mb-2 shadow-sm" style="height: 120px; width: 100%; object-fit: cover;">
                                
                                <p class="small mb-0 fw-bold text-dark text-truncate"><?= htmlspecialchars($g['judul']); ?></p>
                                
                                <p class="small text-muted mb-2 fst-italic" style="font-size: 0.75rem; line-height: 1.2;">
                                    <?= !empty($g['keterangan']) ? htmlspecialchars($g['keterangan']) : '-'; ?>
                                </p>
                                
                                <div class="mt-auto">
                                    <a href="data_anak.php?hapus_galeri=<?= $g['id_galeri']; ?>" 
                                       class="btn btn-danger btn-sm w-100 py-0" 
                                       onclick="return confirm('Hapus foto ini dari galeri?')">
                                       Hapus
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php 
                        } 
                    } else {
                        echo '<div class="col-12 text-center text-muted small py-4">Belum ada foto di galeri.</div>';
                    }
                    ?>
                </div>

            </div>
        </div>
        </div> 
    </div> 
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>