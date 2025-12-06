<?php
ob_start(); // Start output buffering
session_start();
error_reporting(0);
include('penting/config.php');
include('penting/format_rupiah.php');
include('penting/library.php');

if(strlen($_SESSION['alogin'])==0){	
    header('location:index.php');
    exit();
}

// Get filter parameters
$tgl_awal = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : date('Y-m-01');
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : date('Y-m-t');

// Query untuk data laporan sesuai struktur database
$sql_laporan = "SELECT 
                    b.*,
                    bk.kode_booking,
                    bk.tgl_mulai,
                    bk.tgl_selesai,
                    bk.durasi,
                    bk.status,
                    m.nama_user,
                    m.email,
                    m.telp,
                    j.nama_jenis,
                    (bk.durasi * b.harga) as total
                FROM booking bk
                JOIN baju b ON bk.id_baju = b.id_baju
                JOIN jenis j ON b.id_jenis = j.id_jenis
                JOIN member m ON bk.email = m.email
                WHERE bk.status IN ('Sudah Dibayar', 'Selesai')
                AND DATE(bk.tgl_mulai) BETWEEN '$tgl_awal' AND '$tgl_akhir'
                ORDER BY bk.tgl_mulai DESC";

$query_laporan = mysqli_query($koneksidb, $sql_laporan);

if(!$query_laporan) {
    die("Query error: " . mysqli_error($koneksidb));
}

// Hitung total
$total_pendapatan = 0;
$total_transaksi = 0;
$total_sewa = 0;
$data_laporan = [];

while($row = mysqli_fetch_assoc($query_laporan)) {
    $total_pendapatan += $row['total'];
    $total_transaksi++;
    $total_sewa += $row['durasi'];
    $data_laporan[] = $row;
}

// Clean output buffer
ob_end_clean();

// Set header untuk export Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"Laporan_Sewa_" . date('Y-m-d_H-i') . ".xls\"");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #2c3e50;
            font-size: 20px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 14px;
        }
        .summary {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .summary-item {
            margin-bottom: 5px;
        }
        .summary-label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
        }
        td {
            padding: 6px;
            border: 1px solid #ddd;
            vertical-align: middle;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total-row {
            background-color: #e8f4f8 !important;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h1>BUSANARA - Laporan Sewa</h1>
        <p>Periode: <?php echo IndonesiaTgl($tgl_awal); ?> s/d <?php echo IndonesiaTgl($tgl_akhir); ?></p>
        <p>Tanggal Export: <?php echo date('d-m-Y H:i:s'); ?></p>
    </div>

    <!-- Summary -->
    <div class="summary">
        <div class="summary-item">
            <span class="summary-label">Total Pendapatan:</span>
            <?php echo format_rupiah($total_pendapatan); ?>
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Transaksi:</span>
            <?php echo $total_transaksi; ?> transaksi
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Hari Sewa:</span>
            <?php echo $total_sewa; ?> hari
        </div>
        <div class="summary-item">
            <span class="summary-label">Periode Laporan:</span>
            <?php echo IndonesiaTgl($tgl_awal); ?> - <?php echo IndonesiaTgl($tgl_akhir); ?>
        </div>
    </div>

    <!-- Table -->
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Sewa</th>
                <th>Baju</th>
                <th>Jenis</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Durasi</th>
                <th>Harga/Hari</th>
                <th>Penyewa</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Status</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($data_laporan as $row) {
                $i++;
            ?>
                <tr>
                    <td class="text-center"><?php echo $i; ?></td>
                    <td><?php echo htmlspecialchars($row['kode_booking']); ?></td>
                    <td class="text-left"><?php echo htmlspecialchars($row['nama_baju']); ?></td>
                    <td class="text-left"><?php echo htmlspecialchars($row['nama_jenis']); ?></td>
                    <td class="text-center"><?php echo IndonesiaTgl(htmlspecialchars($row['tgl_mulai'])); ?></td>
                    <td class="text-center"><?php echo IndonesiaTgl(htmlspecialchars($row['tgl_selesai'])); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($row['durasi']); ?> hari</td>
                    <td class="text-right"><?php echo format_rupiah($row['harga']); ?></td>
                    <td class="text-left"><?php echo htmlspecialchars($row['nama_user']); ?></td>
                    <td class="text-left"><?php echo htmlspecialchars($row['email']); ?></td>
                    <td class="text-left"><?php echo htmlspecialchars($row['telp']); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($row['status']); ?></td>
                    <td class="text-right"><?php echo format_rupiah($row['total']); ?></td>
                </tr>
            <?php } ?>
            
            <!-- Total Row -->
            <?php if($total_transaksi > 0): ?>
            <tr class="total-row">
                <td colspan="11" class="text-right"><strong>GRAND TOTAL</strong></td>
                <td class="text-center"><strong><?php echo $total_transaksi; ?> transaksi</strong></td>
                <td class="text-right"><strong><?php echo format_rupiah($total_pendapatan); ?></strong></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Footer Notes -->
    <div style="margin-top: 30px; font-size: 11px; color: #666;">
        <p><strong>Catatan:</strong></p>
        <ul style="margin-top: 5px;">
            <li>Laporan ini hanya mencakup transaksi dengan status "Sudah Dibayar" dan "Selesai"</li>
            <li>Data diambil dari database sistem BUSANARA</li>
            <li>Total pendapatan belum termasuk biaya tambahan (jika ada)</li>
            <li>Data diurutkan berdasarkan tanggal mulai sewa (terbaru ke terlama)</li>
        </ul>
    </div>

</body>
</html>