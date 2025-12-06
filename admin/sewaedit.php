<?php
session_start();
error_reporting(0);
include('penting/config.php');
include('penting/format_rupiah.php');
include('penting/library.php');

if(strlen($_SESSION['alogin'])==0){	
    header('location:index.php');
} else {
    if(isset($_POST['submit'])){
        $status = $_POST['status'];
        $kode = $_POST['id'];
        $mySql = "UPDATE booking SET status = '$status' WHERE kode_booking='$kode'";
        $myQry = mysqli_query($koneksidb, $mySql);
        $mySql1 = "UPDATE cek_booking SET status = '$status' WHERE kode_booking='$kode'";
        $myQry1 = mysqli_query($koneksidb, $mySql1);
        echo "<script type='text/javascript'>
                alert('Status berhasil diupdate.'); 
                document.location = 'sewa_konfirmasi.php'; 
              </script>";
    }

    $id = $_GET['id'];
    $sqlsewa = "SELECT booking.*,baju.*,jenis.*,member.* FROM booking,baju,jenis,member WHERE booking.id_baju=baju.id_baju
                AND baju.id_jenis=jenis.id_jenis AND booking.email=member.email AND booking.kode_booking ='$id'";
    $querysewa = mysqli_query($koneksidb,$sqlsewa);
    $result = mysqli_fetch_array($querysewa);
    $total = $result['durasi']*$result['harga'];
    $bukti = $result['bukti_bayar'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Status Sewa</title>
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
            max-width: 700px;
            margin: 0 auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: #3498db;
            color: white;
            padding: 15px 20px;
        }
        
        .header h1 {
            font-size: 16px;
            font-weight: 500;
        }
        
        .content {
            padding: 20px;
        }
        
        .section {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .section-title {
            font-size: 13px;
            color: #3498db;
            font-weight: 600;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .form-group {
            margin-bottom: 12px;
        }
        
        .form-group label {
            display: block;
            font-size: 11px;
            color: #666;
            margin-bottom: 4px;
            font-weight: 500;
        }
        
        .required:after {
            content: " *";
            color: #e74c3c;
        }
        
        .form-control {
            width: 100%;
            padding: 7px 10px;
            font-size: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #f8f9fa;
            color: #333;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #3498db;
            background: white;
        }
        
        .form-control[readonly] {
            background: #f8f9fa;
            cursor: not-allowed;
        }
        
        select.form-control {
            cursor: pointer;
        }
        
        textarea.form-control {
            min-height: 50px;
            resize: vertical;
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -8px;
        }
        
        .col {
            flex: 1;
            padding: 0 8px;
            min-width: 150px;
        }
        
        .col-2 {
            flex: 2;
        }
        
        .bukti-img {
            max-width: 150px;
            max-height: 150px;
            border-radius: 4px;
            border: 1px solid #ddd;
            display: block;
            margin-top: 5px;
        }
        
        .bukti-link {
            display: inline-block;
            margin-top: 5px;
            font-size: 11px;
            color: #3498db;
            text-decoration: none;
        }
        
        .bukti-link:hover {
            text-decoration: underline;
        }
        
        .total-value {
            font-weight: 600;
            color: #e74c3c;
        }
        
        .btn {
            padding: 8px 18px;
            font-size: 12px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            border: none;
        }
        
        .btn-cancel {
            background: #95a5a6;
            color: white;
        }
        
        .btn-submit {
            background: #2ecc71;
            color: white;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        .buttons {
            text-align: right;
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            font-size: 10px;
            border-radius: 12px;
            font-weight: 500;
            margin-left: 10px;
        }
        
        .status-waiting {
            background: #ffeaa7;
            color: #e17055;
        }
        
        .status-paid {
            background: #a3e4d7;
            color: #1d8348;
        }
        
        @media (max-width: 768px) {
            .col {
                flex: 100%;
                margin-bottom: 8px;
            }
            
            .row {
                margin: 0;
            }
            
            .content {
                padding: 15px;
            }
            
            .buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
        
        .no-bukti {
            color: #95a5a6;
            font-size: 11px;
            font-style: italic;
            padding: 6px;
            background: #f8f9fa;
            border-radius: 4px;
            border: 1px dashed #ddd;
            display: inline-block;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Edit Status Sewa</h1>
        </div>
        
        <div class="content">
            <form method="post" class="theform" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                
                <div class="section">
                    <div class="section-title">Info Penyewa</div>
                    
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Kode Sewa</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($id); ?>" readonly>
                            </div>
                        </div>
                        
                        <div class="col">
                            <div class="form-group">
                                <label class="required">Status</label>
                                <select class="form-control" name="status" required>
                                    <?php
                                        $stt = $result['status'];
                                        echo "<option value='$stt' selected>".strtoupper($stt)."</option>";
                                        echo "<option value='Menunggu Pembayaran'>".strtoupper("Menunggu Pembayaran")."</option>";
                                        echo "<option value='Sudah Dibayar'>".strtoupper("Sudah Dibayar")."</option>";
                                        echo "<option value='Cancel'>".strtoupper("Cancel")."</option>";
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Penyewa</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($result['nama_user']); ?>" readonly>
                            </div>
                        </div>
                        
                        <div class="col">
                            <div class="form-group">
                                <label>Telepon</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($result['telp']); ?>" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea class="form-control" readonly><?php echo htmlspecialchars($result['alamat']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Bukti Bayar</label>
                        <div>
                            <?php if(!empty($bukti)): ?>
                                <a href="../image/bukti/<?php echo htmlspecialchars($bukti); ?>" target="_blank" class="bukti-link">
                                    <img src="../image/bukti/<?php echo htmlspecialchars($bukti); ?>" class="bukti-img" alt="Bukti Bayar">
                                    <span>Klik untuk lihat ukuran penuh</span>
                                </a>
                            <?php else: ?>
                                <div class="no-bukti">Belum ada bukti pembayaran</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-title">Detail Sewa</div>
                    
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Baju</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($result['nama_baju']); ?>" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Tanggal Mulai</label>
                                <input type="text" class="form-control" value="<?php echo IndonesiaTgl($result['tgl_mulai']); ?>" readonly>
                            </div>
                        </div>
                        
                        <div class="col">
                            <div class="form-group">
                                <label>Tanggal Selesai</label>
                                <input type="text" class="form-control" value="<?php echo IndonesiaTgl($result['tgl_selesai']); ?>" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Durasi</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($result['durasi']); ?> hari" readonly>
                            </div>
                        </div>
                        
                        <div class="col">
                            <div class="form-group">
                                <label>Total Biaya</label>
                                <input type="text" class="form-control total-value" value="<?php echo format_rupiah($total); ?>" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Status Saat Ini</label>
                        <div>
                            <?php 
                            $status = strtolower($result['status']);
                            $statusClass = 'status-badge ';
                            if(strpos($status, 'sudah dibayar') !== false || strpos($status, 'paid') !== false) {
                                $statusClass .= 'status-paid';
                            } else {
                                $statusClass .= 'status-waiting';
                            }
                            ?>
                            <span class="<?php echo $statusClass; ?>"><?php echo htmlspecialchars($result['status']); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="buttons">
                    <button type="button" class="btn btn-cancel" onclick="window.history.back()">Batal</button>
                    <button type="submit" name="submit" class="btn btn-submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
    // Konfirmasi sebelum submit jika status diubah ke Cancel
    document.querySelector('.theform').addEventListener('submit', function(e) {
        var statusSelect = this.querySelector('select[name="status"]');
        var currentStatus = "<?php echo $result['status']; ?>";
        
        if(statusSelect.value.toLowerCase() === 'cancel' && currentStatus.toLowerCase() !== 'cancel') {
            if(!confirm('Yakin ingin membatalkan sewa ini?')) {
                e.preventDefault();
            }
        }
    });
    </script>
</body>
</html>
<?php } ?>