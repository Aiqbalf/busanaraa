<?php
session_start();
error_reporting(0);

include __DIR__ . '/penting/config.php';

// Ambil ID
$id = $_GET['id'];

// Ambil data baju
$sql = mysqli_query($koneksidb, "SELECT * FROM baju WHERE id_baju='$id'");
$data = mysqli_fetch_assoc($sql);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan'); window.location='baju.php';</script>";
    exit;
}

// ==== UPDATE DATA LANGSUNG DI FILE INI ====
if (isset($_POST['submit'])) {

    $nama_baju  = $_POST['nama_baju'];
    $jenis_baju = $_POST['jenis_baju'];
    $deskripsi  = $_POST['deskripsi'];
    $kategori   = $_POST['kategori'];
    $harga      = $_POST['harga'];

    // Foto lama
    $foto_lama1 = $data['img1'];
    $foto_lama2 = $data['img2'];
    $foto_lama3 = $data['img3'];

    // Upload baru (jika ada)
    $img1 = $_FILES['img1']['name'] ? time().'_1_'.$_FILES['img1']['name'] : $foto_lama1;
    $img2 = $_FILES['img2']['name'] ? time().'_2_'.$_FILES['img2']['name'] : $foto_lama2;
    $img3 = $_FILES['img3']['name'] ? time().'_3_'.$_FILES['img3']['name'] : $foto_lama3;

    $folder = "img/";

    // Simpan gambar baru
    if ($_FILES['img1']['name']) move_uploaded_file($_FILES['img1']['tmp_name'], $folder.$img1);
    if ($_FILES['img2']['name']) move_uploaded_file($_FILES['img2']['tmp_name'], $folder.$img2);
    if ($_FILES['img3']['name']) move_uploaded_file($_FILES['img3']['tmp_name'], $folder.$img3);

    // Update DB
    $update = mysqli_query($koneksidb, "
        UPDATE baju SET
        nama_baju='$nama_baju',
        jenis_baju='$jenis_baju',
        deskripsi='$deskripsi',
        kategori='$kategori',
        harga='$harga',
        img1='$img1',
        img2='$img2',
        img3='$img3'
        WHERE id_baju='$id'
    ");

    if ($update) {
        echo "<script>alert('Data berhasil diupdate'); window.location='baju.php';</script>";
    } else {
        echo "<script>alert('Gagal update data');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Edit Baju</title>

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

.box-field textarea,
.box-field input{
    width:100%;
    border:none;
    padding:6px 9px;
    outline:none;
    border-radius:14px;
    font-size:11px;
}

textarea{
    font-size:10px !important;
}

.upload-box{
    background:#bfc0c3;
    border-radius:16px;
    padding:15px;
    margin-bottom:16px;
    box-shadow:4px 4px 8px #999;
    text-align:center;
    font-size:11px;
}

.upload-box h3{
    font-size:12px;
}

.preview-img{
    width:90px;
    height:90px;
    object-fit:cover;
    border-radius:8px;
    margin-bottom:8px;
    background:#fff;
    border:1px solid #ccc;
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

    <div class="header-title">EDIT BAJU</div>

    <div class="form-section">

    <form method="post" enctype="multipart/form-data">

        <div style="display:flex; gap:25px;">
            <div style="width:60%;">
                <label>Nama Baju</label>
                <div class="box-field">
                    <input type="text" name="nama_baju" value="<?= $data['nama_baju'] ?>" required>
                </div>
            </div>

            <div style="width:40%;">
                <label>Jenis Baju</label>
                <div class="box-field">
                    <input type="text" name="jenis_baju" value="<?= $data['jenis_baju'] ?>" required>
                </div>
            </div>
        </div>

        <div style="display:flex; gap:25px;">
            <div style="width:60%;">
                <label>Deskripsi</label>
                <div class="box-field" style="height:120px;">
                    <textarea name="deskripsi" style="height:90px;"><?= $data['deskripsi'] ?></textarea>
                </div>
            </div>

            <div style="width:40%;">
                <label>Kategori</label>
                <div class="box-field">
                    <input type="text" name="kategori" value="<?= $data['kategori'] ?>">
                </div>
            </div>
        </div>

        <div style="display:flex; gap:25px;">
            <div style="width:60%;">
                <label>Harga per hari</label>
                <div class="box-field">
                    <input type="number" name="harga" value="<?= $data['harga'] ?>" required>
                </div>
            </div>

            <div style="width:40%;">
                <!-- stok kalau ada -->
            </div>
        </div>

        <div class="upload-box">
            <h3>Foto Lama</h3>

            <div style="display:flex; gap:25px; margin:12px 0;">
                <img src="img/<?= $data['img1'] ?>" class="preview-img">
                <img src="img/<?= $data['img2'] ?>" class="preview-img">
                <img src="img/<?= $data['img3'] ?>" class="preview-img">
            </div>

            <h3>Ganti Foto (Opsional)</h3>

            <div style="display:flex; gap:25px; margin-top:14px;">
                <div style="width:33%;">
                    Gambar 1
                    <div class="box-field"><input type="file" name="img1"></div>
                </div>
                
                <div style="width:33%;">
                    Gambar 2
                    <div class="box-field"><input type="file" name="img2"></div>
                </div>
                
                <div style="width:33%;">
                    Gambar 3
                    <div class="box-field"><input type="file" name="img3"></div>
                </div>
            </div>
        </div>

        <div class="btn-area">
            <button class="btn-simpan" name="submit">SIMPAN</button>
            <a href="baju.php" class="btn-batal">BATAL</a>
        </div>

    </form>

    </div>

</div>

</body>
</html>