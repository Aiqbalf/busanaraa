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

if(isset($_POST['submit'])){
  $fromdate=$_POST['fromdate'];
  $todate=$_POST['todate'];
  $durasi = intval($_POST['durasi']);
  $pickup=$_POST['pickup'];
  $id=$_POST['id'];
  $size=$_POST['size'];
  $email=$_POST['email'];
  $kode = buatKode("booking", "TRX");
  $status = "Menunggu Pembayaran";
  $tgl=date('Y-m-d');

  $sql  = "INSERT INTO booking (kode_booking,id_baju,ukuran,tgl_mulai,tgl_selesai,durasi,status,email,pickup,tgl_booking)
           VALUES('$kode','$id','$size','$fromdate','$todate','$durasi','$status','$email','$pickup','$tgl')";
  $query  = mysqli_query($koneksidb,$sql);

  if($query){
    for($cek=0;$cek<$durasi;$cek++){
      $tglmulai = strtotime($fromdate);
      $tglhasil = date("Y-m-d",$tglmulai+(86400*$cek));
      $sql1 = "INSERT INTO cek_booking (kode_booking,id_baju,ukuran,tgl_booking,status)
               VALUES('$kode','$id','$size','$tglhasil','$status')";
      mysqli_query($koneksidb,$sql1);
    }
    echo "<script>alert('Baju berhasil disewa.');</script>";
    echo "<script>document.location='booking_detail.php?kode=$kode';</script>";
  } else {
    echo "<script>alert('Terjadi kesalahan. Silakan coba lagi.');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Booking - Busanara</title>

  <link rel="stylesheet" href="assets/css/custom-style.css">
  <link rel="stylesheet" href="assets/css/bookingready_style.css">
  <script src="assets/js/custom-script.js" defer></script>
</head>

<body>
<?php include('penting/header.php'); ?>

<section class="booking-ready">
  <div class="container">
    <h3 class="page-title">Baju Tersedia untuk disewa.</h3>
    <hr>

    <?php
    $email=$_SESSION['ulogin']; 
    $id=$_GET['id'];
    $mulai=$_GET['mulai'];
    $selesai=$_GET['selesai'];
    $ukuran=$_GET['size'];
    $pickup=$_GET['pickup'];

    $start = new DateTime($mulai);
    $finish = new DateTime($selesai);
    $durasi = $start->diff($finish)->days + 1;

    $sql1 = "SELECT baju.*, jenis.* FROM baju, jenis WHERE baju.id_jenis=jenis.id_jenis AND baju.id_baju='$id'";
    $query1 = mysqli_query($koneksidb,$sql1);
    $result = mysqli_fetch_array($query1);
    $harga = $result['harga'];
    $totalsewa = $durasi * $harga;
    ?>

    <div class="booking-container">
      <div class="left-side">
        <img src="admin/img/baju/<?php echo htmlentities($result['gambar1']);?>" alt="Baju">
        <h4><?php echo htmlentities($result['nama_baju']);?></h4>
        <p class="price"><?php echo htmlentities(format_rupiah($result['harga']));?> / Hari</p>
      </div>

      <div class="right-side">
        <form method="post" name="sewa" onsubmit="return validasiForm();">
          <input type="hidden" name="id" value="<?php echo $id;?>">
          <input type="hidden" name="email" value="<?php echo $email;?>">

          <div class="form-row">
            <div>
              <label>Tanggal Mulai</label>
              <input type="date" name="fromdate" value="<?php echo $mulai;?>" readonly>
            </div>
            <div>
              <label>Tanggal Selesai</label>
              <input type="date" name="todate" value="<?php echo $selesai;?>" readonly>
            </div>
          </div>

          <div class="form-row">
            <div>
              <label>Durasi</label>
              <input type="text" name="durasi" value="<?php echo $durasi;?> Hari" readonly>
            </div>
            <div>
              <label>Ukuran</label>
              <input type="text" name="size" value="<?php echo $ukuran;?>" readonly>
            </div>
          </div>

          <div class="form-row">
            <div>
              <label>Metode Pickup</label>
              <input type="text" name="pickup" value="<?php echo $pickup;?>" readonly>
            </div>
            <div>
              <label>Biaya Sewa</label>
              <input type="text" name="sewa" value="<?php echo format_rupiah($totalsewa);?>" readonly>
            </div>
          </div>

          <button type="submit" name="submit" class="btn-sewa">Sewa</button>
        </form>
      </div>
    </div>
  </div>
</section>

<?php include('penting/footer.php'); ?>
</body>
</html>
