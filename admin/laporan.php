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

// Tanggal default
$tgl_awal = date('Y-m-01');
$tgl_akhir = date('Y-m-t');
$filter_periode = 'bulan_ini';

// Proses filter jika ada submit
if(isset($_POST['filter'])) {
    $tgl_awal = $_POST['tgl_awal'];
    $tgl_akhir = $_POST['tgl_akhir'];
    $filter_periode = $_POST['filter_periode'];
    
    // Jika memilih periode cepat
    if($filter_periode != 'custom') {
        $today = date('Y-m-d');
        switch($filter_periode) {
            case 'hari_ini':
                $tgl_awal = $today;
                $tgl_akhir = $today;
                break;
            case 'kemarin':
                $tgl_awal = date('Y-m-d', strtotime('-1 day'));
                $tgl_akhir = $tgl_awal;
                break;
            case 'minggu_ini':
                $tgl_awal = date('Y-m-d', strtotime('monday this week'));
                $tgl_akhir = date('Y-m-d', strtotime('sunday this week'));
                break;
            case 'bulan_ini':
                $tgl_awal = date('Y-m-01');
                $tgl_akhir = date('Y-m-t');
                break;
            case 'bulan_lalu':
                $tgl_awal = date('Y-m-01', strtotime('-1 month'));
                $tgl_akhir = date('Y-m-t', strtotime('-1 month'));
                break;
            case 'tahun_ini':
                $tgl_awal = date('Y-01-01');
                $tgl_akhir = date('Y-12-31');
                break;
        }
    }
}

// Query untuk data laporan
$sql_laporan = "SELECT 
                    b.*,
                    bk.kode_booking,
                    bk.tgl_mulai,
                    bk.tgl_selesai,
                    bk.durasi,
                    bk.status,
                    m.nama_user,
                    m.email,
                    j.nama_jenis,
                    (bk.durasi * b.harga) as total
                FROM booking bk
                JOIN baju b ON bk.id_baju = b.id_baju
                JOIN jenis j ON b.id_jenis = j.id_jenis
                JOIN member m ON bk.email = m.email
                WHERE bk.status IN ('Sudah Dibayar', 'Selesai')
                AND DATE(bk.tgl_mulai) BETWEEN '$tgl_awal' AND '$tgl_akhir'
                ORDER BY bk.tgl_mulai DESC";

$query_laporan = mysqli_query($koneksidb, $sql_laporan);

// Hitung total
$total_pendapatan = 0;
$total_transaksi = 0;
$total_sewa = 0;

while($row = mysqli_fetch_assoc($query_laporan)) {
    $total_pendapatan += $row['total'];
    $total_transaksi++;
    $total_sewa += $row['durasi'];
}

// Reset pointer untuk iterasi berikutnya
mysqli_data_seek($query_laporan, 0);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pagedesc; ?> - Laporan</title>
    <link rel="stylesheet" href="assets/css/leftbar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
            margin-left: 220px;
            padding: 20px;
            width: calc(100% - 220px);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .content-wrapper {
            width: 100%;
            max-width: 1200px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .page-title {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 30px;
            text-align: center;
            width: 100%;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
        }

        /* ========= STATS CARDS ========= */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            width: 100%;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            text-align: center;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
        }

        /* ========= FILTER SECTION ========= */
        .filter-container {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            width: 100%;
            margin-bottom: 30px;
        }

        .filter-title {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .filter-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
            font-size: 14px;
        }

        .form-input,
        .form-select {
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            background: white;
            width: 100%;
        }

        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
        }

        .date-inputs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        /* ========= TABLE SECTION ========= */
        .table-container {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            min-width: 1000px;
        }

        table th {
            background: #3498db;
            color: white;
            padding: 14px 12px;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
        }

        table td {
            padding: 12px;
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

        /* ========= STATUS BADGES ========= */
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-paid {
            background: #d1f2eb;
            color: #1d8348;
        }

        .status-completed {
            background: #d4efdf;
            color: #27ae60;
        }

        /* ========= ACTION BUTTONS ========= */
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            justify-content: center;
        }

        /* ========= EMPTY STATE ========= */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #666;
        }

        .empty-state p {
            margin-top: 10px;
            font-size: 14px;
            color: #888;
        }

        /* ========= RESPONSIVE ========= */
        @media (max-width: 1024px) {
            .main-content {
                margin-left: 200px;
                width: calc(100% - 200px);
                padding: 15px;
            }
            
            .filter-form {
                grid-template-columns: 1fr;
            }
            
            .date-inputs {
                grid-template-columns: 1fr;
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
            
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
            
            table {
                min-width: 800px;
            }
        }

        /* ========= PRINT STYLES ========= */
        @media print {
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .filter-container,
            .action-buttons {
                display: none;
            }
            
            .table-container {
                box-shadow: none;
                padding: 0;
            }
            
            table {
                border: 1px solid #000;
            }
            
            table th {
                background: #ccc !important;
                color: #000 !important;
                -webkit-print-color-adjust: exact;
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
            <h2 class="page-title">Laporan Sewa</h2>

            <!-- Stats Cards -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-icon" style="color: #3498db;">💰</div>
                    <div class="stat-value"><?php echo format_rupiah($total_pendapatan); ?></div>
                    <div class="stat-label">Total Pendapatan</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="color: #2ecc71;">📊</div>
                    <div class="stat-value"><?php echo $total_transaksi; ?></div>
                    <div class="stat-label">Total Transaksi</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="color: #e74c3c;">👕</div>
                    <div class="stat-value"><?php echo $total_sewa; ?></div>
                    <div class="stat-label">Total Hari Sewa</div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="filter-container">
                <div class="filter-title">Filter Laporan</div>
                <form method="POST" class="filter-form">
                    <div class="form-group">
                        <label class="form-label">Pilih Periode</label>
                        <select name="filter_periode" class="form-select" onchange="toggleCustomDate(this.value)">
                            <option value="hari_ini" <?php echo $filter_periode == 'hari_ini' ? 'selected' : ''; ?>>Hari Ini</option>
                            <option value="kemarin" <?php echo $filter_periode == 'kemarin' ? 'selected' : ''; ?>>Kemarin</option>
                            <option value="minggu_ini" <?php echo $filter_periode == 'minggu_ini' ? 'selected' : ''; ?>>Minggu Ini</option>
                            <option value="bulan_ini" <?php echo $filter_periode == 'bulan_ini' ? 'selected' : ''; ?>>Bulan Ini</option>
                            <option value="bulan_lalu" <?php echo $filter_periode == 'bulan_lalu' ? 'selected' : ''; ?>>Bulan Lalu</option>
                            <option value="tahun_ini" <?php echo $filter_periode == 'tahun_ini' ? 'selected' : ''; ?>>Tahun Ini</option>
                            <option value="custom" <?php echo $filter_periode == 'custom' ? 'selected' : ''; ?>>Custom Tanggal</option>
                        </select>
                    </div>
                    
                    <div class="date-inputs" id="customDateGroup" style="display: <?php echo $filter_periode == 'custom' ? 'grid' : 'none'; ?>">
                        <div class="form-group">
                            <label class="form-label">Tanggal Awal</label>
                            <input type="text" name="tgl_awal" class="form-input datepicker" 
                                   value="<?php echo $tgl_awal; ?>" placeholder="Pilih tanggal">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="text" name="tgl_akhir" class="form-input datepicker" 
                                   value="<?php echo $tgl_akhir; ?>" placeholder="Pilih tanggal">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" name="filter" class="btn btn-primary">
                            <span>🔍</span> Tampilkan Laporan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Table Section -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Sewa</th>
                            <th>Baju</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Durasi</th>
                            <th>Penyewa</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        if(mysqli_num_rows($query_laporan) > 0) {
                            while ($row = mysqli_fetch_array($query_laporan)) {
                                $i++;
                                $statusClass = $row['status'] == 'Sudah Dibayar' ? 'status-paid' : 'status-completed';
                        ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><strong><?php echo htmlspecialchars($row['kode_booking']); ?></strong></td>
                                    <td style="text-align: left;"><?php echo htmlspecialchars($row['nama_baju']); ?></td>
                                    <td><?php echo IndonesiaTgl(htmlspecialchars($row['tgl_mulai'])); ?></td>
                                    <td><?php echo IndonesiaTgl(htmlspecialchars($row['tgl_selesai'])); ?></td>
                                    <td><?php echo htmlspecialchars($row['durasi']); ?> hari</td>
                                    <td><?php echo htmlspecialchars($row['nama_user']); ?></td>
                                    <td>
                                        <span class="status-badge <?php echo $statusClass; ?>">
                                            <?php echo htmlspecialchars($row['status']); ?>
                                        </span>
                                    </td>
                                    <td><strong><?php echo format_rupiah($row['total']); ?></strong></td>
                                </tr>
                        <?php 
                            }
                        } else { 
                        ?>
                            <tr>
                                <td colspan="9" class="empty-state">
                                    <div style="font-size: 48px; margin-bottom: 10px; color: #95a5a6;">📊</div>
                                    <p>Tidak ada data laporan untuk periode ini</p>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                
                <?php if(mysqli_num_rows($query_laporan) > 0): ?>
                <div class="summary" style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 6px;">
                    <div style="display: flex; justify-content: space-between; font-weight: bold;">
                        <span>Total Pendapatan Periode:</span>
                        <span><?php echo format_rupiah($total_pendapatan); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 5px; color: #666;">
                        <span>Periode: <?php echo IndonesiaTgl($tgl_awal); ?> - <?php echo IndonesiaTgl($tgl_akhir); ?></span>
                        <span><?php echo $total_transaksi; ?> transaksi</span>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button onclick="window.print()" class="btn btn-secondary">
                    <span>🖨️</span> Cetak Laporan
                </button>
                <a href="export_laporan.php?tgl_awal=<?php echo $tgl_awal; ?>&tgl_akhir=<?php echo $tgl_akhir; ?>" 
                   class="btn btn-primary">
                    <span>📥</span> Export Excel
                </a>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    <script>
        // Initialize date pickers
        flatpickr(".datepicker", {
            dateFormat: "Y-m-d",
            locale: "id",
            allowInput: true
        });

        // Toggle custom date inputs
        function toggleCustomDate(value) {
            const customDateGroup = document.getElementById('customDateGroup');
            if (value === 'custom') {
                customDateGroup.style.display = 'grid';
            } else {
                customDateGroup.style.display = 'none';
            }
        }

        // Set default dates based on quick filter
        document.querySelector('select[name="filter_periode"]').addEventListener('change', function() {
            const today = new Date();
            let startDate, endDate;
            
            switch(this.value) {
                case 'hari_ini':
                    startDate = endDate = today.toISOString().split('T')[0];
                    break;
                case 'kemarin':
                    const yesterday = new Date(today);
                    yesterday.setDate(yesterday.getDate() - 1);
                    startDate = endDate = yesterday.toISOString().split('T')[0];
                    break;
                case 'minggu_ini':
                    const monday = new Date(today);
                    monday.setDate(today.getDate() - today.getDay() + 1);
                    const sunday = new Date(today);
                    sunday.setDate(today.getDate() - today.getDay() + 7);
                    startDate = monday.toISOString().split('T')[0];
                    endDate = sunday.toISOString().split('T')[0];
                    break;
                case 'bulan_ini':
                    startDate = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-01';
                    const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                    endDate = lastDay.toISOString().split('T')[0];
                    break;
                case 'bulan_lalu':
                    const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    const lastMonthEnd = new Date(today.getFullYear(), today.getMonth(), 0);
                    startDate = lastMonth.getFullYear() + '-' + String(lastMonth.getMonth() + 1).padStart(2, '0') + '-01';
                    endDate = lastMonthEnd.toISOString().split('T')[0];
                    break;
                case 'tahun_ini':
                    startDate = today.getFullYear() + '-01-01';
                    endDate = today.getFullYear() + '-12-31';
                    break;
            }
            
            if (startDate && endDate && this.value !== 'custom') {
                document.querySelectorAll('.datepicker').forEach(input => {
                    input.value = this.value.includes('tgl_awal') ? startDate : endDate;
                });
            }
        });

        // Print functionality
        function printReport() {
            window.print();
        }
    </script>
</body>
</html>