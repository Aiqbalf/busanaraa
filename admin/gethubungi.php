<?php
require_once __DIR__ . '/../penting/config.php';

header('Content-Type: application/json');

// cek koneksi
if (!isset($koneksidb) || !$koneksidb) {
    echo json_encode(["error" => "Koneksi database gagal"]);
    exit;
}

// query
$sql = "SELECT * FROM contactus ORDER BY id_cu DESC";
$result = mysqli_query($koneksidb, $sql);

$data = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>
