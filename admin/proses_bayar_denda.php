<?php
session_start();
error_reporting(0);
include('penting/config.php');
include('penting/format_rupiah.php');

if(strlen($_SESSION['alogin'])==0){    
    header('location:index.php');
} else {
    $kode = $_GET['id'];
    
    // Query untuk mendapatkan data booking
    $sql = "SELECT * FROM booking WHERE kode_booking='$kode'";
    $query = mysqli_query($koneksidb,$sql);
    $result = mysqli_fetch_array($query);
    $denda = $result['denda'];
    $denda_numeric = (float)str_replace(['Rp', '.', ','], '', $denda);
    
    // Proses pembayaran denda - Update status denda
    if($denda_numeric > 0) {
        $sql_update = "UPDATE booking SET denda='0' WHERE kode_booking='$kode'";
        mysqli_query($koneksidb, $sql_update);
        
        // Catat pembayaran denda dalam tabel pembayaran jika ada
        // $sql_payment = "INSERT INTO pembayaran (kode_booking, jenis_pembayaran, jumlah, tanggal) VALUES ('$kode', 'Denda', '$denda_numeric', NOW())";
        // mysqli_query($koneksidb, $sql_payment);
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - <?php echo htmlspecialchars($kode); ?></title>
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
            background: #2ecc71;
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
        
        .success-info {
            background: #f0f8f0;
            border: 2px dashed #2ecc71;
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 25px;
        }
        
        .success-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .success-amount {
            font-size: 32px;
            color: #2ecc71;
            font-weight: 700;
            margin: 10px 0;
        }
        
        .success-icon {
            font-size: 50px;
            color: #2ecc71;
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
            transition: all 0.3s;
        }
        
        .btn-selesai {
            background: #3498db;
            color: white;
            text-decoration: none;
        }
        
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
        
        .payment-details {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #2ecc71;
        }
        
        .payment-details h3 {
            color: #2ecc71;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        @media (max-width: 768px) {
            .content {
                padding: 20px 15px;
            }
            
            .success-amount {
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
        
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background: #2ecc71;
            border-radius: 50%;
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pembayaran Denda Berhasil</h1>
        </div>
        
        <div class="content">
            <div class="success-info">
                <div class="success-icon">
                    ✓
                </div>
                
                <div class="success-label">
                    Denda Berhasil Dibayar
                </div>
                
                <div class="success-amount">
                    <?php 
                    echo format_rupiah($denda);
                    ?>
                </div>
                
                <div class="info-text">
                    Pembayaran denda telah berhasil diproses. Anda dapat melanjutkan proses pengembalian.
                </div>
            </div>
            
            <div class="payment-details">
                <h3>Detail Pembayaran</h3>
                <p><strong>Kode Sewa:</strong> <?php echo htmlspecialchars($kode); ?></p>
                <p><strong>Jumlah Denda:</strong> <?php echo format_rupiah($denda); ?></p>
                <p><strong>Tanggal Pembayaran:</strong> <?php echo date('d/m/Y H:i:s'); ?></p>
                <p><strong>Status:</strong> <span style="color: #2ecc71; font-weight: bold;">LUNAS</span></p>
            </div>
            
            <div class="detail-info">
                <p><strong>Catatan:</strong> Denda telah dibayar sepenuhnya. Status sewa telah diperbarui dan siap untuk proses pengembalian.</p>
            </div>
            
            <div class="buttons">
                <a href="sewa_kembali.php" class="btn btn-selesai">
                    <span>✅</span> Selesai & Lanjutkan
                </a>
            </div>
        </div>
    </div>
    
    <script>
        // Tambahkan sedikit animasi confetti
        function createConfetti() {
            const colors = ['#2ecc71', '#3498db', '#9b59b6', '#e74c3c', '#f1c40f'];
            for(let i = 0; i < 20; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.top = '-10px';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.width = Math.random() * 10 + 5 + 'px';
                confetti.style.height = confetti.style.width;
                document.body.appendChild(confetti);
                
                // Animasi jatuh
                setTimeout(() => {
                    confetti.style.transition = 'all 1s ease-out';
                    confetti.style.transform = `translateY(${window.innerHeight}px) rotate(${Math.random() * 360}deg)`;
                    confetti.style.opacity = '0';
                    
                    // Hapus elemen setelah animasi selesai
                    setTimeout(() => {
                        confetti.remove();
                    }, 1000);
                }, 100);
            }
        }
        
        // Jalankan confetti saat halaman dimuat
        window.onload = function() {
            createConfetti();
        };
    </script>
</body>
</html>
<?php } ?>