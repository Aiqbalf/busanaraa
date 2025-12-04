<?php
session_start();
include('../penting/config.php');
include('../penting/format_rupiah.php');
error_reporting(0);

$pagedesc = "Baju Anak Laki-Laki - Penyewaan Baju";
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?= htmlspecialchars($pagedesc); ?></title>

  <link rel="stylesheet" href="../assets/css/custom-style.css" />
  <link rel="stylesheet" href="../assets/css/footer_style.css" />
  <link rel="stylesheet" href="../assets/css/header_style.css" />
  <link rel="stylesheet" href="../assets/css/bajucari_style.css">
</head>

<body>

<?php include('../penting/header.php'); ?>

<!-- Page Header -->
<section class="page-header">
  <div class="overlay"></div>
  <div class="header-content">
    <h1>Daftar Baju Dewasa Laki-Laki</h1>
    <ul class="breadcrumb">
      <li><a href="../index.php">Home</a></li>
      <li>Daftar Baju Dewasa Laki-Laki</li>
    </ul>
  </div>
</section>

<!-- Listing Section -->
<section class="listing-container">
  <div class="main-content">

    <div class="result-info">
      <?php 
      $jenis = $_POST['jenis'];
      $sql = "SELECT * FROM baju WHERE id_jenis='$jenis' AND kategori='Dewasa Laki-Laki'";
      $query = mysqli_query($koneksidb, $sql);
      $cnt = mysqli_num_rows($query);
      ?>
      <p><?= htmlentities($cnt); ?> Baju Ditemukan</p>
    </div>

    <?php 
    $sql1 = "SELECT baju.*,jenis.* FROM baju,jenis WHERE baju.id_jenis=jenis.id_jenis AND jenis.id_jenis='$jenis' AND baju.kategori='Dewasa Laki-Laki'";
    $query1 = mysqli_query($koneksidb,$sql1);
    if(mysqli_num_rows($query1)>0){
      while($result = mysqli_fetch_array($query1)){ 
    ?>
    <div class="product-card">
      <div class="product-image">
        <img src="../admin/img/<?= htmlentities($result['gambar1']); ?>" alt="Gambar Baju">
      </div>
      <div class="product-info">
        <h3><a href="baju_details.php?id=<?= htmlentities($result['id_baju']); ?>"><?= htmlentities($result['nama_baju']); ?></a></h3>
        <p class="price"><?= htmlentities(format_rupiah($result['harga'])); ?> / Hari</p>
        <a href="baju_details.php?id=<?= htmlentities($result['id_baju']); ?>" class="btn">Lihat Detail</a>
      </div>
    </div>
    <?php }} else { ?>
      <p class="no-result">Tidak ada baju ditemukan.</p>
    <?php } ?>
  </div>

  <!-- Sidebar -->
  <aside class="sidebar">

    <div class="sidebar-box">
      <h4>Cari Baju</h4>
      <form action="bajudewasal_cari.php" method="post">
        <select name="jenis" required>
          <option value="">Pilih Jenis</option>
          <?php 
          $sql3 = "SELECT * FROM jenis";
          $query3 = mysqli_query($koneksidb,$sql3);
          while($result = mysqli_fetch_array($query3)){ ?>
            <option value="<?= htmlentities($result['id_jenis']); ?>"><?= htmlentities($result['nama_jenis']); ?></option>
          <?php } ?>
        </select>
        <button type="submit" class="btn">Cari</button>
      </form>
    </div>

    <div class="sidebar-box">
      <h4>Baju Terbaru</h4>
      <ul class="recent-list">
      <?php
      $sql2 = "SELECT baju.*,jenis.* FROM baju,jenis 
              WHERE baju.id_jenis=jenis.id_jenis AND baju.kategori ='Dewasa Laki-Laki' 
              ORDER BY baju.id_baju DESC LIMIT 4";
      $query2 = mysqli_query($koneksidb,$sql2);
      while($result = mysqli_fetch_array($query2)){ ?>
        <li>
          <a href="baju_details.php?id=<?= htmlentities($result['id_baju']); ?>">
            <img src="../admin/img/baju/<?= htmlentities($result['gambar1']); ?>" alt="image">
            <div>
              <p class="recent-title"><?= htmlentities($result['nama_baju']); ?></p>
              <p class="recent-price"><?= htmlentities(format_rupiah($result['harga'])); ?> / Hari</p>
            </div>
          </a>
        </li>
      <?php } ?>
      </ul>
    </div>

  </aside>
</section>

<?php include('../penting/footer.php'); ?>
<script src="../assets/js/custom-script.js"></script>

</body>
</html>
