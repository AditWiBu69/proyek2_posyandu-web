<!DOCTYPE html>
<html>
<?php
// Masukkan koneksi di paling atas
include "connection.php"; 

// Cek apakah $conn berhasil dikenali
if (!isset($conn)) {
    die("Error: File connection.php berhasil di-include, tapi variabel \$conn tidak ditemukan. Cek nama variabel di connection.php");
}
?>
<head>
  <title>Data Anak Posyandu</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    .navbar-logo img {
      width: 50px;
      margin-right: 10px;
    }

    .navbar a {
      text-decoration: none;
    }

    .title {
      text-align: center;
      margin-top: 20px;
      font-size: 26px;
      margin-bottom: 20px;
    }
  </style>
</head>

<body>
  <?php include_once("connection.php"); ?>
  <div class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="navbar-logo">
      <img src="img/Favicon.png" alt="Posyandu Logo">
    </div>
    <a class="navbar-brand" href="#">Posyandu</a>
    <ul class="navbar-menu navbar-nav ms-auto">
      <li class="nav-item"><a class="nav-link" href="data_anak.php">Data Anak </a></li>
      <li class="nav-item"><a class="nav-link" href="index.html">Keluar </a></li>
    </ul>
  </div>

  <div class="container mt-4">
    <h2 class="title">Data Anak Posyandu</h2>
    <table class="table">
      <thead class="table-dark">
        <tr>
          <th class="text-center">No</th> 
          <th class="text-center">Nama Anak</th>
          <th class="text-center">Nama Ibu</th>
          <th class="text-center">Tempat Lahir</th>
          <th class="text-center">Tanggal Lahir</th>
          <th class="text-center">Jenis Kelamin</th>
          <th class="text-center">Alamat</th>
          <th class="text-center">Action</th>
          <th class="text-center">Data Penimbangan</th>
        </tr>
      </thead>
      <?php $query = $conn->query('SELECT * FROM t_anak'); ?>

      <?php if ($query->rowCount() > 0): ?>
        <?php
        $no = 1; // Inisialisasi nomor urut
        foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $row):
          ?>
          <tr>
            <td class="text-center">
              <?php echo $no; ?>
            </td>
            
            <td class="text-center">
              <?php echo $row['nama_anak']; ?>
            </td>
            <td class="text-center">
              <?php echo $row['nama_ibu']; ?>
            </td>
            <td class="text-center">
              <?php echo $row['tempat_lahir']; ?>
            </td>
            <td class="text-center">
              <?php echo $row['tanggal_lahir']; ?>
            </td>
            <td class="text-center">
              <?php echo $row['jenis_kelamin']; ?>
            </td>
            <td class="text-center">
              <?php echo $row['alamat']; ?>
            </td>
            <td class="text-center">
              <a href="edit.php?id_anak=<?php echo $row['id_anak']; ?>" class="btn btn-sm btn-primary">
                <i class="fas fa-pencil-alt"></i>
              </a>
              <a href="delete.php?id_anak=<?php echo $row['id_anak']; ?>" class="btn btn-sm btn-danger">
                <i class="fas fa-trash"></i>
              </a>
            </td>
            <td class="text-center">
              <a href="penimbangan/data_penimbangan.php?id_anak=<?php echo $row['id_anak']; ?>"
                class="btn btn-sm btn-info">Pilih</a>
            </td>
          </tr>
          <?php
          $no++; // Increment nomor urut untuk baris selanjutnya
        endforeach;
        ?>
      <?php else: ?>
        <tr>
          <td colspan="9" class="empty-data text-center">Belum ada data anak</td>
        </tr>
      <?php endif; ?>
    </table>
  </div>

  <div class="container mt-3">
    <div class="action-links">
      <a href="create.php" class="btn btn-primary">+ Add Data Anak</a>
    </div>
  </div>
</body>

</html>