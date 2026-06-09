<?php
require_once __DIR__ . "/auth/check.php";
include "config.php";

if (!in_array($_SESSION['role'], ['Admin', 'Staff'])) {
    header("Location: dashboard.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$q = mysqli_query($conn, "
SELECT 
    t.id_transaksi,
    t.id_pemesanan,
    t.total,
    COALESCE(t.dp_booking, 500000) AS dp_booking,
    COALESCE(t.sisa_pembayaran, (t.total - COALESCE(t.dp_booking, 500000))) AS sisa_pembayaran,
    t.metode_pembayaran,
    t.status,
    pb.nama AS pembeli
FROM transaksi t
JOIN pemesanan p ON t.id_pemesanan = p.id_pemesanan
JOIN pembeli pb ON p.id_pembeli = pb.id_pembeli
WHERE t.id_transaksi = $id
");

if (!$q || mysqli_num_rows($q) == 0) {
    die('Transaksi tidak ditemukan.');
}

$row = mysqli_fetch_assoc($q);

$dp_booking = isset($row['dp_booking']) ? (float)$row['dp_booking'] : 500000;
$sisa_pembayaran = isset($row['sisa_pembayaran']) ? (float)$row['sisa_pembayaran'] : ((float)$row['total'] - $dp_booking);

$details = mysqli_query($conn, "
SELECT 
    COALESCE(mm.merk, m.merk) AS merk,
    COALESCE(mm.tipe, m.tipe) AS tipe,
    dp.jumlah,
    dp.harga,
    dp.subtotal
FROM detail_pemesanan dp
JOIN mobil m ON dp.id_mobil = m.id_mobil
LEFT JOIN master_mobil mm ON m.id_master = mm.id_master
WHERE dp.id_pemesanan = " . (int)$row['id_pemesanan'] . "
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Transaksi</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="header">
    <div class="inner">
        <h1>Sistem Penjualan Mobil Bekas</h1>
        <div class="top-user">
            <?php if (isset($_SESSION['role'])) { ?>
                <span class="user-badge">
                    <?= htmlspecialchars($_SESSION['role']); ?>
                    <?php if (isset($_SESSION['username'])) { ?> / <?= htmlspecialchars($_SESSION['username']); ?><?php } ?>
                </span>
                <a class="logout-link" href="logout.php">Logout</a>
            <?php } ?>
        </div>
    </div>
</div>

<div class="container">
<div class="card">
<h2>Detail Transaksi #<?= htmlspecialchars($row['id_transaksi']); ?></h2>

<div class="form-group"><label>Pembeli</label><div><?= htmlspecialchars($row['pembeli']); ?></div></div>
<div class="form-group"><label>Total</label><div>Rp <?= number_format($row['total'], 0, ',', '.'); ?></div></div>
<div class="form-group"><label>DP / Booking Fee</label><div>Rp <?= number_format($dp_booking, 0, ',', '.'); ?></div></div>
<div class="form-group"><label>Sisa Pembayaran</label><div>Rp <?= number_format($sisa_pembayaran, 0, ',', '.'); ?></div></div>
<div class="form-group"><label>Metode Pembayaran</label><div><?= htmlspecialchars($row['metode_pembayaran']); ?></div></div>
<div class="form-group"><label>Status</label><div><?= htmlspecialchars($row['status']); ?></div></div>

<h3>Detail Mobil</h3>
<table>
<tr>
    <th>No</th>
    <th>Merk</th>
    <th>Tipe</th>
    <th>Jumlah</th>
    <th>Harga</th>
    <th>Subtotal</th>
</tr>

<?php if ($details && mysqli_num_rows($details) > 0) { ?>
    <?php $no = 1; ?>
    <?php while ($d = mysqli_fetch_assoc($details)) { ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= htmlspecialchars($d['merk']); ?></td>
        <td><?= htmlspecialchars($d['tipe']); ?></td>
        <td><?= htmlspecialchars($d['jumlah']); ?></td>
        <td>Rp <?= number_format($d['harga'], 0, ',', '.'); ?></td>
        <td>Rp <?= number_format($d['subtotal'], 0, ',', '.'); ?></td>
    </tr>
    <?php } ?>
<?php } else { ?>
    <tr>
        <td colspan="6">Belum ada detail mobil.</td>
    </tr>
<?php } ?>
</table>

<div class="form-actions">
<?php if ($_SESSION['role'] === 'Admin') { ?>
    <a class="btn btn-secondary" href="laporan/transaksi.php">Kembali</a>
<?php } else { ?>
    <a class="btn btn-secondary" href="transaksi/index.php">Kembali</a>
<?php } ?>
</div>
</div>
</div>
</body>
</html>
