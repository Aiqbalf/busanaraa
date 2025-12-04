<?php
session_start();
error_reporting(0);
include('penting/config.php');
include('penting/format_rupiah.php');

if(strlen($_SESSION['ulogin'])==0){ 
	header('location:index.php');
}else{
	$tglnow   = date('Y-m-d');
	$tglmulai = strtotime($tglnow);
	$jmlhari  = 86400*1;
	$tglplus  = $tglmulai+$jmlhari;
	$now = date("Y-m-d",$tglplus);

	if(isset($_POST['submit'])){
		$fromdate=$_POST['fromdate'];
		$todate=$_POST['todate'];
		$id=$_POST['id'];
		$pickup=$_POST['pickup'];
		$ukuran=$_POST['ukuran'];

		$sql 	= "SELECT kode_booking FROM cek_booking 
					WHERE tgl_booking BETWEEN '$fromdate' AND '$todate' 
					AND id_baju='$id' AND ukuran='$ukuran' AND status!='Cancel'";
		$query 	= mysqli_query($koneksidb,$sql);
		$tersewa = mysqli_num_rows($query);

		if($tersewa>0){
			$sql2 	= "SELECT * FROM baju WHERE id_baju='$id'";
			$query2 = mysqli_query($koneksidb,$sql2);
			$res2 	= mysqli_fetch_array($query2);
			$stok1 	= $res2[$ukuran];

			if($tersewa<$stok1){
				echo "<script>document.location = 'booking_ready.php?id=$id&mulai=$fromdate&selesai=$todate&pickup=$pickup&size=$ukuran';</script>";
			}else{
				echo "<script>alert('Baju tidak tersedia di tanggal yang anda pilih, silahkan pilih tanggal atau ukuran lain!');</script>";
			}
		}else{
			$sql1 	= "SELECT * FROM baju WHERE id_baju='$id'";
			$query1 = mysqli_query($koneksidb,$sql1);
			$res1 	= mysqli_fetch_array($query1);
			$stok 	= $res1[$ukuran];

			if($stok>0){
				echo "<script>document.location = 'booking_ready.php?id=$id&mulai=$fromdate&selesai=$todate&pickup=$pickup&size=$ukuran';</script>";
			}else{
				echo "<script>alert('Baju tidak tersedia di tanggal yang anda pilih, silahkan pilih tanggal atau ukuran lain!');</script>";
			}
		}
	}
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Booking - Busanara</title>

  <link rel="stylesheet" href="assets/css/custom-style.css" />
  <link rel="stylesheet" href="assets/css/booking_style.css" />
  <script src="assets/js/custom-script.js" defer></script>
</head>

<body>
  <?php include('penting/header.php'); ?>

  <?php 
  $id=$_GET['id'];
  $sql1 = "SELECT baju.*, jenis.* FROM baju, jenis WHERE baju.id_jenis=jenis.id_jenis AND baju.id_baju='$id'";
  $query1 = mysqli_query($koneksidb,$sql1);
  $result = mysqli_fetch_array($query1);
  ?>

  <section class="booking-section">
    <div class="booking-container">
      
      <div class="booking-left">
        <img src="admin/img/<?php echo htmlentities($result['gambar1']);?>" alt="Foto Baju">
        <h3><?php echo htmlentities($result['nama_baju']);?></h3>
        <p class="price"><?php echo htmlentities(format_rupiah($result['harga']));?> / Hari</p>
      </div>

      <div class="booking-right">
        <form method="post" name="sewa" onsubmit="return validasiForm()">
          <input type="hidden" name="id" value="<?php echo $id;?>">
          <input type="hidden" name="now" value="<?php echo $now;?>">

          <label>Tanggal Mulai</label>
          <input type="date" name="fromdate" required>

          <label>Tanggal Selesai</label>
          <input type="date" name="todate" required>

          <label>Ukuran</label>
          <select name="ukuran" required>
            <option value="">== Pilih Ukuran ==</option>
            <option value="s">S</option>
            <option value="m">M</option>
            <option value="l">L</option>
            <option value="xl">XL</option>
          </select>

          <label>Metode Pengambilan</label>
          <select name="pickup" required>
            <option value="">== Metode Pengambilan ==</option>
            <option value="Ambil Sendiri">Ambil Sendiri</option>
            <option value="Kurir">Kurir</option>
          </select>

          <button type="submit" name="submit">Cek Ketersediaan</button>
        </form>
      </div>

    </div>
  </section>

  <?php include('penting/footer.php'); ?>
</body>
</html>