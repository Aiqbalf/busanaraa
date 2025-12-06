<?php
session_start();
error_reporting(0);
include('penting/config.php');
include('penting/format_rupiah.php');

if(strlen($_SESSION['alogin'])==0){	
    header('location:index.php');
} else {
    $kode = $_GET['id'];
    $sql = "SELECT * FROM booking WHERE kode_booking='$kode'";
    $query = mysqli_query($koneksidb,$sql);
    $result = mysqli_fetch_array($query);
    $denda = $result['denda'];
    $denda_numeric = (float)str_replace(['Rp', '.', ','], '', $denda);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Denda - <?php echo htmlspecialchars($kode); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        
        body {
            background: #f0f2f5;
            padding: 20px;
        }
        
        .container {
            background: white;
            max-width: 600px;
            margin: 0 auto;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: #e74c3c;
            color: white;
            padding: 15px 20px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 18px;
            font-weight: 500;
        }
        
        .content {
            padding: 30px 20px;
            text-align: center;
        }
        
        .denda-info {
            background: #fff9f9;
            border: 2px dashed #e74c3c;
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 25px;
        }
        
        .denda-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .denda-amount {
            font-size: 32px;
            color: #e74c3c;
            font-weight: 700;
            margin: 10px 0;
        }
        
        .warning-icon {
            font-size: 40px;
            color: #e74c3c;
            margin-bottom: 15px;
        }
        
        .info-text {
            font-size: 13px;
            color: #666;
            margin-top: 15px;
            line-height: 1.5;
        }
        
        .buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 25px;
        }
        
        .btn {
            padding: 10px 25px;
            font-size: 13px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-close {
            background: #95a5a6;
            color: white;
        }
        
        .btn-bayar {
            background: #2ecc71;
            color: white;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        .detail-info {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 15px;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
            text-align: left;
        }
        
        .detail-info p {
            margin-bottom: 8px;
        }
        
        .detail-info strong {
            color: #333;
        }
        
        @media (max-width: 768px) {
            .content {
                padding: 20px 15px;
            }
            
            .denda-amount {
                font-size: 28px;
            }
            
            .buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
        
        .no-denda {
            background: #f0f8f0;
            border: 2px dashed #2ecc71;
        }
        
        .no-denda .denda-amount {
            color: #2ecc71;
        }
        
        .no-denda .warning-icon {
            color: #2ecc71;
            content: "✓";
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Informasi Denda</h1>
        </div>
        
        <div class="content">
            <div class="denda-info <?php echo $denda_numeric == 0 ? 'no-denda' : ''; ?>">
                <div class="warning-icon">
                    <?php echo $denda_numeric == 0 ? '✓' : '⚠'; ?>
                </div>
                
                <div class="denda-label">
                    <?php echo $denda_numeric == 0 ? 'Tidak Ada Denda' : 'Total Denda'; ?>
                </div>
                
                <div class="denda-amount">
                    <?php 
                    if($denda_numeric == 0) {
                        echo 'Rp 0';
                    } else {
                        echo format_rupiah($denda);
                    }
                    ?>
                </div>
                
                <div class="info-text">
                    <?php if($denda_numeric == 0): ?>
                        Tidak ada denda yang perlu dibayar untuk sewa ini.
                    <?php else: ?>
                        Denda harus dibayar sebelum melanjutkan proses pengembalian.
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if($denda_numeric > 0): ?>
            <div class="detail-info">
                <p><strong>Kode Sewa:</strong> <?php echo htmlspecialchars($kode); ?></p>
                <p><strong>Status:</strong> Memiliki denda keterlambatan/pengembalian</p>
                <p><strong>Catatan:</strong> Denda akan ditambahkan ke total pembayaran</p>
            </div>
            <?php endif; ?>
            
            <div class="buttons">
                <button class="btn btn-close" onclick="window.history.back()">
                    <span>←</span> Kembali
                </button>
                
                <?php if($denda_numeric > 0): ?>
                <button class="btn btn-bayar" onclick="bayarDenda('<?php echo $kode; ?>')">
                    <span>💳</span> Bayar Denda
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
    function bayarDenda(kode) {
        if(confirm('Lanjutkan pembayaran denda untuk sewa ' + kode + '?')) {
            // Redirect ke halaman pembayaran atau proses bayar denda
            // window.location.href = 'proses_bayar_denda.php?id=' + kode;
            
            // Untuk sekarang, tampilkan pesan
            alert('Fitur pembayaran denda sedang dalam pengembangan.');
        }
    }
    
    // Auto close setelah beberapa detik jika tidak ada denda
    <?php if($denda_numeric == 0): ?>
    setTimeout(function() {
        if(confirm('Tidak ada denda. Kembali ke halaman sebelumnya?')) {
            window.history.back();
        }
    }, 3000);
    <?php endif; ?>
    </script>
</body>
</html>
<?php } ?>