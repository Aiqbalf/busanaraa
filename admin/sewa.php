<?php
session_start();
error_reporting(0);
include('penting/config.php');
include('penting/format_rupiah.php');
include('penting/library.php');

if(strlen($_SESSION['alogin'])==0){	
    header('location:index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pagedesc; ?> - Kelola Sewa</title>
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
            border-bottom: 2px solid #3498db; /* Warna ungu untuk kelola sewa */
        }

        /* ========= FILTER SECTION ========= */
        .filter-section {
            width: 100%;
            max-width: 1200px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .filter-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .filter-btn {
            padding: 8px 16px;
            font-size: 13px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 20px;
            cursor: pointer;
            color: #666;
            transition: all 0.3s;
        }

        .filter-btn:hover {
            background: #f8f9fa;
            border-color: #3498db;
        }

        .filter-btn.active {
            background: #3498db;
            color: white;
            border-color: #3498db;
        }

        /* ========= SEARCH BOX ========= */
        .search-container {
            position: relative;
            width: 300px;
        }

        .search-input {
            width: 100%;
            padding: 10px 15px;
            padding-left: 40px;
            font-size: 13px;
            border: 1px solid #ddd;
            border-radius: 25px;
            background: white;
        }

        .search-input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 5px rgba(155, 89, 182, 0.3);
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
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
            min-width: 1000px;
        }

        table th {
            background: #3498db; /* Warna ungu untuk kelola sewa */
            color: white;
            padding: 12px 10px;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
        }

        table td {
            padding: 12px 10px;
            border-bottom: 1px solid #eee;
            text-align: center;
            vertical-align: middle;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f5eef8; /* Hover warna ungu muda */
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

        /* ========= NOTIFICATION ========= */
        .notification-container {
            width: 100%;
            max-width: 1200px;
            margin-bottom: 20px;
        }

        .notification {
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .notification.error {
            background: #ffeaea;
            border-left: 4px solid #e74c3c;
            color: #333;
        }

        .notification.success {
            background: #eaffea;
            border-left: 4px solid #2ecc71;
            color: #333;
        }

        /* ========= ACTION BUTTONS ========= */
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 32px;
            background: #3498db;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s;
        }

        .action-btn:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        /* ========= USER LINK ========= */
        .user-link {
            color: #9b59b6;
            text-decoration: none;
            font-weight: 500;
            padding: 4px 10px;
            background: #f5eef8;
            border-radius: 4px;
            display: inline-block;
            transition: all 0.2s;
        }

        .user-link:hover {
            background: #9b59b6;
            color: white;
            text-decoration: none;
        }

        /* ========= STATUS BADGES ========= */
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            min-width: 140px;
        }

        .status-waiting {
            background: #ffeaa7;
            color: #d35400;
        }

        .status-paid {
            background: #d1f2eb;
            color: #1d8348;
        }

        .status-completed {
            background: #d4efdf;
            color: #27ae60;
        }

        .status-cancel {
            background: #fadbd8;
            color: #c0392b;
        }

        .status-confirm {
            background: #aed6f1;
            color: #1a5276;
        }

        /* ========= OVERDUE STYLING ========= */
        .overdue {
            color: #e74c3c !important;
            font-weight: 600;
        }

        .overdue-icon {
            color: #e74c3c;
            margin-left: 5px;
            font-size: 14px;
        }

        /* ========= EMPTY STATE ========= */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }

        .empty-state p {
            margin-top: 10px;
            font-size: 14px;
            color: #888;
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
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .close-btn:hover {
            background: #f5f5f5;
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
            
            .filter-section {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .search-container {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 10px;
            }
            
            .page-title {
                font-size: 20px;
            }
            
            .filter-buttons {
                width: 100%;
                overflow-x: auto;
                padding-bottom: 10px;
                justify-content: flex-start;
            }
            
            table {
                min-width: 900px;
            }
            
            .status-badge {
                min-width: 120px;
                font-size: 11px;
                padding: 4px 8px;
            }
        }

        @media (max-width: 480px) {
            .table-container {
                padding: 10px;
            }
            
            table {
                font-size: 13px;
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
            <h2 class="page-title">Kelola Sewa</h2>

            <!-- Notifications -->
            <?php 
            $error = isset($_GET['error']) ? $_GET['error'] : '';
            $msg = isset($_GET['msg']) ? $_GET['msg'] : '';
            
            if($error || $msg): ?>
            <div class="notification-container">
                <?php if($error): ?>
                    <div class="notification error">❌ <?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <?php if($msg): ?>
                    <div class="notification success">✅ <?php echo htmlspecialchars($msg); ?></div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Filter and Search Section -->
            <div class="filter-section">
                <div class="filter-buttons" id="filterButtons">
                    <button class="filter-btn active" onclick="filterTable('all')">Semua</button>
                    <button class="filter-btn" onclick="filterTable('Menunggu Konfirmasi')">Menunggu Konfirmasi</button>
                    <button class="filter-btn" onclick="filterTable('Menunggu Pembayaran')">Menunggu Pembayaran</button>
                    <button class="filter-btn" onclick="filterTable('Sudah Dibayar')">Sudah Dibayar</button>
                    <button class="filter-btn" onclick="filterTable('Selesai')">Selesai</button>
                    <button class="filter-btn" onclick="filterTable('Cancel')">Cancel</button>
                </div>

                <div class="search-container">
                    <span class="search-icon">🔍</span>
                    <input type="text" class="search-input" id="searchInput" 
                           placeholder="Cari kode sewa, baju, atau penyewa...">
                </div>
            </div>

            <div class="table-container">
                <table id="sewaTable">
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
                        $today = date('Y-m-d');
                        $sqlsewa = "SELECT booking.*, baju.*, jenis.*, member.* 
                                    FROM booking 
                                    JOIN baju ON booking.id_baju = baju.id_baju
                                    JOIN jenis ON baju.id_jenis = jenis.id_jenis
                                    JOIN member ON booking.email = member.email 
                                    ORDER BY booking.kode_booking DESC";
                        $querysewa = mysqli_query($koneksidb, $sqlsewa);
                        $num_rows = mysqli_num_rows($querysewa);
                        
                        if($num_rows > 0) {
                            while ($result = mysqli_fetch_array($querysewa)) {
                                $total = $result['durasi'] * $result['harga'];
                                $tgl_selesai = $result['tgl_selesai'];
                                $isOverdue = $today > $tgl_selesai && $result['status'] == 'Sudah Dibayar';
                                $i++;
                                
                                $statusClass = 'status-badge ';
                                switch($result['status']) {
                                    case 'Menunggu Konfirmasi':
                                        $statusClass .= 'status-waiting';
                                        break;
                                    case 'Menunggu Pembayaran':
                                        $statusClass .= 'status-waiting';
                                        break;
                                    case 'Sudah Dibayar':
                                        $statusClass .= 'status-paid';
                                        break;
                                    case 'Selesai':
                                        $statusClass .= 'status-completed';
                                        break;
                                    case 'Cancel':
                                        $statusClass .= 'status-cancel';
                                        break;
                                    default:
                                        $statusClass .= 'status-confirm';
                                }
                        ?>
                                <tr class="sewa-row" data-status="<?php echo htmlspecialchars($result['status']); ?>">
                                    <td><?php echo $i; ?></td>
                                    <td><strong><?php echo htmlspecialchars($result['kode_booking']); ?></strong></td>
                                    <td style="text-align: left;"><?php echo htmlspecialchars($result['nama_baju']); ?></td>
                                    <td class="<?php echo $isOverdue ? 'overdue' : ''; ?>">
                                        <?php echo IndonesiaTgl(htmlspecialchars($result['tgl_mulai'])); ?>
                                    </td>
                                    <td class="<?php echo $isOverdue ? 'overdue' : ''; ?>">
                                        <?php echo IndonesiaTgl(htmlspecialchars($tgl_selesai)); ?>
                                        <?php if($isOverdue): ?>
                                            <span class="overdue-icon" title="Telat dikembalikan">⚠</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><strong><?php echo format_rupiah(htmlspecialchars($total)); ?></strong></td>
                                    <td>
                                        <a href="javascript:void(0)" onclick="showUserModal('<?php echo $result['email']; ?>')" class="user-link">
                                            <?php echo htmlspecialchars($result['nama_user']); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="<?php echo $statusClass; ?>">
                                            <?php echo htmlspecialchars($result['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" onclick="showSewaModal('<?php echo $result['kode_booking']; ?>')" 
                                           class="action-btn" title="Lihat Detail">
                                            👁
                                        </a>
                                    </td>
                                </tr>
                        <?php 
                            }
                        } else { 
                        ?>
                            <tr>
                                <td colspan="9" class="empty-state">
                                    <div style="font-size: 48px; margin-bottom: 10px; color: #9b59b6;">📋</div>
                                    <p>Belum ada data sewa</p>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal untuk detail sewa -->
    <div id="sewaModal" class="modal">
        <div class="modal-content">
            <button class="close-btn" onclick="closeModal('sewaModal')">✖</button>
            <div id="sewaModalBody">Memuat data...</div>
        </div>
    </div>
    
    <!-- Modal untuk detail user -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <button class="close-btn" onclick="closeModal('userModal')">✖</button>
            <div id="userModalBody">Memuat data...</div>
        </div>
    </div>

    <script>
    // Fungsi untuk membuka modal detail sewa
    function showSewaModal(code) {
        if(code) {
            document.getElementById('sewaModal').style.display = 'flex';
            document.getElementById('sewaModalBody').innerHTML = 'Memuat data...';
            
            fetch('sewaview.php?code=' + code)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('sewaModalBody').innerHTML = data;
                })
                .catch(error => {
                    document.getElementById('sewaModalBody').innerHTML = 'Terjadi kesalahan saat memuat data.';
                });
        }
    }
    
    // Fungsi untuk membuka modal detail user
    function showUserModal(email) {
        if(email) {
            document.getElementById('userModal').style.display = 'flex';
            document.getElementById('userModalBody').innerHTML = 'Memuat data...';
            
            fetch('userview.php?code=' + email)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('userModalBody').innerHTML = data;
                })
                .catch(error => {
                    document.getElementById('userModalBody').innerHTML = 'Terjadi kesalahan saat memuat data.';
                });
        }
    }
    
    // Fungsi untuk menutup modal
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }
    
    // Filter tabel berdasarkan status
    function filterTable(status) {
        const rows = document.querySelectorAll('.sewa-row');
        const filterButtons = document.querySelectorAll('.filter-btn');
        
        // Update active button
        filterButtons.forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.classList.add('active');
        
        // Filter rows
        rows.forEach(row => {
            if(status === 'all' || row.getAttribute('data-status') === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('.sewa-row');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if(text.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    
    // Close modal saat klik di luar konten
    window.onclick = function(event) {
        if(event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    };
    
    // Close dengan ESC key
    document.addEventListener('keydown', function(event) {
        if(event.key === 'Escape') {
            closeModal('sewaModal');
            closeModal('userModal');
        }
    });

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        // Cek jika ada parameter status di URL
        const urlParams = new URLSearchParams(window.location.search);
        const statusFilter = urlParams.get('status');
        
        if(statusFilter) {
            const filterButton = document.querySelector(`.filter-btn[onclick*="${statusFilter}"]`);
            if(filterButton) {
                filterButton.click();
            }
        }
    });
    </script>
</body>
</html>