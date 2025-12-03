<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('config.php');
?>
<header class="site-header">

  <link rel="stylesheet" href="assets/css/header_style.css">

  <div class="wrapper header-inner">

    <!-- Brand -->
    <a href="/busanara/index.php" class="site-brand">BUSANARA</a>

    <!-- Toggle untuk mobile -->
    <button id="navToggle" class="nav-toggle" aria-label="Menu">&#9776;</button>

    <!-- Navigation -->
    <nav class="nav">
      <ul>
        <li><a href="/busanara/index.php">Home</a></li>

        <li class="has-dropdown">
          <a href="#">Baju Anak ▾</a>
          <ul class="dropdown">
            <li><a href="/busanara/katalog/bajuanakp.php">Perempuan</a></li>
            <li><a href="/busanara/katalog/bajuanakl.php">Laki-Laki</a></li>
          </ul>
        </li>

        <li class="has-dropdown">
          <a href="#">Baju Dewasa ▾</a>
          <ul class="dropdown">
            <li><a href="/busanara/katalog/bajudewasap.php">Perempuan</a></li>
            <li><a href="/busanara/katalog/bajudewasal.php">Laki-Laki</a></li>
          </ul>
        </li>

        <li><a href="/busanara/tentang_kami.php">Tentang Kami</a></li>
        <li><a href="/busanara/hubungi_kami.php">Hubungi Kami</a></li>
      </ul>
    </nav>

    <!-- User / Login -->
    <div class="user-area">
      <?php if (!empty($_SESSION['ulogin'])): ?>
        <?php
          $email = $_SESSION['ulogin'];
          $sql = "SELECT nama_user FROM member WHERE email='$email'";
          $query = mysqli_query($koneksidb, $sql);
          $nama = '';
          if ($query && mysqli_num_rows($query) > 0) {
            $results = mysqli_fetch_assoc($query);
            $nama = htmlentities($results['nama_user']);
          }
        ?>
        <div class="user-dropdown">
          <button class="user-btn"><?php echo $nama ?: 'User'; ?> ▾</button>
          <ul class="dropdown">
            <li><a href="/busanara/user_fitur/profile.php">Profile Settings</a></li>
            <li><a href="/busanara/user_fitur/update-password.php">Update Password</a></li>
            <li><a href="/busanara/user_fitur/riwayatsewa.php">Riwayat Sewa</a></li>
            <li><a href="/busanara/user_fitur/logout.php">Sign Out</a></li>
          </ul>
        </div>
      <?php else: ?>
        <a href="/busanara/login.php" class="btn btn-secondary" data-toggle="modal">Login / Register</a>
      <?php endif; ?>
    </div>

  </div>
</header>
