<?php
session_start();
error_reporting(0);
include('../penting/config.php');

if(strlen($_SESSION['ulogin'])==0){ 
  header('location:../index.php');
  exit();
}

if(isset($_POST['updateprofile'])){
  $name=$_POST['fullname'];
  $mobileno=$_POST['mobilenumber'];
  $address=$_POST['address'];
  $email=$_POST['email'];

  $sql="UPDATE member SET nama_user='$name', telp='$mobileno', alamat='$address' WHERE email='$email'";
  $query = mysqli_query($koneksidb,$sql);

  if($query){
    $msg="Profil berhasil diperbarui.";
  }else{
    $msg="Terjadi kesalahan: ".mysqli_error($koneksidb);
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Profil Saya</title>

  <link rel="stylesheet" href="../assets/css/custom-style.css">
  <link rel="stylesheet" href="../assets/css/header_style.css" />
  <link rel="stylesheet" href="../assets/css/footer_style.css">
  <link rel="stylesheet" href="../assets/css/profile_style.css">
  <script src="../assets/js/profile.js" defer></script>
</head>

<body>
  <?php include('../penting/header.php'); ?>

  <section class="profile-section">
    <div class="profile-container">
      <h2>Profil Anda</h2>

      <?php 
      $useremail = $_SESSION['ulogin'];
      $sql = "SELECT * FROM member WHERE email='$useremail'";
      $query = mysqli_query($koneksidb, $sql);
      $result = mysqli_fetch_array($query);
      ?>

      <?php if(isset($msg)) echo "<div class='alert'>$msg</div>"; ?>

      <form method="post" name="theform" onsubmit="return validateForm(this)">
        <label>Nama</label>
        <input type="text" name="fullname" value="<?php echo htmlentities($result['nama_user']);?>" required>

        <label>Alamat Email</label>
        <input type="email" name="email" value="<?php echo htmlentities($result['email']);?>" readonly>

        <label>Telepon</label>
        <input type="number" name="mobilenumber" value="<?php echo htmlentities($result['telp']);?>" min="0" required>

        <label>Alamat</label>
        <textarea name="address" rows="4" required><?php echo htmlentities($result['alamat']);?></textarea>

        <button type="submit" name="updateprofile">
          Simpan Perubahan <span class="arrow">➜</span>
        </button>
      </form>
    </div>
  </section>

  <?php include('../penting/footer.php'); ?>
</body>
</html>
