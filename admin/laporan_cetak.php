<?php
include('includes/dbconnection.php');

$awal  = $_GET['awal'];
$akhir = $_GET['akhir'];

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Data_Laporan_Booking_{$awal}_sd_{$akhir}.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Query berdasarkan tanggal booking
$query = mysqli_query($con, "
    SELECT * FROM booking
    WHERE tgl_booking BETWEEN '$awal' AND '$akhir'
    ORDER BY tgl_booking ASC
");

echo "<table border='1'>
        <tr>
            <th colspan='13'><h3>LAPORAN BOOKING BUSANARA</h3></th>
        </tr>
        <tr>
            <th colspan='13'>Periode: $awal sampai $akhir</th>
        </tr>

        <tr>
            <th>No</th>
            <th>Kode Booking</th>
            <th>ID Baju</th>
            <th>Ukuran</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Selesai</th>
            <th>Durasi</th>
            <th>Denda</th>
            <th>Status</th>
            <th>Email</th>
            <th>Pickup</th>
            <th>Tanggal Booking</th>
            <th>Bukti Bayar</th>
        </tr>";

$no = 1;
while($row = mysqli_fetch_assoc($query)){
    echo "<tr>
            <td>".$no++."</td>
            <td>".$row['kode_booking']."</td>
            <td>".$row['id_baju']."</td>
            <td>".$row['ukuran']."</td>
            <td>".$row['tgl_mulai']."</td>
            <td>".$row['tgl_selesai']."</td>
            <td>".$row['durasi']."</td>
            <td>".$row['denda']."</td>
            <td>".$row['status']."</td>
            <td>".$row['email']."</td>
            <td>".$row['pickup']."</td>
            <td>".$row['tgl_booking']."</td>
            <td>".$row['bukti_bayar']."</td>
        </tr>";
}

echo "</table>";
exit;
?>
