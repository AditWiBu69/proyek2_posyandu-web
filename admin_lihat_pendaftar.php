<?php
session_start();
// Koneksi Database
$koneksi = mysqli_connect("127.0.0.1", "root", "", "dbsipograf1", 3307);

// Cek Admin (Sesuaikan dengan session login admin Anda)
if (!isset($_SESSION['username'])) { // Tambahkan && $_SESSION['role'] == 'admin' jika perlu
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pendaftar Kegiatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">📋 Data Pendaftar Kegiatan</h4>
            <a href="admin_jadwal.php" class="btn btn-light btn-sm">Kembali ke Jadwal</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Ibu / User</th>
                            <th>Kegiatan yang Dipilih</th>
                            <th>Tanggal Kegiatan</th>
                            <th>Waktu Mendaftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query Gabungan (JOIN) 3 Tabel: Pendaftaran, Masuk (User), Jadwal
                        $query = "SELECT p.id_daftar, p.tgl_daftar, u.username, j.nama_kegiatan, j.tanggal
                                  FROM t_pendaftaran p
                                  JOIN masuk u ON p.id_user = u.id_user
                                  JOIN t_jadwal j ON p.id_jadwal = j.id_jadwal
                                  ORDER BY p.tgl_daftar DESC";
                        
                        $result = mysqli_query($koneksi, $query);
                        $no = 1;

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($row['username']); ?></td>
                                    <td><?= htmlspecialchars($row['nama_kegiatan']); ?></td>
                                    <td><?= date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                                    <td><?= date('d-m-Y H:i', strtotime($row['tgl_daftar'])); ?></td>
                                    <td>
                                        <a href="proses_admin_hapus_pendaftar.php?id=<?= $row['id_daftar']; ?>" 
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        Hapus
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>Belum ada pendaftar masuk.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
if (isset($_GET['status']) && $_GET['status'] == 'hapus_sukses') {
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Terhapus!',
            text: 'Data pendaftar berhasil dihapus.',
            timer: 2000,
            showConfirmButton: false
        });
    </script>";
}
?>
</body>
</html>