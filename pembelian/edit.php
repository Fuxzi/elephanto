<?php
require_once __DIR__ . "/../auth/staff_only.php";
include "../config.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$query = "
SELECT 
    pb.id_pembelian,
    pb.id_mobil,
    pb.nama_penjual,
    pb.no_hp_penjual,
    pb.tanggal_pembelian,
    pb.total,
    m.id_master,
    COALESCE(mm.merk, m.merk) AS nama_merk,
    COALESCE(mm.tipe, m.tipe) AS nama_tipe,
    m.tahun,
    m.warna,
    m.harga AS harga_jual,
    m.stock,
    dp.jumlah,
    dp.harga_beli
FROM pembelian pb
JOIN mobil m ON pb.id_mobil = m.id_mobil
LEFT JOIN master_mobil mm ON m.id_master = mm.id_master
LEFT JOIN detail_pembelian dp ON pb.id_pembelian = dp.id_pembelian
WHERE pb.id_pembelian=$id
";

$q = mysqli_query($conn, $query);
if (!$q || mysqli_num_rows($q) == 0) {
    die("Data pembelian tidak ditemukan.");
}
$row = mysqli_fetch_assoc($q);

$selected_master = $row['id_master'];
$master_mobil = mysqli_query($conn, "SELECT * FROM master_mobil ORDER BY merk ASC, tipe ASC");
if (!$master_mobil) {
    die("Tabel master_mobil belum tersedia. Jalankan ALTER_MASTER_MOBIL.sql terlebih dahulu. Error: " . mysqli_error($conn));
}

if (isset($_POST['submit'])) {
    $nama_penjual = mysqli_real_escape_string($conn, $_POST['nama_penjual']);
    $no_hp_penjual = mysqli_real_escape_string($conn, $_POST['no_hp_penjual']);
    $id_master = (int) $_POST['id_master'];
    $harga_beli = (float) $_POST['harga_beli'];
    $harga_jual = (float) $_POST['harga_jual'];
    $jumlah = (int) $_POST['jumlah'];
    $total = $harga_beli * $jumlah;
    $id_mobil = (int) $row['id_mobil'];

    mysqli_begin_transaction($conn);

    try {
        $qMaster = mysqli_query($conn, "SELECT merk, tipe FROM master_mobil WHERE id_master=$id_master");
        if (!$qMaster || mysqli_num_rows($qMaster) == 0) {
            throw new Exception("Model mobil tidak valid.");
        }

        $master = mysqli_fetch_assoc($qMaster);
        $merk = mysqli_real_escape_string($conn, $master['merk']);
        $tipe = mysqli_real_escape_string($conn, $master['tipe']);

        mysqli_query($conn, "
            UPDATE mobil 
            SET id_master=$id_master, merk='$merk', tipe='$tipe', harga=$harga_jual, stock=$jumlah 
            WHERE id_mobil=$id_mobil
        ");

        mysqli_query($conn, "
            UPDATE pembelian 
            SET nama_penjual='$nama_penjual', no_hp_penjual='$no_hp_penjual', total=$total 
            WHERE id_pembelian=$id
        ");

        mysqli_query($conn, "
            UPDATE detail_pembelian 
            SET jumlah=$jumlah, harga_beli=$harga_beli, subtotal=$total 
            WHERE id_pembelian=$id
        ");

        mysqli_commit($conn);
        header("Location: index.php?status=edit_berhasil");
        exit;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $error = "Gagal memperbarui pembelian: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta charset="UTF-8">
    <title>Edit Pembelian</title>
</head>
<body>
<div class="header"><div class="inner"><h1>Sistem Penjualan Mobil Bekas</h1><div class="top-user"><?php if (isset($_SESSION['role'])) { ?><span class="user-badge"><?= htmlspecialchars($_SESSION['role']); ?><?php if (isset($_SESSION['username'])) { ?> / <?= htmlspecialchars($_SESSION['username']); ?><?php } ?></span><a class="logout-link" href="../logout.php">Logout</a><?php } ?></div></div></div>
<div class="container"><div class="card">
<h2>Edit Pembelian</h2>

<p>Mobil saat ini: <strong><?= htmlspecialchars($row['nama_merk'] . " " . $row['nama_tipe']); ?></strong></p>

<?php if (isset($error)) { ?>
    <p style="color:red;"><?= htmlspecialchars($error); ?></p>
<?php } ?>

<form method="POST">
    Nama Penjual:<br>
    <input name="nama_penjual" value="<?= htmlspecialchars($row['nama_penjual']); ?>" required><br><br>

    No HP Penjual:<br>
    <input name="no_hp_penjual" value="<?= htmlspecialchars($row['no_hp_penjual']); ?>" required><br><br>

    Model Mobil:<br>
    <select name="id_master" required>
        <option value="">-- Pilih Model Mobil --</option>
        <?php while ($mm = mysqli_fetch_assoc($master_mobil)) { ?>
            <option value="<?= $mm['id_master']; ?>" <?= ($selected_master == $mm['id_master']) ? 'selected' : ''; ?>>
                <?= htmlspecialchars($mm['merk'] . " " . $mm['tipe']); ?>
            </option>
        <?php } ?>
    </select><br><br>

    Harga Beli:<br>
    <input name="harga_beli" type="number" step="0.01" value="<?= htmlspecialchars($row['harga_beli']); ?>" required><br><br>

    Harga Jual:<br>
    <input name="harga_jual" type="number" step="0.01" value="<?= htmlspecialchars($row['harga_jual']); ?>" required><br><br>

    Jumlah/Stok:<br>
    <input name="jumlah" type="number" min="1" value="<?= htmlspecialchars($row['jumlah']); ?>" required><br><br>

    <button type="submit" name="submit">Update</button>
    <a class="btn btn-secondary" href="index.php">Batal</a>
</form>
</div></div>
</body>
</html>
