<?php
require_once __DIR__ . "/../auth/staff_only.php";
include "../config.php";
$data = mysqli_query($conn, "SELECT * FROM pembeli ORDER BY id_pembeli ASC");
if (!$data) die("Query pembeli gagal: " . mysqli_error($conn));
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>Data Pembeli</title><link rel="stylesheet" href="../assets/css/style.css"></head><body>
<div class="header"><div class="inner"><h1>Sistem Penjualan Mobil Bekas</h1><div class="top-user"><?php if (isset($_SESSION['role'])) { ?><span class="user-badge"><?= htmlspecialchars($_SESSION['role']); ?><?php if (isset($_SESSION['username'])) { ?> / <?= htmlspecialchars($_SESSION['username']); ?><?php } ?></span><a class="logout-link" href="../logout.php">Logout</a><?php } ?></div></div></div>
<div class="container"><div class="card"><h2>Data Pembeli</h2>
<div class="toolbar"><a class="btn" href="tambah.php">+ Tambah Pembeli</a><a class="btn btn-secondary" href="../dashboard.php">Kembali</a></div>
<table><tr><th>No</th><th>ID</th><th>Nama</th><th>Alamat</th><th>No HP</th><th>Aksi</th></tr>
<?php if (mysqli_num_rows($data)>0) { $no=1; while($row=mysqli_fetch_assoc($data)) { ?>
<tr><td><?= $no++; ?></td><td><?= htmlspecialchars($row['id_pembeli']); ?></td><td><?= htmlspecialchars($row['nama']); ?></td><td><?= htmlspecialchars($row['alamat']); ?></td><td><?= htmlspecialchars($row['no_hp']); ?></td><td><a class="btn btn-secondary" href="edit.php?id=<?= $row['id_pembeli']; ?>">Edit</a> <a class="btn btn-danger" href="hapus.php?id=<?= $row['id_pembeli']; ?>" onclick="return confirm('Yakin hapus data pembeli ini?')">Hapus</a></td></tr>
<?php } } else { ?><tr><td colspan="6">Belum ada data pembeli.</td></tr><?php } ?>
</table></div></div></body></html>
