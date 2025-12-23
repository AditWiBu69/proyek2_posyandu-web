<?php
// 1. Masukkan koneksi
include "connection.php"; 

// Cek koneksi
if (!isset($conn)) {
    die("Error: File connection.php berhasil di-include, tapi variabel \$conn tidak ditemukan.");
}

// ==========================================
// LOGIKA PHP UNTUK GALERI (UPLOAD & HAPUS)
// ==========================================

// A. Proses Upload
if (isset($_POST['upload_foto'])) {
    $judul = $_POST['judul_galeri'];
    
    // File handling
    $nama_file = $_FILES['file_gambar']['name'];
    $tmp_file = $_FILES['file_gambar']['tmp_name'];
    $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
    
    // Rename agar unik
    $nama_baru = "galeri_" . time() . "." . $ekstensi;
    $tujuan = "uploads/" . $nama_baru; // Pastikan folder 'uploads' ada

    // Validasi
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($ekstensi, $allowed)) {
        if (move_uploaded_file($tmp_file, $tujuan)) {
            // INSERT MENGGUNAKAN PDO
            $stmt = $conn->prepare("INSERT INTO t_galeri (judul, nama_file) VALUES (:judul, :nama_file)");
            $simpan = $stmt->execute([':judul' => $judul, ':nama_file' => $nama_baru]);
            
            if ($simpan) {
                echo "<script>alert('Foto berhasil diupload!'); window.location.href='data_anak.php#galeri-admin';</script>";
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
    $id_hapus = $_GET['hapus_galeri'];
    
    // Ambil nama file dulu untuk dihapus dari folder
    $stmt = $conn->prepare("SELECT nama_file FROM t_galeri WHERE id_galeri = :id");
    $stmt->execute([':id' => $id_hapus]);
    $data_img = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($data_img) {
        // Hapus file fisik
        $path = "uploads/" . $data_img['nama_file'];
        if (file_exists($path)) {
            unlink($path);
        }
        
        // Hapus dari database (PDO)
        $del = $conn->prepare("DELETE FROM t_galeri WHERE id_galeri = :id");
        $del->execute([':id' => $id_hapus]);
        
        echo "<script>alert('Foto dihapus.'); window.location.href='data_anak.php#galeri-admin';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Anak & Galeri - SIPOGRAF Admin</title>
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

  <style>
    body { background-color: #f0f2f5; font-family: 'Poppins', sans-serif; }
    
    /* Navbar Styling */
    .navbar {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        padding: 15px 0;
    }
    .navbar-brand { font-weight: 600; letter-spacing: 1px; color: white !important; }
    .navbar-logo img { width: 40px; height: 40px; border-radius: 50%; background: white; padding: 2px; margin-right: 10px; }
    .nav-link { color: rgba(255,255,255,0.8) !important; font-weight: 500; margin-right: 15px; transition: 0.3s; }
    .nav-link:hover, .nav-link.active { color: #fff !important; transform: translateY(-2px); }
    .btn-logout { background-color: rgba(255,255,255,0.2); color: white !important; border-radius: 20px; padding: 5px 20px !important; }
    .btn-logout:hover { background-color: #dc3545; }

    /* Content Styling */
    .card-custom { border: none; border-radius: 15px; box-shadow: 0 0 20px rgba(0,0,0,0.05); background: white; overflow: hidden; }
    .card-header-custom { background-color: white; border-bottom: 2px solid #f0f2f5; padding: 20px 25px; display: flex; justify-content: space-between; align-items: center; }
    .table thead { background-color: #f8f9fa; color: #495057; }
    .table th { font-weight: 600; border-top: none; }
    .table-hover tbody tr:hover { background-color: #f1f3f9; }
    .action-btn { margin: 0 2px; border-radius: 5px; }
  </style>
</head>

<body>

  <nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <div class="navbar-logo">
            <img src="img/Favicon.png" alt="Logo">
        </div>
        SIPOGRAF ADMIN
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span> </button>
      
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item">
            <a class="nav-link active" href="data_anak.php">
                <i class="fas fa-child me-1"></i> Data Anak
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#galeri-admin">
                <i class="fas fa-images me-1"></i> Galeri
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="admin_jadwal.php">
                <i class="fas fa-calendar-alt me-1"></i> Jadwal
            </a>
          </li>
          <li class="nav-item ps-2">
            <a class="nav-link btn-logout" href="index.html">
                <i class="fas fa-sign-out-alt me-1"></i> Keluar
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-5 mb-5">
    
    <div class="card card-custom mb-5">
        <div class="card-header-custom">
            <h4 class="m-0 fw-bold text-primary"><i class="fas fa-table me-2"></i>Data Anak Posyandu</h4>
            <a href="create.php" class="btn btn-primary rounded-pill shadow-sm">
                <i class="fas fa-plus me-1"></i> Tambah Data Anak
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
                      <th>TTL</th> <th class="text-center">L/P</th>
                      <th>Alamat</th>
                      <th class="text-center">Penimbangan</th>
                      <th class="text-center">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php 
                    $query = $conn->query('SELECT * FROM t_anak ORDER BY id_anak DESC'); 
                  ?>

                  <?php if ($query->rowCount() > 0): ?>
                    <?php
                    $no = 1; 
                    foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $row):
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
                    <?php endforeach; ?>
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
                <div class="col-md-5">
                    <label class="form-label fw-bold">Judul Kegiatan</label>
                    <input type="text" name="judul_galeri" class="form-control" required placeholder="Contoh: Imunisasi Masal">
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-bold">Pilih Foto (JPG/PNG)</label>
                    <input type="file" name="file_gambar" class="form-control" required accept="image/*">
                </div>
                <div class="col-md-2 d-grid">
                    <label class="form-label text-white">.</label>
                    <button type="submit" name="upload_foto" class="btn btn-success"><i class="fas fa-upload me-1"></i> Upload</button>
                </div>
            </form>

            <h6 class="fw-bold mb-3 text-secondary">Daftar Foto Terupload:</h6>
            <div class="row g-3">
                <?php
                // Ambil data galeri (PDO)
                $q_galeri = $conn->query("SELECT * FROM t_galeri ORDER BY id_galeri DESC");
                
                if ($q_galeri->rowCount() > 0) {
                    foreach ($q_galeri->fetchAll(PDO::FETCH_ASSOC) as $g) {
                ?>
                    <div class="col-md-2 col-6 text-center">
                        <div class="border p-2 rounded bg-light h-100 position-relative">
                            <img src="uploads/<?= $g['nama_file']; ?>" class="img-fluid rounded mb-2 shadow-sm" style="height: 100px; width: 100%; object-fit: cover;">
                            <p class="small mb-1 text-truncate fw-bold"><?= htmlspecialchars($g['judul']); ?></p>
                            
                            <a href="data_anak.php?hapus_galeri=<?= $g['id_galeri']; ?>" 
                               class="btn btn-danger btn-sm w-100 py-0" 
                               onclick="return confirm('Hapus foto ini dari galeri?')">
                               Hapus
                            </a>
                        </div>
                    </div>
                <?php 
                    } 
                } else {
                    echo '<div class="col-12 text-center text-muted small">Belum ada foto di galeri.</div>';
                }
                ?>
            </div>

        </div>
    </div>

  </div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>