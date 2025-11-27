<?php
session_start();
error_reporting(0);
include('penting/config.php');

if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

$pagedesc = "Dashboard Administrator";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pagedesc; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/dashboard.css">
	<link rel="stylesheet" href="assets/css/leftbar.css">
</head>

<body>

<?php include('includes/header.php'); ?>

<div class="dashboard-layout">

    <!-- SIDEBAR KIRI -->
    <?php include('penting/leftbar.php'); ?>

    <!-- KONTEN -->
    <div class="dashboard-content">

        <div class="page-header">
            <h1>Dashboard</h1>
            <p>Ringkasan sistem sewa baju Busanara</p>
        </div>

        <?php
        $bayar  = mysqli_num_rows(mysqli_query($koneksidb, "SELECT * FROM booking WHERE status='Menunggu Pembayaran'"));
        $konfir = mysqli_num_rows(mysqli_query($koneksidb, "SELECT * FROM booking WHERE status='Menunggu Konfirmasi'"));
        $belum  = mysqli_num_rows(mysqli_query($koneksidb, "SELECT * FROM booking WHERE status='Sudah Dibayar'"));
        $jenis  = mysqli_num_rows(mysqli_query($koneksidb, "SELECT * FROM jenis"));
        $baju   = mysqli_num_rows(mysqli_query($koneksidb, "SELECT * FROM baju"));
        $sewa   = mysqli_num_rows(mysqli_query($koneksidb, "SELECT * FROM booking"));
        $member = mysqli_num_rows(mysqli_query($koneksidb, "SELECT * FROM member"));
        $kontak = mysqli_num_rows(mysqli_query($koneksidb, "SELECT * FROM contactus"));
        ?>

        <div class="card-grid">

            <div class="stat-card warning">
                <div class="stat-text">
                    <span>Menunggu Pembayaran</span>
                    <h2><?php echo $bayar; ?></h2>
                    <a href="sewa_bayar.php">Lihat Rincian</a>
                </div>
                <div class="stat-icon">⏱</div>
            </div>

            <div class="stat-card info">
                <div class="stat-text">
                    <span>Menunggu Konfirmasi</span>
                    <h2><?php echo $konfir; ?></h2>
                    <a href="sewa_konfirmasi.php">Lihat Rincian</a>
                </div>
                <div class="stat-icon">✔</div>
            </div>

            <div class="stat-card orange">
                <div class="stat-text">
                    <span>Belum Dikembalikan</span>
                    <h2><?php echo $belum; ?></h2>
                    <a href="sewa_kembali.php">Lihat Rincian</a>
                </div>
                <div class="stat-icon">📦</div>
            </div>

            <div class="stat-card purple">
                <div class="stat-text">
                    <span>Total Jenis Baju</span>
                    <h2><?php echo $jenis; ?></h2>
                    <a href="jenis.php">Lihat Rincian</a>
                </div>
                <div class="stat-icon">👕</div>
            </div>

            <div class="stat-card success">
                <div class="stat-text">
                    <span>Jumlah Baju</span>
                    <h2><?php echo $baju; ?></h2>
                    <a href="baju.php">Lihat Rincian</a>
                </div>
                <div class="stat-icon">📊</div>
            </div>

            <div class="stat-card primary">
                <div class="stat-text">
                    <span>Total Sewa</span>
                    <h2><?php echo $sewa; ?></h2>
                    <a href="sewa.php">Lihat Rincian</a>
                </div>
                <div class="stat-icon">🧾</div>
            </div>

            <div class="stat-card blue">
                <div class="stat-text">
                    <span>Jumlah Member</span>
                    <h2><?php echo $member; ?></h2>
                    <a href="reg-users.php">Lihat Rincian</a>
                </div>
                <div class="stat-icon">👤</div>
            </div>

            <div class="stat-card gray">
                <div class="stat-text">
                    <span>Menghubungi</span>
                    <h2><?php echo $kontak; ?></h2>
                    <a href="manage-conactusquery.php">Lihat Rincian</a>
                </div>
                <div class="stat-icon">✉</div>
            </div>

        </div>

    </div>

</div>

</body>
</html>
