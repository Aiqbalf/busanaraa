<?php
// session_start();
include('penting/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fname   = $_POST['fullname'];
  $email   = $_POST['emailid'];
  $mobile  = $_POST['mobileno'];
  $alamat  = $_POST['alamat'];
  $pass    = $_POST['pass'];
  $conf    = $_POST['conf'];

  if ($conf != $pass) {
    echo "<script>alert('Password tidak sama!');</script>";
  } else {
    $sqlcek = "SELECT email FROM member WHERE email='$email'";
    $querycek = mysqli_query($koneksidb, $sqlcek);
    if (mysqli_num_rows($querycek) > 0) {
      echo "<script>alert('Email sudah terdaftar, silahkan gunakan email lain!');</script>";
    } else {
      $password = md5($pass);
      $sql1 = "INSERT INTO member(nama_user,email,telp,password,alamat) 
               VALUES('$fname','$email','$mobile','$password','$alamat')";
      $lastInsertId = mysqli_query($koneksidb, $sql1);
      if ($lastInsertId) {
        echo "<script>alert('Registrasi berhasil. Sekarang anda bisa login.');</script>";
        echo "<script>window.location='login.php';</script>";
      } else {
        echo "<script>alert('Ops, terjadi kesalahan. Silahkan coba lagi.');</script>";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Registrasi Member - BUSANARA</title>

  <link rel="stylesheet" href="assets/css/register_style.css">
  <link rel="stylesheet" href="assets/css/custom-style.css" />
  <link rel="stylesheet" href="assets/css/footer_style.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="assets/js/custom-script.js" defer></script>
</head>

<body>
  <?php include('penting/header.php'); ?>

  <div class="register-container">
    <h2>Registrasi Member</h2>

    <form method="post" action="" onsubmit="return checkLetter(this);">
      <input type="text" name="fullname" placeholder="Nama Lengkap" required>
      <input type="number" name="mobileno" placeholder="Nomor Telepon" minlength="10" maxlength="15" required>
      <input type="email" id="emailid" name="emailid" placeholder="Alamat Email" onblur="checkAvailability()" required>
      <span id="user-availability-status"></span>
      <input type="text" name="alamat" placeholder="Alamat" required>
      <input type="password" id="pass" name="pass" placeholder="Password" required>
      <input type="password" id="conf" name="conf" placeholder="Konfirmasi Password" required>

      <label class="checkbox-container">
        <input type="checkbox" id="terms_agree" required checked>
        <span class="checkmark"></span>
        Saya setuju dengan <a href="#">Syarat dan Ketentuan</a>
      </label>

      <button type="submit" class="btn-register">Register</button>

      <p class="footer-link">
        Sudah punya akun? <a href="login.php">Login di sini</a>
      </p>
    </form>
  </div>

  <?php include('penting/footer.php'); ?>

  <script type="text/javascript">
  function checkLetter(theform) {
    let pola_nama = /^[a-zA-Z .]*$/;
    if (!pola_nama.test(theform.fullname.value)) {
      alert('Hanya huruf yang diperbolehkan untuk Nama!');
      theform.fullname.focus();
      return false;
    }

    if (theform.pass.value !== theform.conf.value) {
      alert('Password dan Konfirmasi Password tidak sama!');
      theform.conf.focus();
      return false;
    }

    return true;
  }

  function checkAvailability() {
    $("#loaderIcon").show();
    jQuery.ajax({
      url: "validasi_register.php",
      data: 'emailid=' + $("#emailid").val(),
      type: "POST",
      success: function (data) {
        $("#user-availability-status").html(data);
        $("#loaderIcon").hide();
      },
      error: function () { }
    });
  }
  </script>
</body>
</html>
