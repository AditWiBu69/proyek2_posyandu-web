<!DOCTYPE html>
<html>

<head>
  <title>Data Penimbangan</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
  <div class="container">
    <h2 class="mt-4">Tambah Data Penimbangan</h2>
    <form action="add_pnb.php" method="post">
      <!-- <label for="id_anak">ID Anak:</label> -->
      <!-- <input type="text" name="id_anak" id="id_anak" placeholder="ID Anak" required><br /> -->
      <?php include_once("connection_pnb.php");
      $id_anak = $_GET['id_anak']; ?>
      <input type="hidden" name="id_anak" value="<?php echo $id_anak; ?>">
      <div class="form-group">
        <label for="tgl_penimbangan">Tanggal Penimbangan:</label>
        <input type="date" class="form-control" name="tgl_penimbangan" id="tgl_penimbangan" required>
      </div>
      <div class="form-group">
        <label for="umur">Umur:</label>
        <input type="text" class="form-control" name="umur" id="umur" placeholder="Umur" required>
      </div>
      <div class="form-group">
        <label for="berat_badan">Berat Badan:</label>
        <input type="text" class="form-control" name="berat_badan" id="berat_badan" placeholder="Berat Badan" required>
      </div>
      <div class="form-group">
        <label for="tinggi_badan">Tinggi Badan:</label>
        <input type="text" class="form-control" name="tinggi_badan" id="tinggi_badan" placeholder="Tinggi Badan"
          required>
      </div>
      <div class="form-group">
        <label for="keterangan">Keterangan:</label>
        <input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan" required>
      </div>
      <div class="form-group">
        <label for="petugas">Petugas:</label>
        <input type="text" class="form-control" name="petugas" id="petugas" placeholder="Petugas" required>
      </div>
      <div class="form-group">
        <label for="posyandu">Posyandu:</label>
        <input type="text" class="form-control" name="posyandu" id="posyandu" placeholder="Posyandu" required>
      </div>
      <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
  </div>
</body>

</html>