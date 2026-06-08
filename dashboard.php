<?php
require_once __DIR__ . "/auth/check.php";
$role = $_SESSION['role'];
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Sistem Penjualan Mobil Bekas</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="header">
    <div class="inner">
        <h1>Sistem Penjualan Mobil Bekas</h1>
        <div class="top-user">
            <span class="user-badge">
                <?= htmlspecialchars($role); ?>
                <?php if ($username !== '') { ?> / <?= htmlspecialchars($username); ?><?php } ?>
            </span>
            <a class="logout-link" href="logout.php">Logout</a>
        </div>
    </div>
</div>

<div class="container">
<div class="card">

<?php if($role === 'Admin'){ ?>
    <h3>Data Utama</h3>
    <ul class="menu-list">
        <li><a href="mobil/index.php">Data Mobil</a></li>
    </ul>

    <h3>Laporan</h3>
    <ul class="menu-list">
        <li><a href="laporan/transaksi.php">Laporan Penjualan</a></li>
        <li><a href="laporan/pembelian.php">Laporan Pembelian</a></li>
    </ul>
<?php } ?>

<?php if($role === 'Staff'){ ?>
    <h3>Penjualan</h3>
    <ul class="menu-list">
        <li><a href="pembeli/index.php">Data Pembeli</a></li>
        <li><a href="pemesanan/index.php">Pemesanan Mobil</a></li>
        <li><a href="transaksi/index.php">Transaksi Pembayaran</a></li>
        <li><a href="pembatalan/index.php">Pembatalan Pesanan</a></li>
    </ul>

    <h3>Pembelian</h3>
    <ul class="menu-list">
        <li><a href="pembelian/index.php">Pembelian Mobil dari Penjual</a></li>
    </ul>
<?php } ?>

</div>
</div>
</body>
</html>
