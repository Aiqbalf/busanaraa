<?php
session_start();
require_once("penting/config.php");

if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header("location:index.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("ID tidak ditemukan.");
}

$id = intval($_GET['id']);

/* === AMBIL DATA MEMBER === */
$stmt = $koneksidb->prepare("SELECT nama_user, email, telp, alamat FROM member WHERE id_user = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($nama, $email, $telp, $alamat);
$stmt->fetch();

if ($stmt->num_rows == 0) {
    die("Member tidak ditemukan.");
}

/* === UPDATE MEMBER === */
if (isset($_POST['update'])) {
    $nama_u = $_POST['nama'];
    $email_u = $_POST['email'];
    $telp_u = $_POST['telp'];
    $alamat_u = $_POST['alamat'];

    $upd = $koneksidb->prepare("
        UPDATE member 
        SET nama_user=?, email=?, telp=?, alamat=? 
        WHERE id_user=?
    ");
    $upd->bind_param("ssssi", $nama_u, $email_u, $telp_u, $alamat_u, $id);
    $upd->execute();

    echo "<script>alert('Data member berhasil diperbarui');</script>";
    echo "<script>window.location.href='member.php'</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Member</title>
<link rel="stylesheet" href="assets/css/leftbar.css">

<style>
body {
    margin: 0;
    padding: 0;
    background: #f4f6f9;
    font-family: Arial, sans-serif;
}

/* Sidebar tetap */
.sidebar {
    width: 180px !important;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
}

/* Konten utama */
.content {
    margin-left: 250px;
    padding: 30px;
    min-height: 100vh;
}

/* Judul */
.page-title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 25px;
    padding-bottom: 8px;
    border-bottom: 3px solid #d2d7e2;
}

/* BOX FORM */
.form-box {
    background: white;
    padding: 25px;
    border-radius: 8px;
    max-width: 550px;
    border: 1px solid #d5d9e2;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
}

/* Label */
.form-box label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
    color: #2d3e50;
}

/* Input */
.form-box input,
.form-box textarea {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #cdd4df;
    margin-bottom: 15px;
    background: #f9fafc;
    font-size: 15px;
}

.form-box textarea {
    resize: vertical;
    height: 80px;
}

/* Tombol */
.btn-save {
    background: #2e518b;
    color: white;
    padding: 10px 18px;
    border: none;
    border-radius: 6px;
    font-size: 15px;
    cursor: pointer;
    font-weight: 600;
}

.btn-cancel {
    background: #dc3545;
    color: white;
    padding: 10px 18px;
    border: none;
    border-radius: 6px;
    font-size: 15px;
    cursor: pointer;
    font-weight: 600;
    margin-left: 10px;
}
</style>
</head>

<body>

<?php require_once("penting/leftbar.php"); ?>

<div class="content">

    <div class="page-title">EDIT MEMBER</div>

    <div class="form-box">

        <form method="POST">

            <label>Nama Lengkap</label>
            <input type="text" name="nama" value="<?= htmlentities($nama) ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?= htmlentities($email) ?>" required>

            <label>No. Telepon</label>
            <input type="text" name="telp" value="<?= htmlentities($telp) ?>" required>

            <label>Alamat</label>
            <textarea name="alamat" required><?= htmlentities($alamat) ?></textarea>

            <button type="submit" name="update" class="btn-save">Simpan Perubahan</button>
            <a href="member.php"><button type="button" class="btn-cancel">Batal</button></a>

        </form>

    </div>

</div>

</body>
</html>
