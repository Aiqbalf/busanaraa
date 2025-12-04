<?php
session_start();
error_reporting(E_ALL);

// Judul halaman
$pagedesc = "Form Tambah Baju";

// === Include konfigurasi & fungsi ===
// Pastikan path sesuai struktur foldermu
include __DIR__ . '/penting/config.php';
include __DIR__ . '/penting/format_rupiah.php';


// ==== PROSES SIMPAN DATA ====
if(isset($_POST['submit'])){
    $nama_baju  = mysqli_real_escape_string($koneksidb, $_POST['nama_baju']);
    $jenis_baju = mysqli_real_escape_string($koneksidb, $_POST['jenis_baju']); // ini id_jenis
    $deskripsi  = mysqli_real_escape_string($koneksidb, $_POST['deskripsi']);
    $kategori   = mysqli_real_escape_string($koneksidb, $_POST['kategori']);
    $harga      = mysqli_real_escape_string($koneksidb, $_POST['harga']);
    $stok_s     = mysqli_real_escape_string($koneksidb, $_POST['stok_s']);
    $stok_m     = mysqli_real_escape_string($koneksidb, $_POST['stok_m']);
    $stok_l     = mysqli_real_escape_string($koneksidb, $_POST['stok_l']);
    $stok_xl    = mysqli_real_escape_string($koneksidb, $_POST['stok_xl']);

    // Upload foto
    $folder = "img/";
    $img1 = $_FILES['img1']['name'] ? time().'_1_'.$_FILES['img1']['name'] : "";
    $img2 = $_FILES['img2']['name'] ? time().'_2_'.$_FILES['img2']['name'] : "";
    $img3 = $_FILES['img3']['name'] ? time().'_3_'.$_FILES['img3']['name'] : "";

    if($_FILES['img1']['name']) move_uploaded_file($_FILES['img1']['tmp_name'], $folder.$img1);
    if($_FILES['img2']['name']) move_uploaded_file($_FILES['img2']['tmp_name'], $folder.$img2);
    if($_FILES['img3']['name']) move_uploaded_file($_FILES['img3']['tmp_name'], $folder.$img3);

    // Simpan ke database
    $sql = "INSERT INTO baju 
        (nama_baju, id_jenis, deskripsi, kategori, harga, s, m, l, xl, gambar1, gambar2, gambar3)
        VALUES 
        ('$nama_baju', '$jenis_baju', '$deskripsi', '$kategori', '$harga', '$stok_s', '$stok_m', '$stok_l', '$stok_xl', '$img1', '$img2', '$img3')";

    if(mysqli_query($koneksidb, $sql)){
        echo "<script>alert('Data berhasil disimpan'); window.location='baju.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal menyimpan data: ".mysqli_error($koneksidb)."');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Form Tambah Baju</title>
<link rel="stylesheet" href="assets/css/leftbar.css">

<style>
body{
    margin:0;
    font-family: 'Segoe UI', sans-serif;
    background:#efeff4;
    font-size:11px;
}
.main-content { margin-left: 240px; padding: 25px 40px; }
.header-title{ width:100%; background:#80838a; padding:10px 22px; font-size:14px; color:white; font-weight:500; }
.form-section{ background:white; padding:23px; border-radius:8px; margin-top:18px; margin-left:15px; box-shadow: 0px 4px 8px rgba(0,0,0,0.13); font-size:11px; }
label { font-size:11px; font-weight:500; }
.box-field{ background:#bfc0c3; border-radius:16px; padding:8px 12px; margin-bottom:14px; display:flex; justify-content:space-between; align-items:center; box-shadow:4px 4px 8px #999; }
.box-field input,
.box-field select,
.box-field textarea{ width:100%; border:none; padding:6px 9px; outline:none; border-radius:14px; font-size:11px; }
textarea{ font-size:10px !important; }
.upload-box{ background:#bfc0c3; border-radius:16px; padding:15px; margin-bottom:16px; box-shadow:4px 4px 8px #999; text-align:center; font-size:11px; }
.upload-box h3{ font-size:12px; }
.ukuran-box{ background:#bfc0c3; border-radius:16px; padding:10px; margin-bottom:16px; text-align:center; box-shadow:4px 4px 8px #999; font-size:11px; }
.ukuran-box input{ width:40px; margin:0 6px; border:none; padding:6px; border-radius:14px; text-align:center; font-size:10px; }
.btn-area { display:flex; justify-content:center; gap:14px; margin-top:20px; }
.btn-simpan{ padding:8px 35px; font-size:11px; font-weight:600; border:none; border-radius:6px; cursor:pointer; background:#686a6e; color:white; }
.btn-simpan:hover{ background:#5a5c60; }
.btn-batal{ padding:8px 35px; font-size:11px; font-weight:600; border:none; border-radius:6px; cursor:pointer; background:#c2c3c7; color:white; display:inline-block; text-align:center; }
.btn-batal:hover{ background:#babbbc; }
</style>
</head>
<body>

<?php include('penting/leftbar.php'); ?>

<div class="main-content">
    <div class="header-title">FORM TAMBAH BAJU</div>

    <div class="form-section">
    <form method="post" enctype="multipart/form-data">

        <div style="display:flex; gap:25px;">
            <div style="width:60%;">
                <label>Nama Baju</label>
                <div class="box-field"><input type="text" name="nama_baju" required></div>
            </div>
            <div style="width:40%;">
                <label>Jenis Baju</label>
                <div class="box-field">
                    <select name="jenis_baju" required>
                        <option value="">-- Pilih Jenis --</option>
                        <?php
                        $qjenis = mysqli_query($koneksidb, "SELECT * FROM jenis ORDER BY nama_jenis ASC");
                        while($rjenis = mysqli_fetch_assoc($qjenis)){
                            echo '<option value="'.$rjenis['id_jenis'].'">'.$rjenis['nama_jenis'].'</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <div style="display:flex; gap:25px;">
            <div style="width:60%;">
                <label>Deskripsi</label>
                <div class="box-field" style="height:120px;"><textarea name="deskripsi" style="height:90px;"></textarea></div>
            </div>
            
            <div style="width:40%;">
                <label>Kategori</label>
                <div class="box-field">
                    <select name="kategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="anak laki-laki">Anak Laki-laki</option>
                        <option value="anak perempuan">Anak Perempuan</option>
                        <option value="dewasa laki-laki">Dewasa Laki-laki</option>
                        <option value="dewasa perempuan">Dewasa Perempuan</option>
                    </select>
                </div>
            </div>
        </div>

        <div style="display:flex; gap:25px;">
            <div style="width:60%;">
                <label>Harga per hari</label>
                <div class="box-field"><input type="number" name="harga" required></div>
            </div>
            <div style="width:40%;">
                <label>Stok Ukuran</label>
                <div class="ukuran-box">
                    S <input type="number" name="stok_s" min="0">
                    M <input type="number" name="stok_m" min="0">
                    L <input type="number" name="stok_l" min="0">
                    XL <input type="number" name="stok_xl" min="0">
                </div>
            </div>
        </div>

        <div class="upload-box">
            <h3>Upload Foto</h3>
            <div style="display:flex; gap:25px; margin-top:14px;">
                <div style="width:33%;">Gambar 1<div class="box-field"><input type="file" name="img1"></div></div>
                <div style="width:33%;">Gambar 2<div class="box-field"><input type="file" name="img2"></div></div>
                <div style="width:33%;">Gambar 3<div class="box-field"><input type="file" name="img3"></div></div>
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