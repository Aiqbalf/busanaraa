<?php
session_start();
error_reporting(0);
include('penting/config.php');

if($_GET) {
    $Kode = $_GET['code'];
    $mySql ="SELECT * FROM member WHERE email ='$Kode'";
    $myQry = mysqli_query($koneksidb, $mySql);
    $result = mysqli_fetch_array($myQry);
}
else {
    echo "Nomor Transaksi Tidak Terbaca";
    exit;
}

$nama = isset($result['nama_user']) ? $result['nama_user'] : '';
$email = isset($result['email']) ? $result['email'] : '';
$telp = isset($result['telp']) ? $result['telp'] : '';
$alamat = isset($result['alamat']) ? $result['alamat'] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Detail Member</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 20px;
        }
        
        .card {
            background: white;
            max-width: 400px;
            margin: 0 auto;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .title {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
            font-size: 18px;
        }
        
        .field {
            margin-bottom: 15px;
        }
        
        .field label {
            display: block;
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-weight: 500;
        }
        
        .field .value {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #eaeaea;
            color: #333;
            font-size: 14px;
        }
        
        .field.address .value {
            min-height: 60px;
            white-space: pre-line;
        }
        
        .close-btn {
            display: block;
            width: 100%;
            background: #3498db;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 20px;
        }
        
        .close-btn:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="title">Detail Member</div>
        
        <div class="field">
            <label>Nama Lengkap</label>
            <div class="value"><?php echo htmlspecialchars($nama); ?></div>
        </div>
        
        <div class="field">
            <label>Email</label>
            <div class="value"><?php echo htmlspecialchars($email); ?></div>
        </div>
        
        <div class="field">
            <label>Telepon</label>
            <div class="value"><?php echo htmlspecialchars($telp); ?></div>
        </div>
        
        <div class="field address">
            <label>Alamat</label>
            <div class="value"><?php echo htmlspecialchars($alamat); ?></div>
        </div>
        
        <button class="close-btn" onclick="window.history.back()">Close</button>
    </div>
</body>
</html>