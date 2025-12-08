<?php
session_start();
error_reporting(E_ALL); // Untuk debugging
include('penting/config.php');
include('penting/library.php');

// Cek apakah parameter id ada
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Kode transaksi tidak valid!');</script>";
    echo "<script>window.location.href = 'sewa_kembali.php';</script>";
    exit;
}

$kode = mysqli_real_escape_string($koneksidb, $_GET['id']);
$status = "Selesai";

// Query data booking sesuai struktur database
$sql = "SELECT booking.*, baju.harga 
        FROM booking 
        JOIN baju ON booking.id_baju = baju.id_baju 
        WHERE booking.kode_booking = '$kode'";
        
$query = mysqli_query($koneksidb, $sql);

if (!$query) {
    echo "<script>alert('Error query: " . addslashes(mysqli_error($koneksidb)) . "');</script>";
    echo "<script>window.location.href = 'sewa_kembali.php';</script>";
    exit;
}

if (mysqli_num_rows($query) == 0) {
    echo "<script>alert('Data transaksi tidak ditemukan!');</script>";
    echo "<script>window.location.href = 'sewa_kembali.php';</script>";
    exit;
}

$result = mysqli_fetch_assoc($query);

// Tanggal penting dari database
$tgl_selesai = $result['tgl_selesai'];
$tgl_kembali = date('Y-m-d'); // Tanggal hari ini (tanggal pengembalian aktual)
$harga = $result['harga'];

// Debug info (opsional, bisa dihapus)
echo "<!-- Debug Info:
Tgl Selesai: $tgl_selesai
Tgl Kembali Aktual: $tgl_kembali
Harga/hari: $harga
-->";

// Perhitungan denda
$denda = 0;
$hari_terlambat = 0;

// Jika dikembalikan setelah tanggal selesai
if (strtotime($tgl_kembali) > strtotime($tgl_selesai)) {
    // Hitung selisih hari
    $selisih_detik = strtotime($tgl_kembali) - strtotime($tgl_selesai);
    $hari_terlambat = floor($selisih_detik / (60 * 60 * 24));
    
    if ($hari_terlambat > 0) {
        // Denda = setengah harga per hari keterlambatan
        $denda = $hari_terlambat * ($harga / 2);
    }
}

// Update database - HANYA kolom yang ada di tabel
$sql_update = "UPDATE booking 
               SET status = '$status', 
                   denda = '$denda' 
               WHERE kode_booking = '$kode'";

echo "<!-- Update Query: $sql_update -->"; // Debug info

if (mysqli_query($koneksidb, $sql_update)) {
    // Update tabel cek_booking jika ada (tidak wajib)
    $sql_cek = "UPDATE cek_booking SET status = '$status' WHERE kode_booking = '$kode'";
    @mysqli_query($koneksidb, $sql_cek); // @ untuk suppress error jika tabel tidak ada
    
    // Format denda untuk display
    $denda_formatted = number_format($denda, 0, ',', '.');
    
    // Tampilkan alert berdasarkan kondisi denda
    if ($denda > 0) {
        echo "<script>
                alert('Transaksi telah diselesaikan!\\\\n\\\\n📋 Detail:\\\\nKode: $kode\\\\nTgl Selesai: $tgl_selesai\\\\nTgl Kembali: $tgl_kembali\\\\n\\\\n⚠ KETERLAMBATAN: $hari_terlambat hari\\\\n💵 DENDA: Rp $denda_formatted');
                window.location.href = 'denda.php?id=$kode';
              </script>";
    } else {
        echo "<script>
                alert('Transaksi telah diselesaikan!\\\\n\\\\n✅ TIDAK ADA DENDA\\\\nKode: $kode\\\\nDikembalikan tepat waktu/lebih cepat.');
                window.location.href = 'denda.php?id=$kode';
              </script>";
    }
    
} else {
    $error = addslashes(mysqli_error($koneksidb));
    echo "<script>
            alert('Gagal menyelesaikan transaksi!\\\\nError: $error');
            window.location.href = 'sewa_kembali.php';
          </script>";
}

// Tutup koneksi
mysqli_close($koneksidb);
?>