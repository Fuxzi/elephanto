<?php
require_once __DIR__ . "/../auth/staff_only.php";
include "../config.php";

$query = "
SELECT 
    pb.id_pembelian,
    pb.nama_penjual,
    pb.no_hp_penjual,
    COALESCE(mm.merk, m.merk) AS nama_merk,
    COALESCE(mm.tipe, m.tipe) AS nama_tipe,
    m.tahun,
    pb.tanggal_pembelian,
    dp.jumlah,
    dp.harga_beli,
    pb.total
FROM pembelian pb
JOIN mobil m ON pb.id_mobil = m.id_mobil
LEFT JOIN master_mobil mm ON m.id_master = mm.id_master
LEFT JOIN detail_pembelian dp ON pb.id_pembelian = dp.id_pembelian
ORDER BY pb.id_pembelian ASC
";

$data = mysqli_query($conn, $query);
if (!$data) {
    die("Query pembelian gagal: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta charset="UTF-8">
    <title>Data Pembelian Mobil dari Penjual</title>
</head>
<body>
<div class="header"><div class="inner"><h1>Sistem Penjualan Mobil Bekas</h1><div class="top-user"><?php if (isset($_SESSION['role'])) { ?><span class="user-badge"><?= htmlspecialchars($_SESSION['role']); ?><?php if (isset($_SESSION['username'])) { ?> / <?= htmlspecialchars($_SESSION['username']); ?><?php } ?></span><a class="logout-link" href="../logout.php">Logout</a><?php } ?></div></div></div>
<div class="container"><div class="card">
<h2>Data Pembelian Mobil dari Penjual</h2>

<a class="btn" href="tambah.php">+ Tambah Pembelian</a> |
<a class="btn btn-secondary" href="../dashboard.php">Kembali</a>
<br><br>

<table border="1" cellpadding="8" cellspacing="0">
<tr>
    <th>No</th>
    <th>ID Pembelian</th>
    <th>Nama Penjual</th>
    <th>No HP Penjual</th>
    <th>Mobil</th>
    <th>Tahun</th>
    <th>Tanggal</th>
    <th>Jumlah</th>
    <th>Harga Beli</th>
    <th>Total</th>
    <th>Aksi</th>
</tr>

<?php if (mysqli_num_rows($data) > 0) { ?>
    <?php $no = 1; ?>
    <?php while ($row = mysqli_fetch_assoc($data)) { ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= htmlspecialchars($row['id_pembelian']); ?></td>
        <td><?= htmlspecialchars($row['nama_penjual']); ?></td>
        <td><?= htmlspecialchars($row['no_hp_penjual']); ?></td>
        <td><?= htmlspecialchars($row['nama_merk'] . " " . $row['nama_tipe']); ?></td>
        <td><?= htmlspecialchars($row['tahun']); ?></td>
        <td><?= htmlspecialchars($row['tanggal_pembelian']); ?></td>
        <td><?= htmlspecialchars($row['jumlah']); ?></td>
        <td>Rp <?= number_format($row['harga_beli'], 0, ',', '.'); ?></td>
        <td>Rp <?= number_format($row['total'], 0, ',', '.'); ?></td>
        <td><a class="btn btn-secondary" href="edit.php?id=<?= $row['id_pembelian']; ?>">Edit</a> <a class="btn btn-danger" href="hapus.php?id=<?= $row['id_pembelian']; ?>" onclick="return confirm('Yakin hapus pembelian ini?')">Hapus</a></td>
    </tr>
    <?php } ?>
<?php } else { ?>
    <tr>
        <td colspan="11">Belum ada data pembelian.</td>
    </tr>
<?php } ?>
</table>
</div></div>
</body>
</html>
