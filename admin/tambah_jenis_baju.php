<?php
include __DIR__ . '/penting/config.php'; // pastikan $koneksidb tersedia

if(isset($_POST['submit'])){
    $jenis_baru = mysqli_real_escape_string($koneksidb, $_POST['jenis_baru']);

    $sql = "INSERT INTO jenis (nama_jenis) VALUES ('$jenis_baru')";
    if(mysqli_query($koneksidb, $sql)){
        echo "<script>window.location.href='jenis_baju.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal menambahkan data!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Tambah Jenis Baju</title>

<link rel="stylesheet" href="assets/css/leftbar.css">

<style>
/* gaya sama seperti form tambah baju */
body{
    margin:0;
    font-family:'Segoe UI', sans-serif;
    background:#efeff4;
    font-size:11px;
}

.main-content{
    margin-left:240px;
    padding:25px 40px;
}

.header-title{
    width:100%;
    background:#80838a;
    padding:10px 22px;
    font-size:14px;
    color:white;
    font-weight:500;
}

.form-section{
    background:white;
    padding:23px;
    border-radius:8px;
    margin-top:18px;
    margin-left:15px;
    box-shadow:0px 4px 8px rgba(0,0,0,0.13);
}

.box-field{
    background:#bfc0c3;
    border-radius:16px;
    padding:8px 12px;
    margin-bottom:15px;
    box-shadow:4px 4px 8px #999;
}

.box-field input{
    width:100%;
    border:none;
    padding:6px 9px;
    border-radius:14px;
    outline:none;
}

.btn-area{
    display:flex;
    justify-content:center;
    gap:14px;
    margin-top:20px;
}

.btn-simpan{
    padding:8px 35px;
    font-size:11px;
    font-weight:600;
    border:none;
    border-radius:6px;
    cursor:pointer;
    background:#686a6e;
    color:white;
}
.btn-simpan:hover{
    background:#5a5c60;
}

.btn-batal{
    padding:8px 35px;
    font-size:11px;
    font-weight:600;
    border:none;
    border-radius:6px;
    cursor:pointer;
    background:#c2c3c7;
    color:white;
    text-align:center;
}
.btn-batal:hover{
    background:#babbbc;
}
</style>
</head>

<body>

<?php include('penting/leftbar.php'); ?>

<div class="main-content">

    <div class="header-title">FORM TAMBAH JENIS BAJU</div>

    <div class="form-section">

        <form method="post">

            <label>Nama Jenis Baju</label>
            <div class="box-field">
                <input type="text" name="jenis_baru" required>
            </div>

            <div class="btn-area">
                <button class="btn-simpan" name="submit">SIMPAN</button>
                <a href="jenis_baju.php" class="btn-batal">BATAL</a>
            </div>

        </form>

    </div>
</div>

</body>
</html>