<?php
session_start();
error_reporting(0);
include __DIR__ . '/penting/config.php';

// Ambil ID
$id = $_GET['id'];

// Ambil data jenis
$sql = mysqli_query($koneksidb, "SELECT * FROM jenis WHERE id_jenis='$id'");
$data = mysqli_fetch_assoc($sql);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan'); window.location='jenis_baju.php';</script>";
    exit;
}

// ==== UPDATE DATA LANGSUNG DI FILE INI ====
if (isset($_POST['submit'])) {
    $nama_jenis = mysqli_real_escape_string($koneksidb, $_POST['nama_jenis']);

    $update = mysqli_query($koneksidb, "UPDATE jenis SET nama_jenis='$nama_jenis' WHERE id_jenis='$id'");

    if ($update) {
        echo "<script>alert('Data berhasil diupdate'); window.location='jenis_baju.php';</script>";
    } else {
        echo "<script>alert('Gagal update data');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Edit Jenis Baju</title>

<link rel="stylesheet" href="assets/css/leftbar.css">

<style>
body{
    margin:0;
    font-family: 'Segoe UI', sans-serif;
    background:#efeff4;
    font-size:11px;
}

.main-content {
    margin-left: 240px;
    padding: 25px 40px;
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
    box-shadow: 0px 4px 8px rgba(0,0,0,0.13);
    font-size:11px;
}

label {
    font-size:11px;
    font-weight:500;
}

.box-field{
    background:#bfc0c3;
    border-radius:16px;
    padding:8px 12px;
    margin-bottom:14px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:4px 4px 8px #999;
}

.box-field input{
    width:100%;
    border:none;
    padding:6px 9px;
    outline:none;
    border-radius:14px;
    font-size:11px;
}

.btn-area {
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
    display:inline-block;
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

    <div class="header-title">EDIT JENIS BAJU</div>

    <div class="form-section">

    <form method="post">

        <label>Nama Jenis Baju</label>
        <div class="box-field">
            <input type="text" name="nama_jenis" value="<?= $data['nama_jenis']; ?>" required>
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