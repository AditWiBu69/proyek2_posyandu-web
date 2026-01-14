<?php
// Pastikan file koneksi sudah ada
include 'koneksi.php'; 
?>

<!DOCTYPE html>
<html>
<head>
  <title>Tambah Data Anak</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
  <div class="container">
    <h2 class="mt-4">Tambah Data Anak</h2>
    <form action="add.php" method="post">
      
      <div class="form-group">
        <label for="nama_anak">Nama Anak:</label>
        <input type="text" class="form-control" id="nama_anak" name="nama_anak" placeholder="Nama Anak" required>
      </div>

      <div class="form-group">
        <label for="id_orangtua">Nama Ibu:</label>
        <select class="form-control" id="id_orangtua" name="id_orangtua" required>
            <option value="">-- Pilih Nama Ibu --</option>
            <?php
            // Mengambil data dari tabel t_orangtua
            // Asumsi menggunakan PDO (sesuai kode Anda sebelumnya)
            if (isset($conn)) {
             $sql_ortu = $conn->query("SELECT * FROM t_orangtua ORDER BY nama_ibu ASC");
               while ($row_ortu = $sql_ortu->fetch_assoc()) {
                       echo "<option value='" . $row_ortu['id_orangtua'] . "'>" 
                       . $row_ortu['nama_ibu'] . " (Ayah: " . $row_ortu['nama_ayah'] . ")</option>";
                        }
                        } else {
                           echo "<option value=''>Koneksi database gagal</option>";
                                        }
?>

        </select>
        <small class="form-text text-muted">Data ibu diambil dari Data Orang Tua.</small>
      </div>

      <div class="form-group">
        <label for="tempat_lahir">Tempat Lahir:</label>
        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" placeholder="Tempat Lahir" required>
      </div>

      <div class="form-group">
        <label for="tanggal_lahir">Tanggal Lahir:</label>
        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
      </div>

      <div class="form-group">
        <label for="jenis_kelamin">Jenis Kelamin:</label>
        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
          <option value="Laki-laki">Laki-laki</option>
          <option value="Perempuan">Perempuan</option>
        </select>
      </div>

      <div class="form-group">
        <label for="alamat">Alamat:</label>
        <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat" required>
      </div>

      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="data_anak.php" class="btn btn-secondary">Kembali</a>
    </form>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>