<?php
require_once __DIR__ . "/../auth/admin_only.php";
include "../config.php";

$master_mobil = mysqli_query($conn, "SELECT * FROM master_mobil ORDER BY merk ASC, tipe ASC");
if (!$master_mobil) {
    die("Tabel master_mobil belum tersedia. Jalankan ALTER_MASTER_MOBIL.sql terlebih dahulu. Error: " . mysqli_error($conn));
}

if (isset($_POST['submit'])) {
    $id_master = (int) $_POST['id_master'];
    $tahun = (int) $_POST['tahun'];
    $warna = mysqli_real_escape_string($conn, $_POST['warna']);
    $harga = (float) $_POST['harga'];
    $stock = (int) $_POST['stock'];

    $qMaster = mysqli_query($conn, "SELECT merk, tipe FROM master_mobil WHERE id_master=$id_master");
    if (!$qMaster || mysqli_num_rows($qMaster) == 0) {
        $error = "Model mobil tidak valid.";
    } else {
        $master = mysqli_fetch_assoc($qMaster);
        $merk = mysqli_real_escape_string($conn, $master['merk']);
        $tipe = mysqli_real_escape_string($conn, $master['tipe']);

        $sql = "
            INSERT INTO mobil (id_master, merk, tipe, tahun, warna, harga, stock, status)
            VALUES ($id_master, '$merk', '$tipe', $tahun, '$warna', $harga, $stock, 'Tersedia')
        ";

        if (mysqli_query($conn, $sql)) {
            header("Location: index.php?status=tambah_berhasil");
            exit;
        } else {
            $error = "Gagal menambah mobil: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta charset="UTF-8">
    <title>Tambah Mobil</title>
</head>
<body>
<div class="header"><div class="inner"><h1>Sistem Penjualan Mobil Bekas</h1><div class="top-user"><?php if (isset($_SESSION['role'])) { ?><span class="user-badge"><?= htmlspecialchars($_SESSION['role']); ?><?php if (isset($_SESSION['username'])) { ?> / <?= htmlspecialchars($_SESSION['username']); ?><?php } ?></span><a class="logout-link" href="../logout.php">Logout</a><?php } ?></div></div></div>
<div class="container"><div class="card">
<h2>Tambah Mobil</h2>

<?php if (isset($error)) { ?>
    <p style="color:red;"><?= htmlspecialchars($error); ?></p>
<?php } ?>

<form method="POST">
    Model Mobil:<br>
    <select name="id_master" required>
        <option value="">-- Pilih Model Mobil --</option>
        <?php while ($mm = mysqli_fetch_assoc($master_mobil)) { ?>
            <option value="<?= $mm['id_master']; ?>">
                <?= htmlspecialchars($mm['merk'] . " " . $mm['tipe']); ?>
            </option>
        <?php } ?>
    </select><br><br>

    Tahun:<br>
    <input name="tahun" type="number" min="1980" max="2035" required><br><br>

    Warna:<br>
    <input name="warna" type="text" required><br><br>

    Harga:<br>
    <input name="harga" type="number" step="0.01" required><br><br>

    Stok:<br>
    <input name="stock" type="number" min="0" required><br><br>

    <button type="submit" name="submit">Simpan Mobil</button>
    <a class="btn btn-secondary" href="index.php">Batal</a>
</form>
</div></div>
</body>
</html>
