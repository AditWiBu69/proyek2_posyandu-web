<?php
// --- BAGIAN 1: PHP & KONEKSI ---
include_once("koneksi.php");

// Cek apakah ada ID di URL
if (isset($_GET['id_anak'])) {
    $id_anak = $_GET['id_anak'];

    // --- PERUBAHAN PENTING DI SINI ---
    // Kita gunakan JOIN untuk mengambil nama ibu dari tabel t_orangtua
    // Pastikan nama tabel 't_orangtua' sesuai dengan database Anda
    $query  = "SELECT t_anak.*, t_orangtua.nama_ibu 
               FROM t_anak 
               LEFT JOIN t_orangtua ON t_anak.id_orangtua = t_orangtua.id_orangtua 
               WHERE t_anak.id_anak = '$id_anak'";
    
    $result = mysqli_query($koneksi, $query);

    // Cek error query
    if (!$result) {
        die("Query Error: " . mysqli_error($koneksi));
    }

    $data_anak = mysqli_fetch_assoc($result);

    if (!$data_anak) {
        die("Data anak tidak ditemukan.");
    }

} else {
    die("ID Anak tidak disertakan di URL.");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Data Anak</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  
  <div class="container">
    <h2 class="mt-4">Edit Data Anak</h2>
    
    <form action="update.php?id_anak=<?php echo $id_anak; ?>" method="post">
      
      <div class="form-group">
        <label>Nama Anak:</label>
        <input type="text" class="form-control" name="nama_anak" 
               value="<?php echo $data_anak['nama_anak']; ?>" required>
      </div>

      <div class="form-group">
        <label>Nama Ibu:</label>
        <input type="text" class="form-control" 
               value="<?php echo $data_anak['nama_ibu']; ?>" readonly>
        
        <input type="hidden" name="id_orangtua" value="<?php echo $data_anak['id_orangtua']; ?>">
      </div>

      <div class="form-group">
        <label>Tempat Lahir:</label>
        <input type="text" class="form-control" name="tempat_lahir" 
               value="<?php echo $data_anak['tempat_lahir']; ?>" required>
      </div>

      <div class="form-group">
        <label>Tanggal Lahir:</label>
        <input type="date" class="form-control" name="tanggal_lahir"
               value="<?php echo $data_anak['tanggal_lahir']; ?>" required>
      </div>

      <div class="form-group">
        <label>Jenis Kelamin:</label>
        <select class="form-control" name="jenis_kelamin" required>
          <option value="Laki-laki" <?php echo ($data_anak['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
          <option value="Perempuan" <?php echo ($data_anak['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
        </select>
      </div>

      <div class="form-group">
        <label>Alamat:</label>
        <input type="text" class="form-control" name="alamat" 
               value="<?php echo $data_anak['alamat']; ?>" required>
      </div>

      <button type="submit" class="btn btn-primary">Update</button>
      <a href="data_anak.php" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</body>
</html>