<?php
require_once __DIR__ . "/../auth/admin_only.php";
include "../config.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$q = mysqli_query($conn, "SELECT * FROM mobil WHERE id_mobil=$id");
if (!$q || mysqli_num_rows($q) == 0) {
    die("Data mobil tidak ditemukan.");
}
$row = mysqli_fetch_assoc($q);

if (isset($_POST['submit'])) {
    $merk = mysqli_real_escape_string($conn, $_POST['merk']);
    $tipe = mysqli_real_escape_string($conn, $_POST['tipe']);
    $tahun = (int)$_POST['tahun'];
    $warna = mysqli_real_escape_string($conn, $_POST['warna']);
    $harga = (float)$_POST['harga'];
    $stock = (int)$_POST['stock'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $sql = "UPDATE mobil SET merk='$merk', tipe='$tipe', tahun=$tahun, warna='$warna', harga=$harga, stock=$stock, status='$status' WHERE id_mobil=$id";
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?status=edit_berhasil");
        exit;
    } else {
        $error = "Gagal memperbarui mobil: ".mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Mobil</title>
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
<h2>Edit Mobil</h2>
<?php if(isset($error)){ ?>
<div class="alert-error"><?= htmlspecialchars($error); ?></div>
<?php } ?>
<form method="POST">
<div class="form-group"><label>Merk</label><input name="merk" type="text" value="<?= htmlspecialchars($row['merk']); ?>" required></div>
<div class="form-group"><label>Tipe</label><input name="tipe" type="text" value="<?= htmlspecialchars($row['tipe']); ?>" required></div>
<div class="form-group"><label>Tahun</label><input name="tahun" type="number" value="<?= htmlspecialchars($row['tahun']); ?>" required></div>
<div class="form-group"><label>Warna</label><input name="warna" type="text" value="<?= htmlspecialchars($row['warna']); ?>" required></div>
<div class="form-group"><label>Harga</label><input name="harga" type="number" step="0.01" value="<?= htmlspecialchars($row['harga']); ?>" required></div>
<div class="form-group"><label>Stok</label><input name="stock" type="number" value="<?= htmlspecialchars($row['stock']); ?>" required></div>
<div class="form-group"><label>Status</label>
<select name="status">
<option value="Tersedia" <?= $row['status']=="Tersedia"?"selected":"" ?>>Tersedia</option>
<option value="Terjual" <?= $row['status']=="Terjual"?"selected":"" ?>>Terjual</option>
<option value="Dipesan" <?= $row['status']=="Dipesan"?"selected":"" ?>>Dipesan</option>
</select>
</div>
<div class="form-actions">
<button type="submit" name="submit">Simpan</button>
<a class="btn btn-secondary" href="index.php">Batal</a>
</div>
</form>
</div>
</div>
</body>
</html>
