<div class="sidebar">

    <div class="sidebar-brand">
        <span>BUSANARA</span><br>
        <small>Admin Panel</small>
    </div>

    <ul class="sidebar-menu">

        <li>
            <a href="dashboard.php">
                <span class="icon">🏠</span>
                <span class="text">Dashboard</span>
            </a>
        </li>

        <!-- DROPDOWN DATA SEWA -->
        <li class="dropdown">
            <div class="dropdown-toggle">
                <span class="icon">📦</span>
                <span class="text">Data Sewa</span>
                <span class="arrow">▾</span>
            </div>
            <ul class="dropdown-menu">
                <li><a href="sewa_bayar.php">Menunggu Pembayaran</a></li>
                <li><a href="sewa_konfirmasi.php">Menunggu Konfirmasi</a></li>
                <li><a href="sewa_kembali.php">Pengembalian</a></li>
                <li><a href="sewa.php">Data Sewa</a></li>
            </ul>
        </li>

        <!-- DROPDOWN JENIS & BAJU -->
        <li class="dropdown">
            <div class="dropdown-toggle">
                <span class="icon">👕</span>
                <span class="text">Jenis & Baju</span>
                <span class="arrow">▾</span>
            </div>
            <ul class="dropdown-menu">
                <li><a href="jenis_baju.php">Data Jenis</a></li>
                <li><a href="baju.php">Data Baju</a></li>
            </ul>
        </li>

        <li>
            <a href="reg-users.php">
                <span class="icon">👤</span>
                <span class="text">Member</span>
            </a>
        </li>

        <li>
            <a href="manage-conactusquery.php">
                <span class="icon">✉</span>
                <span class="text">Pesan Masuk</span>
            </a>
        </li>

        <!-- MENU LAPORAN -->
        <li class="menu-laporan">
            <a href="laporan.php">
                <span class="icon">📊</span>
                <span class="text">Laporan</span>
            </a>
        </li>

        <!-- LOGOUT -->
        <li class="logout">
            <a href="logout.php">
                <span class="icon">🚪</span>
                <span class="text">Logout</span>
            </a>
        </li>

    </ul>
</div>

<script>
    const drops = document.querySelectorAll(".dropdown-toggle");
    drops.forEach(btn => {
        btn.addEventListener("click", () => {
            btn.parentElement.classList.toggle("active");
        });
    });
</script>
