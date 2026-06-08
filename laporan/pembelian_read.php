<?php
require_once __DIR__ . "/../auth/admin_only.php";
include "../config.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$q = mysqli_query($conn, "
SELECT 
    pb.id_pembelian,
    pb.nama_penjual,
    pb.no_hp_penjual,
    pb.tanggal_pembelian,
    pb.total,
    COALESCE(mm.merk, m.merk) AS nama_merk,
    COALESCE(mm.tipe, m.tipe) AS nama_tipe,
    m.tahun,
    m.warna,
    m.harga AS harga_jual,
    m.stock,
    m.status,
    dp.jumlah,
    dp.harga_beli,
    dp.subtotal
FROM pembelian pb
JOIN mobil m ON pb.id_mobil = m.id_mobil
LEFT JOIN master_mobil mm ON m.id_master = mm.id_master
LEFT JOIN detail_pembelian dp ON pb.id_pembelian = dp.id_pembelian
WHERE pb.id_pembelian = $id
");

if (!$q || mysqli_num_rows($q) == 0) {
    die("Data pembelian tidak ditemukan.");
}

$row = mysqli_fetch_assoc($q);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Pembelian</title>
<link rel="stylesheet" href="../assets/css/style.css">
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
                <a class="logout-link" href="../logout.php">Logout</a>
            <?php } ?>
        </div>
    </div>
</div>

<div class="container">
<div class="card">
<h2>Detail Pembelian #<?= htmlspecialchars($row['id_pembelian']); ?></h2>
<p class="subtitle">Rincian pembelian mobil dari penjual.</p>

<table>
    <tr><th>ID Pembelian</th><td><?= htmlspecialchars($row['id_pembelian']); ?></td></tr>
    <tr><th>Nama Penjual</th><td><?= htmlspecialchars($row['nama_penjual']); ?></td></tr>
    <tr><th>No HP Penjual</th><td><?= htmlspecialchars($row['no_hp_penjual']); ?></td></tr>
    <tr><th>Tanggal Pembelian</th><td><?= htmlspecialchars($row['tanggal_pembelian']); ?></td></tr>
    <tr><th>Mobil</th><td><?= htmlspecialchars($row['nama_merk'] . " " . $row['nama_tipe']); ?></td></tr>
    <tr><th>Tahun</th><td><?= htmlspecialchars($row['tahun']); ?></td></tr>
    <tr><th>Warna</th><td><?= htmlspecialchars($row['warna']); ?></td></tr>
    <tr><th>Jumlah</th><td><?= htmlspecialchars($row['jumlah']); ?></td></tr>
    <tr><th>Harga Beli</th><td>Rp <?= number_format($row['harga_beli'], 0, ',', '.'); ?></td></tr>
    <tr><th>Subtotal</th><td>Rp <?= number_format($row['subtotal'], 0, ',', '.'); ?></td></tr>
    <tr><th>Total Pembelian</th><td>Rp <?= number_format($row['total'], 0, ',', '.'); ?></td></tr>
    <tr><th>Harga Jual</th><td>Rp <?= number_format($row['harga_jual'], 0, ',', '.'); ?></td></tr>
    <tr><th>Stok Saat Ini</th><td><?= htmlspecialchars($row['stock']); ?></td></tr>
    <tr><th>Status Mobil</th><td><?= htmlspecialchars($row['status']); ?></td></tr>
</table>

<div class="form-actions">
    <a class="btn btn-secondary" href="pembelian.php">Kembali</a>
</div>
</div>
</div>
</body>
</html>
