<?php
session_start();
include 'koneksi.php';

// Cek Keamanan Login
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Kegiatan - User Posyandu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .section-header {
            color: #198754; /* Warna hijau untuk galeri */
            font-weight: bold;
            margin-bottom: 25px; text-transform: uppercase;
            border-bottom: 2px solid #e9ecef; padding-bottom: 10px;
        }
        .gallery-img {
            width: 100%; height: 220px;
            object-fit: cover; border-top-left-radius: 10px; border-top-right-radius: 10px;
        }
        .card {
            transition: transform 0.3s;
            border-radius: 10px;
            overflow: hidden;
        }
        .card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    </style>
</head>
<body>

    <?php 
    $page = 'galeri'; // Penanda halaman aktif untuk Navbar
    include 'navbar.php'; 
    ?>

    <div class="container mt-5">
        <section id="galeri" class="mb-5">
            <h3 class="section-header"><i class="fas fa-images me-2"></i>Galeri Kegiatan Posyandu</h3>
            <p class="text-muted mb-4">Dokumentasi kegiatan-kegiatan terkini di Posyandu kami.</p>
            
            <div class="row g-4">
                <?php
                // Query mengambil semua foto, bukan cuma limit 6
                $query_galeri = mysqli_query($koneksi, "SELECT * FROM t_galeri ORDER BY id_galeri DESC"); 
                
                if (mysqli_num_rows($query_galeri) > 0) {
                    while ($foto = mysqli_fetch_assoc($query_galeri)) {
                        ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <img src="uploads/<?= htmlspecialchars($foto['nama_file']); ?>" 
                                     class="gallery-img card-img-top" 
                                     alt="<?= htmlspecialchars($foto['judul']); ?>">
                                <div class="card-body p-3 text-center bg-white">
                                    <h6 class="card-title fw-bold text-dark mb-1"><?= htmlspecialchars($foto['judul']); ?></h6>
                                    <?php if (!empty($foto['keterangan'])): ?>
                                        <hr class="my-2" style="opacity: 0.1">
                                        <p class="card-text small text-muted"><?= htmlspecialchars($foto['keterangan']); ?></p>
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
    </div>

    <footer class="bg-dark text-white text-center py-4 mt-auto fixed-bottom" style="position: relative;">
        <div class="container">
            <p class="mb-0 small">&copy; 2025 SiMona - Sistem Informasi Posyandu.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</body>
</html>