<!DOCTYPE html>
<html>

<head>
  <title>Data Penimbangan Anak</title>
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
  <?php
  $id_anak = $_GET["id_anak"];
  include_once("connection_pnb.php");
  ?>
  <div class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="navbar-logo">
      <img src="../img/Favicon.png" alt="Posyandu Logo">
    </div>
    <a class="navbar-brand" href="#">Posyandu</a>
    <ul class="navbar-menu navbar-nav ms-auto">
      <li class="nav-item"><a class="nav-link" href="kms.php?id_anak=<?php echo $id_anak; ?>">KMS </a></li>
      <li class="nav-item"><a class="nav-link" href="../data_anak.php">Kembali ke Data Anak</a></li>
    </ul>
  </div>

  <div class="container mt-4">
    <h2 class="title">Data Penimbangan</h2> 
    <table class="table">
      <thead class="table-dark">
        <tr>
          <th class="text-center">No</th>
          <th class="text-center">ID Anak</th>
          <th class="text-center">Tanggal Penimbangan</th>
          <th class="text-center">Umur</th>
          <th class="text-center">Berat Badan</th>
          <th class="text-center">Tinggi Badan</th>
          <th class="text-center">Keterangan</th>
          <th class="text-center">Petugas</th>
          <th class="text-center">Posyandu</th>
          <th class="text-center">Action</th>
        </tr>
      </thead>
      <?php $query = $conn->query("SELECT * FROM t_penimbangan WHERE id_anak = '$id_anak'"); ?>

      <?php if ($query->rowCount() > 0): ?>
        <?php
        $no = 1;
        foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $row):
          ?>
          <tr>
            <td class="text-center">
              <?php echo $no; ?>
            </td>
            
            <td class="text-center">
              <?php echo $row['id_anak']; ?>
            </td>
            <td class="text-center">
              <?php echo $row['tgl_penimbangan']; ?>
            </td>
            <td class="text-center">
              <?php echo $row['umur']; ?>
            </td>
            <td class="text-center">
              <?php echo $row['berat_badan']; ?>
            </td>
            <td class="text-center">
              <?php echo $row['tinggi_badan']; ?>
            </td>
            <td class="text-center">
              <?php echo $row['keterangan']; ?>
            </td>
            <td class="text-center">
              <?php echo $row['petugas']; ?>
            </td>
            <td class="text-center">
              <?php echo $row['posyandu']; ?>
            </td>
            <td class="text-center">
              <a href="edit_pnb.php?id_penimbangan=<?php echo $row['id_penimbangan']; ?>" class="btn btn-sm btn-primary">
                <i class="fas fa-pencil-alt"></i> 
              </a>
              <a href="delete_pnb.php?id_penimbangan=<?php echo $row['id_penimbangan']; ?>&id_anak=<?php echo $row['id_anak']; ?>"
                class="btn btn-sm btn-danger">
                <i class="fas fa-trash"></i> 
              </a>
            </td>
          </tr>
          <?php
          $no++; // Angka bertambah setiap baris
        endforeach;
        ?>
      <?php else: ?>
        <tr>
          <td colspan="10" class="empty-data text-center">Belum ada data penimbangan</td>
        </tr>
      <?php endif; ?>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="10"> </th>
        </tr>
      </tfoot>
    </table>
    <div class="action-links">
      <a href="kms.php?id_anak=<?php echo $id_anak; ?>" class="btn btn-info">Grafik KMS</a>
      <a href="create_pnb.php?id_anak=<?php echo $id_anak; ?>" class="btn btn-primary">+ Tambah Data Penimbangan</a>
    </div>
  </div>
</body>

</html>