<?php
require_once __DIR__ . "/../auth/admin_only.php";
include "../config.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$q = mysqli_query($conn, "SELECT * FROM mobil WHERE id_mobil=$id");
if (!$q || mysqli_num_rows($q) == 0) {
    die("Data mobil tidak ditemukan.");
}
$row = mysqli_fetch_assoc($q);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Mobil</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="header">
    <div class="inner">
        <h1>Sistem Penjualan Mobil Bekas</h1>
    </div>
</div>
<div class="container">
<div class="card">
<h2>Detail Mobil</h2>
<div class="form-group"><label>ID Mobil</label><div><?= htmlspecialchars($row['id_mobil']); ?></div></div>
<div class="form-group"><label>Merk</label><div><?= htmlspecialchars($row['merk']); ?></div></div>
<div class="form-group"><label>Tipe</label><div><?= htmlspecialchars($row['tipe']); ?></div></div>
<div class="form-group"><label>Tahun</label><div><?= htmlspecialchars($row['tahun']); ?></div></div>
<div class="form-group"><label>Warna</label><div><?= htmlspecialchars($row['warna']); ?></div></div>
<div class="form-group"><label>Harga</label><div>Rp <?= number_format($row['harga'],0,',','.'); ?></div></div>
<div class="form-group"><label>Stok</label><div><?= htmlspecialchars($row['stock']); ?></div></div>
<div class="form-group"><label>Status</label><div><?= htmlspecialchars($row['status']); ?></div></div>
<div class="form-actions">
<a class="btn btn-secondary" href="index.php">Kembali</a>
</div>
</div>
</div>
</body>
</html>
