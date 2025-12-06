<?php
session_start();
if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header('location:admin/login.php'); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Pesan Masuk - Busanara</title>

<link rel="stylesheet" href="assets/css/leftbar.css">


<style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', sans-serif;
}
body {
    background: #f4f6fa;
    display: flex;
    min-height: 100vh;
}




/* CONTENT */
.content {
    margin-left: 250px;
    width: calc(100% - 250px);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

.page {
    padding: 20px 24px;
}

.title {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 6px;
}

.subtitle {
    font-size: 13px;
    color: #777;
    margin-bottom: 18px;
}

.table-box {
    background: #fff;
    border-radius: 10px;
    padding: 8px;
    border: 1px solid #d3dce6;
    overflow: hidden;
}

.table-scroll {
    max-height: 360px;
    overflow-y: auto;
    overflow-x: hidden;
}

.table-scroll::-webkit-scrollbar {
    width: 8px;
}

.table-scroll::-webkit-scrollbar-thumb {
    background: #c9d4e1;
    border-radius: 6px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 12px;
    text-align: left;
    border-right: 2px solid #c4d0e0;
    border-bottom: 2px solid #c4d0e0;
}

tr td:last-child,
tr th:last-child {
    border-right: none;
}

th {
    background: #8aa5c6;
    color: #fff;
    font-weight: 700;
}

.pesan-box {
    margin-top: 26px;
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    border: 1px solid #d3dce6;
    height: 220px;
}

.pesan-title {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 12px;
}

.btn {
    display: inline-block;
    padding: 8px 12px;
    border-radius: 6px;
    border: none;
    background: #234a7c;
    color: #fff;
    cursor: pointer;
    font-size: 13px;
}
</style>
</head>
<body>

    <?php include('penting/leftbar.php'); ?>

<div class="content">
    <div class="page">
        <div class="title">Pesan Masuk</div>
        <div class="subtitle">Ringkasan sistem sewa baju Busanara</div>

        <div class="table-box">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px">
                <div style="font-weight: 600; color: #333">Data Pengunjung</div>
                <button class="btn" id="btnRefresh">Get Data</button>
            </div>
            <div class="table-scroll">
                <table id="tblHubungi">
                    <thead>
                        <tr>
                            <th>NAMA</th>
                            <th>EMAIL</th>
                            <th>NO HP</th>
                            <th>PESAN</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

<script>
function toggleSubMenu(id) {
    const submenu = document.getElementById(id);
    submenu.style.display = submenu.style.display === "block" ? "none" : "block";
}

function loadHubungi(){
    fetch('gethubungi.php')
    .then(res => res.json())
    .then(data => {
        const tbody = document.querySelector('#tblHubungi tbody');
        tbody.innerHTML = '';
        if (!Array.isArray(data) || data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;padding:18px;color:#999">Tidak ada data.</td></tr>';
            return;
        }
        data.forEach(row => {
            const tr = document.createElement('tr');
            tr.innerHTML = `<td>${row.nama_visit}</td><td>${row.email_visit}</td><td>${row.telp_visit}</td><td>${row.pesan}</td>`;
            tbody.appendChild(tr);
        });
    })
    .catch(err => alert('Error mengambil data.'));
}

document.getElementById('btnRefresh').addEventListener('click', loadHubungi);
window.addEventListener('DOMContentLoaded', loadHubungi);
</script>

</body>
</html>
