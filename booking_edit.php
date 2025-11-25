<?php
session_start();
error_reporting(0);
include('penting/config.php');
include('penting/format_rupiah.php');
include('penting/library.php');

if(strlen($_SESSION['ulogin'])==0){ 
  header('location:index.php');
  exit();
}

$kode = $_GET['kode'];
$sql1 = "SELECT booking.*, baju.*, jenis.* 
         FROM booking, baju, jenis 
         WHERE booking.id_baju=baju.id_baju 
         AND baju.id_jenis=jenis.id_jenis 
         AND booking.kode_booking='$kode'";
$query1 = mysqli_query($koneksidb, $sql1);
$result = mysqli_fetch_array($query1);

$harga = $result['harga'];
$durasi = $result['durasi'];
$totalsewa = $durasi * $harga;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Detail Sewa</title>

  <link rel="stylesheet" href="assets/css/custom-style.css">
  <link rel="stylesheet" href="assets/css/footer_style.css">
  <link rel="stylesheet" href="assets/css/bookingedit_style.css">
  <script src="assets/js/booking_edit.js" defer></script>
</head>
<body>

  <?php include('penting/header.php'); ?>

  <section class="detail-sewa-container">
    <h2>Detail Sewa</h2>
    <form method="post" action="user_fitur/update_sewa.php" enctype="multipart/form-data">
      <div class="form-group">
        <label>Kode Sewa</label>
        <input type="text" name="kode" value="<?php echo $result['kode_booking'];?>" readonly>
      </div>

      <div class="form-group">
        <label>Baju</label>
        <input type="text" name="baju" value="<?php echo $result['nama_baju'];?>" readonly>
      </div>

      <div class="form-group">
        <label>Tanggal Mulai</label>
        <input type="text" value="<?php echo date('d/m/Y', strtotime($result['tgl_mulai']));?>" readonly>
      </div>

      <div class="form-group">
        <label>Tanggal Selesai</label>
        <input type="text" value="<?php echo date('d/m/Y', strtotime($result['tgl_selesai']));?>" readonly>
      </div>

      <div class="form-group">
        <label>Durasi</label>
        <input type="text" value="<?php echo $durasi;?> Hari" readonly>
      </div>

      <div class="form-group">
        <label>Total Biaya Sewa (<?php echo $durasi;?> Hari)</label>
        <input type="text" value="<?php echo format_rupiah($totalsewa);?>" readonly>
      </div>

      <div class="form-group">
        <label>Upload Bukti Pembayaran</label>
        <input type="file" name="img1" accept="image/*" required>
      </div>

      <button type="submit" name="submit" class="submit-btn">Submit</button>
    </form>
  </section>

  <?php include('penting/footer.php'); ?>
</body>
</html>
