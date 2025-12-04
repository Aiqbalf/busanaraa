<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include __DIR__ . '/penting/config.php';

// Ambil ID dari GET
$id = $_GET['id'];

// Ambil data baju
$sql = mysqli_query($koneksidb, "
   SELECT baju.*, jenis.nama_jenis 
   FROM baju 
   LEFT JOIN jenis ON baju.id_jenis = jenis.id_jenis
   WHERE baju.id_baju='$id'
");
$data = mysqli_fetch_assoc($sql);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan'); window.location='baju.php';</script>";
    exit;
}

// Ambil semua jenis baju
$listJenis = mysqli_query($koneksidb, "SELECT * FROM jenis ORDER BY nama_jenis ASC");

// Jika menyimpan
if (isset($_POST['submit'])) {

    $nama_baju  = $_POST['nama_baju'];
    $id_jenis   = $_POST['id_jenis'];
    $deskripsi  = $_POST['deskripsi'];
    $kategori   = $_POST['kategori'];
    $harga      = $_POST['harga'];

    // Foto lama dari database
    $foto_lama1 = $data['gambar1'];
    $foto_lama2 = $data['gambar2'];
    $foto_lama3 = $data['gambar3'];

    $folder = "img/";

    // Buat nama file baru jika ada upload
    $gambar1 = !empty($_FILES['img1']['name']) ? time().'_1_'.$_FILES['img1']['name'] : $foto_lama1;
    $gambar2 = !empty($_FILES['img2']['name']) ? time().'_2_'.$_FILES['img2']['name'] : $foto_lama2;
    $gambar3 = !empty($_FILES['img3']['name']) ? time().'_3_'.$_FILES['img3']['name'] : $foto_lama3;

    // Upload file baru dan hapus file lama jika ada
    if (!empty($_FILES['img1']['name'])) {
        if (!empty($foto_lama1) && file_exists($folder.$foto_lama1)) {
            unlink($folder.$foto_lama1); // hapus foto lama
        }
        move_uploaded_file($_FILES['img1']['tmp_name'], $folder.$gambar1);
    }

    if (!empty($_FILES['img2']['name'])) {
        if (!empty($foto_lama2) && file_exists($folder.$foto_lama2)) {
            unlink($folder.$foto_lama2);
        }
        move_uploaded_file($_FILES['img2']['tmp_name'], $folder.$gambar2);
    }

    if (!empty($_FILES['img3']['name'])) {
        if (!empty($foto_lama3) && file_exists($folder.$foto_lama3)) {
            unlink($folder.$foto_lama3);
        }
        move_uploaded_file($_FILES['img3']['tmp_name'], $folder.$gambar3);
    }

    // UPDATE DATABASE
    $update = mysqli_query($koneksidb, "
        UPDATE baju SET
            nama_baju='$nama_baju',
            id_jenis='$id_jenis',
            deskripsi='$deskripsi',
            kategori='$kategori',
            harga='$harga',
            gambar1='$gambar1',
            gambar2='$gambar2',
            gambar3='$gambar3'
        WHERE id_baju='$id'
    ") or die(mysqli_error($koneksidb));

    if ($update) {
        echo "<script>alert('Data berhasil diupdate'); window.location='baju.php';</script>";
        exit;
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
.box-field input,
.box-field select{
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
                <select name="id_jenis" required>
                    <?php while($j = mysqli_fetch_assoc($listJenis)) { ?>
                        <option value="<?= $j['id_jenis'] ?>" 
                            <?= ($j['id_jenis'] == $data['id_jenis']) ? 'selected' : '' ?>>
                            <?= $j['nama_jenis'] ?>
                        </option>
                    <?php } ?>
                </select>
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
                <select name="kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="anak laki-laki" <?= ($data['kategori']=='anak laki-laki') ? 'selected' : '' ?>>Anak Laki-laki</option>
                    <option value="anak perempuan" <?= ($data['kategori']=='anak perempuan') ? 'selected' : '' ?>>Anak Perempuan</option>
                    <option value="dewasa laki-laki" <?= ($data['kategori']=='dewasa laki-laki') ? 'selected' : '' ?>>Dewasa Laki-laki</option>
                    <option value="dewasa perempuan" <?= ($data['kategori']=='dewasa perempuan') ? 'selected' : '' ?>>Dewasa Perempuan</option>
                </select>
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
            <div style="width:40%;"></div>
        </div>

        <div class="upload-box">
            <h3>Foto Lama</h3>
            <div style="display:flex; gap:25px; margin:12px 0;">
                <img src="img/<?= $data['gambar1'] ?>" class="preview-img">
                <img src="img/<?= $data['gambar2'] ?>" class="preview-img">
                <img src="img/<?= $data['gambar3'] ?>" class="preview-img">
            </div>
            <h3>Ganti Foto (Opsional)</h3>
            <div style="display:flex; gap:25px; margin-top:14px;">
                <div style="width:33%;">
                    <div class="box-field"><input type="file" name="img1"></div>
                </div>
                <div style="width:33%;">
                    <div class="box-field"><input type="file" name="img2"></div>
                </div>
                <div style="width:33%;">
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