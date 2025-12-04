<?php
session_start();
include('../penting/config.php');
include('../penting/format_rupiah.php');
error_reporting(0);

$pagedesc = "Detail Baju";
$id = intval($_GET['id']);
$sql = "SELECT baju.*, jenis.* FROM baju, jenis WHERE baju.id_jenis=jenis.id_jenis AND baju.id_baju='$id'";
$query = mysqli_query($koneksidb, $sql);
$result = mysqli_fetch_array($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($pagedesc); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link rel="stylesheet" href="../assets/css/custom-style.css" />
  <link rel="stylesheet" href="../assets/css/header_style.css" />
  <link rel="stylesheet" href="../assets/css/footer_style.css" />
  <link rel="stylesheet" href="../assets/css/bajudetails_style.css">
</head>
<body>

<?php include('../penting/header.php'); ?>

<section class="baju-detail-container">

  <!-- ====== Gambar Produk ====== -->
  <div class="image-slider">
    <img src="../admin/img/<?= htmlentities($result['gambar1']); ?>" alt="">
    <img src="../admin/img/<?= htmlentities($result['gambar2']); ?>" alt="">
    <img src="../admin/img/<?= htmlentities($result['gambar3']); ?>" alt="">
  </div>

  <!-- ====== Info Produk ====== -->
  <div class="baju-info">
    <h2><?= htmlentities($result['nama_baju']); ?></h2>
    <p class="harga"><?= htmlentities(format_rupiah($result['harga'])); ?> <span>/ Hari</span></p>
  </div>

  <!-- ====== Deskripsi ====== -->
  <div class="baju-deskripsi">
    <button id="tabDeskripsi" class="tab aktif">Deskripsi Baju</button>
    <div id="kontenDeskripsi" class="konten-aktif">
      <p><?= nl2br(htmlentities($result['deskripsi'])); ?></p>
    </div>
  </div>

  <!-- ====== Sidebar ====== -->
  <aside class="sidebar">
    <h4>Sewa Sekarang</h4>
    <form method="get" action="/busanara/booking.php">
      <input type="hidden" name="id" value="<?= $id; ?>">
      <?php if ($_SESSION['ulogin']) { ?>
        <button type="submit" class="btn-sewa">Sewa Sekarang</button>
      <?php } else { ?>
        <a href="../login.php" class="btn-login">Login untuk Menyewa</a>
      <?php } ?>
    </form>
  </aside>

  <!-- ====== Produk Serupa ====== -->
  <div class="produk-serupa">
    <h3>Baju Lainnya</h3>
    <div class="produk-grid">
      <?php
      $sql1 = "SELECT * FROM baju WHERE id_baju!='$id' LIMIT 4";
      $query1 = mysqli_query($koneksidb, $sql1);
      while ($r = mysqli_fetch_array($query1)) { ?>
        <div class="produk-item">
          <img src="../admin/img/baju/<?= htmlentities($r['gambar1']); ?>" alt="">
          <h5><?= htmlentities($r['nama_baju']); ?></h5>
          <p><?= htmlentities(format_rupiah($r['harga'])); ?></p>
          <a href="baju_details.php?id=<?= $r['id_baju']; ?>" class="btn-lihat">Lihat Detail</a>
        </div>
      <?php } ?>
    </div>
  </div>

</section>

<?php include('../penting/footer.php'); ?>

<script src="../assets/js/bajudetails.js"></script>
</body>
</html>
