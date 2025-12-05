<?php

session_start();
error_reporting(E_ALL & ~E_NOTICE);

$pagedesc = "Sewa Menunggu Konfirmasi";

if (file_exists(__DIR__ . '/penting/config.php')) {
    include __DIR__ . '/penting/config.php';
} elseif (file_exists(__DIR__ . '/includes/config.php')) {
    include __DIR__ . '/includes/config.php';
} else {

    die("File konfigurasi database tidak ditemukan. Pastikan 'penting/config.php' ada.");
}


if (file_exists(__DIR__ . '/penting/format_rupiah.php')) {
    include __DIR__ . '/penting/format_rupiah.php';
} elseif (file_exists(__DIR__ . '/includes/format_rupiah.php')) {
    include __DIR__ . '/includes/format_rupiah.php';
}


if (file_exists(__DIR__ . '/includes/header.php')) {
    include __DIR__ . '/includes/header.php';
} elseif (file_exists(__DIR__ . '/header.php')) {
    include __DIR__ . '/header.php';
}


if (file_exists(__DIR__ . '/penting/leftbar.php')) {
    include __DIR__ . '/penting/leftbar.php';
} elseif (file_exists(__DIR__ . '/includes/leftbar.php')) {
    include __DIR__ . '/includes/leftbar.php';
}


if (!isset($koneksidb)) {
    if (isset($conn)) $koneksidb = $conn;
    elseif (isset($koneksi)) $koneksidb = $koneksi;
}


if (!isset($koneksidb) || !($koneksidb instanceof mysqli)) {
    die("Koneksi database tidak ditemukan. Pastikan file config meng-set \$koneksidb (mysqli).");
}

?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($pagedesc); ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <!-- Styles: dashboard + leftbar -->
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/leftbar.css">

    <!-- Bootstrap (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <style>
        
        .main-content { margin-left: 250px; padding: 28px; }
        @media (max-width:900px){ .main-content { margin-left: 72px; } }
    </style>
</head>
<body>


<div class="main-content">
    <div class="container-fluid px-4">
        <h3 class="mt-4 mb-4 fw-bold">Daftar Sewa — Menunggu Konfirmasi</h3>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body">
                <h5 class="mb-3">Daftar Sewa Menunggu Konfirmasi</h5>

                <div class="table-responsive">
                    <table id="tblSewaKonfirmasi" class="table table-striped table-bordered align-middle" style="width:100%">
                        <thead class="table-dark">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Kode Booking</th>
                                <th>Penyewa</th>
                                <th>Nama Baju</th>
                                <th>Tgl. Mulai</th>
                                <th>Tgl. Selesai</th>
                                <th>Durasi (hari)</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
              
                        $sql = "
                            SELECT
                                booking.kode_booking,
                                booking.tgl_mulai,
                                booking.tgl_selesai,
                                booking.durasi,
                                booking.status,
                                booking.email,
                                baju.nama_baju,
                                baju.harga,
                                member.nama_user
                            FROM booking
                            LEFT JOIN baju ON booking.id_baju = baju.id_baju
                            LEFT JOIN member ON booking.email = member.email
                            WHERE booking.status = 'Menunggu Konfirmasi'
                            ORDER BY booking.tgl_booking DESC, booking.kode_booking DESC
                        ";

                        $res = mysqli_query($koneksidb, $sql);
                        if (!$res) {
                            // tampilkan pesan error query (berguna saat debugging)
                            echo '<tr><td colspan="10">Query error: ' . htmlspecialchars(mysqli_error($koneksidb)) . '</td></tr>';
                        } else {
                            $no = 1;
                            while ($r = mysqli_fetch_assoc($res)) {
                                // hitung total: durasi * harga (jika harga ada), fallback 0
                                $durasi = (int) $r['durasi'];
                                $harga = isset($r['harga']) ? (int)$r['harga'] : 0;
                                $total = $durasi * $harga;

                                echo '<tr class="align-middle text-center">';
                                echo '<td>' . $no++ . '</td>';
                                echo '<td>' . htmlspecialchars($r['kode_booking']) . '</td>';
                                echo '<td>' . htmlspecialchars($r['nama_user'] ?? $r['email']) . '</td>';
                                echo '<td>' . htmlspecialchars($r['nama_baju'] ?? '-') . '</td>';
                                echo '<td>' . htmlspecialchars($r['tgl_mulai']) . '</td>';
                                echo '<td>' . htmlspecialchars($r['tgl_selesai']) . '</td>';
                                echo '<td>' . $durasi . '</td>';
                                echo '<td>' . (function_exists('format_rupiah') ? format_rupiah($total) : number_format($total,0,',','.')) . '</td>';
                                echo '<td><span class="badge bg-warning text-dark">' . htmlspecialchars($r['status']) . '</span></td>';

                                // aksi: view & edit (sesuaikan file tujuan)
                                $kode = urlencode($r['kode_booking']);
                                echo '<td class="text-center">';
                                echo '<a class="btn btn-sm btn-outline-primary me-1" href="sewaview.php?code=' . $kode . '" title="Lihat">Lihat</a>';
                                echo '<a class="btn btn-sm btn-primary" href="sewaedit.php?id=' . $kode . '" title="Edit">Edit</a>';
                                echo '</td>';

                                echo '</tr>';
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- JS: jQuery (DataTables but minimal), Bootstrap, DataTables -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function(){
    $('#tblSewaKonfirmasi').DataTable({
        "pageLength": 10,
        "lengthChange": true,
        "ordering": true,
        "order": [[ 4, "desc" ]], // default order by Tgl. Mulai (kolom index 4)
        "columnDefs": [
            { "orderable": false, "targets": [0, 8, 9] } // disable ordering di No/Status/Aksi
        ],
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ entri",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri"
        }
    });
});
</script>

</body>
</html>
