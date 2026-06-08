<?php
require_once __DIR__ . "/../auth/staff_only.php";
include "../config.php";

$query = "
SELECT 
    b.id_pembatalan,
    t.id_transaksi,
    pm.id_pemesanan,
    p.nama AS pembeli,
    b.tanggal_batal,
    b.alasan
FROM pembatalan b
JOIN transaksi t ON b.id_transaksi = t.id_transaksi
JOIN pemesanan pm ON t.id_pemesanan = pm.id_pemesanan
JOIN pembeli p ON pm.id_pembeli = p.id_pembeli
ORDER BY b.id_pembatalan ASC
";

$data = mysqli_query($conn, $query);
if (!$data) {
    die("Query pembatalan gagal: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta charset="UTF-8">
    <title>Data Pembatalan</title>
</head>
<body>
<div class="header"><div class="inner"><h1>Sistem Penjualan Mobil Bekas</h1><div class="top-user"><?php if (isset($_SESSION['role'])) { ?><span class="user-badge"><?= htmlspecialchars($_SESSION['role']); ?><?php if (isset($_SESSION['username'])) { ?> / <?= htmlspecialchars($_SESSION['username']); ?><?php } ?></span><a class="logout-link" href="../logout.php">Logout</a><?php } ?></div></div></div>
<div class="container"><div class="card">
<h2>Data Pembatalan</h2>

<a class="btn" href="tambah.php">+ Tambah Pembatalan</a> |
<a class="btn btn-secondary" href="../dashboard.php">Kembali</a>
<br><br>

<table border="1" cellpadding="8" cellspacing="0">
<tr>
    <th>No</th>
    <th>ID Pembatalan</th>
    <th>ID Transaksi</th>
    <th>ID Pemesanan</th>
    <th>Nama Pembeli</th>
    <th>Tanggal Batal</th>
    <th>Alasan</th>
</tr>

<?php if (mysqli_num_rows($data) > 0) { ?>
    <?php $no = 1; ?>
    <?php while ($row = mysqli_fetch_assoc($data)) { ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= htmlspecialchars($row['id_pembatalan']); ?></td>
        <td><?= htmlspecialchars($row['id_transaksi']); ?></td>
        <td><?= htmlspecialchars($row['id_pemesanan']); ?></td>
        <td><?= htmlspecialchars($row['pembeli']); ?></td>
        <td><?= htmlspecialchars($row['tanggal_batal']); ?></td>
        <td><?= htmlspecialchars($row['alasan']); ?></td>
    </tr>
    <?php } ?>
<?php } else { ?>
    <tr>
        <td colspan="7">Belum ada data pembatalan.</td>
    </tr>
<?php } ?>
</table>
</div></div>
</body>
</html>
