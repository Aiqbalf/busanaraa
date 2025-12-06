<?php
session_start();
error_reporting(0);
include('penting/config.php');
include('penting/format_rupiah.php');
include('penting/library.php');

if($_GET) {
    $Kode = $_GET['code'];
    $sqlsewa = "SELECT booking.*,baju.*,jenis.*,member.* FROM booking, baju, jenis, member WHERE booking.id_baju=baju.id_baju
                AND baju.id_jenis=jenis.id_jenis AND booking.email=member.email AND booking.kode_booking='$Kode'";
    $querysewa = mysqli_query($koneksidb,$sqlsewa);
    $result = mysqli_fetch_array($querysewa);
    $total=$result['durasi']*$result['harga'];
    $bukti=$result['bukti_bayar'];
}
else {
    echo "Nomor Transaksi Tidak Terbaca";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Detail Sewa</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        
        body {
            background: #f0f2f5;
            padding: 15px;
        }
        
        .card {
            background: white;
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        
        .title {
            text-align: center;
            color: #333;
            font-size: 16px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .detail-item {
            margin-bottom: 12px;
        }
        
        .detail-item:last-child {
            margin-bottom: 0;
        }
        
        .detail-label {
            font-size: 11px;
            color: #666;
            margin-bottom: 3px;
            text-transform: uppercase;
            font-weight: 500;
            letter-spacing: 0.3px;
        }
        
        .detail-value {
            font-size: 13px;
            color: #333;
            padding: 6px 8px;
            background: #f8f9fa;
            border-radius: 4px;
            border: 1px solid #eaeaea;
        }
        
        .total-value {
            color: #e74c3c;
            font-weight: 600;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            font-size: 11px;
            border-radius: 12px;
            font-weight: 500;
        }
        
        .status-pending {
            background: #ffeaa7;
            color: #e17055;
        }
        
        .bukti-section {
            margin-top: 5px;
        }
        
        .bukti-image {
            max-width: 100%;
            max-height: 150px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        
        .no-bukti {
            color: #95a5a6;
            font-size: 12px;
            font-style: italic;
            padding: 6px;
            background: #f8f9fa;
            border-radius: 4px;
            border: 1px dashed #ddd;
        }
        
        .close-btn {
            display: block;
            width: 100%;
            background: #3498db;
            color: white;
            border: none;
            padding: 8px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            margin-top: 15px;
        }
        
        .close-btn:hover {
            background: #2980b9;
        }
        
        /* Print styles */
        @media print {
            body {
                background: white;
                padding: 5px;
            }
            
            .card {
                box-shadow: none;
                max-width: 100%;
            }
            
            .close-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="title">Detail Sewa</div>
        
        <div class="detail-item">
            <div class="detail-label">Baju</div>
            <div class="detail-value"><?php echo htmlspecialchars($result['nama_baju']); ?></div>
        </div>
        
        <div class="detail-item">
            <div class="detail-label">Tanggal Mulai</div>
            <div class="detail-value"><?php echo IndonesiaTgl($result['tgl_mulai']); ?></div>
        </div>
        
        <div class="detail-item">
            <div class="detail-label">Tanggal Selesai</div>
            <div class="detail-value"><?php echo IndonesiaTgl($result['tgl_selesai']); ?></div>
        </div>
        
        <div class="detail-item">
            <div class="detail-label">Durasi</div>
            <div class="detail-value"><?php echo htmlspecialchars($result['durasi']); ?> hari</div>
        </div>
        
        <div class="detail-item">
            <div class="detail-label">Metode Pickup</div>
            <div class="detail-value"><?php echo htmlspecialchars($result['pickup']); ?></div>
        </div>
        
        <div class="detail-item">
            <div class="detail-label">Penyewa</div>
            <div class="detail-value"><?php echo htmlspecialchars($result['nama_user']); ?></div>
        </div>
        
        <div class="detail-item">
            <div class="detail-label">Total Biaya</div>
            <div class="detail-value total-value"><?php echo format_rupiah($total); ?></div>
        </div>
        
        <div class="detail-item">
            <div class="detail-label">Status</div>
            <div class="detail-value">
                <?php 
                $status = strtolower($result['status']);
                $statusClass = 'status-badge ';
                if(strpos($status, 'lunas') !== false || strpos($status, 'paid') !== false) {
                    $statusClass .= 'status-paid';
                } else {
                    $statusClass .= 'status-pending';
                }
                ?>
                <span class="<?php echo $statusClass; ?>"><?php echo htmlspecialchars($result['status']); ?></span>
            </div>
        </div>
        
        <div class="detail-item">
            <div class="detail-label">Bukti Pembayaran</div>
            <div class="bukti-section">
                <?php
                if(empty($bukti)){
                    echo '<div class="no-bukti">Belum ada bukti pembayaran</div>';
                } else {
                    echo '<img src="../image/bukti/' . htmlspecialchars($result['bukti_bayar']) . '" class="bukti-image" alt="Bukti Pembayaran">';
                }
                ?>
            </div>
        </div>
        
        <button class="close-btn" onclick="window.history.back()">Close</button>
    </div>
</body>
</html>