<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE);

$pagedesc = "Pengembalian Baju";

/**
 * Flexible includes: cek di /penting lalu /includes (sesuai struktur projectmu)
 */
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

/* header + leftbar flexible */
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

/* fallback variable name: kalau config pakai $conn atau $koneksi, kita harmonisasi */
if (!isset($koneksidb)) {
    if (isset($conn)) $koneksidb = $conn;
    elseif (isset($koneksi)) $koneksidb = $koneksi;
}

/* pastikan koneksi valid */
if (!isset($koneksidb) || !($koneksidb instanceof mysqli)) {
    die("Koneksi database tidak ditemukan. Pastikan \$koneksidb (mysqli) tersedia dari config.");
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($pagedesc); ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <!-- Styles -->
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/leftbar.css">
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
        <h3 class="mt-4 mb-4 fw-bold">Pengembalian Baju</h3>
        <p class="text-muted">Menampilkan semua data sewa untuk proses pengembalian (semua status ditampilkan dulu).</p>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tblSewaKembali" class="table table-striped table-bordered align-middle" style="width:100%">
                        <thead class="table-dark">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Kode Booking</th>
                                <th>Nama Baju</th>
                                <th>Tgl Mulai</th>
                                <th>Tgl Selesai</th>
                                <th>Durasi (hari)</th>
                                <th>Penyewa</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Ambil semua booking, join baju + member. Tampilkan semua status.
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
                            ORDER BY booking.tgl_booking DESC, booking.kode_booking DESC
                        ";

                        $res = mysqli_query($koneksidb, $sql);
                        if (!$res) {
                            echo '<tr><td colspan="9">Query error: ' . htmlspecialchars(mysqli_error($koneksidb)) . '</td></tr>';
                        } else {
                            $no = 1;
                            while ($r = mysqli_fetch_assoc($res)) {
                                $durasi = (int) $r['durasi'];
                                $harga = isset($r['harga']) ? (int)$r['harga'] : 0;
                                $total = $durasi * $harga;

                                echo '<tr class="align-middle text-center">';
                                echo '<td>' . $no++ . '</td>';
                                echo '<td>' . htmlspecialchars($r['kode_booking']) . '</td>';
                                echo '<td>' . htmlspecialchars($r['nama_baju'] ?? '-') . '</td>';
                                echo '<td>' . htmlspecialchars($r['tgl_mulai']) . '</td>';
                                echo '<td>' . htmlspecialchars($r['tgl_selesai']) . '</td>';
                                echo '<td>' . $durasi . '</td>';
                                echo '<td>' . htmlspecialchars($r['nama_user'] ?? $r['email']) . '</td>';
                                echo '<td><span class="badge bg-secondary">' . htmlspecialchars($r['status']) . '</span></td>';

                                $kode = urlencode($r['kode_booking']);
                                echo '<td class="text-center">';
                                // tombol detail (buka page/view)
                                echo '<a class="btn btn-sm btn-outline-primary me-1" href="sewaview.php?code=' . $kode . '" title="Detail">Detail</a>';
                                // tombol selesai / hilang (aksinya default ke file yang ada di project)
                                echo '<a class="btn btn-sm btn-primary me-1" href="selesai.php?id=' . $kode . '" onclick="return confirm(\'Tandai selesai untuk ' . htmlspecialchars($r['kode_booking']) . ' ?\');">Selesai</a>';
                                echo '<a class="btn btn-sm btn-danger" href="hilang.php?id=' . $kode . '" onclick="return confirm(\'Ubah status jadi Hilang/Rusak untuk ' . htmlspecialchars($r['kode_booking']) . ' ?\');">Hilang/Rusak</a>';
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

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function(){
    $('#tblSewaKembali').DataTable({
        pageLength: 10,
        lengthChange: true,
        order: [[ 3, "desc" ]],
        columnDefs: [{ orderable: false, targets: [0,7,8] }],
        language: { search: "Cari:", lengthMenu: "Tampilkan _MENU_ entri", info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri" }
    });
});
</script>

</body>
</html>
