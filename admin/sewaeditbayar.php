<?php
session_start();
error_reporting(0);
include('penting/config.php');
include('penting/format_rupiah.php');
include('penting/library.php');

if(strlen($_SESSION['alogin'])==0){	
    header('location:index.php');
}else{
    if(isset($_POST['submit'])){
        $status = $_POST['status'];
        $kode = $_POST['id'];
        $mySql = "UPDATE booking SET status = '$status' WHERE kode_booking='$kode'";
        $myQry = mysqli_query($koneksidb, $mySql);
        $mySql1 = "UPDATE cek_booking SET status = '$status' WHERE kode_booking='$kode'";
        $myQry1 = mysqli_query($koneksidb, $mySql1);
        echo "<script type='text/javascript'>
                alert('Status berhasil diupdate.'); 
                document.location = 'sewa_bayar.php'; 
              </script>";
    }

    $id = $_GET['id'];
    $sqlsewa = "SELECT booking.*,baju.*,jenis.*,member.* FROM booking,baju,jenis,member WHERE booking.id_baju=baju.id_baju
                AND baju.id_jenis=jenis.id_jenis AND booking.email=member.email AND booking.kode_booking ='$id'";
    $querysewa = mysqli_query($koneksidb,$sqlsewa);
    $result = mysqli_fetch_array($querysewa);
    $total = $result['durasi']*$result['harga'];
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
            max-width: 800px;
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
            font-size: 18px;
            font-weight: 500;
        }
        
        .content {
            padding: 20px;
        }
        
        .section {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .section-title {
            font-size: 14px;
            color: #3498db;
            font-weight: 600;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 8px 10px;
            font-size: 13px;
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
            min-height: 60px;
            resize: vertical;
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        
        .col {
            flex: 1;
            padding: 0 10px;
            min-width: 200px;
        }
        
        .btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 8px 20px;
            font-size: 13px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
        }
        
        .btn:hover {
            background: #2980b9;
        }
        
        .btn-submit {
            background: #2ecc71;
        }
        
        .btn-submit:hover {
            background: #27ae60;
        }
        
        .buttons {
            text-align: right;
            margin-top: 20px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            font-size: 11px;
            border-radius: 12px;
            font-weight: 500;
            margin-left: 10px;
        }
        
        .status-pending {
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
                margin-bottom: 10px;
            }
            
            .row {
                margin: 0;
            }
            
            .content {
                padding: 15px;
            }
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
                                <label>Status <span style="color:red">*</span></label>
                                <select class="form-control" name="status" required>
                                    <?php
                                        $stt = $result['status'];
                                        echo "<option value='$stt' selected>".strtoupper($stt)."</option>";
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
                                <input type="text" class="form-control" value="<?php echo format_rupiah($total); ?>" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Status Saat Ini</label>
                        <div>
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
                </div>
                
                <div class="buttons">
                    <button type="button" class="btn" onclick="window.history.back()">Batal</button>
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