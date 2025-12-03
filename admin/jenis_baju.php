<?php
session_start();
error_reporting(0);

include('penting/config.php'); // Koneksi ke database
$pagedesc = "Kelola Jenis Baju";
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title><?= $pagedesc ?> - Busanara</title>

<link rel="stylesheet" href="assets/css/leftbar.css">

<style>
body{
    margin:0;
    font-family: Arial, sans-serif;
    background:#f2f2f7;
}
.content{
    margin-left:260px;
    padding:25px;
}
.page-title{
    font-size:28px;
    font-weight:700;
    color:#2c2c2c;
    margin-bottom:5px;
    margin-left:35px;
}
.page-subtitle{
    font-size:14px;
    color:#6b6b6b;
    margin-left:35px;
    margin-bottom:20px;
}
.box-header{
    background:#7d8189;
    padding:25px;
    border-radius:8px 8px 0 0;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.btn-tambah{
    background:#3a8bc2;
    color:white;
    padding:12px 28px;
    font-weight:bold;
    border-radius:8px;
    text-decoration:none;
    font-size:15px;
    box-shadow:0 3px 5px rgba(0,0,0,.2);
}
.search-box{
    background:#eceadf;
    padding:10px 18px;
    width:250px;
    border-radius:30px;
    display:flex;
    align-items:center;
}
.search-box input{
    border:none;
    outline:none;
    background:transparent;
    margin-left:10px;
    width:100%;
    font-size:14px;
}
.table-wrapper{
    background:white;
    padding:20px;
    border-radius:0 0 8px 8px;
    box-shadow:0px 3px 8px rgba(0,0,0,0.1);
}
table{
    width:100%;
    border-collapse:collapse;
}
thead{
    background:#b5c2d3;
    font-weight:bold;
}
th, td{
    padding:12px;
    border-bottom:1px solid #ccc;
    text-align:center;
}
.btn-edit{
    background:#52c152;
    color:white;
    padding:8px 15px;
    border-radius:5px;
    text-decoration:none;
    font-weight:bold;
}
.btn-hapus{
    background:#e14b4b;
    color:white;
    padding:8px 15px;
    border-radius:5px;
    text-decoration:none;
    font-weight:bold;
}
</style>

</head>
<body>

<?php include('penting/header.php'); ?>
<?php include('penting/leftbar.php'); ?>

<div class="content">

    <div class="page-title">Kelola Jenis Baju</div>
    <div class="page-subtitle">Atur jenis baju untuk digunakan pada form tambah baju!</div>

    <div class="box-header">
        <a href="tambah_jenis_baju.php" class="btn-tambah">+ TAMBAH JENIS</a>

        <div class="search-box">
            🔍 <input type="text" id="searchInput" placeholder="Cari jenis...">
        </div>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Baju</th>
                    <th>OPSI</th>
                </tr>
            </thead>

            <tbody>
                <?php
                // Query ambil data dari database
                $sql = "SELECT * FROM jenis ORDER BY id_jenis DESC";
                $q = mysqli_query($koneksidb, $sql);
                $no = 1;

                while ($row = mysqli_fetch_array($q)) {
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['nama_jenis']; ?></td>
                    <td>
                        <a href="edit_jenis_baju.php?id=<?= $row['id_jenis']; ?>" class="btn-edit">EDIT</a>
                        <a href="hapus_jenis_baju.php?id=<?= $row['id_jenis']; ?>" class="btn-hapus" onclick="return confirm('Yakin hapus?')">HAPUS</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>

        </table>
    </div>

</div>

<script>
// Search filter
document.getElementById("searchInput").addEventListener("keyup", function(){
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("tbody tr");

    rows.forEach(r => {
        let jenis = r.cells[1].textContent.toLowerCase();
        r.style.display = jenis.includes(filter) ? "" : "none";
    });
});
</script>

</body>
</html>