<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

include('penting/config.php');
include('penting/format_rupiah.php');
include('penting/library.php');

/* ------------------ CEK LOGIN ------------------ */
if (!isset($_SESSION['ulogin']) || strlen($_SESSION['ulogin']) == 0) {
    header('location:index.php');
    exit();
}


if (isset($_POST['submit'])) {

    $fromdate   = $_POST['fromdate'];
    $todate     = $_POST['todate'];
    $durasi     = intval($_POST['durasi']);
    $pickup     = $_POST['pickup'];
    $id         = $_POST['id'];
    $size       = $_POST['size'];
    $email      = $_POST['email'];

    $kode       = buatKode("booking", "TRX");
    $status     = "Menunggu Pembayaran";
    $tgl        = date('Y-m-d');

    mysqli_begin_transaction($koneksidb);

try {

    /* ---------- INSERT KE booking DULU ✅ ---------- */
    $sql2 = "INSERT INTO booking (kode_booking, id_baju, ukuran, tgl_mulai, tgl_selesai, durasi, status, email, pickup, tgl_booking)
             VALUES ('$kode', '$id', '$size', '$fromdate', '$todate', '$durasi', '$status', '$email', '$pickup', '$tgl')";
    mysqli_query($koneksidb, $sql2);

    /* ---------- BARU SIMPAN KE cek_booking ✅ ---------- */
    $tglmulai = strtotime($fromdate);
    for ($cek = 0; $cek < $durasi; $cek++) {

        $tglhasil = date("Y-m-d", $tglmulai + (86400 * $cek));

        $sql1 = "INSERT INTO cek_booking (kode_booking, id_baju, ukuran, tgl_booking, status)
                 VALUES ('$kode', '$id', '$size', '$tglhasil', '$status')";
        mysqli_query($koneksidb, $sql1);
    }

    mysqli_commit($koneksidb);

    echo "<script>alert('Baju berhasil disewa.');</script>";
    echo "<script>document.location='booking_detail.php?kode=$kode';</script>";
    exit();

} catch (Exception $e) {
    mysqli_rollback($koneksidb);
    echo "<h3 style='color:red;'>TRANSACTION ERROR: " . $e->getMessage() . "</h3>";
}

}


$email  = $_SESSION['ulogin'];

$id     = $_GET['id'] ?? '';
$mulai  = $_GET['mulai'] ?? '';
$selesai= $_GET['selesai'] ?? '';
$ukuran = $_GET['size'] ?? '';
$pickup = $_GET['pickup'] ?? '';

/* --------- Validasi GET --------- */
if ($id=="" || $mulai=="" || $selesai=="" || $ukuran=="" || $pickup=="") {
    echo "<h3 style='color:red; text-align:center; margin-top:50px;'>Parameter tidak lengkap!</h3>";
    exit();
}

/* ------------------ HITUNG DURASI ------------------ */
$start  = new DateTime($mulai);
$finish = new DateTime($selesai);
$durasi = $start->diff($finish)->days + 1;

/* ------------------ AMBIL DATA BAJU ------------------ */
$sql1   = "SELECT baju.*, jenis.* 
           FROM baju 
           JOIN jenis ON baju.id_jenis = jenis.id_jenis 
           WHERE baju.id_baju='$id'";
$query1 = mysqli_query($koneksidb, $sql1);

if (!$query1) {
    echo "<h3 style='color:red;'>SQL ERROR: " . mysqli_error($koneksidb) . "</h3>";
    exit();
}

$result = mysqli_fetch_array($query1);

if (!$result) {
    echo "<h3 style='color:red;'>Data baju tidak ditemukan!</h3>";
    exit();
}

$harga      = $result['harga'];
$totalsewa  = $durasi * $harga;

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

    <div class="booking-container">

      <!-- ========== BAGIAN KIRI ========== -->
      <div class="left-side">
        <img src="admin/img/baju/<?php echo $result['gambar1']; ?>" alt="Baju">
        <h4><?php echo htmlentities($result['nama_baju']); ?></h4>
        <p class="price"><?php echo htmlentities(format_rupiah($harga)); ?> / Hari</p>
      </div>

      <!-- ========== BAGIAN KANAN ========== -->
      <div class="right-side">

        <form method="post">

          <input type="hidden" name="id" value="<?php echo $id; ?>">
          <input type="hidden" name="email" value="<?php echo $email; ?>">

          <div class="form-row">
            <div>
              <label>Tanggal Mulai</label>
              <input type="date" name="fromdate" value="<?php echo $mulai; ?>" readonly>
            </div>
            <div>
              <label>Tanggal Selesai</label>
              <input type="date" name="todate" value="<?php echo $selesai; ?>" readonly>
            </div>
          </div>

          <div class="form-row">
            <div>
              <label>Durasi</label>
              <input type="number" name="durasi" value="<?php echo $durasi; ?>" readonly>
            </div>
            <div>
              <label>Ukuran</label>
              <input type="text" name="size" value="<?php echo $ukuran; ?>" readonly>
            </div>
          </div>

          <div class="form-row">
            <div>
              <label>Metode Pickup</label>
              <input type="text" name="pickup" value="<?php echo $pickup; ?>" readonly>
            </div>
            <div>
              <label>Biaya Sewa</label>
              <input type="text" value="<?php echo format_rupiah($totalsewa); ?>" readonly>
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
