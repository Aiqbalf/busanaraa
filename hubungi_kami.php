<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(isset($_POST['send']))
{
  $name = $_POST['fullname'];
  $email = $_POST['email'];
  $contactno = $_POST['contactno'];
  $message = $_POST['message'];

  $sql1 = "INSERT INTO contactus (nama_visit,email_visit,telp_visit,pesan) 
           VALUES('$name','$email','$contactno','$message')";
  $lastInsertId = mysqli_query($koneksidb, $sql1);

  if($lastInsertId){
    $msg="Pesan Terkirim. Kami akan menghubungi anda secepatnya.";
  } else {
    $error="Terjadi Kesalahan! Silahkan coba lagi.";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Hubungi Kami - Busanara</title>

  <link rel="stylesheet" href="assets/css/custom-style.css">
  <link rel="stylesheet" href="assets/css/hubungikami_style.css">
  <script src="assets/js/custom-script.js" defer></script>
</head>

<body>
  <?php include('penting/header.php'); ?>

  <section class="contact-page">
    <div class="container">
      <div class="form-section">
        <h2>Ada Pertanyaan? Silahkan Isi Form Berikut:</h2>
        <?php if($error){?><div class="errorWrap"><?php echo htmlentities($error); ?></div><?php } 
        else if($msg){?><div class="succWrap"><?php echo htmlentities($msg); ?></div><?php }?>

        <form method="post">
          <label>Nama <span>*</span></label>
          <input type="text" name="fullname" required>

          <label>Email <span>*</span></label>
          <input type="email" name="email" required>

          <label>No. Hp <span>*</span></label>
          <input type="text" name="contactno" required>

          <label>Pesan <span>*</span></label>
          <textarea name="message" rows="4" required></textarea>

          <button type="submit" name="send">Kirim ➤</button>
        </form>
      </div>

      <div class="info-section">
        <h2>Info Kontak</h2>
        <ul>
          <?php 
          $sql1 = "SELECT * FROM contactusinfo";
          $query1 = mysqli_query($koneksidb,$sql1);
          while($result = mysqli_fetch_array($query1)) { ?>
            <li>
              <i class="fa fa-map-marker"></i>
              <span><?php echo htmlentities($result['alamat_kami']); ?></span>
            </li>
            <li>
              <i class="fa fa-phone"></i>
              <span><?php echo htmlentities($result['telp_kami']); ?></span>
            </li>
            <li>
              <i class="fa fa-envelope-o"></i>
              <span><?php echo htmlentities($result['email_kami']); ?></span>
            </li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </section>

  <?php include('penting/footer.php'); ?>
</body>
</html>
