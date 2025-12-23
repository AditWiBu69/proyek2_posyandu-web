<?php
session_start();

// Konfigurasi Koneksi (Sama seperti sebelumnya)
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "dbsipograf1";
$port = 3307;

$koneksi = mysqli_connect($host, $user, $pass, $db, $port);

// Cek Keamanan Admin
// (Sesuaikan kondisi ini dengan kolom role di tabel user Anda, misal: 'admin')
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
// Optional: Tambahkan pengecekan if($_SESSION['role'] != 'admin') { ... }

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Jadwal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">

    <?php if (isset($_GET['pesan'])): ?>
        <?php if ($_GET['pesan'] == 'sukses'): ?>
            <script>Swal.fire('Berhasil!', 'Data jadwal berhasil disimpan.', 'success');</script>
        <?php elseif ($_GET['pesan'] == 'hapus'): ?>
            <script>Swal.fire('Berhasil!', 'Data jadwal berhasil dihapus.', 'success');</script>
        <?php elseif ($_GET['pesan'] == 'gagal'): ?>
            <script>Swal.fire('Gagal!', 'Terjadi kesalahan sistem.', 'error');</script>
        <?php endif; ?>
    <?php endif; ?>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary">📅 Kelola Jadwal Posyandu</h2>
            <a href="admin_lihat_pendaftar.php" class="btn btn-success ms-2">
    👥 Lihat Peserta Terdaftar
</a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalJadwal" onclick="resetForm()">
                + Tambah Jadwal Baru
            </button>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Kegiatan</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Tempat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query = mysqli_query($koneksi, "SELECT * FROM t_jadwal ORDER BY tanggal DESC");
                            
                            if (mysqli_num_rows($query) > 0) {
                                while ($row = mysqli_fetch_assoc($query)) {
                                    $tgl = date('d-m-Y', strtotime($row['tanggal']));
                                    ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($row['nama_kegiatan']); ?></td>
                                        <td><?= $tgl; ?></td>
                                        <td><?= htmlspecialchars($row['waktu']); ?></td>
                                        <td><?= htmlspecialchars($row['tempat']); ?></td>
                                        <td>
                                            <button class="btn btn-warning btn-sm text-white" 
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
                                                ✏️ Edit
                                            </button>
                                            
                                            <a href="proses_admin_jadwal.php?aksi=hapus&id=<?= $row['id_jadwal']; ?>" 
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Yakin ingin menghapus jadwal ini? Data pendaftar juga mungkin akan hilang.')">
                                                🗑️ Hapus
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center'>Tidak ada data jadwal.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <a href="data_anak.php" class="btn btn-secondary mt-3">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalJadwal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="proses_admin_jadwal.php" method="POST">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="modalTitle">Tambah Jadwal</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_jadwal" id="id_jadwal">
                        
                        <input type="hidden" name="aksi_form" id="aksi_form" value="tambah">

                        <div class="mb-3">
                            <label class="form-label">Nama Kegiatan</label>
                            <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Waktu</label>
                                <input type="text" name="waktu" id="waktu" class="form-control" placeholder="Contoh: 08:00 - 11:00" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tempat / Lokasi</label>
                            <input type="text" name="tempat" id="tempat" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi Singkat</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Fungsi saat tombol Tambah ditekan (Reset Form)
        function resetForm() {
            document.getElementById('modalTitle').innerText = "Tambah Jadwal Baru";
            document.getElementById('aksi_form').value = "tambah";
            document.getElementById('id_jadwal').value = "";
            
            document.getElementById('nama_kegiatan').value = "";
            document.getElementById('tanggal').value = "";
            document.getElementById('waktu').value = "";
            document.getElementById('tempat').value = "";
            document.getElementById('deskripsi').value = "";
        }

        // Fungsi saat tombol Edit ditekan (Isi Form)
        function editData(id, nama, tgl, waktu, tempat, deskripsi) {
            document.getElementById('modalTitle').innerText = "Edit Jadwal";
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