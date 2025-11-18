<?php
session_start();
include('penting/config.php');
include('penting/format_rupiah.php');
error_reporting(0);
$pagedesc = "Beranda - Penyewaan Baju";
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?php echo htmlspecialchars($pagedesc); ?></title>

  <!-- Custom styles  -->
  <link rel="stylesheet" href="assets/css/custom-style.css" />


</head>
<body>

  <!-- Header (include) -->
  <?php include('penting/header.php'); ?>
  
  <!-- Banner -->
  <section id="banner" class="banner-section">
    <div class="banner-overlay"></div>
    <div class="banner-inner wrapper">
      <div class="banner-image" aria-hidden="true">
        <img src="assets/images/banner-image.png" alt="Banner" class="banner-img">
      </div>
      <div class="banner-content">
        <h1>Cari Baju untuk acara spesial Anda?</h1>
        <p>Kami punya beberapa pilihan untuk Anda.</p>
        <a href="bajuanakp.php" class="btn btn-primary">Selengkapnya <span class="arrow">➜</span></a>
      </div>
    </div>
  </section>

  <!-- Recent / Sewa Sekarang -->
  <section class="section section-products">
    <div class="wrapper">
      <div class="section-header">
        <h2>Sewa Sekarang!</h2>
        <p class="muted">Pilih baju yang cocok untuk acara Anda.</p>
      </div>

      <div class="products-grid" id="productsGrid">
        <?php
        $sql = "SELECT baju.*, jenis.* FROM baju JOIN jenis ON baju.id_jenis = jenis.id_jenis ORDER BY baju.id_baju DESC";
        $query = mysqli_query($koneksidb, $sql);
        if ($query && mysqli_num_rows($query) > 0) {
          while ($results = mysqli_fetch_assoc($query)) {
            $img = 'admin/img/baju/' . htmlspecialchars($results['gambar1']);
            $nama = htmlspecialchars($results['nama_baju']);
            $id = (int)$results['id_baju'];
            $harga = htmlspecialchars(format_rupiah($results['harga']));
            $des = htmlspecialchars(substr($results['deskripsi'], 0, 120));
        ?>
          <article class="product-card">
            <a class="product-thumb" href="katalog/baju_details.php?id=<?php echo $id; ?>">
              <img src="<?php echo $img; ?>" alt="<?php echo $nama; ?>" loading="lazy" />
            </a>
            <div class="product-body">
              <h3 class="product-title"><a href="katalog/baju_details.php?id=<?php echo $id; ?>"><?php echo $nama; ?></a></h3>
              <p class="product-price"><?php echo $harga; ?> / Hari</p>
              <p class="product-desc"><?php echo $des; ?></p>
              <div class="product-actions">
                <a class="btn btn-secondary" href="katalog/baju_details.php?id=<?php echo $id; ?>">Detail</a>
                <a class="btn btn-primary" href="katalog/baju_details.php?id=<?php echo $id; ?>">Sewa</a>
              </div>
            </div>
          </article>
        <?php
          }
        } else {
          echo '<p class="info">Belum ada data baju.</p>';
        }
        ?>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <?php include('penting/footer.php'); ?>

  <!-- Custom scripts -->
  <script src="assets/js/custom-script.js"></script>
</body>
</html>
