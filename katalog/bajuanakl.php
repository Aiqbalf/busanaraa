<?php
session_start();
include('../penting/config.php');
include('../penting/format_rupiah.php');
error_reporting(0);

$pagedesc = "Beranda - Penyewaan Baju";
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?= htmlspecialchars($pagedesc); ?></title>

  <link rel="stylesheet" href="../assets/css/footer_style.css" />
  <link rel="stylesheet" href="../assets/css/custom-style.css" />
  <link rel="stylesheet" href="../assets/css/header_style.css" />
  <link rel="stylesheet" href="../assets/css/categoribaju_style.css" />
</head>

<body>
  <?php include('../penting/header.php'); ?>

  <!-- Header Section -->
  <section class="page-header listing_page">
    <div class="container">
      <div class="page-header_wrap">
        <div class="page-heading">
          <h1>Daftar Baju Anak Laki-Laki</h1>
        </div>
        <ul class="coustom-breadcrumb">
          <li><a href="../index.php">Home</a></li>
          <li>Daftar Baju Anak Laki-Laki</li>
        </ul>
      </div>
    </div>
    <div class="dark-overlay"></div>
  </section>

  <!-- Listing Section -->
  <section class="listing-page">
    <div class="container">
      <div class="row">
        <!-- ========== DAFTAR PRODUK ========== -->
        <div class="col-md-9 col-md-push-3">
          <?php
          $sql = "SELECT baju.*, jenis.* 
                  FROM baju 
                  JOIN jenis ON baju.id_jenis = jenis.id_jenis 
                  WHERE kategori = 'Anak Laki-Laki'";
          $query = mysqli_query($koneksidb, $sql);
          $count = mysqli_num_rows($query);
          ?>

          <p><strong><?= $count; ?> Items</strong></p>

          <?php while ($result = mysqli_fetch_array($query)) : ?>
            <div class="product-listing-m gray-bg">
              <div class="product-listing-img">
                <img src="../admin/img/baju/<?= htmlentities($result['gambar1']); ?>" alt="Baju" />
              </div>
              <div class="product-listing-content">
                <h5><a href="baju_details.php?id=<?= htmlentities($result['id_baju']); ?>">
                  <?= htmlentities($result['nama_baju']); ?>
                </a></h5>
                <p class="list-price"><?= format_rupiah($result['harga']); ?> / Hari</p>
                <a href="baju_details.php?id=<?= htmlentities($result['id_baju']); ?>" class="btn">Lihat Detail</a>
              </div>
            </div>
          <?php endwhile; ?>
        </div>

        <!-- ========== SIDEBAR ========== -->
        <aside class="col-md-3 col-md-pull-9">
          <!-- Filter Baju -->
          <div class="sidebar_widget">
            <div class="widget_heading">
              <h5><i class="fa fa-filter" aria-hidden="true"></i> Cari Baju</h5>
            </div>
            <div class="sidebar_filter">
              <form action="bajuanakl_cari.php" method="post">
                <select name="jenis" required>
                  <option value="" selected>Pilih Jenis</option>
                  <?php
                  $jenisQuery = mysqli_query($koneksidb, "SELECT * FROM jenis");
                  while ($j = mysqli_fetch_array($jenisQuery)) :
                  ?>
                    <option value="<?= htmlentities($j['id_jenis']); ?>">
                      <?= htmlentities($j['nama_jenis']); ?>
                    </option>
                  <?php endwhile; ?>
                </select>
                <button type="submit" class="btn btn-block">Cari</button>
              </form>
            </div>
          </div>

          <!-- Baju Terbaru -->
          <div class="sidebar_widget">
            <div class="widget_heading">
              <h5><i class="fa fa-tshirt" aria-hidden="true"></i> Baju Terbaru</h5>
            </div>
            <div class="recent_addedcars">
              <ul>
                <?php
                $recent = mysqli_query($koneksidb, "
                  SELECT baju.*, jenis.* 
                  FROM baju 
                  JOIN jenis ON baju.id_jenis = jenis.id_jenis 
                  WHERE kategori = 'Anak Laki-Laki' 
                  ORDER BY id_baju DESC LIMIT 4");
                while ($r = mysqli_fetch_array($recent)) :
                ?>
                  <li class="gray-bg">
                    <div class="recent_post_img">
                      <a href="baju_details.php?id=<?= htmlentities($r['id_baju']); ?>">
                        <img src="../admin/img/baju/<?= htmlentities($r['gambar1']); ?>" alt="Baju">
                      </a>
                    </div>
                    <div class="recent_post_title">
                      <a href="baju_details.php?id=<?= htmlentities($r['id_baju']); ?>">
                        <?= htmlentities($r['nama_baju']); ?>
                      </a>
                      <p class="widget_price"><?= format_rupiah($r['harga']); ?> / Hari</p>
                    </div>
                  </li>
                <?php endwhile; ?>
              </ul>
            </div>
          </div>
        </aside>
      </div>
    </div>
  </section>

  <?php include('../penting/footer.php'); ?>
  <script src="../assets/js/custom-script.js"></script>
</body>
</html>
