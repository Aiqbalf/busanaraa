<?php
session_start();
error_reporting(0);
include('includes/config.php');
include('includes/format_rupiah.php');
include('includes/library.php');
if(strlen($_SESSION['alogin'])==0){
    header('location:index.php');
    exit;
}

// Nama page title
$pagedesc = 'Laporan - Busanara';
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $pagedesc;?></title>

    <!-- Bootstrap 5 CDN -->
     <link rel="stylesheet" href="assets/css/leftbar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    

	
    <style>
        :root{
            --sidebar-bg: #2E3440; /* biru sidebar */
            --topbar-bg: #fff;
            --card-bg: #fff;
            --muted-bg: #f6f7fb;
        }
        body{
            background: var(--muted-bg);
            font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        }
        /* Sidebar */
        .sidebar{
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 250px;
            background: var(--sidebar-bg);
            color: #fff;
            padding-top: 18px;
            box-shadow: 2px 0 8px rgba(0,0,0,0.08);
        }
        .sidebar .brand{
            padding: 20px 18px;
            font-weight: 700;
            font-size: 18px;
        }
        .sidebar .nav-link{
            color: rgba(255,255,255,0.9);
            padding: 12px 18px;
            border-radius: 8px;
            margin: 4px 12px;
        }
        .sidebar .nav-link i{ width: 18px; }
        .sidebar .nav-link:hover{ background: rgba(255,255,255,0.06); }

        /* Topbar */
        .topbar{
            margin-left: 250px;
            height: 62px;
            background: var(--topbar-bg);
            display: flex;
            align-items: center;
            padding: 0 22px;
            border-bottom: 1px solid rgba(0,0,0,0.06);
            box-shadow: 0 6px 12px rgba(0,0,0,0.06);
            z-index: 5;
            position: sticky;
            top: 0;
        }
        .content{
            margin-left: 250px;
            padding: 28px 48px;
        }

        /* Card style sesuai figma */
        .card-filter{
            background: var(--card-bg);
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 12px 18px rgba(0,0,0,0.08);
            border: 1px solid rgba(0,0,0,0.04);
        }
        .card-filter .card-title{
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: #1f2937;
        }
        .icon-box{
            width:36px; height:36px; border-radius:8px; background:#EEF2FF; display:flex; align-items:center; justify-content:center; color:#2563EB;
        }

        label.small{ font-size: 13px; color: #374151; }
        .muted-desc{ color:#6b7280; margin-bottom:18px; }

        /* Tombol kanan bawah */
        .btn-lihat{
            border-radius:8px;
            padding:8px 14px;
        }

        /* Responsive tweaks */
        @media (max-width: 900px){
            .sidebar{ width:72px; }
            .content, .topbar{ margin-left:72px; }
            .sidebar .brand span{ display:none; }
            .sidebar .nav-link{ text-align:center; padding:10px; }
        }
    </style>

    <script>
    function valid(){
        // contoh validasi sederhana: kalau pake input date
        const awal = document.forms['laporan'].awal.value;
        const akhir = document.forms['laporan'].akhir.value;
        if(awal && akhir && (akhir < awal)){
            alert('Tanggal akhir harus lebih besar dari tanggal awal!');
            return false;
        }
        return true;
    }
    </script>
</head>
<body>


<?php include('penting/leftbar.php'); ?>


    <main class="content">
        <div class="mb-4">
            <h3>Laporan</h3>
            <p class="muted-desc">Lihat dan cetak laporan berdasarkan periode tertentu</p>
        </div>

        <div class="card-filter">
            <div class="card-title mb-3">
                <div class="icon-box"><i class="fa fa-calendar-check"></i></div>
                <div>Filter Periode Laporan</div>
            </div>

            <!-- Form pencarian laporan -->
            <form name="laporan" method="get" onsubmit="return valid();">
                <div class="row align-items-end gy-3">

                    <div class="col-lg-6">
                        <label class="small">Tanggal Awal</label>
                        <div class="row g-2">
                            <div class="col-4">
                                <label class="small">Hari</label>
                                <select name="awal_hari" class="form-select form-select-sm">
                                    <?php for($d=1;$d<=31;$d++){ echo "<option value=\"".$d."\">$d</option>"; } ?>
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="small">Bulan</label>
                                <select name="awal_bulan" class="form-select form-select-sm">
                                    <?php for($m=1;$m<=12;$m++){ echo "<option value=\"".$m."\">$m</option>"; } ?>
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="small">Tahun</label>
                                <select name="awal_tahun" class="form-select form-select-sm">
                                    <?php 


				$year = date('Y'); 
				for($y = $year - 2; $y <= $year + 1; $y++) { 
 				$selected = ($y == $year) ? 'selected' : '';
    			echo "<option value=\"$y\" $selected>$y</option>"; 
				}
				?>

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <label class="small">Tanggal Akhir</label>
                        <div class="row g-2">
                            <div class="col-4">
                                <label class="small">Hari</label>
                                <select name="akhir_hari" class="form-select form-select-sm">
                                    <?php for($d=1;$d<=31;$d++){ echo "<option value=\"".$d."\">$d</option>"; } ?>
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="small">Bulan</label>
                                <select name="akhir_bulan" class="form-select form-select-sm">
                                    <?php for($m=1;$m<=12;$m++){ echo "<option value=\"".$m."\">$m</option>"; } ?>
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="small">Tahun</label>
                                <select name="akhir_tahun" class="form-select form-select-sm">
									<?php
									
									
									
					$year = date('Y'); 
    				for($y = $year - 2; $y <= $year + 1; $y++) { 
        			$selected = ($y == $year) ? 'selected' : '';
        			echo "<option value=\"$y\" $selected>$y</option>"; 
    				}
    				?>	
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" name="submit" class="btn btn-primary btn-lihat"><i class="fa fa-file-lines me-2"></i> Lihat Laporan</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Hasil laporan -->
        <?php
        if(isset($_GET['submit'])){
            // ambil tanggal dari dropdown
            $awal = sprintf('%04d-%02d-%02d', intval($_GET['awal_tahun']), intval($_GET['awal_bulan']), intval($_GET['awal_hari']));
            $akhir = sprintf('%04d-%02d-%02d', intval($_GET['akhir_tahun']), intval($_GET['akhir_bulan']), intval($_GET['akhir_hari']));

            // status yang ingin dimasukkan
            $stt  = "Sudah Dibayar";
            $stt1 = "Selesai";

            // perbaikan logika SQL: gunakan kurung untuk operator OR
            $sqlsewa = "SELECT * FROM booking WHERE (status='".mysqli_real_escape_string($koneksidb,$stt)."' OR status='".mysqli_real_escape_string($koneksidb,$stt1)."') AND tgl_booking BETWEEN '".mysqli_real_escape_string($koneksidb,$awal)."' AND '".mysqli_real_escape_string($koneksidb,$akhir)."' ORDER BY tgl_booking ASC";
            $querysewa = mysqli_query($koneksidb,$sqlsewa);
            
            // tampilkan card hasil jika ada
        ?>
        <div class="card mt-4 p-3">
            <div class="card-body">
                <h5 class="card-title">Laporan Sewa</h5>
                <p class="card-text">Periode: <strong><?php echo IndonesiaTgl($awal); ?></strong> sampai <strong><?php echo IndonesiaTgl($akhir); ?></strong></p>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Sewa</th>
                                <th>Tanggal Sewa</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $no = 0;
                            while($result = mysqli_fetch_array($querysewa)){
                                $idbaju = $result['id_baju'];
                                $sqlbaju = "SELECT * FROM baju WHERE id_baju='".mysqli_real_escape_string($koneksidb,$idbaju)."'";
                                $querybaju = mysqli_query($koneksidb,$sqlbaju);
                                $resultbaju = mysqli_fetch_array($querybaju);
                                $total = intval($result['durasi']) * intval($resultbaju['harga']);
                                $no++;
                        ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo htmlentities($result['kode_booking']); ?></td>
                                <td><?php echo IndonesiaTgl(htmlentities($result['tgl_booking'])); ?></td>
                                <td><?php echo format_rupiah($total); ?></td>
                            </tr>
                        <?php } // end while ?>
                        </tbody>
                    </table>
                </div>










                <div class="mt-3 text-end">
                    <a href="laporan_cetak.php?awal=<?php echo $awal;?>&akhir=<?php echo $akhir;?>" target="_blank" class="btn btn-outline-primary">Cetak</a>
                </div>





				






            </div>
        </div>
        <?php } // end if submit ?>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
