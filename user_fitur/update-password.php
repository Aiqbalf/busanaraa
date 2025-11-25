<?php
session_start();
error_reporting(0);
include('../penting/config.php');

if(strlen($_SESSION['ulogin'])==0){ 
  header('location:../index.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $passw = $_POST['pass'];
    $pass = md5($passw);
    $new = $_POST['new'];
    $confirm = $_POST['confirm'];
    $mail = $_POST['mail'];

    $sql = "SELECT * FROM member WHERE email='$mail' AND password='$pass'";
    $query = mysqli_query($koneksidb, $sql);

    if (mysqli_num_rows($query) == 1) {
        if ($confirm == $new) {
            $newpass = md5($new);
            $sqlup = "UPDATE member SET password='$newpass' WHERE email='$mail'";
            $queryup = mysqli_query($koneksidb, $sqlup);

            if ($queryup) {
                echo "<script>alert('Berhasil update password.'); document.location = 'update-password.php';</script>";
            } else {
                echo "<script>alert('Gagal update password!'); document.location = 'update-password.php';</script>";
            }
        } else {
            echo "<script>alert('Password baru dan konfirmasi tidak sama!'); document.location = 'update-password.php';</script>";
        }
    } else {
        echo "<script>alert('Password lama salah!'); document.location = 'update-password.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Update Password - Busanara</title>

  <link rel="stylesheet" href="../assets/css/custom-style.css">
  <link rel="stylesheet" href="../assets/css/footer_style.css">
  <link rel="stylesheet" href="../assets/css/header_style.css" />
  <link rel="stylesheet" href="../assets/css/update-pw_style.css">
  <script src="../assets/js/u-pw.js" defer></script>
</head>
<body>

  <?php include('../penting/header.php'); ?>

  <section class="update-password-section">
    <div class="update-container">
      <h2>Ubah Password</h2>

      <?php $mail=$_SESSION['ulogin']; ?>

      <form method="post" onsubmit="return validasiPassword()">
        <input type="hidden" name="mail" value="<?php echo $mail; ?>">

        <label>Password Sekarang</label>
        <input type="password" name="pass" id="pass" required>

        <label>Password Baru</label>
        <input type="password" name="new" id="new" required>

        <label>Konfirmasi Password</label>
        <input type="password" name="confirm" id="confirm" required>

        <button type="submit">Update Password →</button>
      </form>
    </div>
  </section>

  <?php include('../penting/footer.php'); ?>
</body>
</html>
