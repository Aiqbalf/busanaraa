<?php
session_start();
include('penting/config.php');
include('penting/format_rupiah.php');
include('penting/library.php');

/* ------ CEK LOGIN ------ */
if (!isset($_SESSION['ulogin'])) {
    header("location:index.php");
    exit();
}

/* ------ CEK KODE BOOKING ------ */
if (!isset($_GET['kode'])) {
    die("Kode booking tidak ditemukan.");
}

$kode = $_GET['kode'];

/* ------ QUERY DETAIL BOOKING ------ */
$sql = "SELECT booking.*, baju.*, jenis.*
        FROM booking
        JOIN baju ON booking.id_baju = baju.id_baju
        JOIN jenis ON baju.id_jenis = jenis.id_jenis
        WHERE booking.kode_booking = '$kode'";

$query = mysqli_query($koneksidb, $sql);

if (!$query) {
    die("SQL ERROR: " . mysqli_error($koneksidb));
}

$data = mysqli_fetch_array($query);

if (!$data) {
    die("Data booking tidak ditemukan.");
}

/* ------ AMBIL DATA ------ */
$namaBaju  = $data['nama_baju'];
$gambar    = $data['gambar1'];
$tglMulai  = date('d-m-Y', strtotime($data['tgl_mulai']));
$tglSelesai= date('d-m-Y', strtotime($data['tgl_selesai']));
$durasi    = $data['durasi'];
$total     = format_rupiah($durasi * $data['harga']);
?>


<style>
/* CARD UTAMA */
.detail-card {
    width: 100%;
    max-width: 750px;
    margin: 30px auto;
    padding: 25px;
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    font-family: 'Segoe UI', sans-serif;
}
.detail-title {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 20px;
}
.label {
    font-weight: 600;
    margin-top: 12px;
}
.detail-input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #f9f9f9;
    margin-top: 5px;
}
.box-info {
    padding: 15px;
    margin-top: 20px;
    border-radius: 12px;
}
.box-warning {
    background: #ffecec;
    border-left: 6px solid #ff4c4c;
}
.box-safe {
    background: #e8fff1;
    border-left: 6px solid #35c76e;
}
.countdown {
    font-size: 22px;
    font-weight: bold;
    margin-top: 10px;
}
.btn-primary {
    width: 100%;
    padding: 15px;
    background: #0077ff;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 18px;
    margin-top: 25px;
    cursor: pointer;
    transition: 0.3s;
}
.btn-primary:hover {
    background: #005fcc;
}
</style>

<div class="detail-card">
    <div class="detail-title">Detail Sewa</div>

    <div class="label">Kode Sewa</div>
    <input class="detail-input" value="<?= $kode ?>" readonly>

    <div class="label">Nama Baju</div>
    <input class="detail-input" value="<?= $namaBaju ?>" readonly>

    <img src="admin/img/baju/<?= $gambar ?>" 
         style="width:180px; border-radius:10px; margin-top:15px">

    <div class="label">Tanggal Mulai</div>
    <input class="detail-input" value="<?= $tglMulai ?>" readonly>

    <div class="label">Tanggal Selesai</div>
    <input class="detail-input" value="<?= $tglSelesai ?>" readonly>

    <div class="label">Durasi</div>
    <input class="detail-input" value="<?= $durasi ?> Hari" readonly>

    <div class="label">Total Biaya Sewa</div>
    <input class="detail-input" value="<?= $total ?>" readonly>

    <div id="paymentBox" class="box-info box-warning">
        <b>Batas Pembayaran:</b><br>
        <span id="deadline"></span>

        <div class="countdown" id="countdownText">Menghitung...</div>
        <div id="expiredMessage" style="color:red; font-weight:bold; display:none;">
            Waktu pembayaran telah habis!
        </div>
    </div>

    <div class="box-info" style="background:#e9f4ff; border-left:6px solid #0077ff;">
        Transfer ke:
        <b>BCA | 1234567890 | BUSANARA STORE</b><br>
        Setelah transfer, kirim bukti melalui menu konfirmasi.
    </div>

    <button class="btn-primary" onclick="window.location.href='user_fitur/riwayatsewa.php'">
    Selanjutnya
    </button>
</div>

<script>
// HITUNG MUNDUR 20 MENIT
let startTime = new Date().getTime();
let deadline = startTime + (20 * 60 * 1000);

document.getElementById("deadline").innerText =
    new Date(deadline).toLocaleString();

let timer = setInterval(function () {
    let now = new Date().getTime();
    let distance = deadline - now;

    if (distance > 0) {
        let minutes = Math.floor((distance % (1000*60*60)) / (1000*60));
        let seconds = Math.floor((distance % (1000*60)) / 1000);

        document.getElementById("countdownText").innerHTML =
            minutes + " menit " + seconds + " detik";
    } else {
        clearInterval(timer);
        document.getElementById("countdownText").style.display = "none";
        document.getElementById("expiredMessage").style.display = "block";
    }
}, 1000);
</script>
