<?php
require_once __DIR__ . "/../auth/staff_only.php";
include "../config.php";

$query = "
SELECT 
    t.id_transaksi,
    p.id_pemesanan,
    pb.nama AS pembeli,
    t.total,
    t.metode_pembayaran,
    t.status
FROM transaksi t
JOIN pemesanan p ON t.id_pemesanan = p.id_pemesanan
JOIN pembeli pb ON p.id_pembeli = pb.id_pembeli
ORDER BY t.id_transaksi ASC
";

$data = mysqli_query($conn, $query);
if (!$data) {
    die("Query transaksi gagal: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta charset="UTF-8">
    <title>Data Transaksi</title>
</head>
<body>
<div class="header"><div class="inner"><h1>Sistem Penjualan Mobil Bekas</h1><div class="top-user"><?php if (isset($_SESSION['role'])) { ?><span class="user-badge"><?= htmlspecialchars($_SESSION['role']); ?><?php if (isset($_SESSION['username'])) { ?> / <?= htmlspecialchars($_SESSION['username']); ?><?php } ?></span><a class="logout-link" href="../logout.php">Logout</a><?php } ?></div></div></div>
<div class="container"><div class="card">
<h2>Data Transaksi</h2>

<a class="btn" href="tambah.php">+ Tambah Transaksi</a> |
<a class="btn btn-secondary" href="../dashboard.php">Kembali</a>
<br><br>

<table border="1" cellpadding="8" cellspacing="0">
<tr>
    <th>No</th>
    <th>ID Transaksi</th>
    <th>ID Pemesanan</th>
    <th>Nama Pembeli</th>
    <th>Total</th>
    <th>Metode</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>

<?php if (mysqli_num_rows($data) > 0) { ?>
    <?php $no = 1; ?>
    <?php while ($row = mysqli_fetch_assoc($data)) { ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= htmlspecialchars($row['id_transaksi']); ?></td>
        <td><?= htmlspecialchars($row['id_pemesanan']); ?></td>
        <td><?= htmlspecialchars($row['pembeli']); ?></td>
        <td>Rp <?= number_format($row['total'], 0, ',', '.'); ?></td>
        <td><?= htmlspecialchars($row['metode_pembayaran']); ?></td>
        <td><?= htmlspecialchars($row['status']); ?></td>
        <td><a class="btn btn-secondary" href="edit.php?id=<?= $row['id_transaksi']; ?>">Edit</a></td>
    </tr>
    <?php } ?>
<?php } else { ?>
    <tr>
        <td colspan="8">Belum ada data transaksi.</td>
    </tr>
<?php } ?>
</table>
</div></div>
</body>
</html>
