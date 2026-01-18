<?php
session_start();
include 'koneksi.php';

// 1. Cek Keamanan Login
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

// 2. Logika Update Data (Jika Tombol Simpan ditekan)
if (isset($_POST['simpan_perubahan'])) {
    $id_ortu_edit = $_POST['id_orangtua'];
    $nama_ayah    = $_POST['nama_ayah'];
    $no_hp        = $_POST['no_hp'];
    $alamat_ortu  = $_POST['alamat_ortu'];

    // Query Update (Nama Ibu tidak diedit agar link ke akun tidak putus)
    $query_update = "UPDATE t_orangtua SET 
                     nama_ayah = '$nama_ayah', 
                     no_hp = '$no_hp', 
                     alamat_ortu = '$alamat_ortu' 
                     WHERE id_orangtua = '$id_ortu_edit'";

    $exec_update = mysqli_query($koneksi, $query_update);

    if ($exec_update) {
        // Refresh halaman dengan parameter sukses
        echo "<script>
                setTimeout(function() { 
                    window.location.href = 'profil.php?status=sukses'; 
                }, 100);
              </script>";
    } else {
        echo "<script>alert('Gagal mengupdate data: " . mysqli_error($koneksi) . "');</script>";
    }
}

// 3. Ambil Data Profil
$username_session = $_SESSION['username'];
$query = "SELECT t.* FROM t_orangtua t 
          JOIN masuk m ON m.nama_lengkap = t.nama_ibu 
          WHERE m.username = '$username_session'";

$result = mysqli_query($koneksi, $query);
$data   = mysqli_fetch_assoc($result);
$ada_data = ($data) ? true : false;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - User Posyandu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { background-color: #f8f9fa; }
        .hero-banner {
            background: linear-gradient(135deg, #0d6efd 0%, #0099ff 100%);
            color: white;
            padding: 40px 20px;
            border-radius: 0 0 20px 20px;
            margin-bottom: -30px;
        }
        .profile-card {
            border: none; border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .profile-header {
            background-color: white; padding: 30px;
            text-align: center; border-bottom: 1px solid #f0f0f0;
        }
        .avatar-circle {
            width: 100px; height: 100px;
            background-color: #e9ecef; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 15px; font-size: 40px; color: #0d6efd;
            border: 4px solid white; box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .profile-body { padding: 30px; background-color: white; }
        .info-item {
            margin-bottom: 20px; border-bottom: 1px solid #f8f9fa; padding-bottom: 10px;
        }
        .info-label { font-size: 0.9rem; color: #6c757d; margin-bottom: 5px; font-weight: 600; }
        .info-value { font-size: 1.1rem; color: #212529; font-weight: 500; }
        .icon-box { width: 30px; display: inline-block; text-align: center; color: #0d6efd; margin-right: 10px; }
    </style>
</head>
<body>

    <?php 
    $page = 'profil';
    include 'navbar.php'; 
    ?>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'sukses'): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data profil berhasil diperbarui.',
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            window.history.replaceState(null, null, window.location.pathname);
        });
    </script>
    <?php endif; ?>

    <div class="hero-banner text-center">
        <h3>Profil Orang Tua</h3>
        <p class="small opacity-75">Data identitas ibu dan ayah</p>
    </div>

    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                <div class="card profile-card mt-4">
                    
                    <?php if ($ada_data): ?>
                        
                        <div class="profile-header">
                            <div class="avatar-circle">
                                <i class="fas fa-user"></i>
                            </div>
                            <h4 class="mb-0"><?php echo htmlspecialchars($data['nama_ibu']); ?></h4>
                            <span class="badge bg-success rounded-pill mt-2">Terdaftar</span>
                        </div>

                        <div class="profile-body">
                            
                            <div class="info-item">
                                <div class="info-label"><i class="fas fa-venus icon-box"></i> Nama Ibu</div>
                                <div class="info-value ps-5"><?php echo htmlspecialchars($data['nama_ibu']); ?></div>
                            </div>

                            <div class="info-item">
                                <div class="info-label"><i class="fas fa-mars icon-box"></i> Nama Ayah</div>
                                <div class="info-value ps-5">
                                    <?php echo !empty($data['nama_ayah']) ? htmlspecialchars($data['nama_ayah']) : '-'; ?>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-label"><i class="fas fa-phone icon-box"></i> Nomor HP / WhatsApp</div>
                                <div class="info-value ps-5">
                                    <?php echo !empty($data['no_hp']) ? htmlspecialchars($data['no_hp']) : '-'; ?>
                                </div>
                            </div>

                            <div class="info-item border-bottom-0">
                                <div class="info-label"><i class="fas fa-map-marker-alt icon-box"></i> Alamat Lengkap</div>
                                <div class="info-value ps-5">
                                    <?php echo htmlspecialchars($data['alamat_ortu']); ?>
                                </div>
                            </div>

                            <div class="row g-2 mt-4">
                                <div class="col-6">
                                    <a href="home_user.php" class="btn btn-outline-secondary w-100 py-2">
                                        <i class="fas fa-arrow-left me-2"></i> Kembali
                                    </a>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-primary w-100 py-2" data-bs-toggle="modal" data-bs-target="#modalEdit">
                                        <i class="fas fa-edit me-2"></i> Edit Profil
                                    </button>
                                </div>
                            </div>

                        </div>

                    <?php else: ?>
                        <div class="card-body text-center p-5">
                            <div class="text-warning mb-3" style="font-size: 50px;">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h5>Data Profil Tidak Ditemukan</h5>
                            <p class="text-muted">
                                Nama akun Anda <b>(<?php echo htmlspecialchars($_SESSION['username']); ?>)</b> belum terhubung dengan data orang tua manapun.
                            </p>
                            <a href="home_user.php" class="btn btn-primary mt-3">Kembali</a>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <?php if ($ada_data): ?>
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalEditLabel"><i class="fas fa-edit me-2"></i> Edit Data Profil</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body">
                        <input type="hidden" name="id_orangtua" value="<?php echo $data['id_orangtua']; ?>">

                        <div class="mb-3">
                            <label class="form-label text-muted small">Nama Ibu (Tidak dapat diubah)</label>
                            <input type="text" class="form-control bg-light" value="<?php echo $data['nama_ibu']; ?>" readonly>
                            <div class="form-text text-danger small">*Hubungi kader jika ingin mengubah nama ibu.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Ayah</label>
                            <input type="text" class="form-control" name="nama_ayah" value="<?php echo $data['nama_ayah']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nomor HP</label>
                            <input type="number" class="form-control" name="no_hp" value="<?php echo $data['no_hp']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat Lengkap</label>
                            <textarea class="form-control" name="alamat_ortu" rows="3" required><?php echo $data['alamat_ortu']; ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="simpan_perubahan" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>