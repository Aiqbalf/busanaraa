<?php
include 'penting/config.php';
include 'penting/format_rupiah.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sewa Menunggu Pembayaran</title>

    <!-- CSS MAIN -->
    <link rel="stylesheet" href="assets/css/dashboard.css" />
    <link rel="stylesheet" href="assets/css/leftbar.css" />

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>

    <!-- SIDEBAR -->
    <?php include 'penting/leftbar.php'; ?>

    <!-- MAIN WRAPPER -->
    <div class="main-content">
        <div class="container mt-4">

            <h2 class="mb-3">Sewa Menunggu Pembayaran</h2>

            <div class="card p-3 shadow-sm">
                <h4 class="mb-3">Daftar Sewa Menunggu Pembayaran</h4>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Kode Sewa</th>
                                <th>Baju</th>
                                <th>Tgl. Mulai</th>
                                <th>Tgl. Selesai</th>
                                <th>Total</th>
                                <th>Penyewa</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $no = 1;
                            $query = mysqli_query($koneksidb, "
    SELECT 
        cek_booking.id_cek,
        cek_booking.kode_booking,
        cek_booking.ukuran,
        cek_booking.tgl_booking,
        booking.tgl_mulai,
        booking.tgl_selesai,
        booking.email,
        booking.durasi,
        booking.bukti_bayar,
        baju.nama_baju
    FROM cek_booking
    LEFT JOIN booking ON cek_booking.kode_booking = booking.kode_booking
    LEFT JOIN baju ON cek_booking.id_baju = baju.id_baju
    WHERE cek_booking.status = 'menunggu pembayaran'
    ORDER BY cek_booking.id_cek DESC
");


                            while ($row = mysqli_fetch_assoc($query)) {
                                ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $row['kode_booking']; ?></td>
<td><?= $row['nama_baju']; ?> (Uk: <?= $row['ukuran']; ?>)</td>
<td><?= $row['tgl_mulai']; ?></td>
<td><?= $row['tgl_selesai']; ?></td>
<td><?= $row['durasi']; ?> Hari</td>
<td><?= $row['email']; ?></td>

                                    <td><span class="badge bg-warning text-dark">Menunggu</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#bayar<?= $row['id_cek']; ?>"
<input type="hidden" name="id_cek" value="<?= $row['id_cek']; ?>">

                                        </button>
                                    </td>
                                </tr>

                                <!-- MODAL BAYAR -->
                                <div class="modal fade" id="bayar<?= $row['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form method="POST" action="bayar_proses.php">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Pembayaran Sewa</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <input type="hidden" name="id" value="<?= $row['id']; ?>">

                                                    <p><strong>Kode Sewa:</strong> <?= $row['kode_sewa']; ?></p>
                                                    <p><strong>Total Pembayaran:</strong>
                                                        <?= format_rupiah($row['total_biaya']); ?>
                                                    </p>

                                                    <label class="form-label">Bayar</label>
                                                    <input type="number" name="bayar" class="form-control" required>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-success">Konfirmasi Pembayaran</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
