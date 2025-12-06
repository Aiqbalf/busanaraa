<?php
session_start();
error_reporting(0);
include('penting/config.php');
include('penting/library.php');
$kode=$_GET['id'];
$status = "Selesai";

$sql = "SELECT booking.*, baju.* FROM booking, baju WHERE booking.id_baju=baju.id_baju AND booking.kode_booking='$kode'";
$query = mysqli_query($koneksidb,$sql);
$result = mysqli_fetch_array($query);

$tgl_selesai = $result['tgl_selesai'];
$tgl = date('Y-m-d');
$datetime1 = new DateTime($tgl_selesai);
$datetime2 = new DateTime($tgl);
$difference = $datetime1->diff($datetime2);
$selisih = $difference->days;
	
$denda=$selisih*($result['harga']/2);

$idbaju = $result['id_baju'];
$sql1 = "UPDATE booking SET status='$status', denda='$denda' WHERE kode_booking='$kode'";
$query1 = mysqli_query($koneksidb,$sql1);
		if($query1){
			$sql3 = "UPDATE cek_booking SET status='$status' WHERE kode_booking='$kode'";
			$query3 = mysqli_query($koneksidb,$sql3);
			echo "<script>alert('Transaksi telah selesai!');</script>";
			echo "<script type='text/javascript'> document.location = 'denda.php?id=".$kode."'; </script>";
		}else {
			echo "<script>alert('Ops, ada kesalahan. Silahkan coba lagi!');</script>";
			echo "<script type='text/javascript'> document.location = 'sewa_kembali.php'; </script>";
		}
?>