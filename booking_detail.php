<?php
session_start();
error_reporting(0);
include('penting/config.php');
include('penting/format_rupiah.php');
include('penting/library.php');
if(strlen($_SESSION['ulogin'])==0){ 
	header('location:index.php');
	exit();
}

$kode = $_GET['kode'];
$sql = "SELECT booking.*, baju.*, jenis.* 
		FROM booking 
		JOIN baju ON booking.id_baju = baju.id_baju 
		JOIN jenis ON baju.id_jenis = jenis.id_jenis 
		WHERE booking.kode_booking = '$kode'";
$query = mysqli_query($koneksidb, $sql);
$result = mysqli_fetch_array($query);

$harga = $result['harga'];
$durasi = $result['durasi'];
$total = $durasi * $harga;

$tglmulai = strtotime($result['tgl_mulai']);
$tglbatas = date("Y-m-d", $tglmulai - 86400);

?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<title>Detail Sewa - Busanara</title>

	<link rel="stylesheet" href="assets/css/costum-style.css">
	<link rel="stylesheet" href="assets/css/bookingdetail_style.css">
	<script src="assets/js/custom-script.js" defer></script>
</head>
<body>

<?php include('penting/header.php'); ?>

<section class="detail-container">
	<h2>Detail Sewa</h2>
	<div class="detail-box">
		<div class="form-group">
			<label>Kode Sewa</label>
			<input type="text" readonly value="<?php echo htmlentities($result['kode_booking']); ?>">
		</div>

		<div class="form-group">
			<label>Baju</label>
			<input type="text" readonly value="<?php echo htmlentities($result['nama_baju']); ?>">
		</div>

		<div class="form-group">
			<label>Tanggal Mulai</label>
			<input type="text" readonly value="<?php echo htmlentities(IndonesiaTgl($result['tgl_mulai'])); ?>">
		</div>

		<div class="form-group">
			<label>Tanggal Selesai</label>
			<input type="text" readonly value="<?php echo htmlentities(IndonesiaTgl($result['tgl_selesai'])); ?>">
		</div>

		<div class="form-group">
			<label>Durasi</label>
			<input type="text" readonly value="<?php echo $durasi . ' Hari'; ?>">
		</div>

		<div class="form-group">
			<label>Biaya Sewa (<?php echo $durasi; ?> Hari)</label>
			<input type="text" readonly value="<?php echo format_rupiah($total); ?>">
		</div>

		<?php 
		if($result['status']=="Menunggu Pembayaran"){
			$sqlrek = "SELECT * FROM tblpages WHERE id='5'";
			$queryrek = mysqli_query($koneksidb, $sqlrek);
			$resultrek = mysqli_fetch_array($queryrek);
		?>
		<p class="note">
			*Silahkan transfer total biaya sewa ke <?php echo $resultrek['detail']; ?> 
			maksimal tanggal <?php echo IndonesiaTgl($tglbatas); ?>.
		</p>
		<?php } ?>

		<button class="btn-cetak" onclick="window.open('detail_cetak.php?kode=<?php echo $kode; ?>','_blank')">
			Cetak
		</button>
	</div>
</section>

<?php include('penting/footer.php'); ?>

</body>
</html>
