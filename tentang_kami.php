<?php
session_start();
include('penting/config.php');
error_reporting(0);
$pagedesc = "Tentang Kami - Busanara";
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?php echo htmlspecialchars($pagedesc); ?></title>

  <link rel="stylesheet" href="assets/css/custom-style.css">
  <link rel="stylesheet" href="assets/css/tentangkami_style.css">
</head>

<body>
  <!-- Header -->
  <?php include('penting/header.php'); ?>

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
</body>

</html>