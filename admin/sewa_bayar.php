<?php
session_start();
error_reporting(0);

include('penting/config.php');
include('penting/format_rupiah.php');
include('penting/library.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pagedesc; ?></title>
    <link rel="stylesheet" href="assets/css/leftbar.css">
    <style>
        /* ========= BASIC PAGE STYLE ========= */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f3f4f6;
            color: #333;
            display: flex;
            min-height: 100vh;
        }

        /* ========= MAIN CONTENT CONTAINER ========= */
        .main-content {
            flex: 1;
            margin-left: 220px; /* Lebar sidebar */
            padding: 20px;
            width: calc(100% - 220px);
            display: flex;
            flex-direction: column;
            align-items: center; /* Pusatkan konten */
        }

        .content-wrapper {
            width: 100%;
            max-width: 1200px; /* Batas maksimal lebar konten */
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .page-title {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 20px;
            text-align: center;
            width: 100%;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
        }

        /* ========= TABLE CONTAINER ========= */
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 100%;
            overflow-x: auto;
        }

        /* ========= TABLE STYLE ========= */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            min-width: 800px;
        }

        table th {
            background: #3498db;
            color: white;
            padding: 10px 8px;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
        }

        table td {
            padding: 10px 8px;
            border-bottom: 1px solid #eee;
            text-align: center;
            vertical-align: middle;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f8ff;
        }

        /* ========= COLUMN WIDTH ========= */
        table th:nth-child(1), /* No */
        table td:nth-child(1) {
            width: 50px;
        }

        table th:nth-child(2), /* Kode Sewa */
        table td:nth-child(2) {
            width: 100px;
        }

        table th:nth-child(3), /* Baju */
        table td:nth-child(3) {
            width: 200px;
            text-align: left;
            padding-left: 10px;
        }

        table th:nth-child(4), /* Tgl Mulai */
        table th:nth-child(5), /* Tgl Selesai */
        table td:nth-child(4),
        table td:nth-child(5) {
            width: 100px;
        }

        table th:nth-child(6), /* Total */
        table td:nth-child(6) {
            width: 120px;
        }

        table th:nth-child(7), /* Penyewa */
        table td:nth-child(7) {
            width: 100px;
        }

        table th:nth-child(8), /* Status */
        table td:nth-child(8) {
            width: 150px;
        }

        table th:nth-child(9), /* Aksi */
        table td:nth-child(9) {
            width: 80px;
        }

        /* ========= SEARCH BOX ========= */
        .search-box {
            margin-bottom: 20px;
            width: 100%;
            max-width: 1200px;
            text-align: right;
        }

        .search-box input {
            padding: 8px 12px;
            width: 200px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 13px;
        }

        .search-box input:focus {
            outline: none;
            border-color: #3498db;
        }

        /* ========= ACTION ICONS ========= */
        .action-icons {
            display: flex;
            justify-content: center;
            gap: 5px;
        }

        .action-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background: #f8f9fa;
            border-radius: 4px;
            color: #333;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s;
        }

        .action-icons a:hover {
            background: #3498db;
            color: white;
        }

        /* ========= LINKS ========= */
        a[onclick*="openUserModal"] {
            color: #2980b9;
            text-decoration: none;
            font-weight: 500;
        }

        a[onclick*="openUserModal"]:hover {
            text-decoration: underline;
        }

        /* ========= STATUS BADGE ========= */
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            background: #f39c12;
            color: white;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }

        /* ========= MODAL ========= */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            width: 90%;
            max-width: 600px;
            padding: 25px;
            border-radius: 10px;
            position: relative;
            max-height: 80vh;
            overflow-y: auto;
        }

        .close-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            cursor: pointer;
            font-size: 20px;
            color: #666;
            background: none;
            border: none;
        }

        /* ========= RESPONSIVE ========= */
        @media (max-width: 1024px) {
            .main-content {
                margin-left: 200px;
                width: calc(100% - 200px);
                padding: 15px;
            }
            
            .table-container {
                padding: 15px;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 10px;
            }
            
            .search-box {
                text-align: center;
            }
            
            .search-box input {
                width: 100%;
                max-width: 300px;
            }
            
            .page-title {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <?php include('penting/leftbar.php'); ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content-wrapper">
            <h2 class="page-title">Sewa Menunggu Pembayaran</h2>

            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Cari data sewa...">
            </div>

            <div class="table-container">
                <table id="mainTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Sewa</th>
                            <th>Baju</th>
                            <th>Tgl Mulai</th>
                            <th>Tgl Selesai</th>
                            <th>Total</th>
                            <th>Penyewa</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php
                    $i = 0;
                    $sql = "SELECT booking.*, baju.*, jenis.*, member.*
                            FROM booking 
                            JOIN baju ON booking.id_baju = baju.id_baju
                            JOIN jenis ON baju.id_jenis = jenis.id_jenis
                            JOIN member ON booking.email = member.email
                            WHERE status='Menunggu Pembayaran'
                            ORDER BY booking.kode_booking DESC";

                    $query = mysqli_query($koneksidb, $sql);

                    while ($row = mysqli_fetch_array($query)) {
                        $i++;
                        $total = $row['durasi'] * $row['harga'];
                    ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td><strong><?= htmlentities($row['kode_booking']) ?></strong></td>
                            <td style="text-align: left;"><?= htmlentities($row['nama_baju']) ?></td>
                            <td><?= IndonesiaTgl($row['tgl_mulai']) ?></td>
                            <td><?= IndonesiaTgl($row['tgl_selesai']) ?></td>
                            <td><strong><?= format_rupiah($total) ?></strong></td>
                            <td>
                                <a href="#" onclick="openUserModal('<?= $row['email'] ?>')">
                                    <?= $row['nama_user'] ?>
                                </a>
                            </td>
                            <td>
                                <span class="status-badge"><?= $row['status'] ?></span>
                            </td>
                            <td>
                                <div class="action-icons">
                                    <a href="#" onclick="openDetailModal('<?= $row['kode_booking'] ?>')" title="Lihat Detail">👁</a>
                                    <a href="sewaeditbayar.php?id=<?= $row['kode_booking'] ?>" title="Edit">✏</a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ========= MODAL ========= -->
    <div class="modal" id="modalBox">
        <div class="modal-content">
            <button class="close-btn" onclick="closeModal()">✖</button>
            <div id="modalContent">Loading...</div>
        </div>
    </div>

    <script>
        // SEARCH TABLE
        document.getElementById("searchInput").addEventListener("keyup", function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll("#mainTable tbody tr");

            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });

        // OPEN MODAL DETAIL
        function openDetailModal(kode) {
            document.getElementById("modalBox").style.display = "flex";
            fetch("sewaview.php?code=" + kode)
                .then(res => res.text())
                .then(html => {
                    document.getElementById("modalContent").innerHTML = html;
                });
        }

        // OPEN MODAL USER DETAIL
        function openUserModal(email) {
            document.getElementById("modalBox").style.display = "flex";
            fetch("userview.php?code=" + email)
                .then(res => res.text())
                .then(html => {
                    document.getElementById("modalContent").innerHTML = html;
                });
        }

        function closeModal() {
            document.getElementById("modalBox").style.display = "none";
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            let modal = document.getElementById('modalBox');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>

</body>
</html>