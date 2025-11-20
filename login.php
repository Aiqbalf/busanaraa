<?php
session_start();
include('penting/config.php');
error_reporting(0);
$pagedesc = "Login - Penyewaan Baju";

if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = md5($_POST['password']);
  $sql = "SELECT * FROM member WHERE email='$email' AND password='$password'";
  $query = mysqli_query($koneksidb, $sql);
  $results = mysqli_fetch_array($query);
  if (mysqli_num_rows($query) > 0) {
    $_SESSION['ulogin'] = $_POST['email'];
    $_SESSION['fname'] = $results['nama_user'];
    echo "<script>alert('Login Berhasil, Silahkan lakukan transaksi');</script>";
    echo "<script>document.location='index.php';</script>";
  } else {
    echo "<script>alert('Email atau Password Salah!');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo htmlspecialchars($pagedesc); ?></title>

  <!-- Custom global styles -->
  <link rel="stylesheet" href="assets/css/custom-style.css">

  <!-- Login page style -->
  <link rel="stylesheet" href="assets/css/login_style.css">
</head>

<body>

  <!-- Header -->
  <?php include('penting/header.php'); ?>

  <!-- Login Form -->
  <div class="login-page">
    <div class="login-container">
      <h2>Login</h2>
      <form method="post">
        <div class="form-group">
          <label for="email">Alamat Email</label>
          <input type="email" id="email" name="email" placeholder="Masukkan email" required>
        </div>

        <div class="form-group">
          <label for="password">Kata Sandi</label>
          <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" required>
        </div>

        <button type="submit" name="login">Login</button>
      </form>

      <p class="register-link">
        Belum punya akun? <a href="register.php">Daftar Disini</a>
      </p>
    </div>
  </div>

  <!-- Footer -->
  <?php include('penting/footer.php'); ?>

</body>
</html>