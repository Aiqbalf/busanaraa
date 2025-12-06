<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('penting/config.php');

if (!isset($koneksidb) || mysqli_connect_errno()) {
    die("ERROR: Koneksi database gagal.");
}

if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

if (isset($_GET['del'])) {
    $id = intval($_GET['del']);
    $del = $koneksidb->prepare("DELETE FROM member WHERE id_user = ?");
    $del->bind_param("i", $id);
    $del->execute();

    echo "<script>alert('Member berhasil dihapus'); window.location='member.php';</script>";
    exit();
}

$sql = "SELECT id_user, nama_user, email, telp, alamat FROM member ORDER BY id_user DESC";
$results = mysqli_query($koneksidb, $sql);
$cnt = 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Menu Member | Admin</title>

<link rel="stylesheet" href="assets/css/leftbar.css">

<style>
body { margin:0; padding:0; overflow-x:hidden; background:#f4f6f9; }
.sidebar { width:180px; height:100vh; position:fixed; top:0; left:0; z-index:999; }
.content { margin-left:250px; padding:30px; min-height:100vh; background:#f4f6f9; }
.header-box { font-size:22px; font-weight:700; margin-bottom:20px; color:#1f2d3d; }
.btn-member { background:#2e518b; padding:10px 18px; border-radius:4px; color:white; font-weight:600; text-decoration:none; cursor:default; }
.search-box { display:flex; align-items:center; gap:8px; background:white; border:2px solid #d3d7dd; border-radius:20px; padding:6px 14px; width:260px; }
.search-box input { border:none; outline:none; flex-grow:1; }
.table-container { border:2px solid #ccd2dc; border-radius:6px; overflow:hidden; }
.member-table { width:100%; border-collapse:collapse; }
.member-table thead tr { background:#d6dee9; font-weight:bold; text-transform:uppercase; }
.member-table th, .member-table td { padding:12px; border:1px solid #dce1e8; }
.btn-action { padding:5px 10px; border-radius:3px; font-size:0.9em; text-decoration:none; }
.btn-edit { background:#ffc107; color:black; }
.btn-delete { background:#dc3545; color:white; }

@media(max-width:900px){ .content{margin-left:0;} }
</style>
</head>
<body>

<?php require_once("penting/leftbar.php"); ?>

<div class="content">

    <div class="header-box">MENU MEMBER</div>

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:15px;">
        <span class="btn-member">Daftar Member</span>
        <div class="search-box">
            🔍 <input type="text" id="searchInput" placeholder="Cari Member...">
        </div>
    </div>

    <div class="table-container">
        <table class="member-table" id="memberTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telp</th>
                    <th>Alamat</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($results)) { ?>
                <tr>
                    <td><?= $cnt ?></td>
                    <td><?= htmlentities($row['nama_user']) ?></td>
                    <td><?= htmlentities($row['email']) ?></td>
                    <td><?= htmlentities($row['telp']) ?></td>
                    <td><?= htmlentities($row['alamat']) ?></td>
                    <td style="text-align:center;">
                        <a class="btn-action btn-edit" href="editmember.php?id=<?= $row['id_user'] ?>">Edit</a>
                        <a class="btn-action btn-delete" onclick="return confirm('Hapus member ini?')" href="member.php?del=<?= $row['id_user'] ?>">Hapus</a>
                    </td>
                </tr>
                <?php $cnt++; } ?>
            </tbody>
        </table>
    </div>

</div>

<script>
document.getElementById("searchInput").addEventListener("keyup", function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#memberTable tbody tr");
    rows.forEach(r => {
        r.style.display = r.textContent.toLowerCase().includes(filter) ? "" : "none";
    });
});
</script>

</body>
</html>