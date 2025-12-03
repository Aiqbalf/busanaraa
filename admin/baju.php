<?php
session_start();
error_reporting(0);
include('penting/config.php'); 
include('penting/format_rupiah.php'); 
$pagedesc = "Kelola Data Baju";
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?= $pagedesc ?> - Busanara</title>

  <link rel="stylesheet" href="assets/css/leftbar.css" />

  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f2f2f7;
    }

    .content {
      margin-left: 260px;
      padding: 25px;
    }

.page-title {
  font-size: 28px;
  font-weight: 700;
  color: #2c2c2c;
  margin-bottom: 5px;
  margin-left: 35px;
}

.page-subtitle {
  font-size: 14px;
  color: #6b6b6b;
  margin-bottom: 20px;
  margin-left: 35px;
}

    .box-header {
      background: #7d8189;
      padding: 25px;
      border-radius: 8px 8px 0 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .btn-tambah {
        background: #3a8bc2;
        color: white;
        padding: 12px 28px;
        font-weight: bold;
        border-radius: 8px;
        text-decoration: none;
        font-size: 15px;
        box-shadow: 0 3px 5px rgba(0,0,0,0.2);
        margin-left: 13px;
    }

    .search-box {
      background: #eceadf;
      padding: 10px 18px;
      width: 250px;
      border-radius: 30px;
      display: flex;
      align-items: center;
    }

    .search-box input {
      border: none;
      outline: none;
      background: transparent;
      margin-left: 10px;
      width: 100%;
      font-size: 14px;
    }

    .table-wrapper {
      background: white;
      padding: 20px;
      border-radius: 0 0 8px 8px;
      box-shadow: 0px 3px 8px rgba(0,0,0,0.1);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    thead {
      background: #b5c2d3;
      font-weight: bold;
    }

    th, td {
      padding: 12px;
      border-bottom: 1px solid #ccc;
      text-align: center;
    }

    .img-baju {
      width: 55px;
      height: 55px;
      object-fit: cover;
      border-radius: 5px;
    }

    .btn-edit {
      background: #52c152;
      color: white;
      padding: 8px 15px;
      border-radius: 5px;
      text-decoration: none;
      font-weight: bold;
    }

    .btn-edit:hover { background: #46a346; }

    .btn-hapus {
      background: #e14b4b;
      color: white;
      padding: 8px 15px;
      border-radius: 5px;
      text-decoration: none;
      font-weight: bold;
    }

    .btn-hapus:hover { background: #c13a3a; }
  </style>

</head>
<body>

<?php include('penting/header.php'); ?>
<?php include('penting/leftbar.php'); ?>

<div class="content">

  <div class="page-title">Kelola Data Baju</div>
  <div class="page-subtitle">Tambahkan koleksi baju barumu disini!!!</div>

  <!-- AREA ATAS -->
  <div class="box-header">
    <a href="baju_tambah.php" class="btn-tambah">+ TAMBAH BAJU</a>

    <div class="search-box">
      🔍 <input type="text" id="searchInput" placeholder="Cari data...">
    </div>
  </div>

  <!-- TABLE -->
  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Baju</th>
          <th>Harga</th>
          <th>Foto</th>
          <th>OPSI</th>
        </tr>
      </thead>
      <tbody>

      <?php
      $sql = "SELECT * FROM baju ORDER BY id_baju DESC";
      $query = mysqli_query($koneksidb,$sql);
      $no = 1;
      while($row = mysqli_fetch_array($query)){
      ?>

        <tr>
          <td><?= $no++; ?></td>
          <td><?= $row['nama_baju']; ?></td>
          <td><?= format_rupiah($row['harga']); ?></td>
          <td><img src="img/<?= $row['img']; ?>" class="img-baju"></td>
          <td>
            <a href="edit_baju.php?id=<?= $row['id_baju']; ?>" class="btn-edit">UBAH</a>
            <a href="baju_hapus.php?id=<?= $row['id_baju']; ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus?')">HAPUS</a>
          </td>
        </tr>

      <?php } ?>

      </tbody>
    </table>
  </div>

</div>

<!-- === LIVE SEARCH JAVASCRIPT === -->
<script>
document.getElementById("searchInput").addEventListener("keyup", function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("table tbody tr");

    rows.forEach(function(row) {
        let nama = row.cells[1].textContent.toLowerCase();
        let harga = row.cells[2].textContent.toLowerCase();

        if (nama.includes(filter) || harga.includes(filter)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});
</script>

</body>
</html>