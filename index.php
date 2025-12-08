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
  <link rel="stylesheet" href="assets/css/tentangkami_style.css">


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
        <a href="#sewa" class="btn btn-primary">Selengkapnya <span class="arrow">➜</span></a>
      </div>
    </div>
  </section>

  <!-- Recent / Sewa Sekarang -->
  <section id="sewa" class="section section-products">
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
            $img = 'admin/img/' . htmlspecialchars($results['gambar1']);
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

  <section class="about-section">
    <div class="container">
      <h1>Tentang Busanara</h1>
      <p>
        <strong>Busanara</strong> adalah penyedia layanan penyewaan baju profesional yang berlokasi di Kabupaten Jember,
        Jawa Timur. Kami menyediakan berbagai macam busana untuk anak-anak hingga dewasa, baik untuk keperluan acara formal, pesta, wisuda, maupun pemotretan.
      </p>
      <p>
        Kami berkomitmen untuk memberikan pelayanan terbaik, dengan koleksi busana berkualitas dan harga yang terjangkau.
        Setiap pakaian kami jaga kebersihan dan kelayakannya agar pelanggan merasa nyaman dan percaya menggunakan layanan Busanara.
      </p>
      <p>
        Selain penyewaan offline, Busanara juga menyediakan layanan pemesanan secara daring untuk memudahkan pelanggan di mana pun berada.
      </p>
    </div>
  </section>

  <!-- Lokasi -->
  <section class="map-section">
    <div class="container">
      <h2>Lokasi Kami</h2>
      <p>Anda dapat mengunjungi Busanara di lokasi berikut:</p>
      <div class="map-container">
        <iframe src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3949.834653121113!2d113.77200599999999!3d-8.118312!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zOMKwMDcnMDUuOSJTIDExM8KwNDYnMTkuMiJF!5e0!3m2!1sid!2sid!4v1761924525888!5m2!1sid!2sid" 
          width="100%" 
          height="400" 
          style="border:0;" 
          allowfullscreen="" 
          loading="lazy" 
          referrerpolicy="no-referrer-when-downgrade"
          ></iframe>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <?php include('penting/footer.php'); ?>

  <!-- Custom scripts -->
  <script src="assets/js/custom-script.js"></script>
</body>
</html>
