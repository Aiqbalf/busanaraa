<?php
session_start();
error_reporting(0);
include('../penting/config.php');
include('../penting/format_rupiah.php');
include('../penting/library.php');

if(strlen($_SESSION['ulogin'])==0){ 
    header('location:../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Riwayat Sewa</title>

  <link rel="stylesheet" href="../assets/css/custom-style.css">
  <link rel="stylesheet" href="../assets/css/header_style.css" />
  <link rel="stylesheet" href="../assets/css/footer_style.css">
  <link rel="stylesheet" href="../assets/css/riwayatsewa_style.css">
  <script src="../assets/js/riwayat_sewa.js" defer></script>
</head>

<body>

<?php include('../penting/header.php'); ?>

<section class="riwayat-container">
  <h2>Riwayat Sewa</h2>
  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Kode Sewa</th>
          <th>Baju</th>
          <th>Tgl. Mulai</th>
          <th>Tgl. Selesai</th>
          <th>Durasi</th>
          <th>Biaya Sewa</th>
          <th>Status</th>
          <th>Opsi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $email = $_SESSION['ulogin'];
        $sql = "SELECT booking.*, baju.*, jenis.*, member.* 
                FROM booking 
                JOIN baju ON booking.id_baju=baju.id_baju 
                JOIN jenis ON baju.id_jenis=jenis.id_jenis 
                JOIN member ON booking.email=member.email 
                WHERE booking.email='$email'";
        $query = mysqli_query($koneksidb, $sql);
        $no = 0;
        if(mysqli_num_rows($query) > 0){
          while($result = mysqli_fetch_array($query)){
            $no++;
            $harga = $result['harga'];
            $durasi = $result['durasi'];
            $total = $durasi * $harga;
            ?>
            <tr>
              <td><?php echo $no; ?></td>
              <td><?php echo $result['kode_booking']; ?></td>
              <td><?php echo $result['nama_baju']; ?></td>
              <td><?php echo IndonesiaTgl($result['tgl_mulai']); ?></td>
              <td><?php echo IndonesiaTgl($result['tgl_selesai']); ?></td>
              <td><?php echo $result['durasi']; ?></td>
              <td><?php echo format_rupiah($total); ?></td>
              <td><?php echo $result['status']; ?></td>
              <td class="opsi">
                <a href="booking_detail.php?kode=<?php echo $result['kode_booking']; ?>" class="lihat">&#128065;</a>
                <?php if($result['status'] != "Sudah Dibayar" && $result['status'] != "Selesai"){ ?>
                  <a href="../booking_edit.php?kode=<?php echo $result['kode_booking']; ?>" class="upload">Upload Bukti Bayar</a>
                <?php } ?>
              </td>
            </tr>
            <?php
          }
        } else {
          echo '<tr><td colspan="9" class="no-data">Belum ada riwayat sewa.</td></tr>';
        }
        ?>
      </tbody>
    </table>
  </div>
</section>

<?php include('../penting/footer.php'); ?>

</body>
</html>
