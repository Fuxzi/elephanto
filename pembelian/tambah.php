<?php
require_once __DIR__ . "/../auth/staff_only.php";
include "../config.php";

$master_mobil = mysqli_query($conn, "SELECT * FROM master_mobil ORDER BY merk ASC, tipe ASC");
if (!$master_mobil) {
    die("Tabel master_mobil belum tersedia. Jalankan ALTER_MASTER_MOBIL.sql terlebih dahulu. Error: " . mysqli_error($conn));
}

if (isset($_POST['submit'])) {
    $nama_penjual = mysqli_real_escape_string($conn, $_POST['nama_penjual']);
    $no_hp_penjual = mysqli_real_escape_string($conn, $_POST['no_hp_penjual']);
    $id_master = (int) $_POST['id_master'];
    $tahun = (int) $_POST['tahun'];
    $warna = mysqli_real_escape_string($conn, $_POST['warna']);
    $harga_beli = (float) $_POST['harga_beli'];
    $harga_jual = (float) $_POST['harga_jual'];
    $stock = (int) $_POST['stock'];
    $tanggal = date('Y-m-d');

    if ($stock <= 0 || $harga_beli <= 0 || $harga_jual <= 0) {
        $error = "Stok, harga beli, dan harga jual harus lebih dari 0.";
    } else {
        mysqli_begin_transaction($conn);

        try {
            $qMaster = mysqli_query($conn, "SELECT merk, tipe FROM master_mobil WHERE id_master=$id_master");
            if (!$qMaster || mysqli_num_rows($qMaster) == 0) {
                throw new Exception("Model mobil tidak valid.");
            }

            $master = mysqli_fetch_assoc($qMaster);
            $merk = mysqli_real_escape_string($conn, $master['merk']);
            $tipe = mysqli_real_escape_string($conn, $master['tipe']);

            $insertMobil = mysqli_query($conn, "
                INSERT INTO mobil (id_master, merk, tipe, tahun, warna, harga, stock, status)
                VALUES ($id_master, '$merk', '$tipe', $tahun, '$warna', $harga_jual, $stock, 'Tersedia')
            ");

            if (!$insertMobil) {
                throw new Exception(mysqli_error($conn));
            }

            $id_mobil = mysqli_insert_id($conn);
            $total = $harga_beli * $stock;

            $insertPembelian = mysqli_query($conn, "
                INSERT INTO pembelian (id_mobil, nama_penjual, no_hp_penjual, tanggal_pembelian, total)
                VALUES ($id_mobil, '$nama_penjual', '$no_hp_penjual', '$tanggal', $total)
            ");

            if (!$insertPembelian) {
                throw new Exception(mysqli_error($conn));
            }

            $id_pembelian = mysqli_insert_id($conn);

            $insertDetail = mysqli_query($conn, "
                INSERT INTO detail_pembelian (id_pembelian, jumlah, harga_beli, subtotal)
                VALUES ($id_pembelian, $stock, $harga_beli, $total)
            ");

            if (!$insertDetail) {
                throw new Exception(mysqli_error($conn));
            }

            mysqli_commit($conn);
            header("Location: index.php?status=tambah_berhasil");
            exit;
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $error = "Gagal menambahkan pembelian: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta charset="UTF-8">
    <title>Tambah Pembelian Mobil</title>
</head>
<body>
<div class="header"><div class="inner"><h1>Sistem Penjualan Mobil Bekas</h1><div class="top-user"><?php if (isset($_SESSION['role'])) { ?><span class="user-badge"><?= htmlspecialchars($_SESSION['role']); ?><?php if (isset($_SESSION['username'])) { ?> / <?= htmlspecialchars($_SESSION['username']); ?><?php } ?></span><a class="logout-link" href="../logout.php">Logout</a><?php } ?></div></div></div>
<div class="container"><div class="card">
<h2>Tambah Pembelian Mobil dari Penjual</h2>

<?php if (isset($error)) { ?>
    <p style="color:red;"><?= htmlspecialchars($error); ?></p>
<?php } ?>

<form method="POST">
    Nama Penjual:<br>
    <input name="nama_penjual" required><br><br>

    No HP Penjual:<br>
    <input name="no_hp_penjual" required><br><br>

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
    <input name="warna" required><br><br>

    Harga Beli dari Penjual:<br>
    <input name="harga_beli" type="number" step="0.01" required><br><br>

    Harga Jual:<br>
    <input name="harga_jual" type="number" step="0.01" required><br><br>

    Stok/Jumlah:<br>
    <input name="stock" type="number" min="1" required><br><br>

    <button type="submit" name="submit">Simpan Pembelian</button>
    <a class="btn btn-secondary" href="index.php">Batal</a>
</form>
</div></div>
</body>
</html>
