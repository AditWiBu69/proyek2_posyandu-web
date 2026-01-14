<?php
include_once("koneksi.php");

$statement = $conn->prepare('SELECT * FROM t_anak WHERE id_anak=:id_anak ');
$statement->execute([
  'id_anak' => $_GET['id_anak']
]);
$user = $statement->fetch(PDO::FETCH_ASSOC);
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
    <form action="update.php?id_anak=<?php echo $_GET['id_anak']; ?>" method="post">
      <div class="form-group">
        <label for="nama_anak">Nama Anak:</label>
        <input type="text" class="form-control" id="nama_anak" name="nama_anak" placeholder="Nama Anak"
          value="<?php echo $user['nama_anak']; ?>" required>
      </div>
      <div class="form-group">
        <label for="nama_ibu">Nama Ibu:</label>
        <input type="text" class="form-control" id="nama_ibu" name="nama_ibu" placeholder="Nama Ibu"
          value="<?php echo $user['nama_ibu']; ?>" required>
      </div>
      <div class="form-group">
        <label for="tempat_lahir">Tempat Lahir:</label>
        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" placeholder="Tempat Lahir"
          value="<?php echo $user['tempat_lahir']; ?>" required>
      </div>
      <div class="form-group">
        <label for="tanggal_lahir">Tanggal Lahir:</label>
        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
          value="<?php echo $user['tanggal_lahir']; ?>" required>
      </div>
      <div class="form-group">
        <label for="jenis_kelamin">Jenis Kelamin:</label>
        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
          <option value="Laki-laki" <?php if ($user['jenis_kelamin'] == 'Laki-laki')
            echo 'selected'; ?>>Laki-laki
          </option>
          <option value="Perempuan" <?php if ($user['jenis_kelamin'] == 'Perempuan')
            echo 'selected'; ?>>Perempuan
          </option>
        </select>
      </div>
      <div class="form-group">
        <label for="alamat">Alamat:</label>
        <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat"
          value="<?php echo $user['alamat']; ?>" required>
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
    </form>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <?php
  // Cek apakah ada parameter status=sukses di URL
  if (isset($_GET['status']) && $_GET['status'] == 'sukses') {
      echo "<script>
          Swal.fire({
              title: 'Berhasil!',
              text: 'Data anak berhasil diupdate!',
              icon: 'success',
              confirmButtonText: 'OK'
          }).then((result) => {
              // Opsional: Jika tombol OK diklik, pindah ke halaman data_anak.php
              if (result.isConfirmed) {
                  window.location.href = 'data_anak.php'; 
              }
          });
      </script>";
  }
  ?>
</body>

</html>