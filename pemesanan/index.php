<?php
require_once __DIR__ . "/../auth/staff_only.php";
include "../config.php";
$query="SELECT pm.id_pemesanan,pb.nama AS nama_pembeli,pm.tanggal,pm.status,GROUP_CONCAT(CONCAT(COALESCE(mm.merk,m.merk),' ',COALESCE(mm.tipe,m.tipe),' (',dp.jumlah,')') SEPARATOR ', ') AS mobil,COALESCE(SUM(dp.subtotal),0) AS total FROM pemesanan pm JOIN pembeli pb ON pm.id_pembeli=pb.id_pembeli LEFT JOIN detail_pemesanan dp ON pm.id_pemesanan=dp.id_pemesanan LEFT JOIN mobil m ON dp.id_mobil=m.id_mobil LEFT JOIN master_mobil mm ON m.id_master=mm.id_master GROUP BY pm.id_pemesanan,pb.nama,pm.tanggal,pm.status ORDER BY pm.id_pemesanan ASC";
$data=mysqli_query($conn,$query); if(!$data) die("Query pemesanan gagal: ".mysqli_error($conn));
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>Data Pemesanan</title><link rel="stylesheet" href="../assets/css/style.css"></head><body>
<div class="header"><div class="inner"><h1>Sistem Penjualan Mobil Bekas</h1><div class="top-user"><?php if (isset($_SESSION['role'])) { ?><span class="user-badge"><?= htmlspecialchars($_SESSION['role']); ?><?php if (isset($_SESSION['username'])) { ?> / <?= htmlspecialchars($_SESSION['username']); ?><?php } ?></span><a class="logout-link" href="../logout.php">Logout</a><?php } ?></div></div></div>
<div class="container"><div class="card"><h2>Data Pemesanan</h2><div class="toolbar"><a class="btn" href="tambah.php">+ Tambah Pemesanan</a><a class="btn btn-secondary" href="../dashboard.php">Kembali</a></div>
<table><tr><th>No</th><th>ID</th><th>Pembeli</th><th>Mobil</th><th>Tanggal</th><th>Total</th><th>Status</th><th>Aksi</th></tr>
<?php if(mysqli_num_rows($data)>0){ $no=1; while($row=mysqli_fetch_assoc($data)){ ?>
<tr><td><?= $no++; ?></td><td><?= htmlspecialchars($row['id_pemesanan']); ?></td><td><?= htmlspecialchars($row['nama_pembeli']); ?></td><td><?= htmlspecialchars($row['mobil']); ?></td><td><?= htmlspecialchars($row['tanggal']); ?></td><td>Rp <?= number_format($row['total'],0,',','.'); ?></td><td><?= htmlspecialchars($row['status']); ?></td><td><a class="btn btn-secondary" href="edit.php?id=<?= $row['id_pemesanan']; ?>">Edit</a> <a class="btn btn-danger" href="hapus.php?id=<?= $row['id_pemesanan']; ?>" onclick="return confirm('Yakin hapus pemesanan ini?')">Hapus</a> <a class="btn btn-danger" href="batal.php?id=<?= $row['id_pemesanan']; ?>" onclick="return confirm('Batalkan pemesanan ini?')">Batalkan</a></td></tr>
<?php }} else { ?><tr><td colspan="8">Belum ada data pemesanan.</td></tr><?php } ?>
</table></div></div></body></html>
