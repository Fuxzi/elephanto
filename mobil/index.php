<?php
require_once __DIR__ . "/../auth/admin_only.php";
include "../config.php";

$query = "
SELECT m.*, COALESCE(mm.merk, m.merk) AS nama_merk, COALESCE(mm.tipe, m.tipe) AS nama_tipe
FROM mobil m
LEFT JOIN master_mobil mm ON m.id_master = mm.id_master
ORDER BY m.id_mobil ASC";
$data = mysqli_query($conn, $query);
if (!$data) die("Query mobil gagal: " . mysqli_error($conn));
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>Data Mobil</title><link rel="stylesheet" href="../assets/css/style.css"></head><body>
<div class="header"><div class="inner"><h1>Sistem Penjualan Mobil Bekas</h1><div class="top-user"><?php if (isset($_SESSION['role'])) { ?><span class="user-badge"><?= htmlspecialchars($_SESSION['role']); ?><?php if (isset($_SESSION['username'])) { ?> / <?= htmlspecialchars($_SESSION['username']); ?><?php } ?></span><a class="logout-link" href="../logout.php">Logout</a><?php } ?></div></div></div>
<div class="container"><div class="card"><h2>Data Mobil</h2>
<div class="toolbar"><a class="btn" href="tambah.php">+ Tambah Mobil</a><a class="btn btn-secondary" href="../dashboard.php">Kembali</a></div>
<table><tr><th>No</th><th>ID</th><th>Merk</th><th>Tipe</th><th>Tahun</th><th>Warna</th><th>Harga</th><th>Stok</th><th>Status</th><th>Aksi</th></tr>
<?php if (mysqli_num_rows($data) > 0) { $no=1; while ($row=mysqli_fetch_assoc($data)) { ?>
<tr>
<td><?= $no++; ?></td><td><?= htmlspecialchars($row['id_mobil']); ?></td><td><?= htmlspecialchars($row['nama_merk']); ?></td><td><?= htmlspecialchars($row['nama_tipe']); ?></td><td><?= htmlspecialchars($row['tahun']); ?></td><td><?= htmlspecialchars($row['warna']); ?></td><td>Rp <?= number_format($row['harga'],0,',','.'); ?></td><td><?= htmlspecialchars($row['stock']); ?></td><td><?= htmlspecialchars($row['status']); ?></td>
<td><a class="btn btn-secondary" href="read.php?id=<?= $row['id_mobil']; ?>">Lihat</a> <a class="btn btn-secondary" href="edit.php?id=<?= $row['id_mobil']; ?>">Edit</a> <a class="btn btn-danger" href="hapus.php?id=<?= $row['id_mobil']; ?>" onclick="return confirm('Yakin hapus data mobil ini?')">Hapus</a></td>
</tr><?php } } else { ?><tr><td colspan="10">Belum ada data mobil.</td></tr><?php } ?>
</table></div></div></body></html>
