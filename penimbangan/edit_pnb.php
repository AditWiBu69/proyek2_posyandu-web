<!DOCTYPE html>
<html>

<head>
  <title>Edit Data Penimbangan</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
  <?php
  include_once("connection_pnb.php");

  // Ambil id_penimbangan dari URL
  $id_penimbangan = $_GET['id_penimbangan'];

  // Periksa apakah id_penimbangan valid
  $statement = $conn->prepare('SELECT * FROM t_penimbangan WHERE id_penimbangan = :id_penimbangan');
  $statement->execute(['id_penimbangan' => $id_penimbangan]);
  $data_penimbangan = $statement->fetch(PDO::FETCH_ASSOC);

  if (!$data_penimbangan) {
    // Data penimbangan tidak ditemukan, mungkin id_penimbangan tidak valid
    echo "Data penimbangan tidak ditemukan.";
    exit;
  }
  ?>
  <div class="container">
    <h2 class="mt-4">Edit Data Penimbangan</h2>
    <form action="update_pnb.php?id_penimbangan=<?php echo $id_penimbangan; ?>" method="post">
      <input type="hidden" name="id_anak" value="<?php echo $data_penimbangan['id_anak']; ?>">
      <div class="form-group">
        <label for="tgl_penimbangan">Tanggal Penimbangan:</label>
        <input type="date" class="form-control" name="tgl_penimbangan"
          value="<?php echo $data_penimbangan['tgl_penimbangan']; ?>">
      </div>
      <div class="form-group">
        <label for="umur">Umur:</label>
        <input type="text" class="form-control" name="umur" value="<?php echo $data_penimbangan['umur']; ?>">
      </div>
      <div class="form-group">
        <label for="berat_badan">Berat Badan:</label>
        <input type="text" class="form-control" name="berat_badan"
          value="<?php echo $data_penimbangan['berat_badan']; ?>">
      </div>
      <div class="form-group">
        <label for="tinggi_badan">Tinggi Badan:</label>
        <input type="text" class="form-control" name="tinggi_badan"
          value="<?php echo $data_penimbangan['tinggi_badan']; ?>">
      </div>
      <div class="form-group">
        <label for="keterangan">Keterangan:</label>
        <input type="text" class="form-control" name="keterangan"
          value="<?php echo $data_penimbangan['keterangan']; ?>">
      </div>
      <div class="form-group">
        <label for="petugas">Petugas:</label>
        <input type="text" class="form-control" name="petugas" value="<?php echo $data_penimbangan['petugas']; ?>">
      </div>
      <div class="form-group">
        <label for="posyandu">Posyandu:</label>
        <input type="text" class="form-control" name="posyandu" value="<?php echo $data_penimbangan['posyandu']; ?>">
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
    </form>
  </div>
</body>

</html>