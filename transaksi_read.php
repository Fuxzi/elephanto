<?php
require_once __DIR__ . "/auth/check.php";
include "config.php";

if (!in_array($_SESSION['role'], ['Admin', 'Staff'])) {
    header("Location: dashboard.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$q = mysqli_query($conn, "
SELECT t.id_transaksi, t.id_pemesanan, t.total, t.metode_pembayaran, t.status,
       pb.nama AS pembeli
FROM transaksi t
JOIN pemesanan p ON t.id_pemesanan=p.id_pemesanan
JOIN pembeli pb ON p.id_pembeli=pb.id_pembeli
WHERE t.id_transaksi=$id
");
if (!$q || mysqli_num_rows($q)==0) {
    die('Transaksi tidak ditemukan.');
}
$row = mysqli_fetch_assoc($q);

$details = mysqli_query($conn, "
SELECT m.merk, m.tipe, dp.jumlah, dp.harga, dp.subtotal
FROM detail_pemesanan dp
JOIN mobil m ON dp.id_mobil=m.id_mobil
WHERE dp.id_pemesanan=".$row['id_pemesanan']);
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
    </div>
</div>
<div class="container">
<div class="card">
<h2>Detail Transaksi #<?= $row['id_transaksi']; ?></h2>
<div class="form-group"><label>Pembeli</label><div><?= htmlspecialchars($row['pembeli']); ?></div></div>
<div class="form-group"><label>Total</label><div>Rp <?= number_format($row['total'],0,',','.'); ?></div></div>
<div class="form-group"><label>Metode Pembayaran</label><div><?= htmlspecialchars($row['metode_pembayaran']); ?></div></div>
<div class="form-group"><label>Status</label><div><?= htmlspecialchars($row['status']); ?></div></div>

<h3>Detail Mobil</h3>
<table>
<tr><th>No</th><th>Merk</th><th>Tipe</th><th>Jumlah</th><th>Harga</th><th>Subtotal</th></tr>
<?php $no=1; while($d=mysqli_fetch_assoc($details)){ ?>
<tr>
<td><?= $no++; ?></td>
<td><?= htmlspecialchars($d['merk']); ?></td>
<td><?= htmlspecialchars($d['tipe']); ?></td>
<td><?= htmlspecialchars($d['jumlah']); ?></td>
<td>Rp <?= number_format($d['harga'],0,',','.'); ?></td>
<td>Rp <?= number_format($d['subtotal'],0,',','.'); ?></td>
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
