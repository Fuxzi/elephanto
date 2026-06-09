<?php
require_once __DIR__ . "/../auth/admin_only.php";
include "../config.php";

$query = "
SELECT 
    transaksi.id_transaksi,
    pemesanan.tanggal,
    pembeli.nama AS nama_pembeli,
    COALESCE(master_mobil.merk, mobil.merk) AS nama_merk,
    COALESCE(master_mobil.tipe, mobil.tipe) AS nama_tipe,
    detail_pemesanan.jumlah,
    transaksi.total,
    transaksi.dp_booking,
    transaksi.sisa_pembayaran,
    transaksi.metode_pembayaran,
    transaksi.status
FROM transaksi
JOIN pemesanan ON transaksi.id_pemesanan = pemesanan.id_pemesanan
JOIN pembeli ON pemesanan.id_pembeli = pembeli.id_pembeli
JOIN detail_pemesanan ON pemesanan.id_pemesanan = detail_pemesanan.id_pemesanan
JOIN mobil ON detail_pemesanan.id_mobil = mobil.id_mobil
LEFT JOIN master_mobil ON mobil.id_master = master_mobil.id_master
ORDER BY transaksi.id_transaksi ASC
";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query laporan penjualan gagal: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Penjualan</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="header"><div class="inner"><h1>Sistem Penjualan Mobil Bekas</h1><div class="top-user"><?php if (isset($_SESSION['role'])) { ?><span class="user-badge"><?= htmlspecialchars($_SESSION['role']); ?><?php if (isset($_SESSION['username'])) { ?> / <?= htmlspecialchars($_SESSION['username']); ?><?php } ?></span><a class="logout-link" href="../logout.php">Logout</a><?php } ?></div></div></div>
<div class="container"><div class="card">
<h2>Laporan Penjualan</h2>
<p class="subtitle">Riwayat transaksi penjualan mobil kepada pembeli.</p>

<div class="toolbar">
    <a class="btn btn-secondary" href="../dashboard.php">Kembali</a>
</div>

<table>
    <tr>
        <th>No</th>
        <th>ID Transaksi</th>
        <th>Tanggal</th>
        <th>Nama Pembeli</th>
        <th>Mobil</th>
        <th>Jumlah</th>
        <th>Total</th>
        <th>DP / Booking Fee</th>
        <th>Sisa</th>
        <th>Metode</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    <?php if (mysqli_num_rows($result) > 0) { ?>
        <?php $no = 1; ?>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['id_transaksi']); ?></td>
            <td><?= htmlspecialchars($row['tanggal']); ?></td>
            <td><?= htmlspecialchars($row['nama_pembeli']); ?></td>
            <td><?= htmlspecialchars($row['nama_merk'] . " " . $row['nama_tipe']); ?></td>
            <td><?= htmlspecialchars($row['jumlah']); ?></td>
            <td>Rp <?= number_format($row['total'], 0, ',', '.'); ?></td>
            <td>Rp <?= number_format($row['dp_booking'], 0, ',', '.'); ?></td>
            <td>Rp <?= number_format($row['sisa_pembayaran'], 0, ',', '.'); ?></td>
            <td><?= htmlspecialchars($row['metode_pembayaran']); ?></td>
            <td><?= htmlspecialchars($row['status']); ?></td>
            <td>
                <a class="btn btn-secondary" href="../transaksi_read.php?id=<?= $row['id_transaksi']; ?>">Detail</a>
            </td>
        </tr>
        <?php } ?>
    <?php } else { ?>
        <tr><td colspan="12">Belum ada data penjualan.</td></tr>
    <?php } ?>
</table>
</div></div>
</body>
</html>
