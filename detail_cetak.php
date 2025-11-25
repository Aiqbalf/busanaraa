<?php
include('penting/config.php');
include('penting/format_rupiah.php');
include('penting/library.php');

$kode = $_GET['kode'];
$sql1 = "SELECT booking.*, baju.*, jenis.*, member.* 
         FROM booking, baju, jenis, member 
         WHERE booking.id_baju=baju.id_baju 
         AND baju.id_jenis=jenis.id_jenis 
         AND booking.email=member.email 
         AND booking.kode_booking='$kode'";
$query1 = mysqli_query($koneksidb, $sql1);
$result = mysqli_fetch_array($query1);

$harga = $result['harga'];
$durasi = $result['durasi'];
$totalsewa = $durasi * $harga;
$tglmulai = strtotime($result['tgl_mulai']);
$jmlhari = 86400 * 1;
$tgl = $tglmulai - $jmlhari;
$tglhasil = date("Y-m-d", $tgl);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Cetak Detail Sewa</title>
  <link rel="stylesheet" href="assets/css/detailcetak_style.css">
  <script src="assets/js/detailcetak.js" defer></script>
  
</head>

<body>
  <section class="kop">
    <div class="kop-container">
      <div class="kop-left">
        <img src="admin/img/icon.png" alt="Logo" width="80">
      </div>
      <div class="kop-center">
        <h2>busanara.com</h2>
        <p>Phone : +62 823-2275-3411 | E-mail : busanara@gmail.com</p>
        <p>Jember</p>
      </div>
      <div class="kop-right"></div>
    </div>
    <hr class="kop-line">
  </section>

  <section class="body">
    <h3>Detail Sewa</h3>
    <table class="detail-table">
      <tr><td>No. Sewa</td><td>:</td><td><?php echo $result['kode_booking']; ?></td></tr>
      <tr><td>Penyewa</td><td>:</td><td><?php echo $result['nama_user']; ?></td></tr>
      <tr><td>Baju</td><td>:</td><td><?php echo $result['nama_baju']; ?></td></tr>
      <tr><td>Tanggal Mulai</td><td>:</td><td><?php echo IndonesiaTgl($result['tgl_mulai']); ?></td></tr>
      <tr><td>Tanggal Selesai</td><td>:</td><td><?php echo IndonesiaTgl($result['tgl_selesai']); ?></td></tr>
      <tr><td>Durasi</td><td>:</td><td><?php echo $result['durasi']; ?> Hari</td></tr>
      <tr><td>Biaya Sewa (<?php echo $result['durasi']; ?> Hari)</td><td>:</td><td><?php echo format_rupiah($totalsewa); ?></td></tr>
      <tr><td>Status</td><td>:</td><td><?php echo $result['status']; ?></td></tr>

      <?php
      if($result['status']=="Menunggu Pembayaran"){
        $sqlrek = "SELECT * FROM tblpages WHERE id='5'";
        $queryrek = mysqli_query($koneksidb, $sqlrek);
        $resultrek = mysqli_fetch_array($queryrek);
        echo "<tr><td colspan='3' class='note'>
              *Silahkan transfer total biaya sewa ke <b>".$resultrek['detail']."</b> 
              maksimal tanggal <b>".IndonesiaTgl($tglhasil)."</b>.
              </td></tr>";
      }
      ?>
    </table>
  </section>

	 

</body>
</html>
